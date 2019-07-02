<?php


namespace App\Service;


use App\Entity\Historique;
use App\Repository\HistoriqueRepository;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use \Twig\Environment;

class BibliothequeService
{

    private $mymailer;
    private $templating;

    public function __construct(\Swift_Mailer $mailer, Environment $templating)
    {
        $this->mymailer = $mailer;
        $this->templating = $templating;

    }

    public function paginator(ServiceEntityRepository $serviceEntityRepository, $page = 1, $limit = 10)
    {
        $count = count($serviceEntityRepository->findAll());
        $query = $serviceEntityRepository->findBy([], null, $limit, ($page - 1) * $limit);

        $totalPages = (ceil($count / $limit) < 1) ? 1 : ceil(($count / $limit));
        $return = [
            'count' => $count,
            'query' => $query,
            'page' => $page,
            'nb_pages' => ceil(($count / $limit))
        ];

        return $return;

    }

    public function notReturn(HistoriqueRepository $historiqueRepository)
    {
        $return = [
            '7day' => [],
            '14day' => [],
            '1month' => [],
        ];

        $dateQuery = new \DateTime();
        $date14day = new \DateTime();
        $date1month = new \DateTime();

        $dateQuery->modify('-7 days');
        $date14day->modify('-14 days');
        $date1month->modify('-1 month');

        //requete récupération des medias non rendu
        $results = $historiqueRepository->notReturn($dateQuery);

        /** @var Historique $result */
        foreach ($results as $result) {

            if ($result->getEmpruntAt() < $date1month) {

                $return['1month'][] = $result;

            } elseif ($result->getEmpruntAt() < $date14day) {

                $return['14day'][] = $result;

            } else {

                $return['7day'][] = $result;

            }
        }


        return $return; //retour des médias non rendu
    }


    public function notReturnSendMail(Registry $doctrine)
    {

        $dayRelance = [
            '7day' => 10,
            '14day' => 20,
            '1month' => 30
        ];

        $result = $this->notReturn($doctrine->getRepository(Historique::class));

        /**
         * @var string $key
         * @var array $items
         */
        foreach ($result as $key => $items) {
            /** @var Historique $item */
            foreach ($items as $item) {
                //on test que la relance à bien été envoyé ou pas
                if ($item->getRelance() < $dayRelance[$key]) {

                    $datas = [
                        'users' => $item->getMembre(),
                        'template_datas' => [
                            'nb_jours' => $item->getEmpruntAt()->diff(new \DateTime())->days,
                            'emprunt_at' => $item->getEmpruntAt(),
                            'media_nom' => $item->getMedia()->getNom()
                        ]
                    ];

                    //appel de la méthode de mailing
                    $this->mailing($item->getRelance(), $datas);
                    //mise à jour du champ relance dans l'objet de type Entity Historique
                    $item->setRelance($dayRelance[$key]);
                    //persist l'entity Historique
                    $doctrine->getManager()->persist($item);
                    //sauvegarde en bdd
                    $doctrine->getManager()->flush();

                }
            }
        }
    }


    protected function mailing($codeTemplate = null, $datas = [])
    {
        $templates = [
            '10' => [
                'path_template' => 'Email/premiere-relance.html.twig',
                'subject' => 'première relance'
            ],
            '20' => [
                'path_template' => 'Email/deuxieme-relance.html.twig',
                'subject' => 'deuxième relance'
            ],
            '30' => [
                'path_template' => 'Email/troisieme-relance.html.twig',
                'subject' => 'troisième relance'
            ]
        ];

        if (array_key_exists($codeTemplate, $templates)) {
            $message = (
            new \Swift_Message(
                $templates[$codeTemplate]['subject']
            )
            )
                ->setFrom('no-reply@bibliotheque.fr')
                ->setTo($datas['users']->getEmail())
                ->setBody(
                    $this->templating->render($templates[$codeTemplate]['path_template'], $datas)
                );

            $this->mymailer->send($message);

            return "Check mail";
        }

    }

    public function reorganisation()
    {

    }
}