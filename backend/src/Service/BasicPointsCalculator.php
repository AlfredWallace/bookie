<?php

namespace App\Service;

class BasicPointsCalculator extends PointsCalculator
{
    protected function getResultPoints(): int
    {
        return 5;
    }

    protected function getScoreGapPoints(int $difference): int
    {
        return 5 - min($difference, 5);
    }
}