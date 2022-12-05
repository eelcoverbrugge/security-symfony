<?php

namespace App\Services;

class MyService
{
    public function __construct(
        MySecondService $mySecondService
    )
    {
        dump('Hello from First Service');
        dump($mySecondService);
    }
}