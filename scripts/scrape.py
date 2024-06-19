import sys
import json
import logging
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.edge.service import Service as EdgeService
from selenium.webdriver.edge.options import Options
from webdriver_manager.microsoft import EdgeChromiumDriverManager

logging.basicConfig(level=logging.INFO)

def scrape(domain_name):
    result = {
        "domain_name": domain_name,
        "status": None,
        "expiration_date": None,
        "registration_date": None,
        "update_date": None,
        "error": None
    }

    try:
        edge_options = Options()
        edge_options.add_argument("--headless")
        edge_options.add_argument("--disable-gpu")
        edge_options.add_argument("--window-size=1920,1080")

        service = EdgeService(executable_path=EdgeChromiumDriverManager().install())
        driver = webdriver.Edge(service=service, options=edge_options)

        url = "https://who.is/whois/" + domain_name
        driver.get(url)

        driver.implicitly_wait(10)
        values = driver.find_elements(By.CSS_SELECTOR, ".col-md-8.queryResponseBodyValue")

        available = driver.find_element(By.ID, "domainAvailabilityPowerBarHeading")
        available_text = available.find_element(By.TAG_NAME, "span").text

        if len(values) > 7:
            result["status"] = "registered"
            result["expiration_date"] = values[4].text
            result["registration_date"] = values[5].text
            result["update_date"] = values[6].text
        elif available_text == domain_name + " is already registered.":
            result["status"] = "registered_no_dates"
        else:
            result["status"] = "available"

        driver.quit()
    except Exception as e:
        logging.error(f"Error during scraping: {e}")
        result["error"] = str(e)

    return result

if __name__ == "__main__":
    domain_name = sys.argv[1]
    logging.info(f"Scraping for domain: {domain_name}")
    result = scrape(domain_name)
    print(json.dumps(result))
