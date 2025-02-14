<?php

namespace Senasgr\KodExplorer\Facades;

use Illuminate\Support\Facades\Facade;

class KodExplorer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'kodexplorer';
    }
}
