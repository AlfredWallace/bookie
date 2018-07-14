<?php

namespace App\DataFixtures;

use App\Entity\Team;
use App\Factory\MatchFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class MatchFixtures extends Fixture
{
    /**
     * @var MatchFactory
     */
    private $matchFactory;

    public function __construct(MatchFactory $matchFactory)
    {
        $this->matchFactory = $matchFactory;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $data = [
            [
                'homeTeam' => 'bel',
                'awayTeam' => 'eng',
                'kickOff' => '2018-07-14 16:00',
            ],
            [
                'homeTeam' => 'fra',
                'awayTeam' => 'cro',
                'kickOff' => '2018-07-15 17:00',
            ],
        ];

        $teams = [];
        foreach ($manager->getRepository(Team::class)->findAll() as $team) {
            $teams[$team->getAbbreviation()] = $team;
        }

        foreach ($data as $matchData) {
            $match = $this->matchFactory::createDefault(
                $teams[$matchData['homeTeam']],
                $teams[$matchData['awayTeam']],
                new \DateTime($matchData['kickOff'])
            );
            $manager->persist($match);
        }
        $manager->flush();
    }
}
