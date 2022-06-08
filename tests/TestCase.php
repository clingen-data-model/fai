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
    
}
