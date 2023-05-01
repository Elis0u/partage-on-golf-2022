<?php

namespace App\Controller;

use App\Entity\Hole;
use App\Entity\Course;
use App\Entity\Region;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use App\Repository\RegionRepository;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CourseController extends AbstractController
{
    
    #[Route('/parcours/new', name: 'course_new')]
    public function course_new(Request $request, EntityManagerInterface $manager, StatusRepository $statusRepository): Response
    {
        $user = $this->getUser();
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $status = $statusRepository->findOneBy(['internalName' => 'draft']); 
            $course->setUpdatedAt(new \DateTime());
            $course->setStatus($status);
            $course->setAuthor($user);

            $manager->persist($course);
            $manager->flush();

            $this->addFlash("success", "Votre parcours va être modérer avant la publication.");
            return $this->redirectToRoute('courses');
        }

        return $this->render('course/new.html.twig', [
            'formCourse' => $form->createView(),
            'pageTitle' => "Ajouter un parcours",
            'btnLabel' => "Ajouter"
        ]);
    }

    #[Route('/parcours/{slug}', name: 'course_show')]
    public function course_show(Course $course): Response
    {

        return $this->render('course/show.html.twig', [
            'course' => $course,
        ]);
    }

    #[Route('/parcours', name: 'courses')]
    public function showCourse(CourseRepository $courseRepository, StatusRepository $statusRepository, RegionRepository $regionRepository): Response
    {
        $regions = $regionRepository->findAll();
        $status = $statusRepository->findOneBy(['internalName' => 'publish']);
        

        return $this->render('course/courses.html.twig', [
            'courses' => $courseRepository->findBy(['status' => $status ]),
            "region" => $regions,
        ]);
    }
}

