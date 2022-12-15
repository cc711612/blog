<?php
namespace App\Macros\Auth\Abstracts;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

abstract class LoginAbstract
{
    /**
     * @var string
     */
    protected $account = '';
    /**
     * @var string
     */
    protected $password = '';
    /**
     * @var
     */
    protected $Auth;
    /**
     * @var string
     */
    protected $redirect_to = '/';
    /**
     * @var
     */
    protected $login_return_type;
    //目前使用的 account_type_id
    /**
     * @var int
     */
    protected $account_type_id = 0;
    //記住我
    /**
     * @var
     */
    protected $remember_me;
    // $Entity [暫存取到的 Entity]
    /**
     * @var null
     */
    protected $Entity = null;
    /**
     * @var array
     */
    protected $params = [];
    /**
     * @var string
     */
    protected $guard_string = '';


    public function setAccount(string $account): void
    {
        $this->account = $account;
    }

    public function getAccount(): string
    {
        // TODO: Implement getAccount() method.
        return $this->account;
    }

    public function setPassword(string $password): void
    {
        // TODO: Implement setPassword() method.
        $this->password = $password;
    }

    public function getPassword(): string
    {
        // TODO: Implement getPassword() method.
        return $this->password;
    }

    public function setRememberMe(bool $remember_me): void
    {
        // TODO: Implement setRememberMe() method.
        $this->remember_me = $remember_me;
    }

    public function getRememberMe(): bool
    {
        // TODO: Implement getRememberMe() method.
        return $this->remember_me;
    }

    public function setParams(array $params): void
    {
        // TODO: Implement setParams() method.
        $this->params = $params;
    }

    public function getParams(): array
    {
        // TODO: Implement getParams() method.
        return $this->params;
    }

    public function getParamsByKey(string $key)
    {
        // TODO: Implement getParams() method.
        return Arr::get($this->params, $key);
    }

    public function guard()
    {
        if (empty($this->guard_string)) {
            throwException('請設定 guard');
        }

        return Auth::guard($this->guard_string);
    }

    public function getResult()
    {
        return is_null($this->getEntity()) == false;
    }

    public function getEntity()
    {
        return $this->Entity;
    }

    abstract public function getAccountEntity();

    abstract public function failReturn();

    abstract public function createLoginLog();
}
