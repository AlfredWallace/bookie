<?php

namespace App\Controller;

use App\Entity\Bet;
use App\Entity\User;
use App\Exception\DuplicateRessourceException;
use App\Factory\UserFactory;
use App\Repository\UserRepository;
use App\Serializer\ConstraintViolationUtilityTrait;
use App\Service\BasicPointsCalculator;
use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends FOSRestController
{
    const USER_NOT_FOUND_MESSAGE = 'User not found.';

    /**
     * @var UserRepository
     */
    private $userRepository;

    use ConstraintViolationUtilityTrait;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Rest\Route("/users/new", name="bookie_users_create", methods={"POST"})
     * @Rest\View(statusCode=201, serializerGroups={"user.default"})
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body"
     * )
     *
     * @param User $user
     * @param UserFactory $factory
     * @param ConstraintViolationListInterface $validationErrors
     * @return mixed
     * @throws DuplicateRessourceException
     */
    public function newAction(
        User $user,
        UserFactory $factory,
        ConstraintViolationListInterface $validationErrors
    )
    {
        $this->checkViolations($validationErrors);
        $em = $this->getDoctrine()->getManager();
        $username = $user->getUsername();
        $dbUser = $this->userRepository->findOneBy(['username' => $username]);
        if (!is_null($dbUser)) {
            throw new DuplicateRessourceException("The user with the login $username already exists");
        }
        $user = $factory->create($username, $user->getPassword());
        $em->persist($user);
        $em->flush();

        return $user;
    }

    /**
     * @Rest\Route("/users/{id}", name="bookie_users_update", requirements={"id"="\d+"}, methods={"PUT"})
     * @Rest\View(statusCode=200, serializerGroups={"user.default"})
     * @ParamConverter(
     *     "requestUser",
     *     converter="fos_rest.request_body"
     * )
     * @Security("is_granted('ROLE_ADMIN') or id == user.getId()")
     *
     * @param User $dbUser
     * @param User $requestUser
     * @param UserPasswordEncoderInterface $encoder
     * @param ConstraintViolationListInterface $validationErrors
     * @return mixed
     * @throws EntityNotFoundException
     */
    public function updateAction(
        User $dbUser = null,
        User $requestUser,
        UserPasswordEncoderInterface $encoder,
        ConstraintViolationListInterface $validationErrors
    )
    {
        if (is_null($dbUser)) {
            throw new EntityNotFoundException(self::USER_NOT_FOUND_MESSAGE);
        }
        $this->checkViolations($validationErrors);
        $dbUser->setUsername($requestUser->getUsername());
        $dbUser->setPassword($encoder->encodePassword($dbUser, $requestUser->getPassword()));
        $em = $this->getDoctrine()->getManager();
        $em->persist($dbUser);
        $em->flush();

        return $dbUser;
    }

    /**
     * @Rest\Route("/users/username/{username}", name="bookie_users_update_by_username", methods={"PUT"})
     * @Rest\View(statusCode=200, serializerGroups={"user.default"})
     * @ParamConverter(
     *     "requestUser",
     *     converter="fos_rest.request_body"
     * )
     * @Security("is_granted('ROLE_ADMIN') or username == user.getUsername()")
     *
     * @param User $dbUser
     * @param User $requestUser
     * @param UserPasswordEncoderInterface $encoder
     * @param ConstraintViolationListInterface $validationErrors
     * @return mixed
     * @throws EntityNotFoundException
     */
    public function updateByUsernameAction(
        User $dbUser = null,
        User $requestUser,
        UserPasswordEncoderInterface $encoder,
        ConstraintViolationListInterface $validationErrors
    )
    {
        if (is_null($dbUser)) {
            throw new EntityNotFoundException(self::USER_NOT_FOUND_MESSAGE);
        }
        $this->checkViolations($validationErrors);
        $dbUser->setUsername($requestUser->getUsername());
        $dbUser->setPassword($encoder->encodePassword($dbUser, $requestUser->getPassword()));
        $em = $this->getDoctrine()->getManager();
        $em->persist($dbUser);
        $em->flush();

        return $dbUser;
    }

    /**
     * @Rest\Route("/users/{id}", name="bookie_users_show", requirements={"id"="\d+"}, methods={"GET"})
     * @Rest\View(statusCode=200, serializerGroups={"user.default"})
     *
     * @param User $user
     * @return mixed
     * @throws EntityNotFoundException
     */
    public function showAction(User $user = null)
    {
        if (is_null($user)) {
            throw new EntityNotFoundException(self::USER_NOT_FOUND_MESSAGE);
        }
        return $user;
    }

    /**
     * @Rest\Route("/users/username/{username}", name="bookie_users_show_by_username", methods={"GET"})
     * @Rest\View(statusCode=200, serializerGroups={"user.default"})
     *
     * @param User $user
     * @return mixed
     * @throws EntityNotFoundException
     */
    public function showByUsernameAction(User $user = null)
    {
        if (is_null($user)) {
            throw new EntityNotFoundException(self::USER_NOT_FOUND_MESSAGE);
        }
        return $user;
    }

    /**
     * @Rest\Route("/users", name="bookie_users_list", methods={"GET"})
     * @Rest\View(statusCode=200, serializerGroups={"user.default"})
     *
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function listAction()
    {
        return $this->userRepository->findAllIndexedById();
    }

    /**
     * @Rest\Route("/users/{id}", name="bookie_users_remove", requirements={"id"="\d+"}, methods={"DELETE"})
     * @Rest\View(statusCode=204)
     * @Security("is_granted('ROLE_ADMIN') or id == user.getId()")
     *
     * @param User $user
     * @throws EntityNotFoundException
     */
    public function removeAction(User $user = null)
    {
        if (is_null($user)) {
            throw new EntityNotFoundException(self::USER_NOT_FOUND_MESSAGE);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
    }

    /**
     * @Route("/users/username/{username}", name="bookie_users_remove_by_username", methods={"DELETE"})
     * @Rest\View(statusCode=204)
     * @Security("is_granted('ROLE_ADMIN') or username == user.getUsername()")
     *
     * @param User $user
     * @throws EntityNotFoundException
     */
    public function removeByUsernameAction(User $user = null)
    {
        if (is_null($user)) {
            throw new EntityNotFoundException(self::USER_NOT_FOUND_MESSAGE);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
    }

    /**
     * @Rest\Route("/users/refresh-all-points", name="bookie_users_refresh_all_points", methods={"POST"})
     * @Rest\View(statusCode=204)
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
