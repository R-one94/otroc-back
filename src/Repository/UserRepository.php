<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->add($user, true);
    }

    /**
     * Retrieves a users informations and it's active offers
     *
     * @param [id] $id
     * @return array
     */
    public function findUserActiveOffers($id) : array
    {   
        // Je veux les offres actives de l'utilisateur courant
        $query = $this->getEntityManager()->createQuery(
            "SELECT u FROM App\Entity\User u
            JOIN u.offer o 
            WHERE u.id = $id 
            AND o.isActive = 1");

        return $query->getResult();
    }

    /**
     * Retrieves a users informations and it's active wishes
     *
     * @param [id] $id
     * @return array
     */
    public function findUserActiveWishes($id) : array
    {   
        // Je veux les offres actives de l'utilisateur courant
        $query = $this->getEntityManager()->createQuery(
            "SELECT u FROM App\Entity\User u
            JOIN u.wish w 
            WHERE u.id = $id 
            AND w.isActive = 1 ");

        return $query->getResult();
    }

    /**
     * Retrieves a users informations and it's inactive advertisements
     *
     * @param [id] $id
     * @return array
     */
    public function findUserInactiveAdverts($id) : array
    {   
        $query = $this->getEntityManager()->createQuery(
            "SELECT u FROM App\Entity\User u
            JOIN u.wish w 
            JOIN u.offer o
            WHERE u.id = $id 
            AND w.isActive = false
            AND o.isActive = false ");

        return $query->getResult();
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
