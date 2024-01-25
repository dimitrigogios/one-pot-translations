<?php

namespace Mortendhansen\OnePotTranslations;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Mortendhansen\OnePotTranslations\Models\OPTTranslationItem;

class OnePotTranslator
{

    public $loaded = [];

    const OPT_CACHE_KEY_PREFIX = 'opt-items-';

    public string $fallbackLocale;

    public function __construct(string $fallbackLocale)
    {
        $this->fallbackLocale = $fallbackLocale;
    }

    public function get(string $key): string
    {
        $keySlug = Str::slug($key);

        if(!$this->all()['local']->has($keySlug)) {
            OPTTranslationItem::firstOrCreate([
                'locale' => App::currentLocale(),
                'key' => $keySlug
            ]);
        }

        $string = $this->all()['local']->get($keySlug);

        if(is_null($string)) {
            $string = $this->all()['fallback']->get($keySlug, $key);
        }

        if(!$this->all()['fallback']->has($keySlug) && $this->fallbackLocale != App::currentLocale()) {
            OPTTranslationItem::firstOrCreate([
                'key' => $keySlug,
                'locale' => $this->fallbackLocale,
                'value' => $key
            ]);
        }

        return is_null($string) ? $key : $string;
    }

    /**
     * @return Collection[]
     */
    public function all(): array
    {
        if(!empty($this->loaded)) {
            return $this->loaded;
        }

        $allItems = Cache::remember(self::OPT_CACHE_KEY_PREFIX . App::currentLocale(), 3600, function () {
            return OPTTranslationItem::where('locale', App::currentLocale())->get(['key', 'value']);
        });

        $allFallbackItems = Cache::remember(self::OPT_CACHE_KEY_PREFIX . $this->fallbackLocale, 3600, function () {
            return OPTTranslationItem::where('locale', $this->fallbackLocale)->get(['key', 'value']);
        });

        $this->loaded = [
            'local'    => $allItems->pluck('value', 'key'),
            'fallback' => $allFallbackItems->pluck('value', 'key')
        ];

        return $this->loaded;
    }
}