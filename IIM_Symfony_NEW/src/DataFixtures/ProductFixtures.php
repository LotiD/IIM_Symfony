<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            [
                'nom' => 'iPhone 13',
                'prix' => 899.99,
                'category' => 'Électronique',
                'description' => 'Le dernier iPhone avec un excellent appareil photo et une grande autonomie.',
                'owner' => 'admin@example.com'
            ],
            [
                'nom' => 'MacBook Pro',
                'prix' => 1299.99,
                'category' => 'Informatique',
                'description' => 'Un ordinateur portable puissant pour les professionnels.',
                'owner' => 'admin@example.com'
            ],
            [
                'nom' => 'Nike Air Max',
                'prix' => 129.99,
                'category' => 'Sport',
                'description' => 'Des chaussures de sport confortables et stylées.',
                'owner' => 'user1@example.com'
            ],
            [
                'nom' => 'Samsung Galaxy S21',
                'prix' => 799.99,
                'category' => 'Électronique',
                'description' => 'Un smartphone Android haut de gamme avec un excellent appareil photo.',
                'owner' => 'user2@example.com'
            ],
            [
                'nom' => 'Canon EOS R5',
                'prix' => 3499.99,
                'category' => 'Photo',
                'description' => 'Un appareil photo professionnel avec une excellente qualité d\'image.',
                'owner' => 'user3@example.com'
            ]
        ];

        foreach ($products as $productData) {
            $product = new Product();
            $product->setNom($productData['nom']);
            $product->setPrix($productData['prix']);
            $product->setCategory($productData['category']);
            $product->setDescription($productData['description']);
            
            // Récupérer l'utilisateur propriétaire
            $owner = $manager->getRepository(User::class)->findOneBy(['email' => $productData['owner']]);
            if ($owner) {
                $product->setOwner($owner);
            }
            
            $manager->persist($product);
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