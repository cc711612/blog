<?php

namespace App\Macros\Auth\Contracts;


use App\Macros\Auth\Abstracts\LoginAbstract;

interface LoginAdapter
{

    public function login();

    public function setAccount(string $account): void;

    public function getAccount(): string;

    public function setPassword(string $password): void;

    public function getPassword(): string;

    public function setRememberMe(bool $remember_me): void;

    public function getRememberMe(): bool;

    public function setParams(array $Params): void;

    public function getParams(): array;

    public function handleLoginSessionData(): array;

    public function createLoginLog();

    public function guard();

    public function updateProfile();
}
