<?php

namespace App\Services;

use App\Repositories\TelegramUserRepository;
use App\Repositories\TelegramFollowEventRepository;
use Exception;

class TelegramUserService
{
    private TelegramUserRepository $telegramUserRepository;
    private TelegramFollowEventRepository $telegramFollowEventRepository;

    /**
     * @param TelegramUserRepository $repository
     */
    public function __construct(
        TelegramUserRepository $telegramUserRepository,
        TelegramFollowEventRepository $telegramFollowEventRepository
    ) {
        $this->telegramUserRepository = $telegramUserRepository;
        $this->telegramFollowEventRepository = $telegramFollowEventRepository;
    }

    /**
     * @param integer $userId
     *
     * @return array
     */
    public function getFollowingEvent(int $userId): array
    {
        return $this->telegramFollowEventRepository->findAllEventsByUserId($userId);
    }

    /**
     * @param array $input
     *
     * @return boolean
     */
    public function followEvent(array $input): bool
    {
        $data = $this->telegramFollowEventRepository->find($input);

        if (empty($data)) {
            $this->telegramFollowEventRepository->create($input);
            return true;
        }

        return false;
    }

    /**
     * @param array $input
     *
     * @return void
     */
    public function unfollowEvent(array $input): void
    {
        $this->telegramFollowEventRepository->delete($input);
    }

    /**
     * @param string $option
     *
     * @return array
     */
    public function getUserByOption(string $option): array
    {
        return $this->telegramUserRepository->getUserByOption($option);
    }

    /**
     * @param string $userId
     *
     * @return void
     */
    public function subscribe(int $userId, string $option): void
    {
        $this->telegramUserRepository->subscribe($userId, $option);
    }


    /**
     * @param string $userId
     *
     * @return void
     */
    public function unsubscribe(int $userId, string $option): void
    {
        $this->telegramUserRepository->unsubscribe($userId, $option);
    }
}
