<?php

namespace App\Controller;

use App\Entity\Bet;
use App\Entity\User;
use App\Exception\DuplicateRessourceException;
use App\Factory\UserFactory;
use App\Serializer\ConstraintViolationUtilityTrait;
use App\Service\AlternativePointsCalculator;
use App\Service\BasicPointsCalculator;
use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @var SerializerInterface
     */
    private $serializer;

    use ConstraintViolationUtilityTrait;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Rest\Route("/users/new", name="bookie_users_create")
     * @Method({"POST"})
     * @Rest\View(statusCode=201, serializerGroups={"Default"})
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body",
     *     options={
     *          "validator"={ "groups"="create" }
     *     }
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
    ) {
        $this->checkViolations($validationErrors);
        $em = $this->getDoctrine()->getManager();
        $username = $user->getUsername();
        $dbUser = $em->getRepository('App:User')->findOneBy(['username' => $username]);
        if (!is_null($dbUser)) {
            throw new DuplicateRessourceException("The user with the login $username already exists");
        }
        $user = $factory->create($username, $user->getPassword());
        $em->persist($user);
        $em->flush();

        return $user;
    }

    /**
     * @Rest\Route("/users/{id}", name="bookie_users_update", requirements={"id"="\d+"})
     * @Method({"PUT"})
     * @Rest\View(statusCode=200, serializerGroups={"Default"})
     * @ParamConverter(
     *     "requestUser",
     *     converter="fos_rest.request_body",
     *     options={
     *          "validator"={ "groups"="update" }
     *     }
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
    ) {
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
     * @Rest\Route("/users/username/{username}", name="bookie_users_update_by_username")
     * @Method({"PUT"})
     * @Rest\View(statusCode=200, serializerGroups={"Default"})
     * @ParamConverter(
     *     "requestUser",
     *     converter="fos_rest.request_body",
     *     options={
     *          "validator"={ "groups"="update" }
     *     }
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
    ) {
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
     * @Rest\Route("/users/{id}", name="bookie_users_show", requirements={"id"="\d+"})
     * @Method({"GET"})
     * @Rest\View(statusCode=200, serializerGroups={"Default"})
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
     * @Rest\Route("/users/username/{username}", name="bookie_users_show_by_username")
     * @Method({"GET"})
     * @Rest\View(statusCode=200, serializerGroups={"Default"})
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
     * @Rest\Route("/users", name="bookie_users_list")
     * @Method({"GET"})
     * @Rest\View(statusCode=200, serializerGroups={"Default"})
     */
    public function listAction()
    {
        return $this->getDoctrine()->getManager()->getRepository('App:User')->findBy([], [
            'points' => 'DESC',
            'username' => 'ASC',
        ]);
    }

    /**
     * @Rest\Route("/users-bets-stats", name="bookie_users_bets_stats_list")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function listWithBetsStats()
    {
        $users =  $this->getDoctrine()->getManager()->getRepository('App:User')->findBy([], [
            'points' => 'DESC',
            'username' => 'ASC',
        ]);
        $usersArray = [];

        foreach ($users as $user) {
            $userData = json_decode(
                $this->serializer->serialize(
                    $user,
                    'json',
                    SerializationContext::create()->setGroups(['Default'])
                ),
                true
            );

            $userData['nbBets'] = 0;
            $userData['nbWins'] = 0;
            $userData['nbPerfects'] = 0;

            /** @var Bet $bet */
            foreach ($user->getBets() as $bet) {
                $match = $bet->getMatch();

                if ($match->isOver()) {
                    $userData['nbBets']++;

                    if ($bet->getPoints() > 0) {
                        $userData['nbWins']++;
                    }

                    if ($bet->getHomeScore() === $match->getHomeScore()
                        && $bet->getAwayScore() === $match->getAwayScore()) {
                        $userData['nbPerfects']++;
                    }
                }
            }

            $usersArray[] = $userData;
        }

        return new JsonResponse($usersArray);
    }

    /**
     * @Rest\Route("/users/{id}", name="bookie_users_remove", requirements={"id"="\d+"})
     * @Method({"DELETE"})
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
     * @Route("/users/username/{username}", name="bookie_users_remove_by_username")
     * @Method({"DELETE"})
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
     * @Rest\Route("/users/refresh-all-points", name="bookie_users_refresh_all_points")
     * @Method({"POST"})
     * @Rest\View(statusCode=204)
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param BasicPointsCalculator $basicCalculator
     * @param AlternativePointsCalculator $alternativeCalculator
     */
    public function refreshAllPoints(
        BasicPointsCalculator $basicCalculator,
        AlternativePointsCalculator $alternativeCalculator
    )
    {
        $em = $this->getDoctrine()->getManager();
        foreach ($em->getRepository('App:User')->findAll() as $user) {
            /** @var Bet $bet */
            foreach ($user->getBets() as $bet) {
                $bet->setPoints($basicCalculator->getBetPoints($bet));
                $bet->setPointsAlternative($alternativeCalculator->getBetPoints($bet));
            }

            $user->setPoints($basicCalculator->getUserPoints($user));
            $user->setPointsAlternative($alternativeCalculator->getUserPoints($user));
        }

        $em->flush();
    }
}
