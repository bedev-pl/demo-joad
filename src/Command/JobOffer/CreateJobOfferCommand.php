<?php

namespace App\Command\JobOffer;

use App\Command\JobOfferSalary\CreateJobOfferSalaryCommand;
use App\Command\Technology\CreateTechnologyCommand;
use App\Entity\Company;
use App\Entity\Technology;
use App\Offerer\JustJoin\Client as JustJoinClient;
use App\Offerer\NoFluffJobs\Client as NoFluffJobsClient;
use DateTimeImmutable;

class CreateJobOfferCommand implements IJobOfferCommand
{
    private string $title;
    /** @var CreateJobOfferSalaryCommand[] */
    private array $salaryCommands;
    private string $city;
    private ?string $country;
    private bool $remote;
    private string $externalId;
    private string $source;
    private string $url;
    private DateTimeImmutable $publishedDate;

    private function __construct(private Company $company, private Technology $mainTechnology)
    {
    }

    public static function createFromJustJoinData(Company $company, array $data, Technology $mainTechnology): self
    {
        $obj = new self($company, $mainTechnology);
        $obj->externalId = $data['id'];
        $obj->title = $data['title'];
        $obj->city = $data['city'];
        $obj->country = $data['country_code'];
        $obj->remote = $data['remote'];
        $obj->salaryCommands = self::createSalaryCommands($data, JustJoinClient::class);
        $obj->source = JustJoinClient::class;
        $obj->url = 'https://justjoin.it/offers/' . $obj->externalId;
        $obj->publishedDate = new DateTimeImmutable($data['published_at']);
        return $obj;
    }

    public static function createFromNoFluffJobsData(Company $company, array $data, Technology $mainTechnology): self
    {
        $obj = new self($company, $mainTechnology);
        $obj->externalId = $data['id'];
        $obj->title = $data['title'];
        $obj->city = 'do ogarniecia';
        $obj->country = $data['regions'] ? $data['regions'][0] : null;
        $obj->remote = $data['location']['fullyRemote'];
        $obj->salaryCommands = self::createSalaryCommands($data, NoFluffJobsClient::class);
        $obj->source = NoFluffJobsClient::class;
        $obj->url = 'https://nofluffjobs.com/job/' . $data['url'];
        $obj->publishedDate = (new DateTimeImmutable())->setTimestamp(substr($data['posted'], 0, -3));

        return $obj;
    }

    /**
     * @return CreateJobOfferSalaryCommand[]
     */
    private static function createSalaryCommands(array $data, string $source): array
    {
        switch ($source) {
            case JustJoinClient::class:
                foreach ($data['employment_types'] as $employmentType) {
                    $commands[] = CreateJobOfferSalaryCommand::createFromJustJoinData($employmentType);
                }
                break;
            case NoFluffJobsClient::class:
                $commands[] = CreateJobOfferSalaryCommand::createFromNoFluffJobsData($data['salary']);
                break;
        }

        return $commands;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSalaryCommands(): array
    {
        return $this->salaryCommands;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function isRemote(): bool
    {
        return $this->remote;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getMainTechnology(): Technology
    {
        return $this->mainTechnology;
    }

    public function getPublishedDate(): DateTimeImmutable
    {
        return $this->publishedDate;
    }
}