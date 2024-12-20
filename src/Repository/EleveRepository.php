<?php

namespace App\Repository;

use App\Entity\Classe;
use App\Entity\Eleve;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Eleve>
 *
 * @method Eleve|null find($id, $lockMode = null, $lockVersion = null)
 * @method Eleve|null findOneBy(array $criteria, array $orderBy = null)
 * @method Eleve[]    findAll()
 * @method Eleve[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EleveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Eleve::class);
    }

    public function findByNameAndClasse(string $name = null, string $classe = null): array
    {
        $queryBuilder = $this->createQueryBuilder('e')
            ->leftJoin('e.classe', 'c')
            ->addSelect('c');

        if ($name) {
            $queryBuilder->andWhere('e.nom LIKE :name OR e.prenom LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }

        if ($classe) {
            $queryBuilder->andWhere('c.nom LIKE :classe')
                ->setParameter('classe', '%' . $classe . '%');
        }

        return $queryBuilder
            ->orderBy('e.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Eleve[] Returns an array of Eleve objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Eleve
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
