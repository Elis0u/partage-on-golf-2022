<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\UX\Chartjs\Model\Chart;
use App\Repository\CourseRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    #[Route('/admin/', name: 'app_admin_dashboard')]
    public function index(ArticleRepository $articleRepository, UserRepository $userRepository, CourseRepository $courseRepository, PostRepository $postRepository, ChartBuilderInterface $chartBuilder): Response
    {

        $users = $userRepository->findAll();
        $nbrUser = count($users);

        $articles = $articleRepository->findAll();
        $nbrArticles = count($articles);

        $courses = $courseRepository->findAll();
        $nbrCourses = count($courses);

        $posts = $postRepository->findAll();
        $nbrPosts = count($posts);

        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);

        $chart->setData(
            [
                'labels' => ["Statistiques"],
                'datasets' => [
                    [
                        'label' => 'Nb de post',
                        'backgroundColor' => '#9AB201',
                        'borderColor' => '#9AB201',
                        'data' => [$nbrPosts],
                    ],
                    [
                        'label' => 'Nb de parcours',
                        'backgroundColor' => '#788C30',
                        'borderColor' => '#788C30',
                        'data' => [$nbrCourses],
                    ],
                    [
                        'label' => 'Nb d article',
                        'backgroundColor' => '#427AA1',
                        'borderColor' => '#427AA1',
                        'data' => [$nbrArticles],
                    ],
                    [
                        'label' => 'Nb de user',
                        'backgroundColor' => '#cd2222',
                        'borderColor' => '#cd2222',
                        'data' => [$nbrUser],
                    ],
                ],
            ],
        );

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                ],
            ],
        ]);

        return $this->render('admin_dashboard/index.html.twig', [
            'controller_name' => 'AdminDashboardController',
            'chart' => $chart,
            'active' => 'dashboard'
        ]);
    }
}
