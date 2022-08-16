<?php

namespace App\Clients;

use GuzzleHttp\ClientInterface;
use App\Clients\PubMedClientInterface;

class PubMedClient implements PubMedClientInterface
{
    public const BASE_URL = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/';

    public function __construct(private ClientInterface $httpClient)
    {
    }

    /**
     * Gets summaries for supplied pmids
     *
     * @param array $pmids array of pmids
     * @return mixed object if only one result, array if multiple
     */
    public function getSummaries(array $pmids): object|array
    {
        if (count($pmids) == 0) {
            return collect();
        }

        $data = [
            'db' => 'pubmed',
            'id' => implode(',', $pmids),
            'retmode' => 'json'
        ];

        $response = $this->get('esummary.fcgi', $data);

        if (!isset($response->result)) {
            return [];
        }

        $results = collect((array)$response->result);

        $results = $results->filter(fn ($val, $key) => $key != 'uids');

        if ($results->count() == 1) {
            return $results->first();
        }

        return $results->toArray();
    }

    private function get($endpoint, $params)
    {
        $endpoint .= count($params) > 0 ? '?'.http_build_query($params) : '';

        $response = $this->httpClient->request('GET', self::BASE_URL.'/'.$endpoint);

        return json_decode($response->getBody()->getContents());
    }
}
