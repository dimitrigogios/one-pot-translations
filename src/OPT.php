<?php

if(!function_exists('opt')) {
    function opt(string $string): string
    {
        /** @var \Mortendhansen\OnePotTranslations\OnePotTranslator $opt */
        $opt = app()->make('opTranslator');
        return $opt->get($string);
    }
}