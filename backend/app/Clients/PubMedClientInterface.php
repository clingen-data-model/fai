<?php

namespace App\Clients;

use GuzzleHttp\ClientInterface;

interface PubMedClientInterface
{
    public function getSummaries(array $pubmedIds): array|object;
}
