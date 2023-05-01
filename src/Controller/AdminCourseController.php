<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\Course1Type;
use App\Repository\CourseRepository;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/course')]
class AdminCourseController extends AbstractController
{
    #[Route('/', name: 'app_admin_course_index', methods: ['GET'])]
    public function index(CourseRepository $courseRepository): Response
    {
        return $this->render('admin_course/index.html.twig', [
            'courses' => $courseRepository->findAll(),
            'active' => 'parcours',
        ]);
    }

    #[Route('/new', name: 'app_admin_course_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CourseRepository $courseRepository): Response
    {
        $course = new Course();
        $form = $this->createForm(Course1Type::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $courseRepository->add($course, true);

            return $this->redirectToRoute('app_admin_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_course/new.html.twig', [
            'course' => $course,
            'form' => $form,
            'active' => 'parcours',
        ]);
    }

    #[Route('/{id}', name: 'app_admin_course_show', methods: ['GET'])]
    public function show(Course $course): Response
    {
        return $this->render('admin_course/show.html.twig', [
            'course' => $course,
            'active' => 'parcours',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_course_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Course $course, CourseRepository $courseRepository): Response
    {
        $form = $this->createForm(Course1Type::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $courseRepository->add($course, true);

            return $this->redirectToRoute('app_admin_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_course/edit.html.twig', [
            'course' => $course,
            'form' => $form,
            'active' => 'parcours',
        ]);
    }

    #[Route('/{id}', name: 'app_admin_course_delete', methods: ['POST'])]
    public function delete(Request $request, Course $course, CourseRepository $courseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
            $courseRepository->remove($course, true);
        }

        return $this->redirectToRoute('app_admin_course_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route("switchValidate/{id}", name:"app_admin_switch_validate_course", methods:["GET"])]
    public function switchValidate(Course $course, StatusRepository $statusRepository, EntityManagerInterface $manager): Response
    {
        $brouillon = $statusRepository->findOneBy(['internalName' => 'draft']);
        $publie = $statusRepository->findOneBy(['internalName' => 'publish']);

        if($course->isIsValidate()) {
            $course->setIsValidate(false);
            $course->setStatus($brouillon);
        } else {
            $course->setIsValidate(true);
            $course->setStatus($publie);
        }

        $manager->persist($course);
        $manager->flush();

        return $this->redirectToRoute('app_admin_course_index');
    }
}
