<?php

namespace App\Service;

use App\Command\JobOffer\CreateJobOfferCommand;
use App\Entity\JobOffer;
use App\Entity\JobOfferSalary;
use App\Offerer\IClient;
use App\Repository\JobOfferRepository;

class JobOfferService
{
    public function __construct(private JobOfferRepository $repository)
    {
    }

    public function processOffers(IClient ...$clients)
    {
        foreach ($clients as $client) {
            $commands = $client->getJobOfferCommandFactory()->createCommands($client->getData());
            $this->saveOffers($commands);
        }
    }

    public function saveOffers(array $commands)
    {
        try {
            $this->repository->getEntityManager()->beginTransaction();

            /** @var CreateJobOfferCommand $command */
            foreach ($commands as $command) {
                $currentOffer = $this->repository->findOneBy(['source' => $command->getSource(), 'externalId' => $command->getExternalId()]);
                if ($currentOffer) {
                    foreach ($command->getSalaryCommands() as $salaryCommand) {
                        if (!$currentOffer->hasSalaryAlready($salaryCommand)) {
                            $currentOffer->addSalary(new JobOfferSalary($salaryCommand));
                        }
                    }
                } else {
                    $currentOffer = JobOffer::createFromCommand($command);
                    $this->repository->getEntityManager()->persist($currentOffer);
                }
            }
            $this->repository->getEntityManager()->flush();
            $this->repository->getEntityManager()->commit();
        } catch (\Throwable $exception) {
            $this->repository->getEntityManager()->rollback();
            throw $exception;
        }
    }
}
