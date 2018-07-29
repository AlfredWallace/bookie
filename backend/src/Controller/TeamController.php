<?php

namespace App\Controller;

use App\Entity\Team;
use App\Repository\TeamRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends ApiController
{
    public const DEFAULT_SERIALIZATION_GROUPS = ['team.default'];

    /**
     * @Route(
     *     "/teams/{id}",
     *     name="bookie_teams_show",
     *     methods={"GET"},
     *     requirements={"id"="\d+"}
     * )
     *
     * @param Team $team
     * @return JsonResponse
     */
    public function show(Team $team): JsonResponse
    {
        return $this->getSerializedResponse($team, self::DEFAULT_SERIALIZATION_GROUPS);
    }

    /**
     * @Route(
     *     "/teams",
     *     name="bookie_teams",
     *     methods={"GET"}
     * )
     *
     * @param TeamRepository $teamRepository
     * @return JsonResponse
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function list(TeamRepository $teamRepository): JsonResponse
    {
        return $this->getSerializedResponse($teamRepository->findAllIndexedById(), ['team.default']);
    }
}
