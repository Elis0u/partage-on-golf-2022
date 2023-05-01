<?php

namespace App\Controller;

use App\Entity\CustomPage;
use App\Form\CustomPage1Type;
use App\Repository\CustomPageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/custom/page')]
class AdminCustomPageController extends AbstractController
{
    #[Route('/', name: 'app_admin_custom_page_index', methods: ['GET'])]
    public function index(CustomPageRepository $customPageRepository): Response
    {
        return $this->render('admin_custom_page/index.html.twig', [
            'custom_pages' => $customPageRepository->findAll(),
            'active' => 'customPage'
        ]);
    }

    #[Route('/new', name: 'app_admin_custom_page_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CustomPageRepository $customPageRepository): Response
    {
        $customPage = new CustomPage();
        $form = $this->createForm(CustomPage1Type::class, $customPage);
        $customPage->setCreatedAt(new \DateTime());
        $customPage->setUpdatedAt(new \DateTime());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customPageRepository->add($customPage, true);

            return $this->redirectToRoute('app_admin_custom_page_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_custom_page/new.html.twig', [
            'custom_page' => $customPage,
            'form' => $form,
            'active' => 'customPage'
        ]);
    }

    #[Route('/{id}', name: 'app_admin_custom_page_show', methods: ['GET'])]
    public function show(CustomPage $customPage): Response
    {
        return $this->render('admin_custom_page/show.html.twig', [
            'custom_page' => $customPage,
            'active' => 'customPage'
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_custom_page_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CustomPage $customPage, CustomPageRepository $customPageRepository): Response
    {
        $form = $this->createForm(CustomPage1Type::class, $customPage);
        $customPage->setUpdatedAt(new \DateTime());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customPageRepository->add($customPage, true);

            return $this->redirectToRoute('app_admin_custom_page_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_custom_page/edit.html.twig', [
            'custom_page' => $customPage,
            'form' => $form,
            'active' => 'customPage'
        ]);
    }

    #[Route('/{id}', name: 'app_admin_custom_page_delete', methods: ['POST'])]
    public function delete(Request $request, CustomPage $customPage, CustomPageRepository $customPageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$customPage->getId(), $request->request->get('_token'))) {
            $customPageRepository->remove($customPage, true);
        }

        return $this->redirectToRoute('app_admin_custom_page_index', [], Response::HTTP_SEE_OTHER);
    }
}
