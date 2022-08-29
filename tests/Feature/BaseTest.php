<?php

namespace MortenDHansen\OnePotTranslations\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use MortenDHansen\OnePotTranslations\Tests\TestCase;

class BaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_loads()
    {
        $this->assertDatabaseCount('opt_translation_items', 0);
    }
}