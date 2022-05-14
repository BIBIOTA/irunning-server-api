<?php
namespace App\Repositories;

use App\Models\TelegramUser;

class TelegramUserRepository
{
    private TelegramUser $model;

    /**
     * @param TelegramUser $model
     */
    public function __construct(TelegramUser $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $option
     *
     * @return array
     */
    public function getUserByOption(string $option): array
    {
        return $this->model->where($option, true)->pluck('telegram_id')->toArray();
    }

    /**
     * @param integer $userId
     * @param string $option
     *
     * @return void
     */
    public function subscribe(int $userId, string $option): void
    {
        $this->model->updateOrCreate([
            'telegram_id' => $userId,
        ], [
            $option => true,
        ]);
    }

    /**
     * @param integer $userId
     * @param string $option
     *
     * @return void
     */
    public function unsubscribe(int $userId, string $option): void
    {
        $this->model->where('telegram_id', $userId)->update([
            $option => false,
        ]);
    }
}
