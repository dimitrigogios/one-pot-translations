<?php

if(!function_exists('opt')) {
    function opt(string $string): string
    {
        return app('opTranslator')->get($string);
    }
}