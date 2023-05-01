<?php

namespace App\DataFixtures;

use App\Entity\Hole;
use App\Entity\Post;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Course;
use App\Entity\Region;
use App\Entity\Status;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\KindCourse;
use App\Entity\CommentPost;
use App\Entity\UserProfile;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {

        $strCom = Array(
            "J'adore ",
            "Mouais c'est pas mal",
            "Pas mal pas mal",
            "Incroyable !",
            "Le golf, incroyable"
        );

        $strAuth = [
            "Tiger Woods",
            "Dustin Johnson",
            "Justin Thomas",
            "G-Mac",
            "Victor Perez",            
        ];

        $Role = new Role();
        $Role->setInternalName("ROLE_USER");
        $Role->setLabel("Utilisateur");

        $Role1 = new Role();
        $Role1->setInternalName("ROLE_ADMIN");
        $Role1->setLabel("Admin");

        $manager->persist($Role);
        $manager->persist($Role1);

        //Region

        $region1 = new Region();
        $region1->setLabel("Normandie");
        $region1->setInternalName('normandy');
        $manager->persist($region1);

        $region2 = new Region();
        $region2->setLabel("Ile de France");
        $region2->setInternalName('ile_de_france');
        $manager->persist($region2);

        // Course Kind

        $type1 = new KindCourse();
        $type1->setLabel("Links");
        $type1->setInternalName('links');
        $manager->persist($type1);


        // STATUS
        $stat1 = new Status();
        $stat1->setLabel("Brouillon");
        $stat1->setInternalName('draft');
        $manager->persist($stat1);

        $stat2 = new Status();
        $stat2->setLabel("Publié");
        $stat2->setInternalName('publish');
        $manager->persist($stat2);

        $stat3 = new Status();
        $stat3->setLabel("A supprimer");
        $stat3->setInternalName('to_delete');
        $manager->persist($stat3);

       /*
            User
        */

        $user1 = new User();
        $user1->setEmail('u1@mail.com');
        $user1->setLastname('Martin');
        $user1->setFirstname('Charlotte');
        $user1->setPassword($this->encoder->hashPassword($user1, 'admin123'));
        $user1->setHasRGPD(true);
        $user1->setRoles(['ROLE_USER']);

        $userProfile1 = new UserProfile();
        $userProfile1->setUser($user1);
        $userProfile1->setCreatedAt(new \Datetime());
        $userProfile1->setUpdatedAt(new \Datetime());

        $manager->persist($user1);
        $manager->persist($userProfile1);

        $user2 = new User();
        $user2->setEmail('u2@mail.com');
        $user2->setLastname('admin');
        $user2->setFirstname('Elisa');
        $user2->setPassword($this->encoder->hashPassword($user2, 'admin123'));
        $user2->setHasRGPD(true);
        $user2->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $userProfile2 = new UserProfile();
        $userProfile2->setUser($user2);
        $userProfile2->setCreatedAt(new \Datetime());
        $userProfile2->setUpdatedAt(new \Datetime());

        $manager->persist($user2);
        $manager->persist($userProfile2);

        for ($b=1; $b < 10; $b++) { 
            $post = new Post();
            $post->setContent("Contenu du post numéro ".$b)  
                    // ->setImageName("https://media.discordapp.net/attachments/933900460597858434/981574436656386188/unknown.png")
                    ->setCreatedAt(new \DateTime())
                    ->setUpdatedAt(new \DateTime())
                    ->setAuthor($user1);
            for ($c = 0; $c < rand(0, 1); $c++) {
                $com1 = new CommentPost();
                $com1->setAuthor($user1);
                $com1->setContent($strCom[rand(0,count($strCom)-1)]);
                $com1->setPost($post);
                $com1->setCreatedAt(new \DateTime());
                $manager->persist($com1);
            }
            $manager->persist($post);
        }
        $manager->flush();

        for ($i=1; $i < 5; $i++) { 
            $article = new Article();
            $article->setTitle("Titre de  l'article numéro ".$i )  
                    ->setContent("Contenu de  l'article numéro ".$i)  
                    // ->setImageName("https://media.discordapp.net/attachments/933900460597858434/981574436656386188/unknown.png")
                    ->setCreatedAt(new \DateTime())
                    ->setUpdatedAt(new \DateTime())
                    ->setAuthor($user1)
                    ->setStatus($stat2)
                    ->setIsValidate(true);
            for ($j = 0; $j < rand(0, 1); $j++) {
                $com1 = new Comment();
                $com1->setAuthor($user1);
                $com1->setContent($strCom[rand(0,count($strCom)-1)]);
                $com1->setArticle($article);
                $com1->setCreatedAt(new \DateTime());
                $manager->persist($com1);
            }
            $manager->persist($article);
        }
        $manager->flush();

        $article2 = new Article();
        $article2->setTitle("Paul Casey fait le pas à son tour en rejoignant le LIV TOUR")  
                ->setContent("Paul Casey, vainqueur de 21 tournois depuis son passage pro en 2000, a donc décidé que son retour se ferait sur le circuit polémique de Greg Norman aux côtés de ses anciens partenaires de Ryder Cup, Lee Westwood et Ian Poulter entre autres") 
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
                ->setAuthor($user1)
                ->setStatus($stat2)
                ->setIsValidate(true);
            $manager->persist($article2);
            $manager->flush($article2);

        for ($a=1; $a < 5; $a++) { 
            $course = new Course();
            $course->setName('Golf numéro '.$a )  
                    ->setdescription("Description du parcours numéro ".$a )  
                    // ->setImageName("https://media.discordapp.net/attachments/933900460597858434/981574436656386188/unknown.png")
                    ->setAdress("Adresse du parcours n° ".$a )
                    ->setPhone("N° du parcours n° ".$a )
                    ->setUpdatedAt(new \DateTime())
                    ->setAuthor($user1)
                    ->setStatus($stat2)
                    ->setKind($type1)
                    ->setIsValidate(true)
                    ->setRegion($region2);
            $manager->persist($course);
        }
        $manager->flush($course);

        $hole1 = new Hole();
        $hole1->setCourse($course)
              ->setName("Trou 1")
              ->setDistanceRouge(424)
              ->setDistanceBleu(430)
              ->setDistanceJaune(445)
              ->setDistanceBlanc(451)
              ->setTips("Viser l'arbre rose côté droit du fairway, puis taper un coup pour vous approcher le plus du green. Attention aux bunker !");
              $manager->persist($hole1);

        $course1 = new Course();
        $course1->setName('Golf de Garcelles' )  
                ->setdescription("Inauguré en 1989 près d'un magnifique château à 6 kilomètres de Caen, le golf de Garcelles-Secqueville est un parcours de caractère. Le Clos Neuf et le Bois forment un 18 trous par 70 de 5716 mètres de long, au tracé très varié et technique, un véritable challenge accessible à tous les golfeurs.
                Un nouveau parcours de 9 trous, Le Parc, est maintenant disponible." )  
                ->setAdress("Rte de Lorguichon, 14540 Le Castelet" )
                ->setPhone("02 31 39 09 09" )
                ->setUpdatedAt(new \DateTime())
                ->setAuthor($user2)
                ->setStatus($stat2)
                ->setKind($type1)
                ->setRegion($region1)
                ->setIsValidate(true)
                ->addHole($hole1);
        $manager->persist($course1);
        $manager->flush($course1);
    }
}
