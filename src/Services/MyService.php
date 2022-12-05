<?php

namespace App\Services;

class MyService
{
    public function __construct(
        string $param,
        string $adminEmail,
        string $globalParam
    )
    {
        dump($param);
        dump($adminEmail);
        dump($globalParam);
    }
}