<?php

namespace App\Models\Concerns;

trait Translatable
{
    /** tr('title') => title_ar / title_en by current locale, falling back to Arabic. */
    public function tr(string $field): string
    {
        $l = app()->getLocale();

        return (string) ($this->{$field.'_'.$l} ?: $this->{$field.'_ar'} ?: '');
    }
}
