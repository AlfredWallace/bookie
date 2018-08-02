<?php

namespace App\Controller;

use App\Entity\Team;
use App\Repository\TeamRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends ApiController
{
    public const DEFAULT_SERIALIZATION_GROUPS = ['team.default'];

    /**
     * @Route(
     *     "/teams",
     *     name="bookie_teams_new",
     *     methods={"POST"}
     * )
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function new(Request $request): JsonResponse
    {
        $team = $this->getValidatedObject($request->getContent(), Team::class);

        $em = $this->getDoctrine()->getManager();

        $em->persist($team);
        $em->flush();

        return $this->getSerializedResponse($team, self::DEFAULT_SERIALIZATION_GROUPS, Response::HTTP_CREATED);
    }

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
