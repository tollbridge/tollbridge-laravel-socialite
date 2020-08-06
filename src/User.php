<?php

namespace Square1\TollbridgeSocialiteProvider;

use Laravel\Socialite\Two\User as TwoUser;

class User extends TwoUser
{
    /**
     * Active plan
     *
     * @var string
     */
    public $plan;

    public function getPlan()
    {
        return $this->plan;
    }
}
