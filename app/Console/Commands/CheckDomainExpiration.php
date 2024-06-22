<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Domain; // Assurez-vous d'importer votre modèle de domaine

class CheckDomainExpiration extends Command
{
    protected $signature = 'domain:check-expiration';
    protected $description = 'Vérifie l\'expiration des domaines et envoie un mail si nécessaire.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Récupérer tous les domaines
        $domains = Domain::all();

        foreach ($domains as $domain) {
            $expirationDate = Carbon::createFromFormat('d/m/Y', $domain->expiration_date);

            // Vérifier si le domaine expire dans les 7 jours
            if ($expirationDate->diffInDays(Carbon::now()) <= 7) {
                // Envoyer un mail à l'administrateur ou au propriétaire du domaine
                // Utilisez la fonction d'envoi de mail de Laravel
                // Par exemple, pour envoyer un mail à l'administrateur :
                \Mail::raw("Le domaine {$domain->name} expire le {$domain->expiration_date}.", function ($message) {
                    $message->to('admin@example.com');
                    $message->subject('Alerte d\'expiration de domaine');
                });
            }
        }

        $this->info('Vérification d\'expiration de domaine terminée.');
    }
}
