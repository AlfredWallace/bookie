<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class ApiController extends Controller
{
    private $serializer;
    private $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    protected function getSerializedResponse($data, array $groups = [], $code = Response::HTTP_OK): JsonResponse
    {
        $serializedData = $this->serializer->serialize($data, 'json', ['groups' => $groups]);

        return new JsonResponse($serializedData, $code, [], true);
    }

    protected function getValidatedObject($json, $class)
    {
        $object = $this->serializer->deserialize($json, $class, 'json');

        /** @var ConstraintViolationList $violationList */
        $violationList = $this->validator->validate($object, null);
        if (count($violationList) > 0) {
            $violationString = 'Les données soumises sont incorrectes :';
            foreach ($violationList as $violation) {
                $violationString .= sprintf(" [Champ '%s' : %s]", $violation->getPropertyPath(), $violation->getMessage());;
            }
            throw new ValidatorException($violationString, Response::HTTP_BAD_REQUEST);
        }

        return $object;
    }
}
