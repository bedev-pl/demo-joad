<?php

namespace App\Command\Technology;

class CreateTechnologyCommand
{
    public function __construct(private string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
