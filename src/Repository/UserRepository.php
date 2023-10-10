<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Integer;
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

    public function save(User $entity, bool $flush = false): void
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

        $this->save($user, true);
    }

   /**
//     * @return User[] Returns an array of User objects
//     */
   public function findUsersBySearchTerm($searchTerm): array
    {
       return $this->createQueryBuilder('u')
           ->where('u.nom LIKE :searchTerm OR
            u.prenom LIKE :searchTerm OR
            u.email Like :searchTerm OR
            u.specialite LIKE :searchTerm OR 
            u.adresse Like :searchTerm OR
            u.sexe LIKE :searchTerm OR
            u.etat LIKE :searchTerm' )
           ->setParameter('searchTerm', $searchTerm)
           ->getQuery()
           ->getResult()
       ;
    }

    public function findOneByEmail($email): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :val')
            ->setParameter('val', $email)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findUserByEmail($email): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :val')
            ->setParameter('val', $email)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findOneByResetToken($token): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.resetToken  = :val')
            ->setParameter('val', $token)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findUsersByRole($page,$nbre,$role): array
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->Where('u.roles LIKE :role')
            ->setParameter('role', '%'.$role.'%')
            ->setFirstResult(($page - 1 ) * $nbre)
            ->setMaxResults($nbre)
            ->getQuery()
            ->getResult()
            ;
    }

    public function countUsersByRole($role): int
    {
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->Where('u.roles LIKE :role')
            ->setParameter('role', '%'.$role.'%')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
    public function countUsersBySexe($sexe): int
    {
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->Where('u.sexe LIKE :sexe')
            ->setParameter('sexe', '%'.$sexe.'%')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }


}
