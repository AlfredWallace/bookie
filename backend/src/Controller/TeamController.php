<?php

namespace App\Controller;

use App\Repository\TeamRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class TeamController extends FOSRestController
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
     * @Rest\Route("/teams", name="bookie_teams")
     * @Method({"GET"})
     * @Rest\View(statusCode=200, serializerGroups={"Default"})
     *
     * @return mixed
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function listTeams()
    {
        return $this->teamRepository->findAllIndexedById();
    }
}