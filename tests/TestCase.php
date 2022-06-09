<?php

namespace Tests;

use Illuminate\Testing\TestResponse;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setup():void
    {
        parent::setup();
        TestResponse::macro('assertValidationErrors', function($validationErrrors) {
            $this->assertStatus(422)
                ->assertInvalid($validationErrrors);

            return $this;
        });    
    }
    
    protected function jsonifyArrays($data): array
    {
        $jsonified = [];
        foreach ($data as $key => $val) {
            if (is_array($val)) {
                $val = json_encode($val);
            }
            $jsonified[$key] = $val;
        }
        return $jsonified;
    }
}
