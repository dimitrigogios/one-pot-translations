<?php

namespace MortenDHansen\OnePotTranslations\Tests;

use Mortendhansen\OnePotTranslations\OnePotTranslationsServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{



    public function setUp(): void
    {
        parent::setUp();
        // additional setup
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    protected function getPackageProviders($app)
    {
        return [
            OnePotTranslationsServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Database
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app['config']->set('locale', 'en');
        $app['config']->set('fallback_locale', 'en');
        $app['config']->set('cache.file', [
            'driver' => 'file',
            'path' => __DIR__ . '/temp/cache'
        ]);
    }

//    /**
//     * Get package providers.
//     *
//     * @param \Illuminate\Foundation\Application $app
//     *
//     * @return array
//     */
//    protected function overrideApplicationBindings($app)
//    {
//        return [
//        ];
//    }

}