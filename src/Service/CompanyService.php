<?php

namespace App\Service;

use App\Command\Company\CreateCompanyCommand;
use App\Entity\Company;
use App\Offerer\IClient;
use App\Offerer\JustJoin\Client as JustJoinClient;
use App\Offerer\NoFluffJobs\Client as NoFluffJobsClient;
use App\Repository\CompanyRepository;
use Throwable;

class CompanyService
{
    public function __construct(private CompanyRepository $repository)
    {
    }

    public function processCompanies(IClient ...$clients)
    {
        foreach ($clients as $client) {
            $data = $client->getData();
            switch (true) {
                case $client instanceof JustJoinClient:
                    $this->createFromJustJoinData($data);
                    break;
                case $client instanceof NoFluffJobsClient:
                    $this->createFromNoFluffJobsData($data);
                    break;
            }
        }
    }

    protected function createFromJustJoinData(array $data)
    {
        try {
            $this->repository->beginTransaction();
            $temp = [];
            foreach ($data as $offer) {
                $tmpName = $offer['company_name'];
                $tmpName = strtoupper(trim($tmpName));
                if (!in_array($tmpName, $temp)) {
                    $temp[] = $tmpName;
                    $this->createCompany($offer['company_name'], $offer['company_url']);
                }
            }
            $this->repository->flush();
            $this->repository->commit();
        } catch (Throwable $exception) {
            $this->repository->rollback();
            throw $exception;
        }
    }

    protected function createFromNoFluffJobsData(array $data)
    {
        try {
            $this->repository->beginTransaction();
            $temp = [];
            foreach ($data as $offer) {
                $tmpName = $offer['name'];
                $tmpName = strtoupper(trim($tmpName));
                if (!in_array($tmpName, $temp)) {
                    $temp[] = $tmpName;
                    $this->createCompany($offer['name']);
                }
            }
            $this->repository->flush();
            $this->repository->commit();
        } catch (Throwable $exception) {
            $this->repository->rollback();
            throw $exception;
        }
    }

    protected function createCompany(string $companyName, ?string $companyUrl = null): void
    {
        $command = new CreateCompanyCommand($companyName, $companyUrl);
        $company = $this->repository->findOneBy(['name' => $companyName]);
        if (!$company) {
            $company = Company::createFromCommand($command);
            $this->repository->persist($company);
        }
    }
}
