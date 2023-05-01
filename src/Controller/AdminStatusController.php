<?php

namespace App\Controller;

use App\Entity\Status;
use App\Form\StatusType;
use App\Repository\StatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/status')]
class AdminStatusController extends AbstractController
{
    #[Route('/', name: 'app_admin_status_index', methods: ['GET'])]
    public function index(StatusRepository $statusRepository): Response
    {
        return $this->render('admin_status/index.html.twig', [
            'statuses' => $statusRepository->findAll(),
            'active' => 'statu'
        ]);
    }

    #[Route('/new', name: 'app_admin_status_new', methods: ['GET', 'POST'])]
    public function new(Request $request, StatusRepository $statusRepository): Response
    {
        $status = new Status();
        $form = $this->createForm(StatusType::class, $status);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $statusRepository->add($status, true);

            return $this->redirectToRoute('app_admin_status_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_status/new.html.twig', [
            'status' => $status,
            'form' => $form,
            'active' => 'statu'
        ]);
    }

    #[Route('/{id}', name: 'app_admin_status_show', methods: ['GET'])]
    public function show(Status $status): Response
    {
        return $this->render('admin_status/show.html.twig', [
            'status' => $status,
            'active' => 'statu'
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_status_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Status $status, StatusRepository $statusRepository): Response
    {
        $form = $this->createForm(StatusType::class, $status);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $statusRepository->add($status, true);

            return $this->redirectToRoute('app_admin_status_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_status/edit.html.twig', [
            'status' => $status,
            'form' => $form,
            'active' => 'statu'
        ]);
    }

    #[Route('/{id}', name: 'app_admin_status_delete', methods: ['POST'])]
    public function delete(Request $request, Status $status, StatusRepository $statusRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$status->getId(), $request->request->get('_token'))) {
            $statusRepository->remove($status, true);
        }

        return $this->redirectToRoute('app_admin_status_index', [], Response::HTTP_SEE_OTHER);
    }
}
