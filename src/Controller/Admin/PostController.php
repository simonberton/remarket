<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Post;
use App\Form\Admin\CategoryType;
use App\Form\Admin\PostType;
use App\Service\CategoryService;
use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/admin/post", name="admin_post_")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="list")
     */
    public function list(PostService $postService): Response
    {
        return $this->render('admin/post/list.html.twig',
            [
                'posts' => $postService->findAll()
            ]);
    }

    /**
     * @Route("/create", methods={"GET", "POST"}, name="create")
     */
    public function create(Request $request, PostService $postService, SluggerInterface $slugger): Response
    {
        $post = new Post();
        return $this->processForm($post, $request, $slugger, $postService, 'Crear');
    }

    /**
     * @Route("/{id}", methods={"GET", "POST"}, name="edit")
     */
    public function edit(
        Request $request,
        PostService $postService,
        SluggerInterface $slugger,
        string $id): Response
    {
        $post = $postService->findOneById($id);
        return $this->processForm($post, $request, $slugger, $postService, 'Editar');
    }

    /**
     * @param Post|null $post
     * @param Request $request
     * @param SluggerInterface $slugger
     * @param PostService $postService
     * @param string $action
     * @return RedirectResponse|Response
     */
    public function processForm(
        ?Post $post,
        Request $request,
        SluggerInterface $slugger,
        PostService $postService,
        string $action)
    {
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $mainImageFilename */
            $mainImageFilename = $form->get('image')->getData();
            if ($mainImageFilename) {
                $originalFilename = pathinfo($mainImageFilename->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $mainImageFilename->guessExtension();

                try {
                    $mainImageFilename->move(
                        $this->getParameter('product_images'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $post->setImage($newFilename);
            }

            if ($action == 'Crear') {
                $slug = $slugger->slug($post->getTitle());
                $post->setSlug($slug);
            }

            $postService->save($post);
            $this->addFlash('success', 'Categoria guardada correctamente');

            return $this->redirectToRoute('admin_post_list');
        }

        return $this->render('admin/post/create.html.twig',
            [
                'form' => $form->createView(),
                'action' => $action
            ]
        );
    }
}