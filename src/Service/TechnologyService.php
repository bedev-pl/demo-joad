<?php

namespace App\Service;

use App\Command\Technology\CreateTechnologyCommand;
use App\Entity\Technology;
use App\Offerer\IClient;
use App\Offerer\JustJoin\Client as JustJoinClient;
use App\Offerer\NoFluffJobs\Client as NoFluffJobsClient;
use App\Repository\TechnologyRepository;

class TechnologyService
{

    public function __construct(private TechnologyRepository $repository)
    {
    }

    public function processTechnologies(IClient ...$clients)
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
                $tempName = strtoupper(trim($offer['marker_icon']));
                if (!in_array($tempName, $temp)) {
                    $temp[] = $tempName;
                    $this->createTechnology($offer['marker_icon']);
                }
            }
            $this->repository->flush();
            $this->repository->commit();
        } catch (\Throwable $exception) {
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
                if (isset($offer['technology']) && !empty($offer['technology'])) {
                    $tempName = strtoupper(trim($offer['technology']));
                    if (!in_array($tempName, $temp)) {
                        $temp[] = $tempName;
                        $this->createTechnology($offer['technology']);
                    }
                }
            }
            $this->repository->flush();
            $this->repository->commit();
        } catch (\Throwable $exception) {
            $this->repository->rollback();
            throw $exception;
        }
    }

    protected function createTechnology(string $name)
    {
        $exist = $this->repository->findOneBy(['name' => $name]);
        if (!$exist) {
            $this->repository->persist(new Technology(new CreateTechnologyCommand($name)));
        }
    }


}