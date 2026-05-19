<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Recherche les produits en fonction des filtres du catalogue
     */
    public function findWithFilters(?string $search, ?int $categoryId, ?float $priceMin, ?float $priceMax): array
    {
        // 'p' est l'alias pour Product
        $qb = $this->createQueryBuilder('p');

        // S'il y a une recherche textuelle, on cherche dans le nom ou la description
        if ($search) {
            $qb->andWhere('p.name LIKE :search OR p.description LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        // Si une catégorie est sélectionnée
        if ($categoryId) {
            $qb->andWhere('p.category = :categoryId')
               ->setParameter('categoryId', $categoryId);
        }

        // Si un prix minimum est renseigné
        if ($priceMin) {
            $qb->andWhere('p.price >= :priceMin')
               ->setParameter('priceMin', $priceMin);
        }

        // Si un prix maximum est renseigné
        if ($priceMax) {
            $qb->andWhere('p.price <= :priceMax')
               ->setParameter('priceMax', $priceMax);
        }

        // On exécute la requête construite et on renvoie le résultat
        return $qb->getQuery()->getResult();
    }
    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
