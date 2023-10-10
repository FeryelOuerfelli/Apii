<?php

namespace App\Repository;

use App\Entity\RendezVous;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RendezVous>
 *
 * @method RendezVous|null find($id, $lockMode = null, $lockVersion = null)
 * @method RendezVous|null findOneBy(array $criteria, array $orderBy = null)
 * @method RendezVous[]    findAll()
 * @method RendezVous[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RendezVousRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RendezVous::class);
    }

    public function save(RendezVous $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RendezVous $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return RendezVous[] Returns an array of RendezVous objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findRendezVousesBydate($userId,$page,$nbre,$date)
    {
        return $this->createQueryBuilder('r')
            ->join('r.planning','p')
            ->join('p.medecin','m')
            ->join('r.patient','pat')
            ->Where('r.date = :val')
            ->setParameter('val', $date)
            ->andWhere('m.email = :val2 OR pat.email = :val2')
            ->setParameter('val2', $userId)
            ->orderBy('r.heure_debut', 'ASC')
            ->setFirstResult(($page - 1 ) * $nbre)
            ->setMaxResults($nbre)
            ->getQuery()
            ->getResult();
   }
   public function findUpcomingRendezVouses($userId,$page,$nbre,$date)
   {
       return $this->createQueryBuilder('r')
           ->join('r.planning','p')
           ->join('p.medecin','m')
           ->join('r.patient','pat')
           ->Where('r.date >= :val')
           ->andWhere('m.email = :val2 OR pat.email = :val2')
           ->setParameter('val', $date)
           ->setParameter('val2', $userId)
           ->orderBy('r.date', 'ASC')
           ->addOrderBy('r.heure_debut', 'ASC')
           ->setFirstResult(($page - 1 ) * $nbre)
           ->setMaxResults($nbre)
           ->getQuery()
           ->getResult();
  }
  public function findByPlanning($planning_id)
  {
      $req = $this->createQueryBuilder('r');
      if($planning_id!=null){
        $req
        ->join('r.planning','p')
        ->Where('p.id >= :val')
        ->setParameter('val', $planning_id);

      }
      $res = $req->getQuery()
      ->getResult();
      //return $req;

  }

  public function rechercherRDV($value)
  {
    if($value!=null){
      return $this->createQueryBuilder('r')
      ->join('r.planning','p')
      ->join('p.medecin','m')
      ->join('r.patient','pat')
      ->Where('r.date LIKE :val  OR m.nom LIKE :val OR pat.nom LIKE :val OR m.prenom LIKE :val OR pat.prenom LIKE :val OR r.etat LIKE :val')
      ->setParameter('val', $value)
      ->orderBy('r.date', 'ASC')
      ->addOrderBy('r.heure_debut', 'ASC')
      ->getQuery()
      ->getResult()
      ;}
 }
 public function countUpcommingRDVs($date): int
 {
     return $this->createQueryBuilder('r')
         ->select('count(r.id)')
         ->Where('r.date >= :val')
         ->setParameter('val', $date)
         ->getQuery()
         ->getSingleScalarResult()
         ;
 }
    public function findByMedecin($medecin_id)
    {
        return $this->createQueryBuilder('r')
            ->join('r.planning','p')
            ->join('p.medecin','m')
            ->Where('m.id = :val')
            ->setParameter('val', $medecin_id)
            ->getQuery()
            ->getResult();
    }
/*public function findByDate($cin)
    {

      $req=$this->createQueryBuilder('c');
      if($cin!=null)
      {
      $req->select('c.ref')
      ->join('c.utilisateur','u')
      ->addSelect('u.nom')//s.name et c.name : meme nom affiche l'un des deux---->ajouter l'alias :addSelect('c.name t')
      ->where('u.cin=:t')
      ->andWhere("c.agence='BiatTunis'")
      ->setParameter('t',$cin);
      }
      
      $res = $req->getQuery()
      ->getResult();
      dd($res);
      //return $req;
       
    }
*/
}