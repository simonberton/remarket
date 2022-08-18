<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Order;
use App\Form\Admin\CategoryType;
use App\Service\CategoryService;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/admin/order", name="admin_order_")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="list")
     */
    public function list(OrderService $orderService): Response
    {
        return $this->render('admin/order/list.html.twig',
        [
            'orders' => $orderService->findAll()
        ]);
    }

    /**
     * @Route("/{id}", methods={"GET", "POST"}, name="edit")
     */
    public function edit(
        Request $request,
        OrderService $orderService,
        SluggerInterface $slugger,
        string $id): Response
    {
        $order = $orderService->findOneById($id);
        return $this->processForm($order, $request, $slugger, $orderService, 'Editar');
    }

    /**
     * @param Order|null $category
     * @param Request $request
     * @param SluggerInterface $slugger
     * @param OrderService $orderService
     * @param string $action
     * @return RedirectResponse|Response
     */
    public function processForm(
        ?Order $order,
        Request $request,
        SluggerInterface $slugger,
        OrderService $orderService,
        string $action)
    {
        $form = $this->createForm(OrderType::class, $order);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $orderService->save($order);
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