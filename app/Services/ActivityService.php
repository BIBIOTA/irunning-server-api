<?php

namespace App\Services;

use App\Repositories\ActivityRepository;
use App\Repositories\MemberTokenRepositroy;
use App\Repositories\StatRepository;
use App\Http\Controllers\Traits\MemberTrait;
use App\Http\Controllers\Traits\Running;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class ActivityService
{
    use MemberTrait;
    use Running;

    private ActivityRepository $activityRepository;

    private MemberTokenRepositroy $memberTokenRepositroy;

    private StatRepository $statRepository;

    /**
     * @param ActivityRepository $activityRepository
     * @param MemberTokenRepositroy $memberTokenRepositroy
     * @param StatRepository $statRepository
     */
    public function __construct(
        ActivityRepository $activityRepository,
        MemberTokenRepositroy $memberTokenRepositroy,
        StatRepository $statRepository,
    ) {
        $this->activityRepository = $activityRepository;
        $this->memberTokenRepositroy = $memberTokenRepositroy;
        $this->statRepository = $statRepository;
    }

    /**
     * @param array $filters
     *
     * @return LengthAwarePaginator
     */
    public function getActivities(array $filters): LengthAwarePaginator
    {
        $member = $this->me();

        $filters['id'] = $member->id;

        $hasActivities = $this->activityRepository->checkHasMemberActivities($member->id);

        if (!$hasActivities) {
            $tokenData = $this->memberTokenRepositroy->getMemberToken($member->id);

            $this->getActivitiesDataFromStrava($tokenData);
        }

        $results = $this->activityRepository->getFilterData($filters);

        $results->getCollection()->transform(function ($row) {
            return [
                'id' => $row->id,
                'name' => $row->name,
                'pace' => $this->getPace($row->distance, $row->moving_time),
                'distance' => $this->getDistanceIsFloor($row->distance),
                'moving_time' => $row->moving_time,
                'start_date_local' => $row->start_date_local,
                'summary_polyline' => $row->summary_polyline,
            ];
        });

        return $results;
    }

    /**
     * @param string $memberId
     * @param string $stravaActivityId
     *
     * @return array
     */
    public function getActivityFromStrava(string $memberId, string $stravaActivityId): array
    {
        $token = $this->memberTokenRepositroy->getMemberToken($memberId);

        $response = Http::withToken($token->access_token)
                    ->get('https://www.strava.com/api/v3/activities/' . $stravaActivityId);

        if ($response->status() === 200) {
            $data = $response->json();
            $time_raw = strtotime($data['start_date_local']);
            $time_mysql = gmdate('Y-m-d H:i:s', $time_raw);
            $data['start_date_local'] = $time_mysql;
            $data['pace'] = $this->getPace($data['distance'], $data['moving_time']);
            $data['distance'] = $this->getDistanceIsFloor($data['distance']);
            return $data;
        } else {
            throw new Exception('getActivityFromStrava error');
        }
    }

    /**
     * @param string $stravaId
     * @param object $tokenData
     *
     * @return void
     */
    public function getStats(string $stravaId, object $tokenData): void
    {
        $response = Http::withToken($tokenData->access_token)
                    ->get('https://www.strava.com/api/v3/athletes/' . $stravaId . '/stats');

        $resdatas = $response->json();

        if (isset($resdatas['message']) && $resdatas['message'] === 'Forbidden') {
            Log::stack(['activities', 'slack'])->error('取得Strava資料發生錯誤');
        } else {
            $allRunTotals = $resdatas['all_run_totals'];

            $formData = [
                'member_id' => $tokenData->member_id,
            ];

            foreach ($allRunTotals as $key => $value) {
                $formData[$key] = $value;
            }

            $data = $this->statRepository->getStat($tokenData->member_id);

            if ($data) {
                $this->statRepository->updateStat($tokenData->member_id, $formData);
            } else {
                $formData['id'] = uniqid();
                $this->statRepository->createStat($formData);
            }
        }
    }

    /**
     * @param object $tokenData
     * @param boolean $onlyOnePage
     *
     * @return void
     */
    public function getActivitiesDataFromStrava(object $tokenData, bool $onlyOnePage = false)
    {
        $columns = DB::getSchemaBuilder()->getColumnListing('activities');

        $page = 1;
        $hasData = true;
        while ($hasData) {
            $response = Http::withToken($tokenData->access_token)
                        ->get('https://www.strava.com/api/v3/athlete/activities?after=0&per_page=200&page=' . $page);

            $resdatas = $response->json();

            if (count($resdatas) > 0) {
                foreach ($resdatas as $data) {
                    $formData = [
                        'member_id' => $tokenData->member_id,
                    ];
                    if (is_array($data)) {
                        foreach ($data as $key => $value) {
                            if (in_array($key, $columns)) {
                                if ($key === 'start_date_local') {
                                    $time_raw = strtotime($value);
                                    $time_mysql = Carbon::parse($time_raw);
                                    $formData[$key] = $time_mysql;
                                } else {
                                    $formData[$key] = $value;
                                }
                            }
                            if ($key === 'map') {
                                $formData['summary_polyline'] = $value['summary_polyline'];
                            }
                        }
                        if ($data['type'] === 'Run') {
                            $this->activityRepository->updateOrCreateActivities($formData);
                        }
                    }
                }
            } else {
                $hasData = false;
            }
            if (!$onlyOnePage) {
                $page++;
            } else {
                break;
            }
        }
        Log::channel('activities')->info($tokenData->member_id . 'Strava活動更新完成');
    }
}
