<?php

namespace App\Listener\Jwt;

use App\Entity\Player;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CustomPayloadSubscriber implements EventSubscriberInterface
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function customizePayload(JWTCreatedEvent $event)
    {
        /** @var Player $player */
        $player = $this->tokenStorage->getToken()->getUser();
        $payload = $event->getData();

        $payload['playerId'] = $player->getId();

        $event->setData($payload);
    }

    public static function getSubscribedEvents(): array
    {
        return [Events::JWT_CREATED => 'customizePayload'];

    }
}
