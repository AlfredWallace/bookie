<?php

namespace App\Tests\Unit;

use App\Entity\Bet;
use App\Entity\Match;
use App\Entity\Team;
use App\Factory\MatchFactory;
use App\Service\AlternativePointsCalculator;
use App\Service\BasicPointsCalculator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PointsCalculatorTest extends KernelTestCase
{
    /** @var BasicPointsCalculator */
    private static $basicCalculator;

    /** @var AlternativePointsCalculator */
    private static $alternativeCalculator;

    public static function setUpBeforeClass()
    {
        self::bootKernel();
        self::$basicCalculator = self::$container->get('test.app.basic_points_calculator');
        self::$alternativeCalculator = self::$container->get('test.app.alternative_points_calculator');
    }

    public static function betPointsData()
    {
        return [
            [
                'match' => [ 'home' => 3, 'away' => 1, ],
                'bet' => [ 'home' => 1, 'away' => 2, ],
                'basicPoints' => 0,
                'alternativePoints' => 0,
            ],
            [
                'match' => [ 'home' => 3, 'away' => 1, ],
                'bet' => [ 'home' => 2, 'away' => 2, ],
                'basicPoints' => 0,
                'alternativePoints' => 0,
            ],
            [
                'match' => [ 'home' => 2, 'away' => 5, ],
                'bet' => [ 'home' => 3, 'away' => 1, ],
                'basicPoints' => 0,
                'alternativePoints' => 0,
            ],
            [
                'match' => [ 'home' => 2, 'away' => 5, ],
                'bet' => [ 'home' => 3, 'away' => 3, ],
                'basicPoints' => 0,
                'alternativePoints' => 0,
            ],
            [   
                'match' => [ 'home' => 3, 'away' => 3, ],
                'bet' => [ 'home' => 3, 'away' => 1, ],
                'basicPoints' => 0,
                'alternativePoints' => 0,
            ],
            [
                'match' => [ 'home' => 3, 'away' => 3, ],
                'bet' => [ 'home' => 2, 'away' => 4, ],
                'basicPoints' => 0,
                'alternativePoints' => 0,
            ],
            [
                'match' => [ 'home' => 0, 'away' => 0, ],
                'bet' => [ 'home' => 0, 'away' => 0, ],
                'basicPoints' => 10,
                'alternativePoints' => 20,
            ],
            [
                'match' => [ 'home' => 0, 'away' => 0, ],
                'bet' => [ 'home' => 4, 'away' => 4, ],
                'basicPoints' => 5,
                'alternativePoints' => 10,
            ],
            [
                'match' => [ 'home' => 10, 'away' => 0, ],
                'bet' => [ 'home' => 1, 'away' => 0, ],
                'basicPoints' => 5,
                'alternativePoints' => 10,
            ],
            [
                'match' => [ 'home' => 10, 'away' => 8, ],
                'bet' => [ 'home' => 10, 'away' => 0, ],
                'basicPoints' => 5,
                'alternativePoints' => 10,
            ],
            [
                'match' => [ 'home' => 2, 'away' => 12, ],
                'bet' => [ 'home' => 1, 'away' => 2, ],
                'basicPoints' => 5,
                'alternativePoints' => 10,
            ],
            [
                'match' => [ 'home' => 10, 'away' => 12, ],
                'bet' => [ 'home' => 1, 'away' => 12, ],
                'basicPoints' => 5,
                'alternativePoints' => 10,
            ],
            [
                'match' => [ 'home' => 4, 'away' => 1, ],
                'bet' => [ 'home' => 1, 'away' => 0, ],
                'basicPoints' => 6,
                'alternativePoints' => 12,
            ],
            [
                'match' => [ 'home' => 4, 'away' => 1, ],
                'bet' => [ 'home' => 2, 'away' => 0, ],
                'basicPoints' => 7,
                'alternativePoints' => 13,
            ],
            [
                'match' => [ 'home' => 3, 'away' => 1, ],
                'bet' => [ 'home' => 4, 'away' => 0, ],
                'basicPoints' => 8,
                'alternativePoints' => 15,
            ],
            [
                'match' => [ 'home' => 3, 'away' => 1, ],
                'bet' => [ 'home' => 4, 'away' => 1, ],
                'basicPoints' => 9,
                'alternativePoints' => 17,
            ],
            [
                'match' => [ 'home' => 2, 'away' => 5, ],
                'bet' => [ 'home' => 3, 'away' => 4, ],
                'basicPoints' => 8,
                'alternativePoints' => 15,
            ],
            [
                'match' => [ 'home' => 2, 'away' => 5, ],
                'bet' => [ 'home' => 1, 'away' => 6, ],
                'basicPoints' => 8,
                'alternativePoints' => 15,
            ],
            [
                'match' => [ 'home' => 3, 'away' => 3, ],
                'bet' => [ 'home' => 2, 'away' => 2, ],
                'basicPoints' => 8,
                'alternativePoints' => 15,
            ],
            [
                'match' => [ 'home' => 3, 'away' => 3, ],
                'bet' => [ 'home' => 1, 'away' => 1, ],
                'basicPoints' => 6,
                'alternativePoints' => 12,
            ],
            [
                'match' => [ 'home' => 3, 'away' => 3, ],
                'bet' => [ 'home' => 4, 'away' => 4, ],
                'basicPoints' => 8,
                'alternativePoints' => 15,
            ],
            [
                'match' => [ 'home' => 3, 'away' => 3, ],
                'bet' => [ 'home' => 5, 'away' => 5, ],
                'basicPoints' => 6,
                'alternativePoints' => 12,
            ],
        ];
    }

    /**
     * @dataProvider betPointsData
     * @param $match
     * @param $bet
     * @param $basicPoints
     * @param $alternativePoints
     */
    public function testBetPoints($match, $bet, $basicPoints, $alternativePoints)
    {
        $matchObject = MatchFactory::createDefault(
            $this->createMock(Team::class),
            $this->createMock(Team::class),
            new \DateTime()
        );
        $matchObject->setHomeScore($match['home']);
        $matchObject->setAwayScore($match['away']);
        $matchObject->setIsOver(true);

        $betObject = new Bet();
        $betObject->setHomeScore($bet['home']);
        $betObject->setAwayScore($bet['away']);
        $betObject->setMatch($matchObject);

        $this->assertEquals($basicPoints, self::$basicCalculator->getBetPoints($betObject));
        $this->assertEquals($alternativePoints, self::$alternativeCalculator->getBetPoints($betObject));
    }
}