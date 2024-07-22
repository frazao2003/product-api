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
         * @return Product[] Returns an array of Product objects
         */
        public function findByTypeProduct($typeProduct): array
        {
            return $this->createQueryBuilder('p')
                ->andWhere('TRANSLATE(LOWER(p.TypeProduct), \' \', \'\') = TRANSLATE(LOWER(:TypeProduct), \' \', \'\')')
                ->setParameter('TypeProduct', $typeProduct)
                ->orderBy('p.id', 'ASC')
                ->getQuery()
                ->getResult()
            ;
        }

        public function findOneByName($name): ?Product
        {
            return $this->createQueryBuilder('p')
            ->andWhere('TRANSLATE(LOWER(p.name), \' \', \'\') = TRANSLATE(LOWER(:name), \' \', \'\')')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
        }

}
