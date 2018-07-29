<?php

namespace App\Tests\DataProviders;

class PlayerProvider
{
    public static function fixturePlayers()
    {
        return array_map(function ($players) {
            if (array_key_exists('player', $players)) {
                return $players['player'];
            } elseif (array_key_exists('old', $players)) {
                return $players['old'];
            } else {
                return null;
            }
        }, array_merge(
            self::otherPlayer(),
            self::additionalPlayers(),
            self::playersToModify(),
            self::playersToSelfModify(),
            self::playersToDelete(),
            self::playersToSelfDelete()
        ));
    }

    public static function mainPlayer()
    {
        return [
            'main' => [
                'player' => [
                    'username' => 'wallace',
                    'password' => 'wallace',
                    'id' => 1,
                ],
            ],
        ];
    }

    public static function otherPlayer()
    {
        return [
            'other' => [
                'player' => [
                    'username' => 'Other player',
                    'password' => '0th3rP4ssw0rd',
                    'id' => 2,
                ],
            ],
        ];
    }

    public static function additionalPlayers()
    {
        return [
            [
                'player' => [
                    'username' => 'plop',
                    'password' => 'plop',
                    'id' => 3,
                ],
            ],
            [
                'player' => [
                    'username' => 'Talkie Walkie',
                    'password' => 'n0ns3ns3!',
                    'id' => 4,
                ],
            ],
            [
                'player' => [
                    'username' => 'john_doe',
                    'password' => 'qwerty123',
                    'id' => 5,
                ],
            ],
        ];
    }

    public static function playersToModify()
    {
        return [
            [
                'old' => [
                    'username' => 'alice',
                    'password' => 'w0nd3rl4nd',
                    'id' => 6,
                ],
                'new' => [
                    'username' => 'alice2leretour',
                    'password' => 'w0nd3rl4nd',
                    'id' => 6,
                ],
            ],
            [
                'old' => [
                    'username' => 'bob',
                    'password' => 'p4l1ndr0m3',
                    'id' => 7,
                ],
                'new' => [
                    'username' => 'bob3lavengeance',
                    'password' => '3m0rdn1l4p',
                    'id' => 7,
                ],
            ],
            [
                'old' => [
                    'username' => 'charlie_yksi',
                    'password' => 'l3nt0k0n3',
                    'id' => 8,
                ],
                'new' => [
                    'username' => 'charlie_kaksi',
                    'password' => 'l3nt0k0n3',
                    'id' => 8,
                ],
            ],
        ];
    }

    public static function playersToSelfModify()
    {
        return [
            [
                'old' => [
                    'username' => 'gaelle',
                    'password' => 'w0nd3rl4nd',
                    'id' => 9,
                ],
                'new' => [
                    'username' => 'guenael',
                    'password' => 'w0nd3rl4nd',
                    'id' => 9,
                ],
            ],
            [
                'old' => [
                    'username' => 'hector',
                    'password' => 'p4l1ndr0m3',
                    'id' => 10,
                ],
                'new' => [
                    'username' => 'hec thor',
                    'password' => '3m0rdn1l4p',
                    'id' => 10,
                ],
            ],
            [
                'old' => [
                    'username' => 'ignace',
                    'password' => 'l3nt0k0n3',
                    'id' => 11,
                ],
                'new' => [
                    'username' => 'iguane',
                    'password' => 'l3nt0k0n3',
                    'id' => 11,
                ],
            ],
        ];
    }

    public static function playersToDelete()
    {
        return [
            [
                'player' => [
                    'username' => 'useless-player',
                    'password' => 'whyB0th3r',
                    'id' => 12,
                ],
            ],
            [
                'player' => [
                    'username' => 'not-so-useful',
                    'password' => 'mw4h4h4h4',
                    'id' => 13,
                ],
            ],
            [
                'player' => [
                    'username' => 'void',
                    'password' => 'v0idv0id',
                    'id' => 14,
                ],
            ],
        ];
    }

    public static function playersToSelfDelete()
    {
        return [
            [
                'player' => [
                    'username' => 'self_destruct',
                    'password' => 'boum',
                    'id' => 15,
                ],
            ],
            [
                'player' => [
                    'username' => 'dis-integer',
                    'password' => 'zap',
                    'id' => 16,
                ],
            ],
            [
                'player' => [
                    'username' => 'Sue Side',
                    'password' => 'und3rth3cot3',
                    'id' => 17,
                ],
            ],
        ];
    }

    public static function playersToCreate()
    {
        return [
            [
                'player' => [
                    'username' => 'Ray Cent',
                    'password' => 'th1sP4ss1sR3c3nt',
                    'id' => 18,
                ],
            ],
            [
                'player' => [
                    'username' => 'More Ressent',
                    'password' => 'fr3shP4ss',
                    'id' => 19,
                ],
            ],
            [
                'player' => [
                    'username' => 'Evan Morescent',
                    'password' => '1dontkn0wwh4ttos4y',
                    'id' => 20,
                ],
            ],
        ];
    }

    public static function invalidPlayers()
    {
        return [
            [
                'invalid_data' => [],
            ],
            [
                'invalid_data' => [
                    'username' => 'i-need-a-name',
                ],
            ],
            [
                'invalid_data' => [
                    'password' => 'what_else',
                ],
            ],
            [
                'invalid_data' => [
                    'username' => null,
                ],
            ],
            [
                'invalid_data' => [
                    'password' => null,
                ],
            ],
            [
                'invalid_data' => [
                    'username' => '',
                ],
            ],
            [
                'invalid_data' => [
                    'password' => '',
                ],
            ],
            [
                'invalid_data' => [
                    'username' => null,
                    'password' => null,
                ],
            ],
            [
                'invalid_data' => [
                    'username' => null,
                    'password' => '',
                ],
            ],
            [
                'invalid_data' => [
                    'username' => null,
                    'password' => 'johnDoeP4ss',
                ],
            ],
            [
                'invalid_data' => [
                    'username' => '',
                    'password' => null,
                ],
            ],
            [
                'invalid_data' => [
                    'username' => '',
                    'password' => '',
                ],
            ],
            [
                'invalid_data' => [
                    'username' => '',
                    'password' => 'johnDoeP4ss',
                ],
            ],
            [
                'invalid_data' => [
                    'username' => 'i-need-a-name',
                    'password' => null,
                ],
            ],
            [
                'invalid_data' => [
                    'username' => 'i-need-a-name',
                    'password' => '',
                ],
            ],
            [
                'invalid_data' => [
                    'username' => 'this-is-tooooooooooooooooo-long',
                    'password' => 'johnDoeP4ss',
                ],
            ],
            [
                'invalid_data' => [
                    'username' => 'ok',
                    'password' => 'johnDoeP4ss',
                ],
            ],
            [
                'invalid_data' => [
                    'username' => 'inv@lid#ch@rs!',
                    'password' => 'johnDoeP4ss',
                ],
            ],
            [
                'invalid_data' => [
                    'username' => '0thisStartsBadly',
                    'password' => 'johnDoeP4ss',
                ],
            ],
            [
                'invalid_data' => [
                    'username' => 'i-need-a-name',
                    'password' => 'invalid chars',
                ],
            ],
            [
                'invalid_data' => [
                    'username' => 'i-need-a-name',
                    'password' => 'password-too-long-0123456789-0123456789-0123456789-0123456789-0123456789',
                ],
            ],
            [
                'invalid_data' => [
                    'username' => 'i-need-a-name',
                    'password' => '123',
                ],
            ],
        ];
    }
}
