<?php

namespace App\Command\JobOfferSalary;

use JetBrains\PhpStorm\Pure;

class CreateJobOfferSalaryCommand
{
    private ?string $contractType = null;
    private ?int $from = null;
    private ?int $to = null;
    private ?string $currency = null;

    #[Pure] public static function createFromJustJoinData(array $data): self
    {
        $obj = new self();
        $obj->contractType = $data['type'];
        if ($data['salary']) {
            $obj->from = $data['salary']['from'];
            $obj->to = $data['salary']['to'];
            $obj->currency = self::prepareSalaryCode($data['salary']['currency']);
        }

        return $obj;
    }

    #[Pure] public static function createFromNoFluffJobsData(array $data): self
    {
        $obj = new self();
        $obj->contractType = $data['type'];
        $obj->currency = self::prepareSalaryCode($data['currency']);
        $obj->from = $data['from'];
        $obj->to = $data['to'];
        return $obj;
    }

    public function getContractType(): ?string
    {
        return $this->contractType;
    }

    public function getFrom(): ?int
    {
        return $this->from;
    }

    public function getTo(): ?int
    {
        return $this->to;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    private static function prepareSalaryCode(string $code): string
    {
        return strtoupper($code);
    }
}
