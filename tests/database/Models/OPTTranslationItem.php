<?php

namespace MortenDHansen\OnePotTranslations\Tests\database\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use MortenDHansen\OnePotTranslations\Tests\database\factories\OPTTranslationItemFactory;


class OPTTranslationItem extends \Mortendhansen\OnePotTranslations\Models\OPTTranslationItem
{
    use HasFactory;

    protected static function newFactory()
    {
        return OPTTranslationItemFactory::new();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (\Mortendhansen\OnePotTranslations\Models\OPTTranslationItem $item) {

        });
    }
}