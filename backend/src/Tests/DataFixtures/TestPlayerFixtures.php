<?php

namespace App\Tests\DataFixtures;

use App\Entity\Player;
use App\Factory\PlayerFactory;
use App\Tests\DataProviders\PlayerProvider;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class TestPlayerFixtures extends AbstractFixture implements BookieTestFixtureInterface
{
    private $playerFactory;
    private $entityManager;

    public function __construct(PlayerFactory $playerFactory, EntityManagerInterface $entityManager)
    {
        $this->playerFactory = $playerFactory;
        $this->entityManager = $entityManager;
    }

    public function load(ObjectManager $manager)
    {
        $metadata = $this->entityManager->getClassMetaData(Player::class);
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
        $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());

        $mainPlayer = PlayerProvider::mainPlayer();
        $mainPlayerObject = $this->playerFactory->create(
            $mainPlayer['username'],
            $mainPlayer['password'],
            ['ROLE_ADMIN'],
            $mainPlayer['id']
        );
        $manager->persist($mainPlayerObject);

        foreach (PlayerProvider::fixturePlayers() as $player) {
            $playerObject = $this->playerFactory->create($player['username'], $player['password'], [], $player['id']);
            $manager->persist($playerObject);
        }
        $manager->flush();
    }
}
