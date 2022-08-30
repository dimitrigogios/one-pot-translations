<?php

namespace MortenDHansen\OnePotTranslations\Tests\database\factories;

use MortenDHansen\OnePotTranslations\Tests\database\Models\OPTTranslationItem;

class OPTTranslationItemFactory extends \Illuminate\Database\Eloquent\Factories\Factory
{

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'locale' => $this->faker->locale,
            'key' => $this->faker->unique()->word,
            'value' => $this->faker->word
        ];
    }

    protected $model = OPTTranslationItem::class;
}