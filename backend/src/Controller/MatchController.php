<?php

namespace App\Controller;

use App\Entity\Bet;
use App\Entity\Match;
use App\Exception\ScoreFormatException;
use App\Repository\MatchRepository;
use App\Service\AlternativePointsCalculator;
use App\Service\BasicPointsCalculator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MatchController extends ApiController
{
    const MATCH_NOT_FOUND_MESSAGE = 'Match not found.';

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var MatchRepository
     */
    private $matchRepository;

    public function __construct(
        SerializerInterface $serializer,
        MatchRepository $matchRepository)
    {
        $this->serializer = $serializer;
        $this->matchRepository = $matchRepository;
    }

    /**
     * @Route("/matches", name="bookie_matches", methods={"GET"})
     *
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function listMatches()
    {
        return $this->matchRepository->findAllIndexedById();
    }

    /**
     * @Route("/matches/{id}/end", name="bookie_matches_end", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Match $match
     * @param Request $request
     * @param BasicPointsCalculator $basicCalculator
     * @param AlternativePointsCalculator $alternativeCalculator
     * @return mixed
     * @throws ScoreFormatException
     */
    public function endMatchAction(
        Match $match,
        Request $request,
        BasicPointsCalculator $basicCalculator,
        AlternativePointsCalculator $alternativeCalculator
    )
    {
        $data = $request->getContent();
        $scores = json_decode($data, true);

        if ((isset($scores['home_score']) && $scores['home_score'] < 0)
            || (isset($scores['away_score']) && $scores['away_score'] < 0)) {
            throw new ScoreFormatException('Les scores ne peuvent pas être négatifs !');
        }

        $match->setIsOver(true);
        $match->setHomeScore($scores['home_score']);
        $match->setAwayScore($scores['away_score']);

        /** @var Bet $bet */
        foreach ($match->getBets() as $bet) {
            $bet->setPoints($basicCalculator->getBetPoints($bet));
            $bet->setPointsAlternative($alternativeCalculator->getBetPoints($bet));

            $user = $bet->getUser();
            $user->setPoints($basicCalculator->getUserPoints($user));
            $user->setPointsAlternative($alternativeCalculator->getUserPoints($user));
        }

        $this->getDoctrine()->getManager()->flush();

        return $match;
    }
}
