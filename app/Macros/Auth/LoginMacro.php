<?php
namespace App\Macros\Auth;

use App\Macros\Auth\Contracts\LoginAdapter;
use App\Macros\Auth\Contracts\LoginMacro as LoginMacroContracts;

/**
 * Class LoginMacro
 *
 * @package App\Macros\Auth\Logins
 * @Author  : daniel
 * @DateTime: 2020-05-12 10:27
 */
class LoginMacro implements LoginMacroContracts
{
    /**
     * @var \App\Macros\Auth\Contracts\LoginAdapter|\App\Macros\Auth\Logins\Contracts\LoginAdapter
     */
    private $Adapter;

    /**
     * @param  \App\Macros\Auth\Contracts\LoginAdapter  $Adapter
     */
    public function __construct(LoginAdapter $Adapter)
    {
        $this->Adapter = $Adapter;
    }

    /**
     * @param  bool  $remember_me
     *
     * @return \App\Macros\Auth\Contracts\LoginMacro
     * @Author: Roy
     * @DateTime: 2022/12/13 下午 10:40
     */
    public function setRememberMe(bool $remember_me) : LoginMacroContracts
    {
        $this->Adapter->setRememberMe($remember_me);
        return $this;
    }

    /**
     * @param  string  $account
     *
     * @return \App\Macros\Auth\Contracts\LoginMacro
     * @Author: Roy
     * @DateTime: 2022/12/13 下午 10:40
     */
    public function setAccount(string $account): LoginMacroContracts
    {
        $this->Adapter->setAccount($account);
        return $this;
    }

    /**
     * @param  string  $password
     *
     * @return \App\Macros\Auth\Contracts\LoginMacro
     * @Author: Roy
     * @DateTime: 2022/12/13 下午 10:40
     */
    public function setPassword(string $password): LoginMacroContracts
    {
        $this->Adapter->setPassword($password);
        return $this;
    }

    /**
     * @param  array  $Params
     *
     * @return \App\Macros\Auth\Contracts\LoginMacro
     * @Author: Roy
     * @DateTime: 2022/12/13 下午 10:40
     */
    public function setParams(array $Params): LoginMacroContracts
    {
        $this->Adapter->setParams($Params);
        return $this;
    }

    /**
     * @return mixed
     * @Author: Roy
     * @DateTime: 2022/12/13 下午 10:40
     */
    public function login()
    {
        return
            $this->Adapter
                ->login()
                ->getResult()
            ;
    }
}
