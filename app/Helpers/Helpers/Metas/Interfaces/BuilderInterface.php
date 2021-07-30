<?php
namespace App\Helpers\Metas\Interfaces;

interface BuilderInterface
{

    public function set($Key, $Value, $Arguments = null);

    public function get($Key);

    public function all();

    public function html();

}
