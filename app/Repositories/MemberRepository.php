<?php
namespace App\Repositories;

use App\Models\Member;

class MemberRepository
{
    /**
     * Undocumented variable
     *
     * @var Member
     */
    private Member $model;

    /**
     * @param Member $member
     */
    public function __construct(Member $member)
    {
        $this->model = $member;
    }

    /**
     * @param integer $stravaId
     *
     * @return Member|null
     */
    public function getMember(int $stravaId): ?Member
    {
        return $this->model->where('strava_id', $stravaId)->first();
    }

    /**
     * @param array $athlete
     *
     * @return Member
     */
    public function createMember(array $athlete): Member
    {
        return $this->model->create([
            'id' => uniqid(),
            'strava_id' => $athlete['id'],
            'resource_state' => $athlete['resource_state'],
            'firstname' => $athlete['firstname'],
            'lastname' => $athlete['lastname'],
            'city' => $athlete['city'],
            'state' => $athlete['state'],
            'country' => $athlete['country'],
            'sex' => $athlete['sex'],
            'badge_type_id' => $athlete['badge_type_id'],
            'weight' => $athlete['weight'],
        ]);
    }

    /**
     * @param Member $member
     * @param array $athlete
     *
     * @return Member
     */
    public function updateMember(Member $member, array $athlete): Member
    {
        $member->update([
            'resource_state' => $athlete['resource_state'],
            'firstname' => $athlete['firstname'],
            'lastname' => $athlete['lastname'],
            'city' => $athlete['city'],
            'state' => $athlete['state'],
            'country' => $athlete['country'],
            'sex' => $athlete['sex'],
            'badge_type_id' => $athlete['badge_type_id'],
            'weight' => $athlete['weight'],
        ]);

        return $member;
    }
}
