# MaasISO Website Codebase Samenvatting

## Projectstructuur
De projectstructuur is bijgewerkt met nieuwe cookie management bestanden:

```
maasiso/
├── cline_docs/
│   ├── projectRoadmap.md
│   ├── currentTask.md
│   ├── designSystem.md
│   ├── techStack.md
│   └── codebaseSummary.md
├── index.html
├── diensten.html
├── waarom-maasiso.html
├── iso-9001.html
├── iso-27001.html
├── avg-compliance.html
├── over-ons.html
├── contact.html
├── css/
│   ├── reset.css
│   ├── base.css
│   ├── layout.css
│   ├── grid.css
│   ├── navigation.css
│   ├── buttons.css
│   ├── forms.css
│   ├── cards.css
│   ├── hero.css
│   ├── sections.css
│   ├── testimonials.css
│   ├── footer.css
│   ├── over-ons.css
│   ├── contact.css
│   ├── iso-27001-process.css
│   ├── utilities.css
│   ├── animations.css
│   ├── cookie-banner.css
│   ├── responsive-global.css
│   ├── responsive-navigation.css
│   ├── responsive-layout.css
│   └── responsive-components.css
├── js/
│   ├── main.js
│   ├── main.min.js
│   └── cookie-banner.js
└── images/
    (Nog geen afbeeldingen toegevoegd)
```

## Belangrijkste Componenten
De volgende belangrijke componenten zijn geïmplementeerd of bijgewerkt:

1. **HTML-structuur:**
   - Alle HTML-bestanden zijn bijgewerkt met een consistente structuur voor het linken van CSS-bestanden.
   - `index.html`: Hoofdpagina van de website, nu met verbeterde toegankelijkheid en lazy loading
   - `waarom-maasiso.html`: Bijgewerkt met een nieuwe structuur en styling die overeenkomt met `index.html`
   - `cookiebeleid.html`: Gedetailleerd cookiebeleid met informatie over alle gebruikte cookies

2. **CSS-stijlen:**
   - De CSS is nu opgedeeld in meerdere bestanden voor betere modulariteit en onderhoud.
   - Elk CSS-bestand is verantwoordelijk voor een specifiek onderdeel van de styling.
   - `cookie-banner.css`: Styling voor cookie consent banner en instellingen modal
   - `responsive-*.css` bestanden bevatten de responsieve stijlen voor verschillende componenten.

3. **JavaScript:**
   - `js/main.js`: Bevat de hoofdfunctionaliteit voor de website
   - `js/main.min.js`: Geminifieerde versie van `main.js`
   - `js/cookie-banner.js`: Cookie consent management systeem met de volgende features:
     * Cookie banner voor eerste bezoekers
     * Instellingen modal voor granulaire cookie controle
     * Ondersteuning voor verschillende cookie types (noodzakelijk, analytisch, functioneel)
     * Consent opslag voor 1 jaar
     * Integratie met Google Analytics

4. **Robots.txt:**
   - Het `robots.txt` bestand bepaalt welke delen van de website door zoekmachines mogen worden geïndexeerd.
   - De huidige inhoud van `robots.txt` is als volgt:
     ```
     User-agent: *
     Allow: /
     Sitemap: https://www.maasiso.nl/sitemap.xml

     # Prevent indexing of documentation
     Disallow: /cline_docs/

     # Prevent indexing of development assets
     Disallow: /*.css$
     Disallow: /*.js$

     # Prevent indexing of development and testing files
     Disallow: /contact-test.html
     Disallow: /mail-test.html
     Disallow: /mail-test.php
     Disallow: /contact-handler.php5
     Disallow: /composer.json
     Disallow: /install-php.bat
     Disallow: /add-php-to-path.bat

     # Prevent indexing of internal and development directories
     Disallow: /components/
     Disallow: /error_docs/
     Disallow: /git/
     Disallow: /httpdocs/
     Disallow: /lscache/
     ```

## Cookie Management
Het nieuwe cookie management systeem biedt:
1. Transparante cookie controle voor gebruikers
2. Compliance met privacywetgeving
3. Drie cookie categorieën:
   - Noodzakelijke cookies (altijd aan)
   - Analytische cookies (optioneel)
   - Functionele cookies (optioneel)
4. Persistente opslag van gebruikersvoorkeuren
5. Responsief ontwerp voor alle apparaten

## Gegevensstroom
- De website gebruikt voornamelijk statische inhoud in HTML
- Cookie voorkeuren worden lokaal opgeslagen
- Analytische data wordt alleen verzameld na gebruikerstoestemming

## Externe Afhankelijkheden
- Google Fonts: Roboto Slab en Open Sans
- Font Awesome: Voor iconen
- Google Analytics: Voor gebruikersstatistieken (alleen met toestemming)

## Recente Belangrijke Wijzigingen
- Implementatie van cookie consent management systeem
- Toevoeging van `cookie-banner.css` en `cookie-banner.js`
- Herstructurering van CSS: Opgesplitst in meerdere bestanden voor betere modulariteit
- Standaardisatie van CSS-linking in alle HTML-bestanden
- Verbetering van de laadprestaties
- Verbeterde toegankelijkheid
- Bijwerking van de footer met placeholder informatie
- Vereenvoudiging van de websitestructuur
- Bijgewerkt `robots.txt` bestand om specifieke directories en bestanden te verbieden voor zoekmachines

## Geplande Verbeteringen
- Standaardiseren van het gebruik van ofwel `main.js` of `main.min.js` over alle pagina's
- Verdere optimalisatie van de laadsnelheid van de website
- Implementeren van geavanceerde SEO-praktijken op alle pagina's
- Toevoegen van relevante afbeeldingen en optimaliseren voor snelle laadtijden
- Uitvoeren van uitgebreide cross-browser en apparaat-compatibiliteitstests
- Implementeren van verbeterde foutafhandeling en logging
- Toevoegen van interactieve elementen om de gebruikerservaring te verbeteren
- Monitoren en optimaliseren van cookie consent systeem op basis van gebruikersfeedback

## Integratie van Gebruikersfeedback
Gebruikersfeedback zal worden verzameld en geïntegreerd na de recente wijzigingen in de CSS-structuur en de implementatie van het cookie management systeem. Specifieke aandacht zal worden besteed aan:
- Gebruikerservaring met cookie consent banner
- Duidelijkheid van cookie instellingen
- Laadsnelheid
- Toegankelijkheid
- Algehele navigatie van de site

Opmerking: Dit document zal regelmatig worden bijgewerkt naarmate het project vordert om de huidige staat van de codebase en het ontwikkelingsproces weer te geven.
