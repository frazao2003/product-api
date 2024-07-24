<?php

namespace App\Repository;

use App\Dto\TypeProdFilterDto;
use App\Entity\TypeProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeProduct>
 */
class TypeProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeProduct::class);
    }

        public function filterTypeProduct(TypeProdFilterDto $filter): array
        {
            $qb = $this->createQueryBuilder('t');
            $hasFilters=false;
            if ($filter->getType())
            {
                $qb->andWhere('t.typeProduct LIKE :typeProduct')
                ->setParameter('typeProduct', $filter->getType().'%');
                $hasFilters=true;
            }

            if (!$hasFilters){
                $this->findAll();
            }
            return $qb->getQuery()->getResult();
        }
}
