<?php

namespace App\Controller;

use App\Entity\Bet;
use App\Entity\User;
use App\Factory\UserFactory;
use App\Repository\UserRepository;
use App\Service\BasicPointsCalculator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Exception\ValidatorException;

class UserController extends ApiController
{
    public const USER_NOT_FOUND_MESSAGE = 'Utilisateur introuvable.';
    public const DEFAULT_SERIALIZATION_GROUPS = ['user.default'];

    /**
     * @Route(
     *     "/users/new",
     *     name="bookie_users_create",
     *     methods={"POST"}
     * )
     *
     * @param Request $request
     * @param UserFactory $factory
     * @return JsonResponse
     */
    public function new(Request $request, UserFactory $factory): JsonResponse
    {
        try {
            $user = $this->getValidatedObject($request->getContent(), User::class);
        } catch (ValidatorException $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $em = $this->getDoctrine()->getManager();

        $username = $user->getUsername();
        $dbUser = $em->getRepository(User::class)->findOneBy(['username' => $username]);
        if ($dbUser !== null) {
            return new JsonResponse("L'utilisateur '$username' existe déjà !", Response::HTTP_BAD_REQUEST);
        }

        $user = $factory->create($username, $user->getPassword());
        $em->persist($user);
        $em->flush();

        return $this->getSerializedResponse($user, self::DEFAULT_SERIALIZATION_GROUPS);
    }

    /**
     * @Route(
     *     "/users/{id}",
     *     name="bookie_users_show",
     *     methods={"GET"},
     *     requirements={"id"="\d+"}
     * )
     *
     * @param User|null $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        if ($user === null) {
            return new JsonResponse(self::USER_NOT_FOUND_MESSAGE, Response::HTTP_NOT_FOUND);
        }
        return $this->getSerializedResponse($user, self::DEFAULT_SERIALIZATION_GROUPS);
    }

    /**
     * @Route(
     *     "/users",
     *     name="bookie_users_list",
     *     methods={"GET"}
     * )
     *
     * @param UserRepository $userRepository
     * @return JsonResponse
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function list(UserRepository $userRepository): JsonResponse
    {
        return $this->getSerializedResponse($userRepository->findAllIndexedById(), self::DEFAULT_SERIALIZATION_GROUPS);
    }

    /**
     * @Route(
     *     "/users/{id}",
     *     name="bookie_users_update",
     *     methods={"PUT"},
     *     requirements={"id"="\d+"}
     * )
     *
     * @Security("is_granted('ROLE_ADMIN') or id == user.getId()")
     *
     * @param Request $request
     * @param User $user
     * @param UserPasswordEncoderInterface $encoder
     * @return JsonResponse
     */
    public function update(Request $request, User $user, UserPasswordEncoderInterface $encoder): JsonResponse
    {
        if ($user === null) {
            return new JsonResponse(self::USER_NOT_FOUND_MESSAGE, Response::HTTP_NOT_FOUND);
        }

        try {
            $requestBodyUser = $this->getValidatedObject($request->getContent(), User::class);
        } catch (ValidatorException $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $user->setUsername($requestBodyUser->getUsername());
        $user->setPassword($encoder->encodePassword($user, $requestBodyUser->getPassword()));
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->getSerializedResponse($user, self::DEFAULT_SERIALIZATION_GROUPS);
    }

    /**
     * @Route(
     *     "/users/{id}",
     *     name="bookie_users_remove",
     *     methods={"DELETE"},
     *     requirements={"id"="\d+"}
     * )
     *
     * @Security("is_granted('ROLE_ADMIN') or id == user.getId()")
     *
     * @param User $user
     * @return JsonResponse
     */
    public function remove(User $user): JsonResponse
    {
        if ($user === null) {
            return new JsonResponse(self::USER_NOT_FOUND_MESSAGE, Response::HTTP_NOT_FOUND);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/users/refresh-all-points", name="bookie_users_refresh_all_points", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param BasicPointsCalculator $basicCalculator
     */
    public function refreshAllPoints(BasicPointsCalculator $basicCalculator)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        foreach ($this->userRepository->findAll() as $user) {
            /** @var Bet $bet */
            foreach ($user->getBets() as $bet) {
                $bet->setPoints($basicCalculator->getBetPoints($bet));
            }

            $user->setPoints($basicCalculator->getUserPoints($user));
        }

        $em->flush();
    }
}
