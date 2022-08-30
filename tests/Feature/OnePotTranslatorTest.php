<?php

namespace MortenDHansen\OnePotTranslations\Tests\Feature;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
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
}