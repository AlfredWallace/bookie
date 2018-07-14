<?php

namespace App\Service;

class AlternativePointsCalculator extends PointsCalculator
{

    protected function getResultPoints(): int
    {
        return 10;
    }

    protected function getScoreGapPoints(int $difference): int
    {
        switch ($difference) {
            case 0:
                return 10;
            case 1:
                return 7;
            case 2:
                return 5;
            case 3:
                return 3;
            case 4:
                return 2;
            case 5:
                return 1;
            default:
                return 0;
        }
    }
}