<?php

namespace App\Entity;

use App\Command\JobOfferSalary\CreateJobOfferSalaryCommand;
use App\Repository\JobOfferSalaryRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JobOfferSalaryRepository::class)
 */
class JobOfferSalary
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contractType;

    /**
     * @ORM\Column(type="integer", nullable=true, name="`from`")
     */
    private $from;

    /**
     * @ORM\Column(type="integer", nullable=true, name="`to`")
     */
    private $to;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $currency;

    /**
     * @ORM\ManyToOne(targetEntity=JobOffer::class, inversedBy="salary")
     * @ORM\JoinColumn(nullable=false)
     */
    private $jobOffer;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $entryDate;

    public function __construct(CreateJobOfferSalaryCommand $command)
    {
        $this->contractType = $command->getContractType();
        $this->currency = $command->getCurrency();
        $this->from = $command->getFrom();
        $this->to = $command->getTo();
        $this->entryDate = new \DateTimeImmutable();
    }


    public static function createFromCommand(CreateJobOfferSalaryCommand $command): self
    {
        $obj = new self();

        return $obj;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setJobOffer(JobOffer $jobOffer)
    {
        $this->jobOffer = $jobOffer;
    }
}
