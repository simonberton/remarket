<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\Admin\CategoryType;
use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/admin/category", name="admin_category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="list")
     */
    public function list(CategoryService $categoryService): Response
    {
        return $this->render('admin/category/list.html.twig',
        [
            'categories' => $categoryService->findAll()
        ]);
    }

    /**
     * @Route("/create", methods={"GET", "POST"}, name="create")
     */
    public function create(Request $request, CategoryService $categoryService, SluggerInterface $slugger): Response
    {
        $category = new Category();
        return $this->processForm($category, $request, $slugger, $categoryService, 'Crear');
    }

    /**
     * @Route("/{id}", methods={"GET", "POST"}, name="edit")
     */
    public function edit(
        Request $request,
        CategoryService $categoryService,
        SluggerInterface $slugger,
        string $id): Response
    {
        $category = $categoryService->findOneById($id);
        return $this->processForm($category, $request, $slugger, $categoryService, 'Editar');
    }

    /**
     * @param Category|null $category
     * @param Request $request
     * @param SluggerInterface $slugger
     * @param CategoryService $categoryService
     * @param string $action
     * @return RedirectResponse|Response
     */
    public function processForm(
        ?Category $category,
        Request $request,
        SluggerInterface $slugger,
        CategoryService $categoryService,
        string $action)
    {
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $mainImageFilename */
            $mainImageFilename = $form->get('mainImageFilename')->getData();
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

                $category->setMainImageFilename($newFilename);
            }
            /** @var UploadedFile $mainImageFilename */
            $mainImageFilename = $form->get('mainImageFilename')->getData();
            if ($action == 'Crear') {
                $slug = $slugger->slug($category->getName());
                $category->setSlug($slug);
            }

            $categoryService->save($category);
            $this->addFlash('success', 'Categoria guardada correctamente');

            return $this->redirectToRoute('admin_category_list');
        }

        return $this->render('admin/category/create.html.twig',
            [
                'form' => $form->createView(),
                'action' => $action
            ]
        );
    }
}