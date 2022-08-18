<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\Admin\ProductType;
use App\Service\CategoryService;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/admin/product", name="admin_product_")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="list")
     */
    public function list(ProductService $productService): Response
    {
        return $this->render('admin/product/list.html.twig',
        [
            'products' => $productService->findAll()
        ]);
    }

    /**
     * @Route("/load", methods={"GET", "POST"}, name="load")
     */
    public function load(
        Request $request,
        ProductService $productService,
        CategoryService $categoryService,
        SluggerInterface $slugger): Response
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('file');
        if ($file) {
            $ignoreFirst = true;
            // Open the file
            if (($handle = fopen($file->getPathname(), "r")) !== false) {
                // Read and process the lines.
                // Skip the first line if the file includes a header
                while (($data = fgetcsv($handle)) !== false) {
                    if ($ignoreFirst) {
                        $ignoreFirst = false;
                        continue;
                    }
                    $sku = $data[0];

                    $product = $productService->findOneBySku($sku);
                    if (!$product) {
                        $product = new Product();
                        $product->setSku($sku);
                    }
                    $product->setName($data[1]);
                    $product->setDescription(str_replace('/', ',', $data[2]));
                    $category = $categoryService->findOneByName($data[3]);
                    if ($category === null) {
                        $categorySlug = $slugger->slug(strtolower($data[3]));
                        $category = $categoryService->create($data[3], $categorySlug);
                    }
                    $product->setCategory($category);
                    $product->setPrice($data[4]);
                    $product->setDivisible($data[5] == 'si' ? '1': '0');
                    $product->setPriceDivider($data[6]);
                    $product->setType(ucfirst($data[7]));
                    $product->setDivisibleBy($data[8]);
                    $product->setSlug($slugger->slug(strtolower($data[1])));
                    $productService->save($product);

                }
                fclose($handle);
            }
        }
        $this->addFlash('success', 'Productos actualizados correctamente');
        return $this->render('admin/product/load.html.twig');
    }

    /**
     * @Route("/create", methods={"GET", "POST"}, name="create")
     */
    public function create(Request $request, ProductService $productService, SluggerInterface $slugger): Response
    {
        $product = new Product();
        return $this->processForm($product, $request, $slugger, $productService, 'Crear');
    }

    /**
     * @Route("/{id}", methods={"GET", "POST"}, name="edit")
     */
    public function edit(
        Request $request,
        ProductService $productService,
        SluggerInterface $slugger,
        string $id): Response
    {
        $product = $productService->findOneById($id);
        return $this->processForm($product, $request, $slugger, $productService, 'Editar');
    }

    /**
     * @param Product|null $product
     * @param Request $request
     * @param SluggerInterface $slugger
     * @param ProductService $productService
     * @param string $action
     * @return RedirectResponse|Response
     */
    public function processForm(?Product $product, Request $request, SluggerInterface $slugger, ProductService $productService, string $action)
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $mainImageFilename */
            $mainImageFilename = $form->get('mainImageFilename')->getData();
            /** @var UploadedFile $secondaryImageFilename */
            $secondaryImageFilename = $form->get('secondaryImageFilename')->getData();

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

                $product->setMainImageFilename($newFilename);
            }
            if ($secondaryImageFilename) {
                $originalFilename = pathinfo($secondaryImageFilename->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $secondaryImageFilename->guessExtension();

                try {
                    $secondaryImageFilename->move(
                        $this->getParameter('product_images'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                $product->setSecondaryImageFilename($newFilename);
            }
            $productService->save($product);
            $this->addFlash('success', 'Producto guardado correctamente');

            return $this->redirectToRoute('admin_product_list');
        }

        return $this->render('admin/product/create.html.twig',
            [
                'form' => $form->createView(),
                'action' => $action
            ]
        );
    }
}