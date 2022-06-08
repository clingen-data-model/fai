<?php

namespace Tests\Feature\End2End;

use Tests\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use WithFaker;

}
