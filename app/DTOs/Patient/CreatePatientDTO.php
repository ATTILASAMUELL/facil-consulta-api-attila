<?php

namespace App\DTOs\Patient;

class CreatePatientDTO
{
    public $name;
    public $cpf;
    public $cellphone;

    public function __construct($name, $cpf, $cellphone)
    {
        $this->name = $name;
        $this->cpf = $cpf;
        $this->cellphone = $cellphone;
    }

    public static function fromRequest(array $data)
    {
        return new self(
            $data['name'],
            $data['cpf'],
            $data['cellphone']
        );
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'cpf' => $this->cpf,
            'cellphone' => $this->cellphone,
        ];
    }
}
