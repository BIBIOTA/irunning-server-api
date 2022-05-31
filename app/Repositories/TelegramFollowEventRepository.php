<?php
namespace App\Repositories;

use App\Models\TelegramFollowEvent;

class TelegramFollowEventRepository
{
    private TelegramFollowEvent $model;

    public function __construct(TelegramFollowEvent $model)
    {
        $this->model = $model;
    }

    /**
     * @param integer $telegramId
     *
     * @return TelegramFollowEvent
     */
    public function find(array $input): ?TelegramFollowEvent
    {
        $query = $this->model->newModelQuery();
        if (isset($input['userId'])) {
            $query->where('telegram_id', $input['userId']);
        }
        if (isset($input['eventId'])) {
            $query->where('event_id', $input['eventId']);
        }
        return $query->first();
    }

    /**
     * @param array $input
     *
     * @return void
     */
    public function create(array $input)
    {
        $id = uniqid();
        $this->model->create([
            'id' => $id,
            'telegram_id' => $input['userId'],
            'event_id' => $input['eventId'],
        ]);
    }

    /**
     * @param TelegramFollowEvent $model
     * @param array $input
     *
     * @return void
     */
    public function update(TelegramFollowEvent $model, array $input)
    {
        $model->update([
            'telegram_id' => $input['userId'],
            'event_id' => $input['eventId'],
        ]);
    }

    /**
     * @param array $input
     *
     * @return void
     */
    public function delete(array $input): void
    {
        $this->model->where('telegram_id', $input['userId'])->where('event_id', $input['eventId'])->delete();
    }
}
