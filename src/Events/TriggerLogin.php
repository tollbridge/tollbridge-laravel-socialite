<?php

namespace Tollbridge\Socialite\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TriggerLogin
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct()
    {
    }
}
