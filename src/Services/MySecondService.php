<?php

namespace App\Services;

class MySecondService
{
    public function __construct(
        string $param,
        string $adminEmail,
        string $globalParam
    )
    {
        dump('Hello from Second Service');
        dump($param);
        dump($adminEmail);
        dump($globalParam);
    }
}