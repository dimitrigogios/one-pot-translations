<?php

namespace Mortendhansen\OnePotTranslations;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Mortendhansen\OnePotTranslations\Models\OPTTranslationItem;

class OnePotTranslator
{

    public string $locale;
    public string $fallbackLocale;

    public function __construct(string $locale, string $fallbackLocale)
    {
        $this->locale = $locale;
        $this->fallbackLocale = $fallbackLocale;
    }

    public function get(string $key): string
    {
        $keySlug = Str::slug($key);

        if(!$this->all()['local']->has($keySlug)) {
            OPTTranslationItem::create([
                'locale' => $this->locale,
                'key' => $keySlug
            ]);
        }

        $string = $this->all()['local']->get($keySlug);

        if(is_null($string)) {
            $string = $this->all()['fallback']->get($keySlug, $key);
        }

        return is_null($string) ? $key : $string;
    }

    /**
     * @return Collection[]
     */
    public function all(): array
    {
        /** @var Collection $allItems */
        $allItems = OPTTranslationItem::where('locale', $this->locale)->get(['key', 'value']);
        $allFallbackItems = OPTTranslationItem::where('locale', $this->locale)->get(['key', 'value']);

        return [
            'local' => $allItems->pluck('value', 'key'),
            'fallback' => $allFallbackItems->pluck('value', 'key')
        ];
    }
}