# MaasISO Website Codebase Samenvatting

## Projectstructuur
De projectstructuur blijft grotendeels ongewijzigd, met enkele updates in de CSS-structuur:

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
│   ├── responsive-global.css
│   ├── responsive-navigation.css
│   ├── responsive-layout.css
│   └── responsive-components.css
├── js/
│   ├── main.js
│   └── main.min.js
└── images/
    (Nog geen afbeeldingen toegevoegd)
```

## Belangrijkste Componenten
De volgende belangrijke componenten zijn geïmplementeerd of bijgewerkt:

1. HTML-structuur:
   - Alle HTML-bestanden zijn bijgewerkt met een consistente structuur voor het linken van CSS-bestanden.
   - index.html: Hoofdpagina van de website, nu met verbeterde toegankelijkheid en lazy loading
   - waarom-maasiso.html: Bijgewerkt met een nieuwe structuur en styling die overeenkomt met index.html
   (Rest van de HTML-structuur blijft ongewijzigd)

2. CSS-stijlen:
   - De CSS is nu opgedeeld in meerdere bestanden voor betere modulariteit en onderhoud.
   - Elk CSS-bestand is verantwoordelijk voor een specifiek onderdeel van de styling.
   - responsive-*.css bestanden bevatten de responsieve stijlen voor verschillende componenten.

3. JavaScript:
   - js/main.js: Bevat de hoofdfunctionaliteit voor de website
   - js/main.min.js: Geminifieerde versie van main.js, gebruikt in sommige HTML-bestanden

## Gegevensstroom
De website gebruikt voornamelijk statische inhoud in HTML.

## Externe Afhankelijkheden
- Google Fonts: Roboto Slab en Open Sans
- Font Awesome: Voor iconen

## Recente Belangrijke Wijzigingen
- Herstructurering van CSS: Opgesplitst in meerdere bestanden voor betere modulariteit
- Standaardisatie van CSS-linking in alle HTML-bestanden
- Verbetering van de laadprestaties door het gebruik van individuele CSS-bestanden
- Inconsistent gebruik van main.js en main.min.js geïdentificeerd
- Verbeterde toegankelijkheid van index.html door toevoeging van ARIA-attributen
- Implementatie van lazy loading voor icon-elementen in index.html
- Bijwerking van de footer in index.html met placeholder informatie
- Toevoeging van placeholder URL's voor sociale media links
- Verwijdering van de aparte landingspagina (landing-new.html) en bijbehorende bestanden (landing-new.css, landing-new.js)
- Vereenvoudiging van de websitestructuur
- De "Home" knop in de navigatie leidt nu direct naar index.html
- Bijwerking van waarom-maasiso.html met een nieuwe structuur en styling die overeenkomt met index.html

## Geplande Verbeteringen
- Standaardiseren van het gebruik van ofwel main.js of main.min.js over alle pagina's
- Verdere optimalisatie van de laadsnelheid van de website
- Implementeren van geavanceerde SEO-praktijken op alle pagina's
- Toevoegen van relevante afbeeldingen en optimaliseren voor snelle laadtijden
- Uitvoeren van uitgebreide cross-browser en apparaat-compatibiliteitstests
- Implementeren van verbeterde foutafhandeling en logging
- Toevoegen van interactieve elementen om de gebruikerservaring te verbeteren

## Integratie van Gebruikersfeedback
Gebruikersfeedback zal worden verzameld en geïntegreerd na de recente wijzigingen in de CSS-structuur en de update van alle pagina's. Specifieke aandacht zal worden besteed aan de gebruikerservaring met betrekking tot de laadsnelheid, toegankelijkheid en de algehele navigatie van de site.

Opmerking: Dit document zal regelmatig worden bijgewerkt naarmate het project vordert om de huidige staat van de codebase en het ontwikkelingsproces weer te geven.
