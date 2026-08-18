<?php

namespace App\Concerns;

/**
 * Resolves `<attribute>_<locale>` columns down to a single value for the active
 * locale, falling back to the application's fallback locale when the
 * translation is missing or blank.
 */
trait HasTranslatedAttributes
{
    /**
     * Get the value of a translated attribute for the given (or active) locale.
     */
    public function translate(string $attribute, ?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        $value = $this->getAttribute($attribute.'_'.$locale);

        if (filled($value)) {
            return $value;
        }

        foreach ($this->translationFallbackLocales() as $fallback) {
            $value = $this->getAttribute($attribute.'_'.$fallback);

            if (filled($value)) {
                return $value;
            }
        }

        return null;
    }

    /**
     * The locales to try when the requested translation is blank.
     *
     * @return list<string>
     */
    protected function translationFallbackLocales(): array
    {
        return array_values(array_unique([
            config('app.fallback_locale'),
            ...array_keys(config('site.locales', [])),
        ]));
    }
}
