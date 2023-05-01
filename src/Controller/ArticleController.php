<?php

namespace App\Controller;

use App\Entity\Alike;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\AlikeRepository;
use App\Repository\StatusRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    #[Route('/article/new', name: 'article_new')]
    public function article_new(Request $request, EntityManagerInterface $manager, StatusRepository $statusRepository): Response
    {

        $user = $this->getUser();
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $status = $statusRepository->findOneBy(['internalName' => 'draft']);
            $article->setCreatedAt(new \DateTime());
            $article->setUpdatedAt(new \DateTime());
            $article->setAuthor($user);
            $article->setStatus($status);
            $manager->persist($article);
            $manager->flush();

            $this->addFlash("success", "Votre article va être modérer avant la publication.");
            return $this->redirectToRoute('articles');
        }

        return $this->render('article/new.html.twig', [
            'formArticle' => $form->createView(),
            'pageTitle' => "Ajouter un article",
            'btnLabel' => "Ajouter"
        ]);
    }

    #[Route('/article/{slug}', name: 'article_show')]
    public function article_show(Article $article, Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);

        if($formComment->isSubmitted() && $formComment->isValid()){
            $comment->setCreatedAt(new \DateTime());
            $comment->setArticle($article);
            $comment->setAuthor($user);

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash("success", "Votre commentaire a été ajouté");

            return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'formComment' => $formComment->createView()
        ]);
    }

    #[Route('/articles', name: 'articles')]
    public function articles(ArticleRepository $articlerepository, StatusRepository $statusRepository, PaginatorInterface $paginator, Request $request): Response
    {   
        $status = $statusRepository->findOneBy(['internalName' => 'publish']);
        $articles = $articlerepository->findBy(['status' => $status ], ['createdAt' => 'DESC']);
        $articles = $paginator->paginate(
            $articles,
            $request->query->getInt(key:'page', default:1),
                limit:4
        );
        
        // $articles = $articlerepository->findAll();
        return $this->render('article/articles.html.twig', [
            'articles' => $articles,
        ]);
    }
    
       /**
     * @Route("/article/{id}/like_ajax", name="article_like_ajax")
     * // FIXME voir redirect
     */
    public function articleLikeAjax(Article $article, EntityManagerInterface $manager, AlikeRepository $alikeRepository): Response {

        $user = $this->getUser();

        // si l'utilisateur est connecté
        if($user) {
            // si l'article est déjà liker
            
            if($article->isAlikedByUser($user)) {
                // je supprime le like
                // dd("like");
                $alike = $alikeRepository->findOneByUserAndArticle($user->getUserProfile(), $article);
                $manager->remove($alike);
                $manager->flush();

                return $this->json(["code" => "10", "counter" => count($article->getAlikes()), "message"=> "unlike"], 200);
            } else {
                // je like
                // dd("like");
                $alike = new Alike();
                $alike->setArticle($article);
                $alike->setUserProfile($user->getUserProfile()); // Warning ?? FIXME voir cast
                $manager->persist($alike);
                $manager->flush();

                return $this->json(["code" => "20", "counter" => count($article->getAlikes()), "message"=> "like"], 200);
            }
            

        }
    }
}
