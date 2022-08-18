<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Envase;
use App\Form\Admin\CategoryType;
use App\Form\Admin\EnvaseType;
use App\Service\CategoryService;
use App\Service\EnvaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/admin/envase", name="admin_envase_")
 */
class EnvaseController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="list")
     */
    public function list(EnvaseService $envaseService): Response
    {
        return $this->render('admin/envase/list.html.twig',
        [
            'envases' => $envaseService->findAll()
        ]);
    }

    /**
     * @Route("/create", methods={"GET", "POST"}, name="create")
     */
    public function create(Request $request, EnvaseService $envaseService, SluggerInterface $slugger): Response
    {
        $envase = new Envase();
        return $this->processForm($envase, $request, $slugger, $envaseService, 'Crear');
    }

    /**
     * @Route("/{id}", methods={"GET", "POST"}, name="edit")
     */
    public function edit(
        Request $request,
        EnvaseService $envaseService,
        SluggerInterface $slugger,
        string $id): Response
    {
        $category = $envaseService->findOneById($id);
        return $this->processForm($category, $request, $slugger, $envaseService, 'Editar');
    }

    /**
     * @param Envase|null $envase
     * @param Request $request
     * @param SluggerInterface $slugger
     * @param EnvaseService $envaseService
     * @param string $action
     * @return RedirectResponse|Response
     */
    public function processForm(
        ?Envase $envase,
        Request $request,
        SluggerInterface $slugger,
        EnvaseService $envaseService,
        string $action)
    {
        $form = $this->createForm(EnvaseType::class, $envase);

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
                        $this->getParameter('envases_images'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $envase->setMainImageFilename($newFilename);
            }
            if ($action == 'Crear') {
                $slug = $slugger->slug($envase->getName());
                $envase->setSlug($slug);
            }

            $envaseService->save($envase);
            $this->addFlash('success', 'Envase guardada correctamente');

            return $this->redirectToRoute('admin_envase_list');
        }

        return $this->render('admin/envase/create.html.twig',
            [
                'form' => $form->createView(),
                'action' => $action
            ]
        );
    }
}