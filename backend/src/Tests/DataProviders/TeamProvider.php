<?php

namespace App\Tests\DataProviders;

class TeamProvider extends AbstractDataProvider
{
    public static function fixtureTeams(): array
    {
        return self::normalizeProviders(
            self::mainTeamDataProvider(),
            self::basicTeams(),
            self::teamsToModify(),
            self::teamsToDelete()
        );
    }

    public static function mainTeam(): array
    {
        return [
            'id' => 1,
            'name' => 'France',
            'abbreviation' => 'FRA',
        ];
    }

    public static function mainTeamDataProvider(): array {
        return [
            [
                'team' => self::mainTeam(),
            ],
        ];
    }

    public static function basicTeams(): array
    {
        return [
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
            [
                'team' => [

                ],
            ],
            [
                'team' => [
                    'name' => 'Nope Kingdom',
                ],
            ],
            [
                'team' => [
                    'abbreviation' => 'NOPE',
                ],
            ],
            [
                'team' => [
                    'name' => '',
                    'abbreviation' => 'NOPE',
                ],
            ],
            [
                'team' => [
                    'name' => 'Nope Kingdom',
                    'abbreviation' => '',
                ],
            ],
            [
                'team' => [
                    'name' => null,
                    'abbreviation' => 'NOPE',
                ],
            ],
            [
                'team' => [
                    'name' => 'Nope Kingdom',
                    'abbreviation' => null,
                ],
            ],
            [
                'team' => [
                    'name' => 'Ts',
                    'abbreviation' => 'NOPE',
                ],
            ],
            [
                'team' => [
                    'name' => 'This is a team name so long, that it probably does not even exist.',
                    'abbreviation' => 'NOPE',
                ],
            ],
            [
                'team' => [
                    'name' => 'Nope Kingdom',
                    'abbreviation' => 'X',
                ],
            ],
            [
                'team' => [
                    'name' => 'Nope Kingdom',
                    'abbreviation' => 'ABBREVIATION',
                ],
            ],
            [
                'team' => [
                    'name' => 'Nope Kingdom',
                    'abbreviation' => 'Plop',
                ],
            ],
        ];
    }
}
