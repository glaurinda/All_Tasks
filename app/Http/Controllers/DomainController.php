<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\Domain;
use App\Mail\DomainExpired;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class DomainController extends Controller
{
    protected $domains;
    protected $emails;

    public function __construct()
    {
        $this->domains = explode(';', env('DOMAINS_LIST'));
        $this->emails = explode(',', env('ALERT_EMAILS'));
    }

    public function showDomains()
    {
        return view('domains', ['domains' => $this->domains]);
    }

    public function index()
    {
        return view('index');
    }

    // Scrape les informations d'un domaine en utilisant Selenium WebDriver avec Firefox
    public function getDomainInfo($domainName)
    {
        $host = 'http://localhost:4444/wd/hub'; // URL du serveur Selenium
        $driver = RemoteWebDriver::create($host, DesiredCapabilities::firefox());

        $driver->get("https://www.domaine.com/$domainName");

        // Attendez que les éléments soient présents
        $driver->wait()->until(
            WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
                WebDriverBy::cssSelector('.creation-date, .updated-date, .expiry-date')
            )
        );

        $info = [
            'created_date' => $driver->findElement(WebDriverBy::cssSelector('.creation-date'))->getText(),
            'updated_date' => $driver->findElement(WebDriverBy::cssSelector('.updated-date'))->getText(),
            'expires_date' => $driver->findElement(WebDriverBy::cssSelector('.expiry-date'))->getText(),
        ];

        $driver->quit();

        return $info;
    }

    // Met à jour les informations d'un domaine et les enregistre dans la base de données
    public function updateDomainInfo($domainName)
    {
        $info = $this->getDomainInfo($domainName);

        $domain = Domain::updateOrCreate(
            ['name' => $domainName],
            [
                'created_date' => $info['created_date'],
                'updated_date' => $info['updated_date'],
                'expires_date' => $info['expires_date']
            ]
        );

        return $domain;
    }

    // Vérifie tous les domaines enregistrés et envoie un email si un domaine est expiré
    public function domaincheck2()
    {
        $domains = Domain::all();
        foreach ($domains as $domain) {
            $info = $this->getDomainInfo($domain->name);

            $domain->update([
                'created_date' => $info['created_date'],
                'updated_date' => $info['updated_date'],
                'expires_date' => $info['expires_date']
            ]);

            if (strtotime($info['expires_date']) < time()) {
                Mail::to(config('mail.alert_email'))->send(new DomainExpired($domain));
            }
        }

        return response()->json(['message' => 'Domain check completed.']);
    }

    // DomainController.php

public function checkDomain(Request $request)
{
    $domainName = $request->input('domain');

    // Vérifier les informations du domaine en utilisant la méthode getDomainInfo
    $info = $this->getDomainInfo($domainName);

    if (!$info) {
        return redirect()->back()->with('error', 'Domain not found');
    }

    // Enregistrer ou mettre à jour les informations du domaine dans la base de données
    $domain = Domain::updateOrCreate(
        ['name' => $domainName],
        [
            'created_date' => Carbon::parse($info['created_date']),
            'updated_date' => Carbon::parse($info['updated_date']),
            'expires_date' => Carbon::parse($info['expires_date'])
        ]
    );

    // Rediriger avec les informations du domaine
    return view('domain_info', ['domain' => $domain, 'info' => $info]);
}


    protected function sendExpirationAlert($domain, $info)
    {
        foreach ($this->emails as $email) {
            Mail::raw("The domain {$domain} has expired. Expiration date: {$info['expires_date']}", function ($message) use ($email) {
                $message->to($email)->subject('Domain Expiration Alert');
            });
        }
    }

    public function checkExpiration()
    {
        $domains = $this->domains;

        foreach ($domains as $domain) {
            $info = $this->getDomainInfo($domain);

            if (Carbon::parse($info['expires_date'])->isPast()) {
                $this->sendExpirationAlert($domain, $info);
            }
        }

        return response()->json(['message' => 'Expiration check completed.']);
    }
}

