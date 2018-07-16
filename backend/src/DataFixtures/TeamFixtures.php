<?php

namespace App\DataFixtures;

use App\Entity\Team;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TeamFixtures extends AbstractEnvironmentFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    protected function doLoad(ObjectManager $manager)
    {
        $data = [
            'rus' => 'Russie',
            'ksa' => 'Arabie Saoudite',
            'egy' => 'Égypte',
            'uru' => 'Uruguay',

            'por' => 'Portugal',
            'esp' => 'Espagne',
            'mar' => 'Maroc',
            'irn' => 'Iran',

            'fra' => 'France',
            'aus' => 'Australie',
            'per' => 'Pérou',
            'den' => 'Danemark',

            'arg' => 'Argentine',
            'isl' => 'Islande',
            'cro' => 'Croatie',
            'nga' => 'Nigeria',

            'bra' => 'Brésil',
            'sui' => 'Suisse',
            'crc' => 'Costa Rica',
            'srb' => 'Serbie',

            'ger' => 'Allemagne',
            'mex' => 'Mexique',
            'swe' => 'Suède',
            'kor' => 'Corée du Sud',

            'bel' => 'Belgique',
            'pan' => 'Panama',
            'tun' => 'Tunisie',
            'eng' => 'Angleterre',

            'pol' => 'Pologne',
            'sen' => 'Sénégal',
            'col' => 'Colombie',
            'jpn' => 'Japon',
        ];

        foreach ($data as $abbr => $name) {
            $team = new Team($name, $abbr);
            $manager->persist($team);
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

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 100;
    }
}
