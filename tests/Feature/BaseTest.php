<?php

namespace MortenDHansen\OnePotTranslations\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use MortenDHansen\OnePotTranslations\Tests\database\Models\OPTTranslationItem;
use MortenDHansen\OnePotTranslations\Tests\TestCase;

class BaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_loads()
    {
        $this->assertDatabaseCount('opt_translation_items', 0);
        $this->assertIsString(opt('x'));
    }

    public function test_it_returns_string_from_database()
    {
        $this->assertDatabaseCount('opt_translation_items', 0);

        OPTTranslationItem::create([
            'key' => 'my-key',
            'value' => 'another-value',
            'locale' => 'en'
        ]);
        $this->assertDatabaseCount('opt_translation_items', 1);

        $this->assertEquals('another-value', opt('my key'));
        $this->assertDatabaseCount('opt_translation_items', 1);

    }

    public function test_it_creates_key_if_did_not_exist()
    {
        opt('my key');

        $this->assertNotNull(OPTTranslationItem::where('key', 'my-key')->first());
    }
}