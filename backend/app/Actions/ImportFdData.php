<?php

namespace App\Actions;

use Exception;
use Generator;
use App\Models\RawImport;
use App\Models\AssayClass;
use App\Models\Publication;
use InvalidArgumentException;
use App\Models\FunctionalAssay;
use Illuminate\Console\Command;
use Iterator;
use Lorisleiva\Actions\Concerns\AsCommand;



class ImportFdData
{
	use AsCommand;	

    public $commandSignature = 'dev:import';
    private $command;
    private $errors = [];

    public function handle()
    {
        $this->importGeneFiles();
        // $this->importOtherFiles();

        if ($this->hasErrors()) {
            dd(array_map(function ($i) { return $i['msg']; }, $this->errors));
        }
    }    

    private function importOtherFiles()
    {
        $entries = $this->filesInDir(base_path('import-data'));
        $jsonFiles  = $this->filterPaths(
                            $entries,
                            function ($file) { 
                                return preg_match('/(?<!(functionalassays))\.json$/', $file); 
                            }
                        );
        $rawData = $this->parseFiles($jsonFiles);
        $fas = [];
        foreach($rawData as $fa) {
            $fas = $fa;
        }
        $this->command->info(count($fas).' created');

        
        // $faGenerator = $this->createFunctionalAssays($functionalAssays);
        
        // $faModels = [];
        // foreach ($faGenerator as $faModel) {
        //     $faModels[] = $faModel;
        // }
        // $this->command->info(count($faModels).' created');
    }
    
    private function importGeneFiles()
    {
        $entries = $this->filesInDir(base_path('import-data'));
        $jsonFiles  = $this->filterPaths(
                            $entries, 
                            function ($file) { 
                                return preg_match('/.json$/', $file); 
                            }
                        );
        $functionalAssays = $this->parseFiles($jsonFiles);
        $faGenerator = $this->createFunctionalAssays($functionalAssays);
        
        $faModels = [];
        foreach ($faGenerator as $faModel) {
            $faModels[] = $faModel;
        }
        $this->command->info(count($faModels).' created');
    }
    

    public function asCommand(Command $command)
    {
        $this->command = $command;
        $this->handle();
    }

    private function filesInDir($path)
    {
        if (!is_dir($path)) {
            throw new InvalidArgumentException($path.' is not a directory.');
        }
        $dir = opendir($path);
        if ($dir) {
            while(($entry = readdir($dir)) !== false) {
                yield $path.'/'.$entry;
            }
        }
        closedir($dir);
    }

    private function filterPaths($files, $filterFunc)
    {
        foreach ($files as $file) {
            if ($filterFunc($file)) {
                yield $file;
            }
        }
    }

    private function parseFiles($paths)
    {
        $count = 0;
        foreach ($paths as $path) {
            $count++;
            $parts = explode('-', array_reverse(explode('/', $path))[0]);
            
            $geneSymbol = null;
            $hgncId = null;
            if (count($parts) > 1) {
                $geneSymbol = $parts[0];
                $hgncId = $parts[1];
            }
            
            $doc = json_decode(file_get_contents($path), true);
            $functionalAssays = $this->parseDocument($doc);
            foreach ($functionalAssays as $funcAss) {
                $funcAss['Gene Symbol'] = $geneSymbol;
                $funcAss['HGNC ID'] = 'HGNC:'.$hgncId;
                $funcAss['Affiliation ID'] = $count;

                $rawImport = $this->storeRawImport(
                    data: $funcAss, 
                    geneSymbol: $geneSymbol, 
                    assayClassName: $funcAss['Assay Class'],
                    affiliationId: $count
                );

                $funcAss['raw_import_id'] = $rawImport->id;

                yield $funcAss;
            }
            
        }
    }

    private function parseDocument($doc)
    {
        foreach($doc as $assayClassName => $functionalAssays) {
            foreach ($functionalAssays as $funcAss) {
                $funcAss['Assay Class'] = $assayClassName;
                yield $funcAss;
            }
        }
    }
    
    private function storeRawImport($data, $assayClassName, $geneSymbol, $affiliationId)
    {

        return RawImport::create([
            'assay_class' => $assayClassName,
            'affiliation_id' => $affiliationId,
            'pubmed_id' => $this->getAttribute('PMID', $data),
            'gene_symbol' => $geneSymbol,
            'approved' => $this->getApproved($data),
            'data' => $data
        ]);
    }

    private function createFunctionalAssays($items)
    {
        foreach ($items as $fa) {
            try {
                $assayClass = AssayClass::firstOrCreate(['name' => $this->getAttribute('Assay Class', $fa)]);
                
                $publication = Publication::firstOrCreate(
                                    ['coding_system_id' => 1, 'code' => $this->getAttribute('PMID', $fa)],
                                    [
                                        'coding_system_id' => 1, 
                                        'code' => $this->getAttribute('PMID', $fa),
                                        'doi' => $this->getAttribute('DOI / link', $fa),
                                        'author' => $this->getAuthor($fa),
                                        'year' => $this->getAttribute('Year', $fa)
                                    ]
                                );
                $data = array_merge(
                    ['publication_id' => $publication->id], 
                    $this->mapData($fa)
                );

                $model = FunctionalAssay::create($data);
                $model->assayClasses()->sync([$assayClass->id]);
                yield $model;
            } catch (Exception $e) {
                $this->logException($e->getMessage(), $fa);
            }
        }
    }

    private function mapData($raw)
    {
        return [
            "affiliation_id" => $this->getAttribute('Affiliation ID', $raw),
            'hgnc_id' => $this->getAttribute('HGNC ID', $raw),
            'gene_symbol' => $this->getAttribute('Gene Symbol', $raw),
            'approved' => $this->getApproved($raw),
            "description" => $this->getAttribute("Assay (general description)", $raw),
            "material_used" => $this->getMaterialUsed($raw),
            "range_type" => $this->getRangeType($raw),
            "read_out_desciption" => $this->getAttribute("Readout description", $raw),
            "ep_biological_replicates" => $this->getAttribute("Biological replicates (met/not met)", $raw),
            "ep_technical_replicates" => $this->getAttribute("Technical replicates (met/not met); description", $raw),
            "ep_basic_positive_control" => $this->getAttribute("Basic positive control (met/not met); description", $raw),
            "ep_basic_negative_control" => $this->getAttribute("Basic negative control (met/not met); description", $raw),
            "validation_control_pathogenic" => $this->getAttribute("Validation controls P/LP (#)", $raw),
            "validation_control_benign" => $this->getAttribute("Validation controls B/LB (#)", $raw),
            "statistical_analysis" => $this->getAttribute("Statistical analysis (general description)", $raw),
            "normal_range" => $this->getAttribute("Threshold for normal readout ", $raw),
            "abnormal_range" => $this->getAttribute("Threshold for abnormal readout", $raw),
            "ep_proposed_strength_pathogenic" => $this->getProposedStrength($raw),
            "comment" => $this->getAttribute("Notes", $raw),
            'raw_import_id' => $this->getAttribute('raw_import_id', $raw)
        ];
    }
    
    private function getApproved($data)
    {
        if (isset($data['Approved assay (y/n)'])) {
            return  in_array(strtolower($data['Approved assay (y/n)']), ['yes', 'y']) ? true : false;
        }

        return false;
    }
    
    private function getRangeType($data)
    {
        $value = $this->getAttribute("Readout type (qualitative/quantitative)", $data);
        return $value ? strtolower($value) : null;
    }
    
    private function getMaterialUsed($fa)
    {
        if (isset($fa["Material used (patient cells, engineered variants, cell lines, animal model, etc."])) {
            return $fa["Material used (patient cells, engineered variants, cell lines, animal model, etc."];
        }

        if (isset($fa["Material used (patient cells, engineered variants, cell lines, animal model, etc.)"])) {
            return $fa["Material used (patient cells, engineered variants, cell lines, animal model, etc.)"];
        }

        return null;

    }
    
    private function getAuthor($fa)
    {
        if (isset($fa['Author'])) {
            return $fa['Author'];
        }

        if (isset($fa['1st Author'])) {
            return $fa['1st Author'];
        }

        return null;
    }

    private function getProposedStrength($raw)
    {
        if (isset($raw["Proposed strength"])) {
            return $raw["Proposed strength"];
        }

        if (isset($raw["Proposed strength (pathogenicity)"])) {
            return $raw["Proposed strength (pathogenicity)"];
        }

        return null;
    }
    
    private function getAttribute($attribute, $data)
    {
        if (array_key_exists($attribute, $data)) {
            return $data[$attribute] ? trim($data[$attribute]) : $data[$attribute];
        }

        return null;
    }

    private function logException($msg, $data)
    {
        $this->errors[] = compact('msg', 'data');
    }

    private function hasErrors()
    {
        return count($this->errors) > 0;
    }
}