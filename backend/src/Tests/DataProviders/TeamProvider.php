<?php

namespace App\Tests\DataProviders;

class TeamProvider extends AbstractDataProvider
{
    public static function fixtureTeams(): array
    {
        return self::normalizeProviders(
            self::basicTeams(),
            self::teamsToModify(),
            self::teamsToDelete()
        );
    }

    public static function basicTeams(): array
    {
        return [
            [
                'team' => [
                    'id' => 1,
                    'name' => 'France',
                    'abbreviation' => 'FRA',
                ],
            ],
            [
                'team' => [
                    'id' => 2,
                    'name' => 'Croatie',
                    'abbreviation' => 'CRO',
                ],
            ],
            [
                'team' => [
                    'id' => 3,
                    'name' => 'Belgique',
                    'abbreviation' => 'BEL',
                ],
            ],
            [
                'team' => [
                    'id' => 4,
                    'name' => 'Angleterre',
                    'abbreviation' => 'ENG',
                ],
            ],
        ];
    }

    public static function teamsToCreate(): array
    {
        return [
            [
                'team' => [
                    'id' => 10,
                    'name' => 'Ab0àÀèÈéÉêÊëËîÎïÏôÔöÖœŒûÛüÜùÙ !@#$%^&*()-_=+[{]}\|;:\'",<.>/?',
                    'abbreviation' => 'XYZ'
                ],  
            ],
            [
                'team' => [
                    'id' => 5,
                    'name' => 'Brésil',
                    'abbreviation' => 'BRA',
                ],
            ],
            [
                'team' => [
                    'id' => 6,
                    'name' => 'Uruguay',
                    'abbreviation' => 'URU',
                ],
            ],
        ];
    }

    public static function teamsToModify(): array
    {
        return [
            [
                'old' => [
                    'id' => 7,
                    'name' => 'Ruzzie',
                    'abbreviation' => 'RUZ',
                ],
                'new' => [
                    'id' => 7,
                    'name' => 'Russie',
                    'abbreviation' => 'RUS',
                ],
            ],
            [
                'old' => [
                    'id' => 8,
                    'name' => 'Bortugal',
                    'abbreviation' => 'BOR',
                ],
                'new' => [
                    'id' => 8,
                    'name' => 'Portugal',
                    'abbreviation' => 'POR',
                ],
            ],
            [
                'old' => [
                    'id' => 9,
                    'name' => 'Zuède',
                    'abbreviation' => 'ZWE',
                ],
                'new' => [
                    'id' => 9,
                    'name' => 'Suède',
                    'abbreviation' => 'SWE',
                ],
            ],
        ];
    }

    public static function teamsToDelete(): array
    {
        return [
            [
                'team' => [
                    'id' => 11,
                    'name' => 'Argentine',
                    'abbreviation' => 'ARG'
                ],
            ],
            [
                'team' => [
                    'id' => 12,
                    'name' => 'Mexique',
                    'abbreviation' => 'MEX'
                ],
            ],
            [
                'team' => [
                    'id' => 13,
                    'name' => 'Japon',
                    'abbreviation' => 'JPN'
                ],
            ],
        ];
    }

    public static function invalidTeams(): array
    {
        return [
            [],
            [
                'name' => 'Nope Kingdom',
            ],
            [
                'abbreviation' => 'NOPE',
            ],
            [
                'name' => '',
                'abbreviation' => 'NOPE',
            ],
            [
                'name' => 'Nope Kingdom',
                'abbreviation' => '',
            ],
            [
                'name' => null,
                'abbreviation' => 'NOPE',
            ],
            [
                'name' => 'Nope Kingdom',
                'abbreviation' => null,
            ],
            [
                'name' => 'Ts',
                'abbreviation' => 'NOPE',
            ],
            [
                'name' => 'This is a team name so long, that it probably does not even exist.',
                'abbreviation' => 'NOPE',
            ],
            [
                'name' => 'Nope Kingdom',
                'abbreviation' => 'X',
            ],
            [
                'name' => 'Nope Kingdom',
                'abbreviation' => 'ABBREVIATION',
            ],
            [
                'name' => 'Nope Kingdom',
                'abbreviation' => 'Plop',
            ],
        ];
    }
}
