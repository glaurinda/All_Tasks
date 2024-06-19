<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Domain;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Http\Controllers\DomainController;

class CheckDomainExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:domain-expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check domain expiration dates and send alerts if necessary';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $domainController = new DomainController();
        $domains = explode(';', env('DOMAINS_LIST'));

        foreach ($domains as $domain) {
            $info = $domainController->getDomainInfo($domain);

            if ($info && Carbon::parse($info['expiration_date'])->isPast()) {
                foreach ($domainController->emails as $email) {
                    Mail::raw("The domain {$domain} has expired. Expiration date: {$info['expiration_date']}", function ($message) use ($email) {
                        $message->to($email)->subject('Domain Expiration Alert');
                    });
                }
            }
        }

        return Command::SUCCESS;
    }
}
