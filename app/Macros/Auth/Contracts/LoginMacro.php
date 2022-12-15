<?php
namespace App\Macros\Auth\Contracts;

interface LoginMacro
{
    /**
     * @return mixed
     * @Author  : daniel
     * @DateTime: 2020-05-11 16:06
     */
    public function login();

    /**
     * @param string $account
     *
     * @return \App\Macros\Auth\Logins\Contracts\LoginMacro
     * @Author  : daniel
     * @DateTime: 2020-05-11 16:06
     */
    public function setAccount(string $account): self;

    /**
     * @param string $password
     *
     * @return \App\Macros\Auth\Logins\Contracts\LoginMacro
     * @Author  : daniel
     * @DateTime: 2020-05-11 16:06
     */
    public function setPassword(string $password): self;

    /**
     * @param bool $remember_me
     *
     * @return \App\Macros\Auth\Logins\Contracts\LoginMacro
     * @Author  : daniel
     * @DateTime: 2020-05-11 16:06
     */
    public function setRememberMe(bool $remember_me): self;

    /**
     * @param array $Params
     *
     * @return \App\Macros\Auth\Logins\Contracts\LoginMacro
     * @Author  : daniel
     * @DateTime: 2020-05-11 16:06
     */
    public function setParams(array $Params): self;
}
