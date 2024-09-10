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

    public function get(string $key, $locale = null): string
    {
        $keySlug = Str::slug($key);

        $useLocale = App::currentLocale();
        if (!is_null($locale)) {
            $useLocale = $locale;
        }

        if(!$this->all($locale)['local']->has($keySlug)) {
            try {
                OPTTranslationItem::firstOrCreate([
                    'locale' => $useLocale,
                    'key' => $keySlug
                ], [
                    'value' => $this->fallbackLocale == $useLocale ? $key : null
                ]);
            } catch (\Exception $exception) {}
        }

        $string = $this->all($locale)['local']->get($keySlug);

        if(is_null($string)) {
            $string = $this->all()['fallback']->get($keySlug, $key);
        }

        if(!$this->all()['fallback']->has($keySlug)) {
            try {
                OPTTranslationItem::firstOrCreate([
                    'key' => $keySlug,
                    'locale' => $this->fallbackLocale,
                ], [
                    'value' => $key
                ]);
            } catch (\Exception $exception) {
            }
        }

        return is_null($string) ? $key : $string;
    }

    /**
     * @return Collection[]
     */
    public function all($locale = null): array
    {
        if(!empty($this->loaded)) {
            return $this->loaded;
        }

        $useLocale = App::currentLocale();
        if (!is_null($locale)) {
            $useLocale = $locale;
        }

        $allItems = Cache::remember(self::OPT_CACHE_KEY_PREFIX . App::currentLocale(), 3600, function () {
            return OPTTranslationItem::where('locale', )->get(['key', 'value']);
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
