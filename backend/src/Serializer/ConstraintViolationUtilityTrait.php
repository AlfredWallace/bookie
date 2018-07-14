<?php

namespace App\Serializer;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidatorException;

trait ConstraintViolationUtilityTrait
{
    private function checkViolations(ConstraintViolationListInterface $violationList)
    {
        if (count($violationList) > 0) {
            $message = 'Invalid data!';
            foreach ($violationList as $violation) {
                $message .= sprintf(" [Field '%s' -> %s]", $violation->getPropertyPath(), $violation->getMessage());
            }
            throw new ValidatorException($message);
        }
    }
}
