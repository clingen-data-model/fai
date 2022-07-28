<?php

namespace App\Actions;

use Countable;
use GuzzleHttp\Client;
use App\Models\Publication;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsCommand;
use Lorisleiva\Actions\Concerns\AsListener;

class PublicationsFetchPubmedInfo
{
    use AsCommand;

    public $commandSignature = 'publications:pubmed {--limit= : Limit the number of publications for which to get info}';

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/',
        ]);
    }


    public function handle(Collection $publications)
    {
        $publications = $publications->keyBy('code');
        $pubIds = $publications->map(fn ($p) => $p->code);
        $results = $this->getSummaries($pubIds);

        $results->each(function ($result, $key) use ($publications){
            if($key == 'uids') {
                return;
            }
            $publication = $publications->get($result->uid);

            if (!$publication) {
                return;
            }

            try {
                $publication->update([
                        'title' => $result->title,
                        'author' => $result->lastauthor,
                        'year' => Carbon::parse($result->sortpubdate)->year
                    ]);
            } catch (Exception $e) {
                dump($result);
            }

        });

    }

    public function asCommand(Command $command)
    {
        $limit = $command->option('limit');

        $pubQuery = Publication::select(['id', 'code'])
                            ->whereNull('title')
                            ->orWhereNull('author');

        if ($limit) {
            $pubQuery->limit($limit);
        }

        $this->handle($pubQuery->get());

    }

    private function getSummaries(Collection $pmids): Collection
    {
        if ($pmids->count() == 0) {
            return collect();
        }

        $data = [
            'db' => 'pubmed',
            'id' => implode(',', $pmids->toArray()),
            'retmode' => 'json'
        ];

        $url = 'esummary.fcgi?'.http_build_query($data);

        $response = $this->client->request('GET', $url);

        $responseObj = json_decode($response->getBody()->getContents());
        if (!isset($responseObj->result)) {
            return collect();
        }
        return collect((array)$responseObj->result);
    }
}
