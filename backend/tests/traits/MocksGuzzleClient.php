<?php

namespace Tests\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

trait MocksGuzzleClient
{
    protected $mockHandler = null;
    protected $handlerStack = null;


    protected function setupMockClient(array $responses = []): Client
    {
        $this->mockHandler = new MockHandler($responses);
        $this->handlerStack = HandlerStack::create($this->mockHandler);
        return new Client(['handler' => $this->handlerStack]);
    }

    protected function resetResponses(): self
    {
        $this->mockHandler->reset();
        return $this;
    }

    protected function appendResponse(Response $response): self
    {
        $this->mockHandler->append($response);
        return $this;
    }
}
