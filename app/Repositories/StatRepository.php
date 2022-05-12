<?php
namespace App\Repositories;

use App\Models\Stat;

class StatRepository
{
    private Stat $model;

    public function __construct(Stat $stat)
    {
        $this->model = $stat;
    }

    /**
     * @param integer $memberId
     *
     * @return Stat|null
     */
    public function getStat(string $memberId): ?Stat
    {
        return $this->model->where('member_id', $memberId)->first();
    }

    /**
     * @param array $formData
     *
     * @return void
     */
    public function createStat(array $formData)
    {
        $this->model->create($formData);
    }

    /**
     * @param string $memberId
     * @param array $formData
     *
     * @return void
     */
    public function updateStat(string $memberId, array $formData)
    {
        $this->model->where('member_id', $memberId)->update($formData);
    }
}
