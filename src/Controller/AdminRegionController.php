<?php

namespace App\Controller;

use App\Entity\Region;
use App\Form\RegionType;
use App\Repository\RegionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/region')]
class AdminRegionController extends AbstractController
{
    #[Route('/', name: 'app_admin_region_index', methods: ['GET'])]
    public function index(RegionRepository $regionRepository): Response
    {
        return $this->render('admin_region/index.html.twig', [
            'regions' => $regionRepository->findAll(),
            'active' => 'region'
            
        ]);
    }

    #[Route('/new', name: 'app_admin_region_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RegionRepository $regionRepository): Response
    {
        $region = new Region();
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $regionRepository->add($region, true);

            return $this->redirectToRoute('app_admin_region_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_region/new.html.twig', [
            'region' => $region,
            'form' => $form,
            'active' => 'region'
        ]);
    }

    #[Route('/{id}', name: 'app_admin_region_show', methods: ['GET'])]
    public function show(Region $region): Response
    {
        return $this->render('admin_region/show.html.twig', [
            'region' => $region,
            'active' => 'region'
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_region_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Region $region, RegionRepository $regionRepository): Response
    {
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $regionRepository->add($region, true);

            return $this->redirectToRoute('app_admin_region_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_region/edit.html.twig', [
            'region' => $region,
            'form' => $form,
            'active' => 'region'
        ]);
    }

    #[Route('/{id}', name: 'app_admin_region_delete', methods: ['POST'])]
    public function delete(Request $request, Region $region, RegionRepository $regionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$region->getId(), $request->request->get('_token'))) {
            $regionRepository->remove($region, true);
        }

        return $this->redirectToRoute('app_admin_region_index', [], Response::HTTP_SEE_OTHER);
    }
}
