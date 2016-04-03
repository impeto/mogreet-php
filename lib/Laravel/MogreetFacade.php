<?php

namespace Mogreet\Laravel;

use Illuminate\Support\Facades\Facade;

class MogreetFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'mogreet';
    }

}