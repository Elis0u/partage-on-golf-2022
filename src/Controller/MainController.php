<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\CustomPageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, EntityManagerInterface $manager): Response {

        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        if($formContact->isSubmitted() && $formContact->isValid()){

            $manager->persist($contact);
            $manager->flush();

            $this->addFlash("success", "Votre message de contact a bien été envoyé !");

            return $this->redirectToRoute('post');
        }

        return $this->render('main/contact.html.twig', [
            'contact' => $contact,
            'formContact' => $formContact->createView(),
            'btnLabel' => "Envoyer"
        ]);
    }

    /**
     * @Route("/page/{url}", name="show_custom_page")
     */
    public function showCustomPage($url, CustomPageRepository $customPageRepo): Response {
        $page = $customPageRepo->findOneBy(['url' => $url]);
        return $this->render('main/page.html.twig', [
            'page' => $page
        ]);
    }

    /**
     * @Route("/loading", name="loading_page")
     */
    public function LoadingPage(): Response {

        return $this->render('security/loading.html.twig', [
            "pageTitle" => "Explications",
        ]);
    }

}
