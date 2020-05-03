<?php

namespace App\Specification;

class PasswordSpecification
{
    public function isSatisfiedBy(string $password): bool
    {
        return strlen($password) >= 8;
    }
}