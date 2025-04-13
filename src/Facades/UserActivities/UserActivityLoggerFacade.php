<?php

namespace QuickerFaster\CodeGen\Facades\UserActivities;

use Illuminate\Support\Facades\Facade;

class UserActivityLoggerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'user-activity-logger';
    }
}
