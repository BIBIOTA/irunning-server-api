<?php

namespace App\Services;

use App\Repositories\ActivityRepository;
use App\Repositories\MemberTokenRepositroy;
use App\Http\Controllers\Traits\StravaActivitiesTrait;
use App\Http\Controllers\Traits\MemberTrait;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityService
{
    use StravaActivitiesTrait;
    use MemberTrait;

    private ActivityRepository $activityRepository;

    private MemberTokenRepositroy $memberTokenRepositroy;

    public function __construct(ActivityRepository $activityRepository, MemberTokenRepositroy $memberTokenRepositroy)
    {
        $this->activityRepository = $activityRepository;
        $this->memberTokenRepositroy = $memberTokenRepositroy;
    }

    public function getActivities(array $filters): LengthAwarePaginator
    {
        $member = $this->me();

        $filters['id'] = $member->id;

        $hasActivities = $this->activityRepository->checkHasMemberActivities($member->id);

        if (!$hasActivities) {
            $tokenData = $this->memberTokenRepositroy->getMemberToken($member->id);

            $this->getActivitiesDataFromStrava($tokenData);
        }

        return $this->activityRepository->getFilterData($filters);
    }
}
