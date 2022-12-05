<?php

namespace App\Services;

trait OptionalServiceTrait
{
    private MySecondService $service;

    /**
     * @required
     * @param MySecondService $mySecondService
     * @return void
     */
    public function setSecondService(MySecondService $mySecondService)
    {
        $this->service = $mySecondService;
    }
}