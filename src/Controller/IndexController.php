<?php

namespace App\Controller;

use App\Offerer\JustJoin\Client as JustJoinClient;
use App\Offerer\NoFluffJobs\Client as NoFluffJobsClient;
use App\Service\CompanyService;
use App\Service\JobOfferService;
use App\Service\TechnologyService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class IndexController
{

    public function __construct(
        private JustJoinClient    $justJoinClient,
        private NoFluffJobsClient $noFluffJobsClient,
        private JobOfferService   $jobOfferService,
        private CompanyService    $companyService,
        private TechnologyService $technologyService)
    {
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $this->noFluffJobsClient->prepareData();
        $this->justJoinClient->prepareData();

        $this->companyService->processCompanies($this->noFluffJobsClient, $this->justJoinClient);
        $this->technologyService->processTechnologies($this->noFluffJobsClient, $this->justJoinClient);


        $this->jobOfferService->processOffers($this->justJoinClient, $this->noFluffJobsClient);
        return new JsonResponse('OK');
    }
}
