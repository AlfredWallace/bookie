<?php

namespace App\Controller;

use App\Entity\Bet;
use App\Exception\BetFormatException;
use App\Exception\MatchStartedException;
use App\Repository\BetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BetController extends ApiController
{
    /**
     * @var BetRepository
     */
    private $betRepository;

    public function __construct(BetRepository $betRepository)
    {
        $this->betRepository = $betRepository;
    }

    /**
     * @Route("/bets", name="bookie_bets", methods={"GET"})
     *
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function listBets()
    {
        return $this->betRepository->findAllIndexedById();
    }

    /**
     * @Route("/bets/group-stage", name="bookie_bets_group_stage_post", methods={"POST"})
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Bet
     * @throws EntityNotFoundException
     * @throws \App\Exception\BetFormatException
     * @throws \App\Exception\MatchStartedException
     */
    public function postGroupStageAction(Request $request, EntityManagerInterface $manager)
    {
        $data = $request->getContent();
        $betArray = json_decode($data, true);

        if (!isset($betArray['match']) || !is_integer($betArray['match'])) {
            throw new BetFormatException('The "match" key must be set to the match id.');
        }

        if (!isset($betArray['user']) || !is_integer($betArray['user'])) {
            throw new BetFormatException('The "user" key must be set to the user id.');
        }

        if ((isset($betArray['home_score']) && $betArray['home_score'] < 0)
            || (isset($betArray['away_score']) && $betArray['away_score'] < 0)) {
            throw new BetFormatException('Les scores ne peuvent pas être négatifs !');
        }

        $match = $manager->getRepository('App:Match')->find($betArray['match']);
        if (is_null($match)) {
            throw new EntityNotFoundException(MatchController::MATCH_NOT_FOUND_MESSAGE);
        }
        if ($match->getKickOff() < (new \DateTime())->modify('-1 minutes')) {
            throw new MatchStartedException('Le match a déjà commencé !');
        }

        $user = $manager->getRepository('App:User')->find($betArray['user']);
        if (is_null($user)) {
            throw new EntityNotFoundException(UserController::USER_NOT_FOUND_MESSAGE);
        }

        $bet = $manager->getRepository('App:Bet')->findOneBy([
            'user' => $user,
            'match' => $match,
        ]);

        if (is_null($bet)) {
            $bet = new Bet();
            $bet
                ->setUser($user)
                ->setMatch($match)
            ;
            $manager->persist($bet);
        }

        $bet
            ->setHomeScore($betArray['home_score'] ?? 0)
            ->setAwayScore($betArray['away_score'] ?? 0)
        ;
        $manager->flush();

        return $bet;
    }
}
