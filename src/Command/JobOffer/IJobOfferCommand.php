<?php

namespace App\Command\JobOffer;

use App\Entity\Company;

interface IJobOfferCommand
{
    public function getCompany(): Company;
    public function getTitle(): string;
    public function getSource(): string;
    public function getUrl(): string;
}
