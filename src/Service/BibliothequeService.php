<?php

namespace App\Service;

use App\Entity\Historique;
use App\Repository\HistoriqueRepository;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\DateTime;

class BibliothequeService{

    public function paginator(ServiceEntityRepository $serviceEntityRepository, $page = 1, $limit = 10)
    {
        $count = count($serviceEntityRepository->findAll());
        $query = $serviceEntityRepository->findBy([], null, $limit, ($page-1)*$limit);
        //$nbPages = (ceil($count/$limit)<1) ? 1 : ceil(($count/$limit));
        $return = [
            'count' => $count,
            'query' => $query,
            'page' => $page,
            'nb_pages' => ceil(($count/$limit))
        ];
        return $return;
    }

    public function notReturn(HistoriqueRepository $historiqueRepository)
    {
        $return = [
            '7days' => [],
            '14days' => [],
            '1month' => [],
        ];

        $dateQuery = new \DateTime();
        $date14days = new \DateTime();
        $date1month = new \DateTime();

        $dateQuery->modify('-7 days');
        $date14days->modify('-14 days');
        $date1month->modify('-1 month');

        //requête récupération des medias non rendus
        $results =  $historiqueRepository->notReturn($dateQuery);

        /**
         * @var Historique $result
         */
        foreach ($results as $result){
            if ($result->getEmpruntAt() <  $date1month){
                $return['1month'][] = $result;
            } elseif ($result->getEmpruntAt() < $date14days){
                $return['14days'][] = $result;
            } else {
                $return['7days'][] = $result;
            }

        }
        dump($return);
;
        //boucle comparaison des dates array triés envoie de mail
        //si >7 jours appel mailling type mail simple rappel
        return $return;
    }

    public function notReturnSendMail(Registry $doctrine)
    {

        $dayRelance = [
            '7day' => 10,
            '14day' => 20,
            '1month' => 30
        ];

        $result = $this->notReturn($doctrine->getRepository(Historique::class));

        foreach ($result as $key => $items){
            /** @var Historique $item */
            foreach ($items as $item){
                if($item->getRelance() < $dayRelance[$key]){
                    $this->mailing($item->getRelance(), $datas);
                    $item->setRelance($dayRelance[$key]);
                    $doctrine->getManager()->persist($item);
                    $doctrine->getManager()->flush();
                }
            }
        }
    }


    public function mailing()
    {

    }

    public function reorganisationBibliotheque()
    {

    }
}