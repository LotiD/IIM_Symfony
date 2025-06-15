<?php
/*
namespace App\ApiResource;

use ApiPlatform\State\ProviderInterface;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Security\Core\Security;

class ProductCollectionProvider implements ProviderInterface
{
    public function __construct(
        private ProductRepository $productRepository,
        private Security $security
    ) {}

    public function provide(\ApiPlatform\Metadata\Operation $operation, array $uriVariables = [], array $context = []): iterable
    {
        // Log pour debug
        file_put_contents(__DIR__.'/debug_provider.txt', date('Y-m-d H:i:s')." - Provider appelé\n", FILE_APPEND);
        $user = $this->security->getUser();
        $products = $this->productRepository->findBy(['owner' => $user]);
        file_put_contents(__DIR__.'/debug_provider.txt', date('Y-m-d H:i:s')." - Produits trouvés : ".count($products)."\n", FILE_APPEND);
        return $products;
    }
}*/