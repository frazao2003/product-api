<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Dto\ProductFilter;


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

        public function filterProduct(ProductFilter $filter): array
        {
            $qb = $this->createQueryBuilder('p');
            $hasFilter = false;

            if ($filter->getName() !== null) {
                $qb->andWhere('p.name= :name')
                   ->setParameter('name', $filter->getName());
                $hasFilter = true;
            }
    
            if ($filter->getType() !== null) {
                $qb->andWhere('p.typeProduct = :typeProduct')
                   ->setParameter('typeProduct', $filter->getType());
                $hasFilter = true;
            }

            if ($hasFilter == false) {
                $products = $this->findAll();
                return $products;
            }
    
            // Simulação da execução da query e retorno dos resultados
            return $qb->getQuery()->getResult();
        }

        public function findById(int $id): ?Product
        {
            $product = $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

            return $product;
        }

        public function delete(Product $product): void
        {
            $this->getEntityManager()->remove($product);
            $this->getEntityManager()->flush();
        }

}
