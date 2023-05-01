<?php

namespace App\Controller;

use App\Entity\PartageTonGolf;
use App\Form\PartageTonGolfType;
use App\Repository\PartageTonGolfRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/partage/ton/golf')]
class AdminPartageTonGolfController extends AbstractController
{
    #[Route('/', name: 'app_admin_partage_ton_golf_index', methods: ['GET'])]
    public function index(PartageTonGolfRepository $partageTonGolfRepository): Response
    {
        return $this->render('admin_partage_ton_golf/index.html.twig', [
            'partage_ton_golves' => $partageTonGolfRepository->findAll(),
            'active' => 'settings',
        ]);
    }

    #[Route('/new', name: 'app_admin_partage_ton_golf_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PartageTonGolfRepository $partageTonGolfRepository): Response
    {
        $partageTonGolf = new PartageTonGolf();
        $form = $this->createForm(PartageTonGolfType::class, $partageTonGolf);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $partageTonGolfRepository->add($partageTonGolf, true);

            return $this->redirectToRoute('app_admin_partage_ton_golf_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_partage_ton_golf/new.html.twig', [
            'partage_ton_golf' => $partageTonGolf,
            'form' => $form,
            'active' => 'settings',
        ]);
    }

    #[Route('/{id}', name: 'app_admin_partage_ton_golf_show', methods: ['GET'])]
    public function show(PartageTonGolf $partageTonGolf): Response
    {
        return $this->render('admin_partage_ton_golf/show.html.twig', [
            'partage_ton_golf' => $partageTonGolf,
            'active' => 'settings',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_partage_ton_golf_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PartageTonGolf $partageTonGolf, PartageTonGolfRepository $partageTonGolfRepository): Response
    {
        $form = $this->createForm(PartageTonGolfType::class, $partageTonGolf);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $partageTonGolfRepository->add($partageTonGolf, true);

            return $this->redirectToRoute('app_admin_partage_ton_golf_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_partage_ton_golf/edit.html.twig', [
            'partage_ton_golf' => $partageTonGolf,
            'form' => $form,
            'active' => 'settings',
        ]);
    }

    #[Route('/{id}', name: 'app_admin_partage_ton_golf_delete', methods: ['POST'])]
    public function delete(Request $request, PartageTonGolf $partageTonGolf, PartageTonGolfRepository $partageTonGolfRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$partageTonGolf->getId(), $request->request->get('_token'))) {
            $partageTonGolfRepository->remove($partageTonGolf, true);
        }

        return $this->redirectToRoute('app_admin_partage_ton_golf_index', [], Response::HTTP_SEE_OTHER);
    }
}
