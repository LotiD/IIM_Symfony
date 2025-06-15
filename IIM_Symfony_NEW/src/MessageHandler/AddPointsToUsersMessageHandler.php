<?php

namespace App\MessageHandler;

use App\Message\AddPointsToUsersMessage;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AddPointsToUsersMessageHandler
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function __invoke(AddPointsToUsersMessage $message)
    {
        $points = $message->getPoints();
        $userRepo = $this->entityManager->getRepository(User::class);
        $users = $userRepo->findBy(['actif' => true]);

        foreach ($users as $user) {
            $user->setPoints($user->getPoints() + $points);
        }
        $this->entityManager->flush();
    }
} 