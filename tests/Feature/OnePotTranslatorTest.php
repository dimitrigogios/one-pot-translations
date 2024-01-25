<?php

namespace MortenDHansen\OnePotTranslations\Tests\Feature;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Mortendhansen\OnePotTranslations\OnePotTranslator;
use MortenDHansen\OnePotTranslations\Tests\database\Models\OPTTranslationItem;

class OnePotTranslatorTest extends \MortenDHansen\OnePotTranslations\Tests\TestCase
{

    public function test_it_loads_all_translations_for_the_given_language()
    {
        OPTTranslationItem::factory(['locale' => 'en'])->count(10)->create();
        OPTTranslationItem::factory(['locale' => 'pl'])->count(10)->create();

        $opt = new OnePotTranslator('pl', 'en');

        $all = $opt->all();
        $this->assertCount(10, $all['local']);
        $this->assertCount(10, $all['fallback']);

    }

    public function test_translations_are_formatted_key_value_pairs()
    {
        OPTTranslationItem::factory(['locale' => 'en'])->count(10)->create();

        /** @var Builder $query */
        $query = OPTTranslationItem::where('locale', 'en');
        $item = $query->inRandomOrder()->first();

        $opt = new OnePotTranslator('en', 'en');

        $all = $opt->all();
        $this->assertIsArray($all);
        $this->assertInstanceOf(Collection::class, $all['local']);
        $this->assertArrayHasKey($item->key, $all['local']);
        $this->assertEquals($item->value, $all['local'][$item->key]);
        $this->assertEquals($item->value, $opt->get($item->key));
    }

    public function test_it_creates_missing_key()
    {
        $this->assertDatabaseCount('opt_translation_items', 0);
        opt('my string is here');
        $this->assertDatabaseCount('opt_translation_items', 1);
    }

    public function test_it_creates_missing_key_value_in_fallback_language()
    {
        App::setLocale('pl');
        $this->assertDatabaseCount('opt_translation_items', 0);
        $this->assertEquals('pl', App::currentLocale());

        opt('My string is here?');

        $this->assertDatabaseHas('opt_translation_items', [
            'key' => Str::slug('My string is here?'),
            'locale' => 'pl'
        ]);

        $this->assertDatabaseHas('opt_translation_items', [
            'key' => Str::slug('My string is here?'),
            'locale' => 'en',
            'value' => 'My string is here?'
        ]);

    }
}