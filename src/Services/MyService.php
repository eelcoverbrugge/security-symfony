<?php

namespace App\Services;

class MyService
{
    /**
     * Use Trait instead of MySecondService in the __construct
     */
    use OptionalServiceTrait;

    public $my;

    public $logger;

    public function __construct(
//        MySecondService $mySecondService
    )
    {
//        dump('Hello from First Service');
//        dump($mySecondService);
    }

    public function someAction()
    {
        dump($this->service->doSomething());
    }

    public function someParams()
    {
        dump($this->my);
        dump($this->logger);
    }
}