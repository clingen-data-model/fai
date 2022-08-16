<?php

namespace Tests\Unit;

use Tests\TestCase;
use GuzzleHttp\Psr7\Response;
use Tests\traits\MocksPubMedClient;

class PubmedClientTest extends TestCase
{
    use MocksPubMedClient;

    /**
     * @test
     */
    public function gets_summaries_for_array_of_pmids()
    {
        $pmids = [6678417, 9507199, 28558982, 28558984, 28558988, 28558990];
        $client = $this->setupClient([new Response(200, [], file_get_contents(base_path('tests/files/pubmed_summary_response.json')))]);

        $results = $client->getSummaries($pmids);

        $this->assertEquals(6, count($results));
    }

    /**
     * @test
     */
    public function gets_single_summary_if_only_one_pmid()
    {
        $client = $this->setupClient([new Response(200, [], file_get_contents(base_path('tests/files/pubmed_summary_single_response.json')))]);

        $result = $client->getSummaries([6678417]);

        $this->assertIsObject($result);
    }
}
