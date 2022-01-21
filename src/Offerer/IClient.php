<?php

namespace App\Offerer;

interface IClient
{
    public function prepareData(): void;
    public function getData(): array;
    public function getJobOfferCommandFactory(): AbstractJobOfferFactory;
}
