<?php

namespace App\Listener;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        $code = $exception->getCode();
        $message = $exception->getMessage();

        if ($exception instanceof EntityNotFoundException || $exception instanceof NotFoundHttpException) {
            $code = Response::HTTP_NOT_FOUND;
            $message = 'Entity not found.';
        } elseif ($exception instanceof UniqueConstraintViolationException) {
            $code = Response::HTTP_BAD_REQUEST;
            $message = 'Unique constraint violation.';
        } elseif ($exception instanceof AccessDeniedHttpException) {
            $code = Response::HTTP_FORBIDDEN;
        } elseif ($code < Response::HTTP_CONTINUE) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $event->setResponse(new JsonResponse($message, $code));
    }
}
