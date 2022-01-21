<?php

namespace App\Repository;

use App\Entity\JobOfferSalary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method JobOfferSalary|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobOfferSalary|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobOfferSalary[]    findAll()
 * @method JobOfferSalary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobOfferSalaryRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobOfferSalary::class);
    }
}
