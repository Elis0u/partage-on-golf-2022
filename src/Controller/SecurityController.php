<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PartageTonGolfRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route('/inscription', name: 'security_registration')]
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        $userProfil = new UserProfile();
        $userProfil->setUser($user);

        if($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_USER']);
            $hash = $encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($hash);
            $userProfil->setCreatedAt(new \DateTime());
            $userProfil->setUpdatedAt(new \DateTime());
            $manager->persist($user);
            $manager->flush();
            $manager->persist($userProfil);
            $manager->flush();
            return $this->redirectToRoute("security_login");
        }

        return $this->render('security/registration.html.twig', [
            'formRegister' => $form->createView(),
            'pageTitle' => "Inscription"
        ]);
    }

    #[Route('/connexion', name: 'security_login')]
    public function login(PartageTonGolfRepository $partageTonGolfRepository): Response
    {

        $partage = $partageTonGolfRepository->findAll();

        if($partage){
            $slogan = $partage[0]->getSlogan();
        } else {
            $slogan = null;
        }


        return $this->render('security/login.html.twig', [
            'pageTitle' => "Connexion",
            'slogan' => $slogan
        ]);
    }

    #[Route("/logout", name:"security_logout")]
    public function logout()
    {
    }
}
