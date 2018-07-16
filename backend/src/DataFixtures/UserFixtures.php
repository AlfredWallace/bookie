<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use App\Providers\Tests\UserProvider;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends AbstractEnvironmentFixture
{
    /**
     * @var UserFactory
     */
    private $userFactory;

    public function __construct(UserFactory $userFactory)
    {

        $this->userFactory = $userFactory;
    }

    /**
     * Performs the actual fixtures loading.
     *
     * @see \Doctrine\Common\DataFixtures\FixtureInterface::load()
     *
     * @param ObjectManager $manager The object manager.
     */
    protected function doLoad(ObjectManager $manager)
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

    /**
     * Returns the environments the fixtures may be loaded in.
     *
     * @return array The name of the environments.
     */
    protected function getEnvironments(): array
    {
        return ['dev', 'test'];
    }
}
