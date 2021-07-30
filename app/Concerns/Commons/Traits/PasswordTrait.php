<?php
namespace App\Concerns\Commons\Traits;

trait PasswordTrait
{
    public function make_password(string $password)
    {
        return hash('sha256', sprintf('%s%s', $password, config('app.key')));
    }
}
