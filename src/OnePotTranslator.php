<?php

namespace Mortendhansen\OnePotTranslations;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Mortendhansen\OnePotTranslations\Models\OPTTranslationItem;

class OnePotTranslator
{

    public string $fallbackLocale;

    public function __construct(string $fallbackLocale)
    {
        $this->fallbackLocale = $fallbackLocale;
    }

    public function get(string $key): string
    {
        $keySlug = Str::slug($key);

        if(!$this->all()['local']->has($keySlug)) {
            OPTTranslationItem::create([
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
                'locale' => $this->fallbackLocale
            ]);
        }

        return is_null($string) ? $key : $string;
    }

    /**
     * @return Collection[]
     */
    public function all(): array
    {
        /** @var Collection $allItems */
        $allItems = OPTTranslationItem::where('locale', App::currentLocale())->get(['key', 'value']);
        $allFallbackItems = OPTTranslationItem::where('locale', $this->fallbackLocale)->get(['key', 'value']);

        return [
            'local' => $allItems->pluck('value', 'key'),
            'fallback' => $allFallbackItems->pluck('value', 'key')
        ];
    }
}