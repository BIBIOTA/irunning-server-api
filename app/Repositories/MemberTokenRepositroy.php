<?php
namespace App\Repositories;

use App\Models\MemberToken;
use Carbon\Carbon;

class MemberTokenRepositroy
{

    /**
     * Undocumented variable
     *
     * @var MemberToken
     */
    private MemberToken $model;

    /**
     * @param MemberToken $memberToken
     */
    public function __construct(MemberToken $memberToken)
    {
        $this->model = $memberToken;
    }

    /**
     * @param string $memberId
     *
     * @return MemberToken|null
     */
    public function getMemberToken(string $memberId): ?MemberToken
    {
        return $this->model->where('member_id', $memberId)->first();
    }

    /**
     * @param string $memberId
     * @param array $input
     *
     * @return MemberToken
     */
    public function createToken(string $memberId, array $input): MemberToken
    {
        return $this
                ->model
                ->create([
                    'id' => uniqid(),
                    'member_id' => $memberId,
                    'expires_at' => Carbon::parse(intval($input['expires_at']))
                                    ->setTimezone('Asia/Taipei')
                                    ->format('Y-m-d H:i:s'),
                    'expires_in' => intval(gmdate('H', $input['expires_in'])),
                    'refresh_token' => $input['refresh_token'],
                    'access_token' => $input['access_token'],
                ]);
    }

    /**
     * @param MemberToken $memberToken
     * @param array $input
     *
     * @return MemberToken
     */
    public function updateToken(MemberToken $memberToken, array $input): MemberToken
    {
        $memberToken->update([
            'expires_at' => Carbon::parse(intval($input['expires_at']))
                            ->setTimezone('Asia/Taipei')
                            ->format('Y-m-d H:i:s'),
            'expires_in' => intval(gmdate('H', $input['expires_in'])),
            'refresh_token' => $input['refresh_token'],
            'access_token' => $input['access_token'],
        ]);

        return $memberToken;
    }
}
