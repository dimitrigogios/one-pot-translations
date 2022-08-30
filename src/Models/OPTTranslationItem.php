<?php

namespace Mortendhansen\OnePotTranslations\Models;

class OPTTranslationItem extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'opt_translation_items';

    protected $fillable = [
        'key',
        'value',
        'locale'
    ];
}