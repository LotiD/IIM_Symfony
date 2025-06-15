<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Création d'un admin
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $admin->setNom('Admin');
        $admin->setPrenom('Super');
        $admin->setPoints(1000);
        $admin->setActif(true);
        $manager->persist($admin);

        // Création d'utilisateurs normaux
        $users = [
            [
                'email' => 'user1@example.com',
                'password' => 'user123',
                'nom' => 'Dupont',
                'prenom' => 'Jean',
                'points' => 500,
                'actif' => true
            ],
            [
                'email' => 'user2@example.com',
                'password' => 'user123',
                'nom' => 'Martin',
                'prenom' => 'Marie',
                'points' => 300,
                'actif' => true
            ],
            [
                'email' => 'user3@example.com',
                'password' => 'user123',
                'nom' => 'Dubois',
                'prenom' => 'Pierre',
                'points' => 200,
                'actif' => false
            ]
        ];

        foreach ($users as $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $userData['password']));
            $user->setNom($userData['nom']);
            $user->setPrenom($userData['prenom']);
            $user->setPoints($userData['points']);
            $user->setActif($userData['actif']);
            $manager->persist($user);
        }

        $manager->flush();
    }
} 