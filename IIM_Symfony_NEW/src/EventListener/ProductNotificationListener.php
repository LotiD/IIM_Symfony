<?php

namespace App\EventListener;

use App\Entity\Product;
use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Product::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Product::class)]
#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: Product::class)]
class ProductNotificationListener
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TokenStorageInterface $tokenStorage
    ) {}

    public function postPersist(Product $product, LifecycleEventArgs $args): void
    {
        $this->createNotification('Création', $product);
    }

    public function postUpdate(Product $product, LifecycleEventArgs $args): void
    {
        $this->createNotification('Modification', $product);
    }

    public function postRemove(Product $product, LifecycleEventArgs $args): void
    {
        $this->createNotification('Suppression', $product);
    }

    private function createNotification(string $action, Product $product): void
    {
        $user = $this->tokenStorage->getToken()?->getUser();
        if (!$user instanceof User) {
            return;
        }
        $notification = new Notification();
        $notification->setLabel(sprintf('%s du produit %s par %s le %s', $action, $product->getNom(), $user->getEmail(), (new \DateTime())->format('d/m/Y H:i')));
        $notification->setUser($user);
        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }
} 