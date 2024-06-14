import sys
import json
import whois

domain = sys.argv[1]

w = whois.whois(domain)

info = {
    'domain': domain,
    'creation_date': w.creation_date.strftime('%Y-%m-%d') if w.creation_date else None,
    'expiration_date': w.expiration_date.strftime('%Y-%m-%d') if w.expiration_date else None,
    'updated_date': w.updated_date.strftime('%Y-%m-%d') if w.updated_date else None
}

print(json.dumps(info))
