<?php

namespace App\Repository;

use App\Entity\Alike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Alike>
 *
 * @method Alike|null find($id, $lockMode = null, $lockVersion = null)
 * @method Alike|null findOneBy(array $criteria, array $orderBy = null)
 * @method Alike[]    findAll()
 * @method Alike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Alike::class);
    }

    public function add(Alike $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Alike $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByUserAndArticle($userProfile, $article): ?Alike
    {
            return $this->createQueryBuilder('a')
                ->andWhere('a.userProfile = :userP')
                ->andWhere('a.article = :article')
                ->setParameters([
                    'userP'=> $userProfile ,
                    'article'=> $article 
                ])
                // ->setParameter('article', $article)

                // ->setParameter('userP', $userProfile)
                // ->setParameter('article', $article)

                ->getQuery()
                ->getOneOrNullResult();
    }

//    /**
//     * @return Alike[] Returns an array of Alike objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Alike
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
