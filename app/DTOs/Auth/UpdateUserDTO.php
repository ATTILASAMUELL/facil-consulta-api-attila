<?php

namespace App\DTOs\Auth;

class UpdateUserDTO
{
    public ?string $name;
    public ?string $email;
    public ?string $password;

    public function __construct(
        ?string $name = null,
        ?string $email = null,
        ?string $password = null
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            $data['name'] ?? null,
            $data['email'] ?? null,
            $data['password'] ?? null
        );
    }
}
