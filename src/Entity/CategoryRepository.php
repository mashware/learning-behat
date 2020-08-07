<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Category::class);
    }

    public function addAndSave(Category $category): void
    {
        $this->getEntityManager()->persist($category);
        $this->getEntityManager()->flush($category);
    }

    public function add(Category $category): CategoryRepository
    {
        $this->getEntityManager()->persist($category);

        return $this;
    }

    public function save(): CategoryRepository
    {
        $this->getEntityManager()->flush();

        return $this;
    }
}
