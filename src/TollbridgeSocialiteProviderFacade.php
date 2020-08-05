<?php

namespace Square1\TollbridgeSocialiteProvider;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Square1-io\TollbridgeSocialiteProvider\TollbridgeSocialiteProvider
 */
class TollbridgeSocialiteProviderFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'tollbridge-socialite-provider';
    }
}
