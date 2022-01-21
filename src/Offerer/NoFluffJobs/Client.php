<?php

namespace App\Offerer\NoFluffJobs;

use App\Offerer\AbstractJobOfferFactory;
use App\Offerer\IClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Client implements IClient
{
    private array $data = [];

    public function __construct(
        private HttpClientInterface    $noFluffJobsClient,
        private JobOfferCommandFactory $factory,
    ) {
    }

    public function prepareData(): void
    {
        $respose = $this->noFluffJobsClient->request('GET', 'api/search/posting');
        $data =  json_decode($respose->getContent(), true);
        $this->data = $data['postings'];
    }

    public function getJobOfferCommandFactory(): AbstractJobOfferFactory
    {
        return $this->factory;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
