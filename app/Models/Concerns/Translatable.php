<?php

namespace App\Models\Concerns;

trait Translatable
{
    /** Current-language value with graceful fallback: tr('title') */
    public function tr(string $field): string
    {
        foreach ([app()->getLocale(), 'ar', 'en'] as $l) {
            $v = $this->getTranslation($field, $l, false);
            if (filled($v)) {
                return (string) $v;
            }
        }

        return '';
    }
}
