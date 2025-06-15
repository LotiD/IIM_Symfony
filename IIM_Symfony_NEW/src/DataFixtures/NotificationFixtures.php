<?php

namespace App\DataFixtures;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class NotificationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $notifications = [
            [
                'label' => 'Bienvenue sur notre plateforme !',
                'user' => 'user1@example.com'
            ],
            [
                'label' => 'Votre compte a été activé avec succès.',
                'user' => 'user2@example.com'
            ],
            [
                'label' => 'Nouveau produit disponible : iPhone 13',
                'user' => 'admin@example.com'
            ],
            [
                'label' => 'Votre achat a été confirmé.',
                'user' => 'user1@example.com'
            ],
            [
                'label' => 'Points bonus ajoutés à votre compte.',
                'user' => 'user3@example.com'
            ]
        ];

        foreach ($notifications as $notificationData) {
            $notification = new Notification();
            $notification->setLabel($notificationData['label']);
            
            // Récupérer l'utilisateur associé
            $user = $manager->getRepository(User::class)->findOneBy(['email' => $notificationData['user']]);
            if ($user) {
                $notification->setUser($user);
            }
            
            $manager->persist($notification);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
} 