<?php

namespace App\Services;

use App\Repositories\EventRepository;

class EventService
{
    private $repository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->repository = $eventRepository;
    }

    public function getIndexEvents(): array
    {
        return $this->repository->getIndexEvents();
    }
}
