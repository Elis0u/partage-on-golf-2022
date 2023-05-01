<?php

namespace App\Repository;

use App\Entity\AlikePost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AlikePost>
 *
 * @method AlikePost|null find($id, $lockMode = null, $lockVersion = null)
 * @method AlikePost|null findOneBy(array $criteria, array $orderBy = null)
 * @method AlikePost[]    findAll()
 * @method AlikePost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlikePostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AlikePost::class);
    }

    public function add(AlikePost $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AlikePost $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByUserAndPost($userProfile, $post): ?AlikePost
    {
            return $this->createQueryBuilder('a')
                ->andWhere('a.userProfile = :userP')
                ->andWhere('a.post = :post')
                ->setParameters([
                    'userP'=> $userProfile ,
                    'post'=> $post 
                ])
                ->getQuery()
                ->getOneOrNullResult();
    }

//    /**
//     * @return AlikePost[] Returns an array of AlikePost objects
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

//    public function findOneBySomeField($value): ?AlikePost
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
