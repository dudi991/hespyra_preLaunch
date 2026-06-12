# HESPYRA — Setup vor Live-Gang: Analytics & Fonts

Zwei Dinge sind im Code vorbereitet, aber brauchen noch eure echten Werte/Dateien.
Beides ist DSGVO-konform ohne Cookie-Banner.

---

## 1 · Umami Analytics (cookiefrei, kein Consent-Banner nötig)

Umami trackt anonym und ohne Cookies — deshalb braucht ihr **kein** Einwilligungs-Banner.

### Schritt 1: Umami bereitstellen
Zwei Wege:
- **Selbst gehostet** (gratis): Umami auf einem eigenen Server/Vercel/Railway installieren.
  Anleitung: https://umami.is/docs/install
- **Umami Cloud** (einfacher, ab gratis-Tier): Account auf https://cloud.umami.is anlegen.

### Schritt 2: Website anlegen
Im Umami-Dashboard die Website hinzufügen (Domain hespyra.com eintragen).
Danach bekommt ihr zwei Werte:
- eine **Website-ID** (lange Zeichenkette, z. B. `a1b2c3d4-...`)
- die **Script-URL** (selbst gehostet: `https://eure-umami-domain/script.js`,
  Cloud: `https://cloud.umami.is/script.js`)

### Schritt 3: Werte in die HTML eintragen
In `hespyra-landingpage.html` im `<head>` diese Zeile suchen:
```html
<script defer data-website-id="UMAMI_WEBSITE_ID" src="UMAMI_SCRIPT_URL"></script>
```
- `UMAMI_WEBSITE_ID` → eure Website-ID
- `UMAMI_SCRIPT_URL` → eure Script-URL

Fertig. Ab dann werden Seitenaufrufe automatisch gezählt.

### Was bereits getrackt wird (Events)
Der Code sendet diese Events automatisch, sobald Umami aktiv ist:
- **waitlist-signup** — Eintrag ins Wartelisten-Formular (mit `source: hero-form` oder `sticky`)
- **hero-view** — welche Hero-Variante gesehen wurde (`variant: A` oder `B`) → für A/B-Tests
- **cta-click** — Klick auf den Hero-Button „Die erste Edition begleiten"
- **composition-click** — Klick auf „Zur Komposition"

Diese Events erscheinen im Umami-Dashboard unter „Events". Die wichtigste
Conversion-Kennzahl für eure Ads ist **waitlist-signup** — daran messt ihr den
Preis pro Eintrag (Ziel <4 €, gut <2,50 €).

### Hinweis Meta-Pixel
Bewusst NICHT eingebaut. Der Meta-Pixel setzt Cookies und bräuchte ein
Consent-Banner. Für den Waitlist-Start reicht Umami. Wenn ihr später für
Ad-Optimierung den Pixel wollt, braucht ihr vorher ein Consent-Banner —
sag Bescheid, dann bauen wir das sauber nach.

---

## 2 · Fonts self-hosten (DSGVO: kein Google-Server-Aufruf)

Aktuell lädt die Seite die Schriften noch von Google. Das ist in DE rechtlich
heikel (Google bekommt die IP der Besucher). Lösung: Schriften selbst hosten.

### Schritt 1: Schriftdateien holen
Auf https://gwfh.mranftl.com/fonts (Google Webfonts Helper):
1. **Cormorant Garamond** suchen → Schnitte wählen: `regular (400)`, `500`, `italic (400)`
2. **Jost** suchen → Schnitte wählen: `300`, `regular (400)`, `500`
3. Bei „Select charsets": `latin` reicht (spart Größe)
4. „Modern Browsers" wählen → das liefert **.woff2**-Dateien
5. Alle Dateien herunterladen

### Schritt 2: Dateien benennen und ablegen
Einen Ordner `fonts/` neben der HTML anlegen. Dateien so benennen:
```
fonts/cormorant-garamond-400.woff2
fonts/cormorant-garamond-500.woff2
fonts/cormorant-garamond-400italic.woff2
fonts/jost-300.woff2
fonts/jost-400.woff2
fonts/jost-500.woff2
```
(Falls der Webfonts-Helper andere Namen liefert, entweder die Dateien umbenennen
oder die Pfade im HTML anpassen.)

### Schritt 3: Im HTML umschalten
Im `<head>` von `hespyra-landingpage.html`:
1. Den Block **„VARIANTE A: Self-hosted"** ent-kommentieren
   (die umschließenden `<!--` und `-->` entfernen).
2. Den Block **„VARIANTE B: Google Fonts"** löschen (oder auskommentieren).

Danach lädt die Seite die Schriften ausschließlich von eurem eigenen Server.

### Prüfen
Nach dem Umstellen die Seite öffnen, Entwicklertools → Netzwerk-Tab → Filter „Font".
Es darf **kein** Request an `fonts.gstatic.com` oder `fonts.googleapis.com` mehr
auftauchen — nur noch eure eigenen `/fonts/*.woff2`.

---

## Reihenfolge zum Launch
1. Umami einrichten + Werte eintragen (sofort, kein Risiko)
2. Fonts self-hosten (vor Live-Gang)
3. Datenschutzerklärung: Umami-Abschnitt ergänzen (cookiefrei, berechtigtes
   Interesse), Google-Fonts-Abschnitt entfernen sobald self-hosted
4. Rapidmail-Doppel-Opt-in einmal echt durchtesten
5. Impressum/Datenschutz-Platzhalter füllen, noindex entfernen
