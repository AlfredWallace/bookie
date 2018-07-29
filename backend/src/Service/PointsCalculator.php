<?php

namespace App\Service;

use App\Entity\Bet;
use App\Entity\Player;

abstract class PointsCalculator
{
    public function getUserPoints(Player $player): int
    {
        $points = 0;
        /** @var Bet $bet */
        foreach ($player->getBets() as $bet) {
            $points += $this->getBetPoints($bet);
        }

        return $points;
    }

    public function getBetPoints(Bet $bet): int
    {
        $points = 0;
        $match = $bet->getMatch();

        $betHomeScore = $bet->getHomeScore();
        $betAwayScore = $bet->getAwayScore();

        $matchHomeScore = $match->getHomeScore();
        $matchAwayScore = $match->getAwayScore();

        if ($match !== null && $match->isOver() && $betHomeScore !== null && $betAwayScore !== null) {
            if ($this->signOf($matchHomeScore - $matchAwayScore) === $this->signOf($betHomeScore - $betAwayScore)) {
                $points += $this->getResultPoints();

                $difference = abs($matchHomeScore - $betHomeScore) + abs($matchAwayScore - $betAwayScore);

                $points += $this->getScoreGapPoints($difference);
            }
        }

        return $points;
    }

    protected function signOf(int $n): int
    {
        return (int)($n > 0) - (int)($n < 0);
    }

    protected abstract function getResultPoints(): int;

    protected abstract function getScoreGapPoints(int $difference): int;
}
