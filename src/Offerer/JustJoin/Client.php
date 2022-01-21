<?php

namespace App\Offerer\JustJoin;

use App\Offerer\IClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Client implements IClient
{
    private array $data = [];

    public function __construct(
        private HttpClientInterface $justJoinClient,
        private JobOfferCommandFactory $jobOfferCommandFactory
    )
    {
    }

    public function prepareData(): void
    {
        $respose = $this->justJoinClient->request('GET', 'api/offers');
        $this->data = json_decode($respose->getContent(), true);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getJobOfferCommandFactory(): JobOfferCommandFactory
    {
        return $this->jobOfferCommandFactory;
    }
}
