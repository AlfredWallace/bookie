<?php

namespace App\Tests\DataProviders;

abstract class AbstractDataProvider
{
    protected static function normalizeProviders(array ...$dataProviders): array
    {
        return array_map(function ($players) {
            if (count($players) === 1) {
                return array_shift($players);
            } elseif (array_key_exists('old', $players)) {
                return $players['old'];
            } else {
                return null;
            }
        }, array_merge(...$dataProviders));
    }
}
