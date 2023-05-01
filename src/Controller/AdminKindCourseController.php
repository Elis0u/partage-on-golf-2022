<?php

namespace App\Controller;

use App\Entity\KindCourse;
use App\Form\KindCourseType;
use App\Repository\KindCourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/kind/course')]
class AdminKindCourseController extends AbstractController
{
    #[Route('/', name: 'app_admin_kind_course_index', methods: ['GET'])]
    public function index(KindCourseRepository $kindCourseRepository): Response
    {
        return $this->render('admin_kind_course/index.html.twig', [
            'kind_courses' => $kindCourseRepository->findAll(),
            'active' => 'type',
            
        ]);
    }

    #[Route('/new', name: 'app_admin_kind_course_new', methods: ['GET', 'POST'])]
    public function new(Request $request, KindCourseRepository $kindCourseRepository): Response
    {
        $kindCourse = new KindCourse();
        $form = $this->createForm(KindCourseType::class, $kindCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $kindCourseRepository->add($kindCourse, true);

            return $this->redirectToRoute('app_admin_kind_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_kind_course/new.html.twig', [
            'kind_course' => $kindCourse,
            'form' => $form,
            'active' => 'type',
        ]);
    }

    #[Route('/{id}', name: 'app_admin_kind_course_show', methods: ['GET'])]
    public function show(KindCourse $kindCourse): Response
    {
        return $this->render('admin_kind_course/show.html.twig', [
            'kind_course' => $kindCourse,
            'active' => 'type',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_kind_course_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, KindCourse $kindCourse, KindCourseRepository $kindCourseRepository): Response
    {
        $form = $this->createForm(KindCourseType::class, $kindCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $kindCourseRepository->add($kindCourse, true);

            return $this->redirectToRoute('app_admin_kind_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_kind_course/edit.html.twig', [
            'kind_course' => $kindCourse,
            'form' => $form,
            'active' => 'type',
        ]);
    }

    #[Route('/{id}', name: 'app_admin_kind_course_delete', methods: ['POST'])]
    public function delete(Request $request, KindCourse $kindCourse, KindCourseRepository $kindCourseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$kindCourse->getId(), $request->request->get('_token'))) {
            $kindCourseRepository->remove($kindCourse, true);
        }

        return $this->redirectToRoute('app_admin_kind_course_index', [], Response::HTTP_SEE_OTHER);
    }
}
