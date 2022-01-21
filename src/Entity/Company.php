<?php

namespace App\Entity;

use App\Command\Company\CreateCompanyCommand;
use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 * @UniqueEntity("name")
 */
class Company
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     */
    private string $name;

    /**
     * @ORM\OneToMany(targetEntity=JobOffer::class, mappedBy="company", orphanRemoval=true)
     */
    private $jobOffers;

    /**
     * @ORM\Column(type="string", length=255, name="url", nullable=true)
     */
    private $url;

    public function __construct()
    {
        $this->jobOffers = new ArrayCollection();
    }

    public static function createFromCommand(CreateCompanyCommand $command)
    {
        $obj = new self();
        $obj->name = $command->getName();
        $obj->url = $command->getUrl();
        return $obj;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return Collection|JobOffer[]
     */
    public function getJobOffers(): Collection
    {
        return $this->jobOffers;
    }

//    public function addJobOffer(JobOffer $jobOffer): self
//    {
//        if (!$this->jobOffers->contains($jobOffer)) {
//            $this->jobOffers[] = $jobOffer;
//            $jobOffer->setCompany($this);
//        }
//
//        return $this;
//    }
//
//    public function removeJobOffer(JobOffer $jobOffer): self
//    {
//        if ($this->jobOffers->removeElement($jobOffer)) {
//            // set the owning side to null (unless already changed)
//            if ($jobOffer->getCompany() === $this) {
//                $jobOffer->setCompany(null);
//            }
//        }
//
//        return $this;
//    }
}
