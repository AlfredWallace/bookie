<?php

namespace App\Tests\DataProviders;

class PlayerProvider extends AbstractDataProvider
{
    public static function fixturePlayers(): array
    {
        return [
            self::mainPlayer(),
            self::otherPlayer(),
            self::playerToDelete(),
        ];
    }

    public static function testTokenPlayers(): array
    {
        return [
            [
                'player' => self::mainPlayer(),
            ],
            [
                'player' => self::otherPlayer(),
            ],
        ];
    }

    public static function mainPlayer(): array
    {
        return [
            'id' => 1,
            'username' => 'wallace',
            'password' => 'wallace',
            'roles' => ['ROLE_ADMIN'],
        ];
    }

    public static function otherPlayer(): array
    {
        return [
            'id' => 2,
            'username' => 'Other player',
            'password' => '0th3rP4ssw0rd',
            'roles' => ['ROLE_USER'],
        ];
    }

    public static function playerToDelete(): array
    {
        return [
            'id' => 3,
            'username' => 'useless-player',
            'password' => 'whyB0th3r',
            'roles' => ['ROLE_USER'],
        ];
    }

//    public static function playerToSelfUpdate(): array
//    {
//        return [
//            'id' => 4,
//            'username' => 'useless-player',
//            'password' => 'whyB0th3r',
//            'roles' => ['ROLE_USER'],
//        ];
//    }

    public static function badLoginData(): array
    {
        return [
            [
                'player' => [
                    'username' => 'dummy' . self::otherPlayer()['username'],
                    'password' => self::otherPlayer()['password'],
                ],
            ],
            [
                'player' => [
                    'username' => self::otherPlayer()['username'],
                    'password' => 'dummy' . self::otherPlayer()['password'],
                ],
            ],
        ];
    }

//    public static function playerToSelfModify(): array
//    {
//        return [
//            [
//                'old' => [
//                    'username' => 'alice',
//                    'password' => 'wonderland',
//                    'id' => 3,
//                ],
//                'self' => [
//                    'username' => 'isBack',
//                    'password' => 'w0nd3rl4nd',
//                    'id' => 3,
//                ],
//                'admin' => [
//                    'username' => 'withAVengeance',
//                    'password' => 'wndrlnd',
//                    'id' => 3,
//                ],
//            ],
//            [
//                'old' => [
//                    'username' => 'bob',
//                    'password' => 'p4l1ndr0m3',
//                    'id' => 4,
//                ],
//                'self' => [
//                    'username' => 'obo',
//                    'password' => '3m0rdn1l4p',
//                    'id' => 4,
//                ],
//                'admin' => [
//                    'username' => 'boo',
//                    'password' => 'isHungry',
//                    'id' => 4,
//                ],
//            ],
//        ];
//    }

    public static function invalidPlayers(): array
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
