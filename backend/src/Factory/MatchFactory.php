<?php

namespace App\Factory;

use App\Entity\Match;

class MatchFactory
{
    public static function createDefault($homeTeam, $awayTeam, $kickOff)
    {
        $match = new Match();
        $match
            ->setHomeTeam($homeTeam)
            ->setAwayTeam($awayTeam)
            ->setKickOff($kickOff)
        ;
        return $match;
    }
}
