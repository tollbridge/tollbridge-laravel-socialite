<?php

namespace Tollbridge\Socialite\Events;

use Exception;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuthenticationFailure
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $exception;

    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }
}
