<?php

namespace App\Offerer;

use App\Command\Company\CreateCompanyCommand;
use App\Command\JobOffer\CreateJobOfferCommand;
use App\Entity\Company;
use App\Entity\Technology;
use App\Repository\CompanyRepository;
use App\Repository\JobOfferRepository;
use App\Repository\TechnologyRepository;

abstract class AbstractJobOfferFactory
{
    public function __construct(
        protected CompanyRepository  $companyRepository,
        protected JobOfferRepository $jobOfferRepository,
        protected TechnologyRepository $technologyRepository,
    ) {
    }

    /**
     * @return CreateJobOfferCommand[]
     */
    abstract public function createCommands(array $data): array;

    protected function getCompany(string $companyName): Company
    {
        return $this->companyRepository->findOneBy(['name' => $companyName]);
    }

    protected function getMainTechnology(string $name): Technology
    {
        return $this->technologyRepository->getByName($name);
    }
}
