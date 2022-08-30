<?php

namespace Mortendhansen\OnePotTranslations\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Mortendhansen\OnePotTranslations\OnePotTranslator;

class OPTTranslationItem extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'opt_translation_items';

    protected $fillable = [
        'key',
        'value',
        'locale'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function (OPTTranslationItem $item) {
            Cache::forget(OnePotTranslator::OPT_CACHE_KEY_PREFIX . $item->locale);
        });

        static::updated(function (OPTTranslationItem $item) {
            Cache::forget(OnePotTranslator::OPT_CACHE_KEY_PREFIX . $item->locale);
        });

        static::deleted(function (OPTTranslationItem $item) {
            Cache::forget(OnePotTranslator::OPT_CACHE_KEY_PREFIX . $item->locale);
        });
    }
}