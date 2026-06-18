# HESPYRA — Pre-Launch Landingpage

Statische Landingpage (HTML/CSS/JS, keine Build-Tools nötig). Zweisprachig DE/EN, Warteliste über Rapidmail, cookiefreies Tracking über Umami.

## Struktur

```
index.html            Hauptseite (Hero, Sektionen, Warteliste)
ueber-uns.html        Unterseite
impressum.html        Rechtstext (Platzhalter prüfen)
kontakt.html          Kontaktseite (Formular-Backend fehlt noch)
datenschutz.html      Rechtstext (Platzhalter prüfen)
img/                  Bilder (WebP, responsive Größen)
```

## Lokal ansehen

Einfach `index.html` im Browser öffnen, oder ein kleiner Server:

```
python3 -m http.server 8000
```

## Vor dem Go-Live (Feinschliff-Checkliste)

**Technisch**
- [ ] `noindex` entfernen (im `<head>` von index.html), sobald öffentlich
- [ ] Domain-Platzhalter `https://hespyra.com` ersetzen (JSON-LD, Canonical)
- [ ] Umami: echte `UMAMI_WEBSITE_ID` und `UMAMI_SCRIPT_URL` eintragen
- [ ] Fonts self-hosten (Cormorant Garamond, Jost) statt via CDN — DSGVO + Performance
- [ ] Rapidmail-Formular mit Echt-Test prüfen (Double-Opt-in-Mail kommt an?)
- [ ] Kontaktformular-Backend anbinden (aktuell ohne Versand)
- [ ] Real-Device-Test (iOS Safari, Android Chrome)

**Rechtlich** (anwaltliche Prüfung empfohlen)
- [ ] Impressum: Angaben vollständig (Dominik Schwab, Wegäcker 11, 91301 Forchheim)
- [ ] Datenschutzerklärung an tatsächlich genutzte Dienste anpassen (Rapidmail, Umami, Fonts)
- [ ] **Tonka/Cumarin**: Kommunikation mit Formulierer + Lebensmittelrechtler klären,
      bevor Tonka prominent beworben wird (EU-Höchstmengen)
- [ ] Magnesium-Aussage nur im exakten zugelassenen Wortlaut
- [ ] Keine Wirkversprechen, „ohne Melatonin" als Dauerprinzip (nie „geplant")

**Inhalt**
- [ ] Anwendung final festlegen (warmes Wasser vs. Pflanzendrink) und Copy schärfen
- [ ] Ggf. Lifestyle-Bilder auf Glas-Motiv vereinheitlichen (Markensymbol)

## Deployment

Reine statische Seite — läuft auf GitHub Pages, Netlify, Vercel, Cloudflare Pages
oder jedem Webspace. Bei GitHub Pages: Repo → Settings → Pages → Branch wählen.
