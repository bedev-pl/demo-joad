<?php

namespace App\Offerer\NoFluffJobs;

use App\Command\JobOffer\CreateJobOfferCommand;
use App\Offerer\AbstractJobOfferFactory;

class JobOfferCommandFactory extends AbstractJobOfferFactory
{
    public function createCommands(array $data): array
    {
        $commands = [];
        foreach ($data as $offer) {
            $company = $this->getCompany($offer['name']);

            if (empty($offer['technology'])) {
                continue;
            }

            $technology = $this->getMainTechnology($offer['technology']);
            $commands[] = CreateJobOfferCommand::createFromNoFluffJobsData($company, $offer, $technology);
        }

        return $commands;
    }
}
