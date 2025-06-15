<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Notification;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/add/{id}', name: 'app_cart_add', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function add(Product $product, CartService $cartService): Response
    {
        $cartService->add($product->getId());
        $this->addFlash('success', 'Produit ajouté au panier !');
        return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
    }

    #[Route('/', name: 'app_cart_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(CartService $cartService, ProductRepository $productRepository): Response
    {
        $cartIds = $cartService->getCart();
        $products = $productRepository->findBy(['id' => $cartIds]);
        return $this->render('cart/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/remove/{id}', name: 'app_cart_remove', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function remove(Product $product, CartService $cartService): Response
    {
        $cartService->remove($product->getId());
        $this->addFlash('success', 'Produit retiré du panier.');
        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/checkout', name: 'app_cart_checkout', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function checkout(CartService $cartService, ProductRepository $productRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user->isActif()) {
            $this->addFlash('error', 'Votre compte est désactivé. Vous ne pouvez pas acheter de produits.');
            return $this->redirectToRoute('app_cart_index');
        }
        $cartIds = $cartService->getCart();
        $products = $productRepository->findBy(['id' => $cartIds]);
        $total = array_sum(array_map(fn($p) => $p->getPrix(), $products));
        if ($user->getPoints() < $total) {
            $this->addFlash('error', 'Vous n\'avez pas assez de points pour acheter tous les produits du panier.');
            return $this->redirectToRoute('app_cart_index');
        }
        // Déduire les points et créer une notification pour chaque produit
        foreach ($products as $product) {
            $user->setPoints($user->getPoints() - $product->getPrix());
            $notification = new Notification();
            $notification->setLabel(sprintf('Achat du produit %s par %s le %s', $product->getNom(), $user->getEmail(), (new \DateTime())->format('d/m/Y H:i')));
            $notification->setUser($user);
            $entityManager->persist($notification);
        }
        $entityManager->flush();
        $cartService->clear();
        $this->addFlash('success', 'Achat effectué avec succès !');
        return $this->redirectToRoute('app_product_index');
    }
} 