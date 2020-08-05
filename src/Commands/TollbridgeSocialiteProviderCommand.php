<?php

namespace Square1\TollbridgeSocialiteProvider\Commands;

use Illuminate\Console\Command;

class TollbridgeSocialiteProviderCommand extends Command
{
    public $signature = 'tollbridge-socialite-provider';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
