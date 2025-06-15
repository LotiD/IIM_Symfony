<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private const CART_KEY = 'cart';
    private SessionInterface $session;

    public function __construct(RequestStack $requestStack)
    {
        $session = $requestStack->getSession();

        if (!$session) {
            throw new \RuntimeException('No active session found. Did you forget to enable the session in config?');
        }

        $this->session = $session;
    }

    public function add(int $productId): void
    {
        $cart = $this->session->get(self::CART_KEY, []);
        if (!in_array($productId, $cart)) {
            $cart[] = $productId;
        }
        $this->session->set(self::CART_KEY, $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->session->get(self::CART_KEY, []);
        $cart = array_diff($cart, [$productId]);
        $this->session->set(self::CART_KEY, $cart);
    }

    public function getCart(): array
    {
        return $this->session->get(self::CART_KEY, []);
    }

    public function clear(): void
    {
        $this->session->remove(self::CART_KEY);
    }
}
