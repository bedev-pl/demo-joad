<?php

namespace App\Offerer\JustJoin;

use App\Command\JobOffer\CreateJobOfferCommand;
use App\Offerer\AbstractJobOfferFactory;

class JobOfferCommandFactory extends AbstractJobOfferFactory
{
    public function createCommands(array $data): array
    {
        $commands = [];
        foreach ($data as $offer) {
            $company = $this->getCompany($offer['company_name']);
            $mainTechnology = $this->getMainTechnology($offer['marker_icon']);
            $commands[] = CreateJobOfferCommand::createFromJustJoinData($company, $offer, $mainTechnology);
        }
        return $commands;
    }
}
