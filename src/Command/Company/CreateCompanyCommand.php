<?php

namespace App\Command\Company;

class CreateCompanyCommand
{
    public function __construct(private string $name, private ?string $url)
    {
        $this->name = trim($this->name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }
}
