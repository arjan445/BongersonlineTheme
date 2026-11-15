# Bongersonline WordPress-thema

Eigen thema voor [bongersonline.nl](https://bongersonline.nl), gebouwd op Bootstrap 5 (via CDN).

## Wat zit erin

- **Homepage** (`front-page.php`): slider of donkerblauwe kop, de inhoud van de statische pagina "Home", een helpdesk-blok met zoekbalk en onderwerpen, en een contactblok.
- **Helpdesk**: berichttype `handleiding` met taxonomie `onderwerp`.
  - Overzicht op `/handleidingen/`, per onderwerp op `/handleidingen/onderwerp/<slug>/`, losse handleidingen op `/handleiding/<slug>/`.
  - Per handleiding: automatische inhoudsopgave (h2), leestijd, printknop, gerelateerde handleidingen.
  - Nieuwe handleidingen starten met een vaste opzet (blocksjabloon).
- **Projecten**: berichttype `project` met taxonomie `installatietype` (`inc/projects.php`).
  - Per project een opdrachtgever, plaats, hoofdfoto en extra foto's (metabox "Projectgegevens"). De foto-volgorde kun je wijzigen door te slepen.
  - Met het vinkje *Uitgelicht project* komt een project vooraan te staan (overzicht, homepage en "Meer projecten"); daarna nieuwste eerst.
  - Overzicht op `/projecten/` met een filter per type, losse projecten op `/project/<slug>/` met een lightbox. De homepage toont de drie nieuwste projecten.
- **Opdrachtgevers**: berichttype `opdrachtgever` met naam, logo (uitgelichte afbeelding), optionele website en volgorde (`inc/clients.php`). Ze worden getoond als logoband op de homepage en onder de projecten; vanaf 5 logo's loopt de band door. Elders te plaatsen met de shortcode `[bo_opdrachtgevers titel="..."]`.
- **Blokstijlen** voor de editor: *Stappen* (genummerde lijst), *Tip*, *Let op*, *Info*, *Vinkjes*, *Intro*.
- **Customizer**
  - *Homepage slider*: 5 slides van 1920 × 600 met titel, tekst en knop.
  - *Contactgegevens*: e-mail, telefoon, WhatsApp, adres, KVK- en btw-nummer. Deze worden gebruikt in de footer, de contactknoppen en de shortcode `[bo_contactgegevens]`.
- **Onderwerpen**: per onderwerp een Bootstrap-icoon (`bo_icon`) en een volgorde (`bo_order`).
- **Contactpagina**: de breedte past zich aan voor een pagina met slug `contact` (formulier via Contact Form 7).

## Installeren

1. Zip de map `bongersonline` (de map zelf, niet alleen de inhoud).
2. Upload de zip in WordPress via **Weergave → Thema's → Nieuwe toevoegen → Thema uploaden**.
3. Stel de permalinks in op `/%postname%/`.

## Bestanden

| Bestand | Doel |
|---|---|
| `functions.php` | Thema-instellingen, berichttype, taxonomie, Customizer, blokstijlen, helpers |
| `front-page.php` | Homepage |
| `archive-handleiding.php`, `taxonomy-onderwerp.php` | Helpdesk-overzicht |
| `single-handleiding.php` | Losse handleiding |
| `inc/clients.php` | Opdrachtgevers en de logoband |
| `inc/projects.php` | Projecten: berichttype, type installatie, metabox, fotogalerij |
| `archive-project.php`, `taxonomy-installatietype.php`, `single-project.php` | Projectoverzicht en losse projecten |
| `page.php`, `index.php`, `search.php`, `404.php` | Overige templates |
| `header.php`, `footer.php`, `template-parts/` | Gedeelde onderdelen |
| `style.css`, `editor-style.css`, `assets/site.js` | Opmaak en scripts |
