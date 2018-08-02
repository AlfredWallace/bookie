<?php

namespace App\Tests\DataFixtures;

use App\DataFixtures\BookieDevFixtureInterface;
use App\Entity\Team;
use App\Tests\DataProviders\TeamProvider;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class TestTeamFixtures extends AbstractFixture implements BookieTestFixtureInterface, BookieDevFixtureInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function load(ObjectManager $manager)
    {
        $metadata = $this->entityManager->getClassMetaData(Team::class);
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
        $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());

        foreach (TeamProvider::fixtureTeams() as $team) {
            $teamObject = new Team($team['name'], $team['abbreviation']);
            if (isset($team['id'])) {
                $teamObject->setId($team['id']);
            }
            $manager->persist($teamObject);
        }
        $manager->flush();
    }
}
