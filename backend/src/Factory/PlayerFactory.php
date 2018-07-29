<?php

namespace App\Factory;

use App\Entity\Player;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PlayerFactory
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function create(string $username, string $password, array $roles = [], int $id = null): Player
    {
        $user = new Player();
        $user
            ->setUsername($username)
            ->setPassword($this->encoder->encodePassword($user, $password))
            ->setRoles($roles === [] ? ['ROLE_USER'] : $roles);

        if ($id !== null) {
            $user->setId($id);
        }

        return $user;
    }
}
