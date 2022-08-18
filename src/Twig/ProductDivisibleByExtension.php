<?php

namespace App\Twig;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ProductDivisibleByExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('loadProductDivisible', [$this, 'loadProductDivisible'], [
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
    public function loadProductDivisible(
        Environment $environment,
        string $divisibleBy,
        string $typeForFront,
        float $price,
        float $priceDivider,
        string $size = ''): string
    {
        $type = 'gramos';
        if ($typeForFront == 'Liquido') {
            $type = 'mililitros';
        }
        return $environment->render('front/product/select_divisible.html.twig', [
            'options' => explode('-', $divisibleBy),
            'type' => $type,
            'price' => $price,
            'priceDivider' => $priceDivider,
            'size' => $size]);
    }
}