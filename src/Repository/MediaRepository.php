<?php

namespace App\Repository;

use App\Entity\Media;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Media|null find($id, $lockMode = null, $lockVersion = null)
 * @method Media|null findOneBy(array $criteria, array $orderBy = null)
 * @method Media[]    findAll()
 * @method Media[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Media::class);
    }

    // /**
    //  * @return Media[] Returns an array of Media objects
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
    public function findOneBySomeField($value): ?Media
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
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
        if(isset($search['sortie']) && $search['sortie']){
            $qb->innerJoin('m.historiques', 'h')
                ->andWhere($qb->expr()->isNull('h.retour_at'));
        }
        dd($search);
        if(isset($search['type']) && $search['type']){
            $qb->expr()->eq('m.designation', '%'.$search['type']->designation.'%');
        }
        return $qb->getQuery()->getResult();
    }
}
