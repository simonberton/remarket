<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\PostCategory;
use App\Form\Admin\CategoryType;
use App\Form\Admin\PostCategoryType;
use App\Service\CategoryService;
use App\Service\PostCategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/admin/post-category", name="admin_post_category_")
 */
class PostCategoryController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="list")
     */
    public function list(PostCategoryService $categoryService): Response
    {
        return $this->render('admin/post_category/list.html.twig',
        [
            'categories' => $categoryService->findAll()
        ]);
    }

    /**
     * @Route("/create", methods={"GET", "POST"}, name="create")
     */
    public function create(Request $request, PostCategoryService $categoryService, SluggerInterface $slugger): Response
    {
        $category = new PostCategory();
        return $this->processForm($category, $request, $slugger, $categoryService, 'Crear');
    }

    /**
     * @Route("/{id}", methods={"GET", "POST"}, name="edit")
     */
    public function edit(
        Request $request,
        PostCategoryService $categoryService,
        SluggerInterface $slugger,
        string $id): Response
    {
        $category = $categoryService->findOneById($id);
        return $this->processForm($category, $request, $slugger, $categoryService, 'Editar');
    }

    /**
     * @param PostCategory|null $category
     * @param Request $request
     * @param SluggerInterface $slugger
     * @param PostCategoryService $categoryService
     * @param string $action
     * @return RedirectResponse|Response
     */
    public function processForm(
        ?PostCategory $category,
        Request $request,
        SluggerInterface $slugger,
        PostCategoryService $categoryService,
        string $action)
    {
        $form = $this->createForm(PostCategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($action == 'Crear') {
                $slug = $slugger->slug($category->getName());
                $category->setSlug($slug);
            }

            $categoryService->save($category);
            $this->addFlash('success', 'Categoria guardada correctamente');

            return $this->redirectToRoute('admin_post_category_list');
        }

        return $this->render('admin/post_category/create.html.twig',
            [
                'form' => $form->createView(),
                'action' => $action
            ]
        );
    }
}