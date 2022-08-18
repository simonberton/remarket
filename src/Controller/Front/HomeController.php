<?php

namespace App\Controller\Front;

use App\Service\PostService;
use App\Service\CategoryService;
use App\Service\ProductService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    const POSTS_HOME_LIMIT = 8;

    /**
     * @Route("/", methods={"GET"}, name="home")
     */
    public function home(CategoryService $categoryService, PostService $postService): Response
    {
        return $this->render('front/home.html.twig', [
            'categories' => $categoryService->findAll(),
            'posts' => $postService->findWithLimit(self::POSTS_HOME_LIMIT)
        ]);
    }

    /**
     * @Route("/search-products", methods={"GET"}, name="search")
     */
    public function search(ProductService $productService, Request $request): Response
    {
        return $this->render('front/search-result.html.twig', [
            'products' => $productService->findByQuery($request->get('q'))
        ]);
    }

    /**
     * @Route("/producto/{slug}", methods={"GET"}, name="product")
     */
    public function product(ProductService $productService, $slug): Response
    {
        return $this->render('front/product.html.twig', [
            'product' => $productService->findOneBySlug($slug)
        ]);
    }

}