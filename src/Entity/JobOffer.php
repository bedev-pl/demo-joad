<?php

namespace App\Entity;

use App\Command\JobOffer\CreateJobOfferCommand;
use App\Command\JobOfferSalary\CreateJobOfferSalaryCommand;
use App\Repository\JobOfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JobOfferRepository::class)
 */
class JobOffer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="jobOffers")
     * @ORM\JoinColumn(nullable=false)
     */
    private Company $company;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $source;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private string $url;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private string $externalId;

    /**
     * @ORM\OneToMany(targetEntity=JobOfferSalary::class, mappedBy="jobOffer", orphanRemoval=true, cascade={"persist"})
     */
    private Collection $salary;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $publishedDate;

    /**
     * @ORM\ManyToOne(targetEntity=Technology::class, inversedBy="jobOffers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mainTechnology;

    public function __construct()
    {
        $this->salary = new ArrayCollection();
    }

    public static function createFromCommand(CreateJobOfferCommand $command): self
    {
        $obj = new self();
        $obj->title = $command->getTitle();
        $obj->source = $command->getSource();
        $obj->url = $command->getUrl();
        $obj->externalId = $command->getExternalId();
        foreach ($command->getSalaryCommands() as $jobOfferSalaryCommand) {
            $obj->addSalary(new JobOfferSalary($jobOfferSalaryCommand));
        }
        foreach ($command->getMainTechnology() as $technologyCommand) {
            $obj->addTechnology(
                $technologyCommand->getExistingTechnology() ?? new Technology($technologyCommand)
            );
        }
        $obj->source = $command->getSource();
        $obj->company = $command->getCompany();
        $obj->mainTechnology = $command->getMainTechnology();
        $obj->publishedDate = $command->getPublishedDate();
        return $obj;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @return Collection|JobOfferSalary[]
     */
    public function getSalary(): Collection
    {
        return $this->salary;
    }

    public function addSalary(JobOfferSalary $salary): self
    {
        if (!$this->salary->contains($salary)) {
            $this->salary[] = $salary;
            $salary->setJobOffer($this);
        }

        return $this;
    }

    public function hasSalaryAlready(CreateJobOfferSalaryCommand $command)
    {
        $criteria = new Criteria();
        $expr = Criteria::expr();
        $criteria->where(
            $expr->andX(
                $expr->eq('from', $command->getFrom()),
                $expr->eq('to', $command->getTo()),
                $expr->eq('contractType', $command->getContractType()),
            )
        );

        return !$this->salary->matching($criteria)->isEmpty();
    }

    public function getPublishedDate(): ?\DateTimeImmutable
    {
        return $this->publishedDate;
    }

    public function setPublishedDate(\DateTimeImmutable $publishedDate): self
    {
        $this->publishedDate = $publishedDate;

        return $this;
    }

    public function getMainTechnology(): ?Technology
    {
        return $this->mainTechnology;
    }

    public function setMainTechnology(?Technology $mainTechnology): self
    {
        $this->mainTechnology = $mainTechnology;

        return $this;
    }
}
