<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Entity\Comment;
use App\Entity\AlikePost;
use App\Form\CommentType;
use App\Entity\CommentPost;
use App\Form\CommentPostType;
use App\Repository\PostRepository;
use App\Repository\CourseRepository;
use App\Repository\StatusRepository;
use App\Repository\ArticleRepository;
use App\Repository\AlikePostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    #[Route('/accueil', name: 'post')]
    public function posts(PostRepository $postRepository, Request $request, EntityManagerInterface $manager, ArticleRepository $articleRepository, CourseRepository $courseRepository, StatusRepository $statusRepository): Response
    {

        $status = $statusRepository->findOneBy(['internalName' => 'publish']);
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

            return $this->redirectToRoute('post');
        }

        // Commenter les post
        $comment = new CommentPost();
        $formComment = $this->createForm(CommentPostType::class, $comment);
        $formComment->handleRequest($request);

        if($formComment->isSubmitted() && $formComment->isValid()){
            $comment->setCreatedAt(new \DateTime());
            $comment->setAuthor($user);

            $postid = $formComment->get("post")->getData();
            $post = $postRepository->find($postid);
            $comment->setPost($post);
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash("success", "Votre commentaire a été ajouté");

            return $this->redirectToRoute('post');
        }

        $articlesrecents = $articleRepository->findBy(['status' => $status ],['id'=>'DESC'], 3);
        $coursesrecents = $courseRepository->findBy(['status' => $status ],['id'=>'DESC'], 3);

        $posts = $postRepository->findBy([],['id' => 'DESC']);
        
        return $this->render('post/posts.html.twig', [
            'article' => $articlesrecents,
            'course' => $coursesrecents,
            'formComment' => $formComment->createView(),
            'formPost' => $form->createView(),
            'posts' => $posts,
            'btnLabel' => "Envoyer"
        ]);
    }

     /**
     * @Route("/post/{id}/like_ajax", name="post_like_ajax")
     * // FIXME voir redirect
     */
    public function postLikeAjax(Post $post, EntityManagerInterface $manager, AlikePostRepository $alikepostRepository): Response {

        $user = $this->getUser();

        // si l'utilisateur est connecté
        if($user) {
            // si l'article est déjà liker
            
            if($post->isAlikedPostByUser($user)) {
                // je supprime le like
                $alikePosts = $alikepostRepository->findOneByUserAndPost($user->getUserProfile(), $post);
                $manager->remove($alikePosts);
                $manager->flush();

                return $this->json(["code" => "10", "counter" => count($post->getAlikePosts()), "message"=> "unlike"], 200);
            } else {
                // je like
                // dd("like");
                $alikePosts = new AlikePost();
                $alikePosts->setPost($post);
                $alikePosts->setUserProfile($user->getUserProfile()); // Warning ?? FIXME voir cast
                $manager->persist($alikePosts);
                $manager->flush();

                return $this->json(["code" => "20", "counter" => count($post->getAlikePosts()), "message"=> "like"], 200);
            }
            

        }
    }
    
}
