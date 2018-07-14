<?php

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFactory
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function create($username, $password, $roles = ['ROLE_USER'])
    {
        $user = new User();
        $user
            ->setUsername($username)
            ->setPassword($this->encoder->encodePassword($user, $password))
            ->setRoles($roles)
        ;
        return $user;
    }
}
