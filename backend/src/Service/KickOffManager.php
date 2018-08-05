<?php

namespace App\Service;

class KickOffManager
{
    const MONTHS = [
        'janvier',
        'février',
        'mars',
        'avril',
        'mai',
        'juin',
        'juillet',
        'août',
        'septembre',
        'octobre',
        'novembre',
        'décembre',
    ];

    const DAYS = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi',];

    public function prettyFormat(\DateTime $kickOff): string
    {
        $weekDay = self::DAYS[(int)$kickOff->format('w')];

        $day = (int)$kickOff->format('j');
        $dayText = $day === 1 ? $day . 'er' : $day;

        $month = self::MONTHS[(int)$kickOff->format('n') - 1];

        $year = $kickOff->format('Y');

        return "$weekDay $dayText $month $year";
    }


    public function isToday(\DateTime $kickOff): bool
    {
        $today = new \DateTime();
        return (int)$today->format('Y') === (int)$kickOff->format('Y')
            && (int)$today->format('n') === (int)$kickOff->format('n')
            && (int)$today->format('j') === (int)$kickOff->format('j');
    }
}
