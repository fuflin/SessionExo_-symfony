<?php

namespace App\Repository;

use App\Entity\Session;
use App\Entity\Stagiaire;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Stagiaire>
 *
 * @method Stagiaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stagiaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stagiaire[]    findAll()
 * @method Stagiaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StagiaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stagiaire::class);
    }

    public function save(Stagiaire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Stagiaire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function showStagInSession(Session $session){

        // on instancie l'entity manager pour récupérer les données pour une fonction ultérieur
        $em = $this->getEntityManager();

        // on instancie la fonction pour créer la requête DQL
        $sub = $em->createQueryBuilder();

        // on prépare la requête pour afficher les stagiaires inscirts dans une

        $sub->select('st.id') // on choisi ce qu'on veux récupérer
                ->from('App\Entity\Stagiaire', 'st') // on dit d'où on récupère les infos (adresse de l'entité)
                ->join('st.sessions', 's') // on lie les tables entre elles (ici la table session et la table associative)
                ->where('st.id = :id') // on précise la condition
                ->setParameter('id', $session); // on défini le paramètre

        $query = $em->createQueryBuilder();

        // on prépare la sous-requête pour afficher les stagiaires non inscrit dans une session

        $query->select('sta.id, sta.firstname, sta.lastname') // on choisi ce qu'on veux récupérer
                ->from('App\Entity\Stagiaire', 'sta') // on dit d'où on récupère les infos (adresse de l'entité)
                ->where($query->expr()->notIn('sta.id',  $sub->getDQL()))
        // dans le where(variable->fonction expression pour appeler->notIn((n'est pas/ n'a pas) ici l'id stagiaire, $sub->dans la requête précédente)
                ->setParameter('id', $session);

            return $query->getQuery()->getResult();

    }

//    /**
//     * @return Stagiaire[] Returns an array of Stagiaire objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Stagiaire
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
