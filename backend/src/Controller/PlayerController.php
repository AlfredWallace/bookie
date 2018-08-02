<?php

namespace App\Controller;

use App\Entity\Player;
use App\Factory\PlayerFactory;
use App\Repository\PlayerRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PlayerController extends ApiController
{
    public const DEFAULT_SERIALIZATION_GROUPS = ['player.default'];

    /**
     * @Route(
     *     "/players/new",
     *     name="bookie_players_new",
     *     methods={"POST"}
     * )
     *
     * @param Request $request
     * @param PlayerFactory $factory
     * @return JsonResponse
     */
    public function new(Request $request, PlayerFactory $factory): JsonResponse
    {
        $player = $this->getValidatedObject($request->getContent(), Player::class);

        $em = $this->getDoctrine()->getManager();

        $playername = $player->getUsername();

        $player = $factory->create($playername, $player->getPassword());
        $em->persist($player);
        $em->flush();

        return $this->getSerializedResponse($player, self::DEFAULT_SERIALIZATION_GROUPS, Response::HTTP_CREATED);
    }

    /**
     * @Route(
     *     "/players/{id}",
     *     name="bookie_players_show",
     *     methods={"GET"},
     *     requirements={"id"="\d+"}
     * )
     *
     * @param Player|null $player
     * @return JsonResponse
     */
    public function show(Player $player): JsonResponse
    {
        return $this->getSerializedResponse($player, self::DEFAULT_SERIALIZATION_GROUPS);
    }

    /**
     * @Route(
     *     "/players",
     *     name="bookie_players_list",
     *     methods={"GET"}
     * )
     *
     * @param PlayerRepository $playerRepository
     * @return JsonResponse
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function list(PlayerRepository $playerRepository): JsonResponse
    {
        return $this->getSerializedResponse($playerRepository->findAllIndexedById(), self::DEFAULT_SERIALIZATION_GROUPS);
    }

    /**
     * @Route(
     *     "/players/{id}",
     *     name="bookie_players_update",
     *     methods={"PUT"},
     *     requirements={"id"="\d+"}
     * )
     *
     * @Security("is_granted('ROLE_ADMIN') or id == user.getId()")
     *
     * @param Request $request
     * @param Player $player
     * @param UserPasswordEncoderInterface $encoder
     * @return JsonResponse
     */
    public function update(Request $request, Player $player, UserPasswordEncoderInterface $encoder): JsonResponse
    {
        $requestBodyPlayer = $this->getValidatedObject($request->getContent(), Player::class);

        $player->setUsername($requestBodyPlayer->getUsername());
        $player->setPassword($encoder->encodePassword($player, $requestBodyPlayer->getPassword()));
        $em = $this->getDoctrine()->getManager();
        $em->persist($player);
        $em->flush();

        return $this->getSerializedResponse($player, self::DEFAULT_SERIALIZATION_GROUPS);
    }

    /**
     * @Route(
     *     "/players/{id}",
     *     name="bookie_players_remove",
     *     methods={"DELETE"},
     *     requirements={"id"="\d+"}
     * )
     *
     * @Security("is_granted('ROLE_ADMIN') or id == user.getId()")
     *
     * @param Player $player
     * @return JsonResponse
     */
    public function remove(Player $player): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($player);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

//    /**
//     * @Route("/players/refresh-all-points", name="bookie_players_refresh_all_points", methods={"POST"})
//     * @Security("is_granted('ROLE_ADMIN')")
//     *
//     * @param BasicPointsCalculator $basicCalculator
//     */
//    public function refreshAllPoints(BasicPointsCalculator $basicCalculator)
//    {
//        $em = $this->getDoctrine()->getManager();
//        /** @var Player $player */
//        foreach ($this->userRepository->findAll() as $player) {
//            /** @var Bet $bet */
//            foreach ($player->getBets() as $bet) {
//                $bet->setPoints($basicCalculator->getBetPoints($bet));
//            }
//
//            $player->setPoints($basicCalculator->getUserPoints($player));
//        }
//
//        $em->flush();
//    }
}
