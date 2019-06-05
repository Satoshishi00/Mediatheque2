<?php

namespace App\Repository;

use App\Entity\Membre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Membre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Membre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Membre[]    findAll()
 * @method Membre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MembreRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Membre::class);
    }

    // /**
    //  * @return Membre[] Returns an array of Membre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Membre
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /*
     * @param array $search
     * @return mixed
     */
    public function search(array $search = null){
        $qb = $this->createQueryBuilder('m');
        if(isset($search['nom']) && !empty($search['nom'])){
            $qb->andWhere(
                $qb->expr()->like('m.nom',
                    $qb->expr()->literal( '%'.$search['nom'].'%')));
        }
        if(isset($search['date']) && !empty($search['date'])){
            $qb->andWhere(
                $qb->expr()->eq('m.created_at', $qb->expr()->literal($search['date']->format('Y-m-d H:i:s')))
            );

        }
        dd($search);
        if(isset($search['type']) && !empty($search['type'])){
            $qb->andWhere($qb->expr()->literal( '%'.$search['type'].'%'));
        }
        return $qb->getQuery()->getResult();
    }
}
