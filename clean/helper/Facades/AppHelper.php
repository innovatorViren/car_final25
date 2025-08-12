<?php
namespace Clean\Helper\Facades;

use Illuminate\Support\Facades\Facade;

class AppHelper extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Clean\Helper\AppHelper';
    }
}
