<?php

namespace App\Providers\Tests;

class UserProvider
{
    public static function fixtureUsers()
    {
        return array_map(function ($users) {
            if (array_key_exists('user', $users)) {
                return $users['user'];
            } elseif (array_key_exists('old', $users)) {
                return $users['old'];
            } else {
                return null;
            }
        }, array_merge(
            self::otherUser(),
            self::additionalUsers(),
            self::usersToModify(),
            self::usersToModifyByUsername(),
            self::usersToSelfModify(),
            self::usersToSelfModifyByUsername(),
            self::usersToDelete(),
            self::usersToDeleteByUsername(),
            self::usersToSelfDelete(),
            self::usersToSelfDeleteByUsername()
        ));
    }

    public static function mainUser()
    {
        return [
            'main' => [
                'user' => [
                    'username' => 'wallace',
                    'password' => 'wallace',
                ],
            ],
        ];
    }

    public static function otherUser()
    {
        return [
            'other' => [
                'user' => [
                    'username' => 'Other User',
                    'password' => '0th3rP4ssw0rd',
                ],
            ],
        ];
    }

    public static function additionalUsers()
    {
        return [
            [
                'user' => [
                    'username' => 'plop',
                    'password' => 'plop',
                ],
            ],
            [
                'user' => [
                    'username' => 'Talkie Walkie',
                    'password' => 'n0ns3ns3!',
                ],
            ],
            [
                'user' => [
                    'username' => 'john_doe',
                    'password' => 'qwerty123',
                ],
            ],
        ];
    }

    public static function usersToModify()
    {
        return [
            [
                'old' => [
                    'username' => 'alice',
                    'password' => 'w0nd3rl4nd',
                ],
                'new' => [
                    'username' => 'alice2leretour',
                    'password' => 'w0nd3rl4nd',
                ],
            ],
            [
                'old' => [
                    'username' => 'bob',
                    'password' => 'p4l1ndr0m3',
                ],
                'new' => [
                    'username' => 'bob3lavengeance',
                    'password' => '3m0rdn1l4p',
                ],
            ],
            [
                'old' => [
                    'username' => 'charlie_yksi',
                    'password' => 'l3nt0k0n3',
                ],
                'new' => [
                    'username' => 'charlie_kaksi',
                    'password' => 'l3nt0k0n3',
                ],
            ],
        ];
    }

    public static function usersToModifyByUsername()
    {
        return [
            [
                'old' => [
                    'username' => 'denise',
                    'password' => 'w0nd3rl4nd',
                ],
                'new' => [
                    'username' => 'deuxnise',
                    'password' => 'w0nd3rl4nd',
                ],
            ],
            [
                'old' => [
                    'username' => 'elvis',
                    'password' => 'p4l1ndr0m3',
                ],
                'new' => [
                    'username' => 'elviis',
                    'password' => '3m0rdn1l4p',
                ],
            ],
            [
                'old' => [
                    'username' => 'franck',
                    'password' => 'l3nt0k0n3',
                ],
                'new' => [
                    'username' => 'kcnarf',
                    'password' => 'l3nt0k0n3',
                ],
            ],
        ];
    }

    public static function usersToSelfModify()
    {
        return [
            [
                'old' => [
                    'username' => 'gaelle',
                    'password' => 'w0nd3rl4nd',
                ],
                'new' => [
                    'username' => 'guenael',
                    'password' => 'w0nd3rl4nd',
                ],
            ],
            [
                'old' => [
                    'username' => 'hector',
                    'password' => 'p4l1ndr0m3',
                ],
                'new' => [
                    'username' => 'hec thor',
                    'password' => '3m0rdn1l4p',
                ],
            ],
            [
                'old' => [
                    'username' => 'ignace',
                    'password' => 'l3nt0k0n3',
                ],
                'new' => [
                    'username' => 'iguane',
                    'password' => 'l3nt0k0n3',
                ],
            ],
        ];
    }

    public static function usersToSelfModifyByUsername()
    {
        return [
            [
                'old' => [
                    'username' => 'jean',
                    'password' => 'w0nd3rl4nd',
                ],
                'new' => [
                    'username' => 'jehan',
                    'password' => 'w0nd3rl4nd',
                ],
            ],
            [
                'old' => [
                    'username' => 'kathleen',
                    'password' => 'p4l1ndr0m3',
                ],
                'new' => [
                    'username' => 'quatre_lignes',
                    'password' => '3m0rdn1l4p',
                ],
            ],
            [
                'old' => [
                    'username' => 'laurent',
                    'password' => 'l3nt0k0n3',
                ],
                'new' => [
                    'username' => 'leaurentre',
                    'password' => 'l3nt0k0n3',
                ],
            ],
        ];
    }

    public static function usersToDelete()
    {
        return [
            [
                'user' => [
                    'username' => 'useless-user',
                    'password' => 'whyB0th3r',
                ],
            ],
            [
                'user' => [
                    'username' => 'not-so-useful',
                    'password' => 'mw4h4h4h4',
                ],
            ],
            [
                'user' => [
                    'username' => 'void',
                    'password' => 'v0idv0id',
                ],
            ],
        ];
    }

    public static function usersToDeleteByUsername()
    {
        return [
            [
                'user' => [
                    'username' => 'still-useless',
                    'password' => 'whyB0th3r',
                ],
            ],
            [
                'user' => [
                    'username' => 'still-not-useful',
                    'password' => 'mw4h4h4h4',
                ],
            ],
            [
                'user' => [
                    'username' => 'void of the void',
                    'password' => 'v0idv0id',
                ],
            ],
        ];
    }

    public static function usersToSelfDelete()
    {
        return [
            [
                'user' => [
                    'username' => 'self_destruct',
                    'password' => 'boum',
                ],
            ],
            [
                'user' => [
                    'username' => 'dis-integer',
                    'password' => 'zap',
                ],
            ],
            [
                'user' => [
                    'username' => 'Sue Side',
                    'password' => 'und3rth3cot3',
                ],
            ],
        ];
    }

    public static function usersToSelfDeleteByUsername()
    {
        return [
            [
                'user' => [
                    'username' => 'Thanos',
                    'password' => 'boum',
                ],
            ],
            [
                'user' => [
                    'username' => 'Hades',
                    'password' => 'zap',
                ],
            ],
            [
                'user' => [
                    'username' => 'Wotan',
                    'password' => 'und3rth3cot3',
                ],
            ],
        ];
    }

    public static function usersToCreate()
    {
        return [
            [
                'user' => [
                    'username' => 'Ray Cent',
                    'password' => 'th1sP4ss1sR3c3nt',
                ],
            ],
            [
                'user' => [
                    'username' => 'More Ressent',
                    'password' => 'fr3shP4ss',
                ],
            ],
            [
                'user' => [
                    'username' => 'Evan Morescent',
                    'password' => '1dontkn0wwh4ttos4y',
                ],
            ],
        ];
    }

    public static function invalidUsers()
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
