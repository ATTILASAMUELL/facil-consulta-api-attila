<?php

namespace App\DTOs\Patient;

class UpdatePatientDTO
{
    public ?string $name;
    public ?string $cpf;
    public ?string $cellphone;

    public function __construct(?string $name, ?string $cpf, ?string $cellphone)
    {
        $this->name = $name;
        $this->cpf = $cpf;
        $this->cellphone = $cellphone;
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            $data['name'] ?? null,
            $data['cpf'] ?? null,
            $data['cellphone'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'cpf' => $this->cpf,
            'cellphone' => $this->cellphone,
        ];
    }
}
