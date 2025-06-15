<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Notification;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/buy', name: 'app_product_buy', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function buy(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        // Vérifier si l'utilisateur est actif
        if (!$user->isActif()) {
            $this->addFlash('error', 'Votre compte est désactivé. Vous ne pouvez pas acheter de produits.');
            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }

        // Vérifier si l'utilisateur a assez de points
        if ($user->getPoints() < $product->getPrix()) {
            $this->addFlash('error', 'Vous n\'avez pas assez de points pour acheter ce produit.');
            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }

        // Déduire les points
        $user->setPoints($user->getPoints() - $product->getPrix());
        
        // Créer une notification d'achat
        $notification = new Notification();
        $notification->setLabel(sprintf('Achat du produit %s le %s', $product->getNom(), (new \DateTime())->format('d/m/Y H:i')));
        $notification->setUser($user);
        
        $entityManager->persist($notification);
        $entityManager->flush();

        $this->addFlash('success', 'Produit acheté avec succès !');
        return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
    }
} 