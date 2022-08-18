<?php

namespace App\Controller\Front;

use App\Entity\Envase;
use App\Entity\Product;
use App\Form\Admin\CategoryType;
use App\Form\CheckoutType;
use App\Form\Data\OrderData;
use App\Service\EnvaseService;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/update-cart", methods={"POST"}, name="update_cart")
     */
    public function home(ProductService $productService, EnvaseService $envaseService, Request $request): Response
    {
        $parametersAsArray = json_decode($request->getContent(), true);

        $product = $productService->findOneById($parametersAsArray['id'] ?? "");
        $envase = $envaseService->findOneById($parametersAsArray['envase'] ?? "");
        $size = $parametersAsArray['size'] ?? null;

        $response = new Response();
        $cartProducts = $this->addProductToCart(
            $request,
            $response,
            $product,
            $size,
            $envase,
            $request->get('removeOld')?? false
        );

        $response->setContent($this->renderView('front/cart.html.twig', [
            'cartProducts' => $cartProducts
        ]));
        return $response;
    }

    private function getCartProducts(Request $request)
    {
        return $request->cookies->get('cart');
    }

    private function addProductToCart(
        Request $request,
        Response $response,
        Product $product = null,
        $size = null,
        Envase $envase = null,
        bool $removeOld = false)
    {
        $cartProducts = json_decode($this->getCartProducts($request), true);

        $priceTotal = 0;
        if ($cartProducts && isset($cartProducts['total'])) {
            $priceTotal = $cartProducts['total'];
        }

        if ($product) {
            if ($cartProducts) {
                foreach ($cartProducts as $cartProduct) {
                    if (!$removeOld && isset($cartProduct['id']) && $cartProduct['id'] == $product->getId() && !$product->isDivisible() && $size > 0) {
                        $size += (int)$cartProduct['size'];
                    }
                }
            }

            $price = round($product->getPrice() * (int)$size);
            if ($product->getDivisible() && $size !== null) {
                $price = round(($product->getPrice() * (float)$size) / $product->getPriceDivider());
            }
            $previousSize = isset($cartProducts['product_' . $product->getId()]) ? $cartProducts['product_' . $product->getId()]['size'] : null;
            if ($size == 0) {
                $priceTotal -= $cartProducts['product_' . $product->getId()]['priceSize'];
                unset($cartProducts['product_' . $product->getId()]);
            } else {
                if ($previousSize && $previousSize > $size) {
                    $priceTotal -= $cartProducts['product_' . $product->getId()]['priceSize'] - $price;
                } else {
                    if (isset($cartProducts['product_' . $product->getId()])) {
                        $priceTotal = $priceTotal - $cartProducts['product_' . $product->getId()]['priceSize'];
                    }
                    $priceTotal += $price;
                }
                $cartProducts['product_' . $product->getId()] = [
                    'id' => $product->getId(),
                    'img' => '/uploads/products/' . $product->getMainImageFilename(),
                    'name' => $product->getName(),
                    'size' => $size,
                    'isDivisible' => $product->getDivisible(),
                    'divisibleBy' => $product->getDivisibleBy(),
                    'priceDivider' => $product->getPriceDivider(),
                    'price' => $product->getPrice(),
                    'priceSize' => $price,
                    'type' => $product->getType(),
                    'typeForFront' => $product->getTypeForFront(),
                    'isProduct' => true
                ];
            }
        }

        if ($envase) {
            $previousSize = isset($cartProducts['envase_' . $envase->getId()]) ? $cartProducts['envase_' . $envase->getId()]['size']: null;
            if ($size == 0) {
                $priceTotal -= $cartProducts['envase_' . $envase->getId()]['priceSize'];
                unset($cartProducts['envase_' . $envase->getId()]);
            } else {
                if ($previousSize && $previousSize > $size) {
                    $priceTotal -= ($envase->getPrice() * $previousSize);
                    $priceTotal += $envase->getPrice() * ($product ? 1 : $size);
                } else {
                    if (isset($cartProducts['envase_' . $envase->getId()])) {
                        $priceTotal = $priceTotal - $cartProducts['envase_' . $envase->getId()]['priceSize'];
                    }
                    $priceTotal += $envase->getPrice() * ($product ? 1 : $size);
                }
                $cartProducts['envase_' . $envase->getId()] = [
                    'id' => $envase->getId(),
                    'img' => '/uploads/envases/' . $envase->getMainImageFilename(),
                    'name' => $envase->getName(),
                    'price' => $envase->getPrice(),
                    'priceSize' => $envase->getPrice() * ($product ? 1 : $size),
                    'size' => $product != null ? 1 : $size,
                    'isDivisible' => false,
                    'typeForFront' => 1,
                    'isProduct' => false
                ];
            }
        }

        $cartProducts['total'] = $priceTotal;
        
        $response->headers->setCookie(new Cookie('cart', json_encode($cartProducts)));

        return $cartProducts;
    }

    /**
     * @Route("/checkout", methods={"GET"}, name="checkout")
     */
    public function checkout(Request $request)
    {
        $form = $this->createForm(CheckoutType::class, new OrderData());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
        }

        $cartProducts = json_decode($this->getCartProducts($request), true);

        return $this->render('front/checkout.html.twig', [
            'cartProducts' => $cartProducts,
            'form' => $form->createView()
        ]);
    }
}