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

    //    /**
    //     * @return TypeProduct[] Returns an array of TypeProduct objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

        public function findOneByTypeProduct($typeProduct): ?TypeProduct
        {
            return $this->createQueryBuilder('t')
                ->andWhere('TRANSLATE(LOWER(t.typeProduct), \' \', \'\') = TRANSLATE(LOWER(:typeProduct), \' \', \'\')')
                ->setParameter('typeProduct', $typeProduct)
                ->getQuery()
                ->getOneOrNullResult()
            ;
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
