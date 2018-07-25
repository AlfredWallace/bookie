<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use App\Providers\Tests\UserProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    private $userFactory;

    public function __construct(UserFactory $userFactory)
    {

        $this->userFactory = $userFactory;
    }

    public function load(ObjectManager $manager)
    {
        $mainUser = UserProvider::mainUser()['main']['user'];
        $mainUserObject = $this->userFactory->create(
            $mainUser['username'],
            $mainUser['password'],
            ['ROLE_ADMIN']
        );
        $manager->persist($mainUserObject);

        foreach (UserProvider::fixtureUsers() as $user) {
            $userObject = $this->userFactory->create($user['username'], $user['password']);
            $manager->persist($userObject);
        }
        $manager->flush();
    }
}
