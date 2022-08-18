<?php

namespace App\Controller\Front;

use App\Service\CategoryService;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/catalogo", methods={"GET"}, name="category")
     */
    public function catalog(ProductService $productService, CategoryService $categoryService): Response
    {
        return $this->render('front/catalog_list.html.twig', [
            'categories' => $categoryService->findAll()
        ]);
    }

    /**
     * @Route("/catalogo/{categorySlug}", methods={"GET"}, name="category_home")
     */
    public function home(ProductService $productService, CategoryService $categoryService, $categorySlug): Response
    {
        $category = $categoryService->findOneBySlug($categorySlug);
        return $this->render('front/catalog.html.twig', [
            'category' => $category,
            'categories' => $categoryService->findAll(),
            'products' => $productService->findAllByCategory($category)
        ]);
    }

}