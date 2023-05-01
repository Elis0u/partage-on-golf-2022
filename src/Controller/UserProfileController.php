<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Entity\UserProfile;
use App\Form\UserProfileType;
use App\Form\UserSettingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserProfileController extends AbstractController
{
    #[Route('/user/profile/{id}', name: 'user_show_profile')]
    public function showProfile(UserProfile $userProfile, Request $request, EntityManagerInterface $manager ): Response
    {
        $canEdit = false;

        if ($this->getUser() == $userProfile->getUser()) {
            $canEdit = true;
        }
        
        // Creation d'un nouveau post
        $user = $this->getUser();
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $post->setCreatedAt(new \DateTime());
            $post->setUpdatedAt(new \DateTime());
            $post->setAuthor($user);
            $manager->persist($post);
            $manager->flush();

            $this->addFlash("success", "Votre post a été publié");

            return $this->redirectToRoute('user_show_profile', ['id' => $userProfile->getId()]);
        }

        return $this->render('user_profile/show.html.twig', [
            'formPost' => $form->createView(),
            'user_profile' => $userProfile,
            'can_edit' => $canEdit,            
            'btnLabel' => "Envoyer"
        ]);
    }

        /**
     * @Route("/user/profile/{id}/edit", name="user_edit_profile")
     */
    public function editProfile(UserProfile $userProfile, Request $request, EntityManagerInterface $manager): Response
    {

        if ($this->getUser() != $userProfile->getUser()) {
            $this->addFlash('warning', "Opération interdite");
            return $this->redirectToRoute('post');
        }

        $formProfile = $this->createForm(UserProfileType::class, $userProfile);

        $formProfile->handleRequest($request);

        if ($formProfile->isSubmitted() && $formProfile->isValid()) {

            $userProfile->setUpdatedAt(new \DateTime());

            $manager->persist($userProfile);
            $manager->flush();

            $this->addFlash("success", "Votre profil a été Mis à jour");

            return $this->redirectToRoute('user_show_profile', ['id' => $userProfile->getId()]);
        }

        return $this->render('user_profile/edit.html.twig', [
            'user_profile' => $userProfile,
            'form_profile' => $formProfile->createView(),
            'btnLabel' => "Enregistrer"
        ]);
    }


            /**
     * @Route("/user/{id}/edit", name="user_edit")
     */
    public function editUserProfile(User $user, UserProfile $userProfile, Request $request, EntityManagerInterface $manager): Response
    {

        if ($this->getUser() != $user) {
            $this->addFlash('warning', "Opération interdite");
            return $this->redirectToRoute('post');
        }

        $formProfile = $this->createForm(UserSettingType::class, $user);

        $formProfile->handleRequest($request);

        if ($formProfile->isSubmitted() && $formProfile->isValid()) {

            $manager->persist($user);
            $manager->flush();

            $this->addFlash("success", "Votre profil a été Mis à jour");

            return $this->redirectToRoute('user_show_profile', ['id' => $userProfile->getId()]);
        }

        return $this->render('user_profile/edit_user.html.twig', [
            'user_profile' => $userProfile,
            'form_profile' => $formProfile->createView(),
            'btnLabel' => "Enregistrer"
        ]);
    }
}
