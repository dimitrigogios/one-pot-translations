<?php

if(!function_exists('opt')) {
    function opt(string $string): string
    {
        /** @var \Mortendhansen\OnePotTranslations\OnePotTranslator $opt */
        $opt = app()->make('optranslator');
        return $opt->get($string);
    }
}