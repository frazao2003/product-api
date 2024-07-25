<?php

namespace App\Repository;

use App\Dto\StockProdFilter;
use App\Entity\StockProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StockProduct>
 */
class StockProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockProduct::class);
    }

    public function findByCodLote(string $cod): ?StockProduct
    {
        return $this->createQueryBuilder("sp")
        ->andwhere("sp.codLote = :codLote")
        ->setParameter("codLote", $cod)
        ->getQuery()->getOneorNullResult();
    }

    public function filterStockProducts(StockProdFilter $filter): array
    {
        $qb = $this->createQueryBuilder('sp')
        ->leftJoin('sp.product', 'p')
        ->leftJoin('p.typeProduct', 'tp');
        $hasfilter = false;

        if ($filter->getCodLote() !== null) {
            $qb->andWhere('sp.codLote = :codLote')
            ->setParameter('codLote', $filter->getCodLote());
            $hasfilter = true;

        }

        if ($filter->getExpirationDate() !== null) {
            $qb->andWhere('sp.expirationDate = :expirationDate')
            ->setParameter('expirationDate', $filter->getExpirationDate());
            $hasfilter = true;

        }

        if ($filter->getProduct() !== null) {
            $qb->andWhere('sp.product = :product')
            ->setParameter('product', $filter->getProduct());
            $hasfilter = true;

        }

        if ($filter->getTypeProduct()!== null) {
            $qb->andWhere('p.typeProduct = :productType')
            ->setParameter('productType', $filter->getTypeProduct());
            $hasfilter = true;

        }
        if (!$hasfilter)
        {
            $this->findAll(); 
            
        }

        return $qb->getQuery()->getResult();
    }
    public function filterDateRange(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate): array
    {
        $qb = $this->createQueryBuilder('sp');

        $qb->where('sp.expirationDate BETWEEN :startDate AND :endDate')
           ->setParameter('startDate', $startDate)
           ->setParameter('endDate', $endDate);
    
        return $qb->getQuery()->getResult();
    }

    public function expiratedProd(): array
    {
        $dateTime = new \DateTimeImmutable('now');

        $qb = $this->createQueryBuilder('sp');
        $qb->where('sp.expirationDate <= :expirationDate')
        ->setParameter('expirationDate', $dateTime);

        return $qb->getQuery()->getResult();
    }
}
