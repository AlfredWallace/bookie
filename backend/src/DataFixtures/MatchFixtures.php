<?php

namespace App\DataFixtures;

use App\Entity\Team;
use App\Factory\MatchFactory;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class MatchFixtures extends AbstractEnvironmentFixture implements OrderedFixtureInterface
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
    protected function doLoad(ObjectManager $manager)
    {
        $data = [
//            [
//                'homeTeam' => 'fra',
//                'awayTeam' => 'arg',
//                'kickOff' => '2018-06-30 16:00',
//            ],
//            [
//                'homeTeam' => 'uru',
//                'awayTeam' => 'por',
//                'kickOff' => '2018-06-30 20:00',
//            ],
//            [
//                'homeTeam' => 'esp',
//                'awayTeam' => 'rus',
//                'kickOff' => '2018-07-01 16:00',
//            ],
//            [
//                'homeTeam' => 'cro',
//                'awayTeam' => 'den',
//                'kickOff' => '2018-07-01 20:00',
//            ],
//            [
//                'homeTeam' => 'bra',
//                'awayTeam' => 'mex',
//                'kickOff' => '2018-07-02 16:00',
//            ],
//            [
//                'homeTeam' => 'bel',
//                'awayTeam' => 'jpn',
//                'kickOff' => '2018-07-02 20:00',
//            ],
//            [
//                'homeTeam' => 'swe',
//                'awayTeam' => 'sui',
//                'kickOff' => '2018-07-03 16:00',
//            ],
//            [
//                'homeTeam' => 'col',
//                'awayTeam' => 'eng',
//                'kickOff' => '2018-07-03 20:00',
//            ],
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

    /**
     * Returns the environments the fixtures may be loaded in.
     *
     * @return array The name of the environments.
     */
    protected function getEnvironments(): array
    {
        return ['dev', 'test', 'prod'];
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 200;
    }
}
