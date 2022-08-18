<?php

namespace App\Twig;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CartExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('loadCart', [$this, 'loadCart'], [
                'needs_environment' => true,
                'is_safe' => ['html']
            ])
        ];
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function loadCart(Environment $environment, $cartProducts): string
    {
        if ($cartProducts === null) {
            return "";
        }
        
        return $environment->render('front/cart.html.twig', [
            'cartProducts' => json_decode($cartProducts, true)
        ]);
    }
}