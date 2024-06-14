import requests
from bs4 import BeautifulSoup
import json

def get_domain_info(domain):
    url = f"https://whois.example.com/{domain}"
    response = requests.get(url)
    soup = BeautifulSoup(response.text, 'html.parser')

    # Extrait les informations du domaine
    creation_date = soup.find('div', {'id': 'creation_date'}).text
    expiration_date = soup.find('div', {'id': 'expiration_date'}).text
    updated_date = soup.find('div', {'id': 'updated_date'}).text

    return {
        'domain': domain,
        'creation_date': creation_date,
        'expiration_date': expiration_date,
        'updated_date': updated_date
    }

domains = ["example.com", "example.net", "example.org"]
info_list = []

for domain in domains:
    info = get_domain_info(domain)
    info_list.append(info)

# Envoyer les données à l'API Laravel
api_url = "http://votre-application-laravel.test/api/store-domain-info"
headers = {'Content-Type': 'application/json'}
response = requests.post(api_url, data=json.dumps(info_list), headers=headers)

print(response.status_code)
