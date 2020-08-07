<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Product::class);
    }

    public function addAndSave(Product $product): void
    {
        $this->getEntityManager()->persist($product);
        $this->getEntityManager()->flush($product);
    }

    public function add(Product $product): ProductRepository
    {
        $this->getEntityManager()->persist($product);

        return $this;
    }

    public function save(): ProductRepository
    {
        $this->getEntityManager()->flush();

        return $this;
    }

    public function ofId(string $id): ?Product
    {
        return $this->getEntityManager()
            ->getRepository(Product::class)
            ->find($id)
        ;
    }
}
