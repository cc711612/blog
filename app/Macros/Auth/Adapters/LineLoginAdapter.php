<?php
/*
 *
 *
 * 還沒做
 *
 */

namespace App\Macros\Auth\Adapters;

use App\Macros\Auth\Abstracts\LoginAbstract;
use App\Macros\Auth\Contracts\LoginAdapter as LoginAdapterContracts;
use App\Models\Supports\SocialType;
use App\Models\Services\UserService;
use App\Models\Services\SocialService;
use App\Models\UserEntity;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Entities\SocialEntity;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class LineLoginAdapter extends LoginAbstract implements LoginAdapterContracts
{
    protected $guard_string = 'web';

    protected $account_type_id = SocialType::Line;

    public function login()
    {
        $this
            # 取得帳號資料
            ->getAccountEntity()
            ->auth();

        return $this;
    }

    public function auth(): void
    {
        if (is_null($this->getEntity()) === false) {
            Auth::login($this->getEntity());
        }
    }

    public function handleLoginSessionData(): array
    {
        $Result = [];

        return $Result;
    }

    /**
     * @return $this
     * @Author  : daniel
     * @DateTime: 2022/7/19 上午11:52
     */
    public function getAccountEntity()
    {
        if (is_null($this->getEntity()) === false) {
            return $this->getEntity();
        }

        $MemberMainService = new UserService();
        $SocialService = new SocialService();
        ## 先判斷第三方 id 有沒有註冊過
        $Social = $SocialService
            ->setRequest($this->getParams())
            ->findLineEmail();

        if (is_null($this->getParamsByKey('email'))) {
            return $this;
        }

        $social_create = is_null($Social);
        # 判斷有沒有非有第三綁定且email相同的帳號
        if ($social_create) {
            $Social = $SocialService
                ->setRequest($this->getParams())
                ->create();
        }

        $MemberEntity = $Social->users->first();

        if (is_null($MemberEntity)) {
            # 新增會員
            $MemberEntity = $MemberMainService
                ->setRequest([
                    UserEntity::Table => [
                        'name'              => $this->getParamsByKey('name'),
                        'email'             => $this->getParamsByKey('email'),
                        'password'          => Hash::make(Str::random(10)),
                        'images'            => [
                            'cover' => $this->getParamsByKey('image'),
                        ],
                        'email_verified_at' => Carbon::now()->toDateTimeString(),
                    ],
                ])
                ->create();
            $Social->users()->sync(['user_id' => $MemberEntity->id]);
        }

        $this->Entity = $MemberEntity;

        if ($social_create === false) {
            $Social->update($this->getParamsByKey(SocialEntity::Table));
        }

        return $this;
    }

    public function failReturn()
    {
        $this->Entity = null;

        return [
            'status'  => false,
            'message' => '登入失敗, 沒有權限',
        ];
    }

    public function createLoginLog()
    {
        if (is_null($this->getEntity())) {
            return $this;
        }

        return $this;
    }

    public function updateProfile()
    {
        if (is_null($this->getEntity())) {
            return $this;
        }

        return $this;
    }
}
