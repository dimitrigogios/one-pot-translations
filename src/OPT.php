<?php

if(!function_exists('opt')) {
    function opt(string $string, string $locale): string
    {
        return app('opTranslator')->get($string, $locale);
    }
}
