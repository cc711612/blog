<?php

namespace App\Helpers\Trail\Interfaces;

interface TrailInterface
{

    const ASC  = 'ASC';
    const DESC = 'DESC';

    public function setDefaultRoot(array $Argument);
    public function getDefaultRoot();

    public function append(array $PathMapBag);
    public function prepend(array $PathMapBag);

    public function all();

    public function offset($Num);

    public function asc();
    public function desc();

    public function reset();

}
