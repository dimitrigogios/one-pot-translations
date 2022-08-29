<?php

namespace Mortendhansen\OnePotTranslations;

class OnePotTranslationsServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $destination = database_path('migrations/' . date('Y_m_d_His',
                    time()) . '_create_opt_translation_items_table.php');
            $this->publishes([
                __DIR__ . '/database/migrations/create_opt_translation_items_table.php.stub' => $destination,
            ], 'migrations');
//
//            $this->publishes([
//                __DIR__ . '/../config/translations-database.php' => config_path('translations-database.php'),
//            ]);

        }
    }
}