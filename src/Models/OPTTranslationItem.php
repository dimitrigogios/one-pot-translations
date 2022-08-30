<?php

namespace Mortendhansen\OnePotTranslations\Models;

use Illuminate\Database\Eloquent\Builder;

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
    }
}