<?php

namespace Modules\Users\Services;

interface AuthServiceInterface
{
    public function login(array $credentials): array;
    public function logout(): void;
    public function me(): array;
}
