<?php

namespace App\Controller\Front;

use App\Service\CategoryService;
use App\Service\PostService;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", methods={"GET"}, name="blog_home")
     */
    public function home(PostService $postService): Response
    {
        return $this->render('front/blog/home.html.twig', [
            'posts' => $postService->findAll()
        ]);
    }

    /**
     * @Route("/blog/{slug}", methods={"GET"}, name="blog_post")
     */
    public function post(PostService $postService, $slug): Response
    {
        return $this->render('front/blog/post.html.twig', [
            'post' => $postService->findOneBySlug($slug)
        ]);
    }

}