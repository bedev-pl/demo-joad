<?php

namespace App\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class AbstractRepository extends ServiceEntityRepository
{
    public function save($entity): void
    {
        $this->persist($entity);
        $this->getEntityManager()->flush($entity);
    }

    public function persist($entity): void
    {
        $this->getEntityManager()->persist($entity);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function beginTransaction(): void
    {
        $this->getEntityManager()->beginTransaction();
    }

    public function rollback(): void
    {
        $this->getEntityManager()->rollback();
    }

    public function commit(): void
    {
        $this->getEntityManager()->commit();
    }
}
