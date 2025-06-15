<?php

namespace App\EventListener;

use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\Notification;

class TimestampSubscriber implements EventSubscriberInterface
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$this->supportsEntity($entity)) {
            return;
        }

        $now = new \DateTimeImmutable();
        $entity->setCreatedAt($now);
        $entity->setUpdatedAt($now);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$this->supportsEntity($entity)) {
            return;
        }

        $entity->setUpdatedAt(new \DateTimeImmutable());
    }

    private function supportsEntity($entity): bool
    {
        return $entity instanceof User
            || $entity instanceof Product
            || $entity instanceof Notification;
    }
} 