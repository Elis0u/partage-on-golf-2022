<?php

namespace App\Controller;

use App\Entity\Hole;
use App\Form\Hole1Type;
use App\Repository\HoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/hole')]
class AdminHoleController extends AbstractController
{
    #[Route('/', name: 'app_admin_hole_index', methods: ['GET'])]
    public function index(HoleRepository $holeRepository): Response
    {
        return $this->render('admin_hole/index.html.twig', [
            'holes' => $holeRepository->findAll(),
            'active' => 'trous'
        ]);
    }

    #[Route('/new', name: 'app_admin_hole_new', methods: ['GET', 'POST'])]
    public function new(Request $request, HoleRepository $holeRepository): Response
    {
        $hole = new Hole();
        $form = $this->createForm(Hole1Type::class, $hole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $holeRepository->add($hole, true);

            return $this->redirectToRoute('app_admin_hole_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_hole/new.html.twig', [
            'hole' => $hole,
            'form' => $form,
            'active' => 'trous'
        ]);
    }

    #[Route('/{id}', name: 'app_admin_hole_show', methods: ['GET'])]
    public function show(Hole $hole): Response
    {
        return $this->render('admin_hole/show.html.twig', [
            'hole' => $hole,
            'active' => 'trous'
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_hole_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Hole $hole, HoleRepository $holeRepository): Response
    {
        $form = $this->createForm(Hole1Type::class, $hole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $holeRepository->add($hole, true);

            return $this->redirectToRoute('app_admin_hole_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_hole/edit.html.twig', [
            'hole' => $hole,
            'form' => $form,
            'active' => 'trous'
        ]);
    }

    #[Route('/{id}', name: 'app_admin_hole_delete', methods: ['POST'])]
    public function delete(Request $request, Hole $hole, HoleRepository $holeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hole->getId(), $request->request->get('_token'))) {
            $holeRepository->remove($hole, true);
        }

        return $this->redirectToRoute('app_admin_hole_index', [], Response::HTTP_SEE_OTHER);
    }
}
