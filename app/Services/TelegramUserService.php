<?php

namespace App\Services;

use App\Repositories\TelegramUserRepository;
use App\Repositories\TelegramFollowEventRepository;
use App\Repositories\EventRepository;
use Illuminate\Database\Eloquent\Collection;

class TelegramUserService
{
    private TelegramUserRepository $telegramUserRepository;
    private TelegramFollowEventRepository $telegramFollowEventRepository;
    private EventRepository $eventRepository;

    /**
     * @param TelegramUserRepository $repository
     */
    public function __construct(
        TelegramUserRepository $telegramUserRepository,
        TelegramFollowEventRepository $telegramFollowEventRepository,
        EventRepository $eventRepository,
    ) {
        $this->telegramUserRepository = $telegramUserRepository;
        $this->telegramFollowEventRepository = $telegramFollowEventRepository;
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param integer $userId
     *
     * @return array
     */
    public function getFollowingEvent(int $userId): array
    {
        return $this->eventRepository->findAllEventsByUserId($userId);
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
