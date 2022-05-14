<?php

namespace App\Services;

use App\Repositories\TelegramUserRepository;

class TelegramUserService
{
    private TelegramUserRepository $repository;

    /**
     * @param TelegramUserRepository $repository
     */
    public function __construct(TelegramUserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $option
     *
     * @return array
     */
    public function getUserByOption(string $option): array
    {
        return $this->repository->getUserByOption($option);
    }

    /**
     * @param string $userId
     *
     * @return void
     */
    public function subscribe(int $userId, string $option): void
    {
        $this->repository->subscribe($userId, $option);
    }


    /**
     * @param string $userId
     *
     * @return void
     */
    public function unsubscribe(int $userId, string $option): void
    {
        $this->repository->unsubscribe($userId, $option);
    }
}
