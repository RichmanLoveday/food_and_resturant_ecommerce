<?php

namespace App\Traits;

use App\Models\SectionTitle;

trait SectionTitlesTrait
{
    public function getSectionTitles(array $keys): array
    {
        return SectionTitle::whereIn('key', $keys)
            ->pluck('value', 'key')
            ->toArray();
    }
}