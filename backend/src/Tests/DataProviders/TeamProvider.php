<?php

namespace App\Tests\DataProviders;

class TeamProvider
{
    public static function fixtureTeams(): array
    {
        return self::basicTeams();
    }

    public static function basicTeams(): array
    {
        return [
            [
                [
                    'id' => 1,
                    'name' => 'France',
                    'abbreviation' => 'FRA',
                ],
            ],
            [
                [
                    'id' => 2,
                    'name' => 'Croatie',
                    'abbreviation' => 'CRO',
                ],
            ],
            [
                [
                    'id' => 3,
                    'name' => 'Belgique',
                    'abbreviation' => 'BEL',
                ],
            ],
        ];
    }
}
