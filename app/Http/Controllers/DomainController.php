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
use Iodev\Whois\Factory;
use Illuminate\Http\Request\Facades;
use Illuminate\Support\Facades\Http;


class DomainController extends Controller
{
    protected $domains;
    protected $emails;

    public function __construct()
    {
        $this->domains = explode(';', env('DOMAINS_LIST'));
        $this->emails = explode(',', env('ALERT_EMAILS'));
    }


    /* public function runPythonScript($domainName)
    {
        $scriptPath = base_path('scripts/scrape.py');
        $command = escapeshellcmd("python3 $scriptPath " . escapeshellarg($domainName));
        $output = shell_exec($command);

        // Décoder la sortie JSON du script Python
        $data = json_decode($output, true);

        // Vérifiez si l'exécution du script a été correcte
        if ($data === null) {
            return response()->json(['error' => 'Failed to execute script or invalid JSON output'], 500);
        }

        return response()->json(['data' => $data]);
    }

    public function showResults(Request $request)
    {
        $domainName = $request->input('domain_name');
        $response = $this->runPythonScript($domainName);
        $data = $response->original['data'] ?? null;

        // Vérifiez si les données sont valides
        if ($data === null) {
            return view('scraping-results', ['error' => 'No data available or failed to scrape']);
        }

        // Passer les données à la vue
        return view('scraping-results', ['data' => $data]);
    }
        */

    public function showDomains()
    {
        return view('domains', ['domains' => $this->domains]);
    }

    public function index()
    {
        return view('index');
    }

    // Scrape les informations d'un domaine en utilisant Selenium WebDriver avec Firefox
   /*
    public function getDomainInfo($domainName)
    {
        $host = 'http://localhost:4444/wd/hub'; // URL du serveur Selenium
        $driver = RemoteWebDriver::create($host, DesiredCapabilities::edgedriver());

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
        */



    // Met à jour les informations d'un domaine et les enregistre dans la base de données
   /* public function updateDomainInfo($domainName)
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


    protected function sendExpirationAlert($domain)
{
    foreach ($this->emails as $email) {
        Mail::to($email)->send(new DomainExpired($domain));
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
        */


    public function getDomainInfo(Request $request)
{
    $domain = $request->input('domain');

    // Vérifier si $domain est null ou une chaîne vide
    if (!$domain) {
        return view('whois')->withErrors(['error' => 'Domain name is required']);
    }

    $whois = Factory::get()->createWhois();


try {
    $whois = Factory::get()->createWhois();
    $response = $whois->lookupDomain($domain);

    if ($response === null) {
        throw new Exception('No WHOIS information available for this domain.');
    }

    // Obtenir les données brutes WHOIS sous forme de chaîne
    $rawData = $response->getText();

    // Fonction pour parser les données WHOIS
    function parseWhoisData($rawData) {
        $result = [];
        $lines = explode("\n", $rawData);

        foreach ($lines as $line) {
            if (strpos($line, ":") !== false) {
                list($key, $value) = explode(":", $line, 2);
                $result[trim($key)] = trim($value);
            }
        }

        return $result;
    }

    $domainInfo = parseWhoisData($rawData);

    return view('whois', ['domainInfo' => $domainInfo]);
} catch (Exception $e) {
    return view('whois', ['error' => $e->getMessage()]);
}
}


public function scraper()
    {
        // Scraper le montant des frais mensuels sur la page Tarification
        $tarificationUrl = 'https://ishowo.net/tarification';
            $tarificationResponse = Http::get($tarificationUrl);
            $tarificationHtml = $tarificationResponse->body();

            libxml_use_internal_errors(true); // Supprimer les erreurs de chargement
            $dom = new \DOMDocument();
            @$dom->loadHTML($tarificationHtml);
            $xpath = new \DOMXPath($dom);

            $montant = '';
            $elements = $xpath->query("//span[contains(@class, 'et_pb_sum')]"); // Sélecteur pour la classe 'et_pb_sum'
            foreach ($elements as $element) {
                $montant = trim($element->textContent);
            }

            if (empty($montant)) {
                $montant = 'Montant non trouvé';
            }

        // Scraper le bouton Découvrir sur la page Fonctionnalités
        $fonctionnalitesUrl = 'https://ishowo.net/fonctionnalites';
        $fonctionnalitesResponse = Http::get($fonctionnalitesUrl);
        $fonctionnalitesHtml = $fonctionnalitesResponse->body();

        @$dom->loadHTML($fonctionnalitesHtml);
        $xpath = new \DOMXPath($dom);

        $decouvrir = '';
        $elements = $xpath->query("//a[contains(@class, 'et_pb_button_2')]");
        foreach ($elements as $element) {
            $decouvrir = $element->getAttribute('href');
        }

        if (empty($decouvrir)) {
            $decouvrir = 'Bouton Découvrir non trouvé';
        }

        // Comparer le montant et l'URL du bouton
        $montantAttendu = 70000;
        $urlAttendue = 'https://ishowo.net/tester/';

        if ($montant != $montantAttendu || $decouvrir != $urlAttendue) {
            // Envoyer un email de signalisation
            $to = 'iwaju.office@gmail.com';
            $subject = 'Alerte de Scraping';
            $message = "Le montant ne correspond pas aux valeurs attendues.\n\nMontant: $montant\nURL du bouton: $decouvrir";

            Mail::raw($message, function ($msg) use ($to, $subject) {
                $msg->to($to)->subject($subject);
            });
        }

        return view('scraper', compact('montant', 'decouvrir'));
    }
}

