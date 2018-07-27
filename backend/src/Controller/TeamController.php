<?php

namespace App\Controller;

use App\Repository\TeamRepository;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends ApiController
{
    /**
     * @var TeamRepository
     */
    private $teamRepository;

    public function __construct(TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    /**
     * @Route("/teams", name="bookie_teams", methods={"GET"})
     *
     * @return mixed
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function listTeams()
    {
        return $this->teamRepository->findAllIndexedById();
    }
}
