<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\DomainController;

class CheckDomains extends Command
{
    protected $signature = 'domains:check';
    protected $description = 'Check the status of all domains and send expiration alerts if needed';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $controller = new DomainController();
        foreach ($controller->domains as $domain) {
            $controller->checkDomain($domain);
        }

        return 0;
    }
}
