<?php

namespace App\Services;

use App\Repositories\MemberRepository;
use App\Repositories\MemberTokenRepositroy;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;

class LoginService
{
    /**
     * Undocumented variable
     *
     * @var MemberRepository
     */
    private MemberRepository $memberRepository;

    /**
     * Undocumented variable
     *
     * @var MemberTokenRepositroy
     */
    private MemberTokenRepositroy $memberTokenRepository;

    /**
     * @param MemberRepository $memberRepository
     * @param MemberTokenRepositroy $memberTokenRepository
     */
    public function __construct(MemberRepository $memberRepository, MemberTokenRepositroy $memberTokenRepository)
    {
        $this->memberRepository = $memberRepository;
        $this->memberTokenRepository = $memberTokenRepository;
    }

    /**
     * @param array $input
     *
     * @return array
     */
    public function login(array $input): array
    {
        $athlete = $input['athlete'];

        $member = $this->memberRepository->getMember($athlete['id']);

        if ($member) {
            $member = $this->memberRepository->updateMember($member, $athlete);
        } else {
            $member = $this->memberRepository->createMember($athlete);
        }

        $memberToken = $this->memberTokenRepository->getMemberToken($member->id);

        if ($memberToken) {
            $memberToken = $this->memberTokenRepository->updateToken($memberToken, $input);
        } else {
            $memberToken = $this->memberTokenRepository->createToken($member->id, $input);
        }

        return [
            'jwtToken' => $this->getJwtToken($member, $input['expires_in']),
            'member' => $member,
            'memberToken' => $memberToken,
        ];
    }

    /**
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::guard()->logout();
    }

    /**
     * @param Member $member
     * @param integer $expiresIn
     *
     * @return array|null
     */
    public function getJwtToken(Member $member, int $expiresIn): ?array
    {
        if ($token = Auth::guard()->fromUser($member)) {
            return $this->respondWithToken($token, $expiresIn);
        }
        return null;
    }

    /**
     * @param string $token
     * @param integer $expiresIn
     *
     * @return array
     */
    protected function respondWithToken(string $token, int $expiresIn): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard()->factory()->getTTL() * floor(($expiresIn / 60))
        ];
    }
}
