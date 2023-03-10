<?php

namespace App\Repository;

use App\Entity\MainCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MainCategory>
 *
 * @method MainCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MainCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MainCategory[]    findAll()
 * @method MainCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MainCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MainCategory::class);
    }

    public function add(MainCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MainCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     */
    public function findAllActiveCategories()
    {
        $query = $this->getEntityManager()->createQuery('SELECT m, c
        FROM App\Entity\MainCategory m
        Join m.categories c
        WHERE c.isActive = true
        ');
        $activeCategories = $query->getResult();
        return $activeCategories;
    }

    /**
     */
    public function findAllCategoriesByMainCat($id)
    {
        $query = $this->getEntityManager()->createQuery("SELECT m, c
        FROM App\Entity\MainCategory m
        Join m.categories c
        WHERE m.id = $id 
        ");
        $activeCategories = $query->getResult();
        return $activeCategories;
    }

    /**
     */
    public function findAllActiveAdvertisements($id)
    {
        $query = $this->getEntityManager()->createQuery('SELECT m, c, o, w
        FROM App\Entity\MainCategory m
        JOIN m.categories c
        JOIN c.offer o
        JOIN c.wish w
        WHERE o.isActive = true
        AND w.isActive = true
        AND c.isActive = true
        AND m.id = '. $id .'
        ');
        $activeCategories = $query->getResult();
        return $activeCategories;
    }


//    /**
//     * @return MainCategory[] Returns an array of MainCategory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MainCategory
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
