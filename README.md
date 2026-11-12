# Bongersonline WordPress-thema

Eigen thema voor [bongersonline.nl](https://bongersonline.nl), gebouwd op Bootstrap 5 (via CDN).

## Wat zit erin

- **Homepage** (`front-page.php`): slider of donkerblauwe kop, de inhoud van de statische pagina "Home", een helpdesk-blok met zoekbalk en onderwerpen, en een contactblok.
- **Helpdesk**: berichttype `handleiding` met taxonomie `onderwerp`.
  - Overzicht op `/handleidingen/`, per onderwerp op `/handleidingen/onderwerp/<slug>/`, losse handleidingen op `/handleiding/<slug>/`.
  - Per handleiding: automatische inhoudsopgave (h2), leestijd, printknop, gerelateerde handleidingen.
  - Nieuwe handleidingen starten met een vaste opzet (blocksjabloon).
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
| `page.php`, `index.php`, `search.php`, `404.php` | Overige templates |
| `header.php`, `footer.php`, `template-parts/` | Gedeelde onderdelen |
| `style.css`, `editor-style.css`, `assets/site.js` | Opmaak en scripts |
