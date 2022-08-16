<?php

namespace Tests\traits;

use App\Clients\PubMedClient;
use App\Clients\PubMedClientInterface;

trait MocksPubMedClient
{
    use MocksGuzzleClient;

    private function setupClient($responses): PubMedClientInterface
    {
        app()->singleton(PubMedInterface::class, fn () => new PubMedClient($this->setupMockClient($responses)));
        return app()->make(PubMedInterface::class);
    }
}
