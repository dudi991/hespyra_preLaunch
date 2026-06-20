<?php
// HESPYRA — Kontaktformular-Verarbeitung
// Empfänger der Nachrichten:
$empfaenger = 'hallo@hespyra.com';

// Nur POST zulassen
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: kontakt.html');
    exit;
}

// --- Spamschutz: Honeypot ---
// Echte Menschen sehen das Feld "website" nicht und lassen es leer.
// Ist es ausgefüllt, ist es mit hoher Wahrscheinlichkeit ein Bot.
if (!empty($_POST['website'])) {
    // Lautlos als Erfolg behandeln, damit der Bot kein Feedback bekommt.
    zeige_seite('ok');
    exit;
}

// --- Eingaben einlesen und säubern ---
$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$message = trim($_POST['message'] ?? '');
$consent = isset($_POST['consent']);

// --- Validierung ---
$fehler = [];
if ($name === '')    { $fehler[] = 'Name fehlt.'; }
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $fehler[] = 'E-Mail ungültig.'; }
if ($message === '') { $fehler[] = 'Nachricht fehlt.'; }
if (!$consent)       { $fehler[] = 'Einwilligung fehlt.'; }

// Schutz gegen Header-Injection: keine Zeilenumbrüche in Name/E-Mail erlauben
if (preg_match('/[\r\n]/', $name) || preg_match('/[\r\n]/', $email)) {
    $fehler[] = 'Ungültige Zeichen.';
}

if (!empty($fehler)) {
    zeige_seite('fehler');
    exit;
}

// --- Länge begrenzen (gegen Missbrauch) ---
$name    = mb_substr($name, 0, 120);
$message = mb_substr($message, 0, 5000);

// --- E-Mail zusammenbauen ---
$betreff = 'Neue Kontaktnachricht über hespyra.com';
$body  = "Neue Nachricht über das Kontaktformular:\n\n";
$body .= "Name:    " . $name . "\n";
$body .= "E-Mail:  " . $email . "\n\n";
$body .= "Nachricht:\n" . $message . "\n";

// Absender-Domain ist die eigene Domain (verhindert SPF-Probleme),
// die Antwort geht per Reply-To an den Absender der Nachricht.
$headers  = "From: HESPYRA Website <noreply@hespyra.com>\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$gesendet = mail($empfaenger, '=?UTF-8?B?' . base64_encode($betreff) . '?=', $body, $headers);

zeige_seite($gesendet ? 'ok' : 'fehler');
exit;


// ---------------------------------------------------------------
// Schlichte Bestätigungs-/Fehlerseite im HESPYRA-Stil
function zeige_seite($status) {
    $ist_ok = ($status === 'ok');
    $titel  = $ist_ok ? 'Vielen Dank' : 'Etwas ist schiefgelaufen';
    $text   = $ist_ok
        ? 'Deine Nachricht wurde übermittelt. Wir lesen jede Nachricht aufmerksam und melden uns so schnell wie möglich bei dir.'
        : 'Deine Nachricht konnte leider nicht gesendet werden. Bitte versuche es noch einmal oder schreibe uns direkt an hallo@hespyra.com.';
    http_response_code($ist_ok ? 200 : 400);
    ?><!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="color-scheme" content="light" />
<meta name="robots" content="noindex" />
<title>HESPYRA — <?php echo htmlspecialchars($titel, ENT_QUOTES, 'UTF-8'); ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;1,400;1,500&family=Jost:wght@400;500&display=swap" rel="stylesheet" />
<style>
  :root { color-scheme: light; --cream:#EFE6D6; --cream-deep:#E6DAC6; --ink:#2A2420; --ink-soft:#6B6256; --amber:#9E6B41; --sand-line:#D8CBB6; }
  * { box-sizing:border-box; }
  body { margin:0; background:var(--cream); color:var(--ink); font-family:'Jost',sans-serif; line-height:1.7; -webkit-font-smoothing:antialiased; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:40px 22px; }
  .box { max-width:520px; text-align:center; }
  .wordmark { letter-spacing:0.42em; text-indent:0.42em; font-size:18px; color:var(--ink); text-decoration:none; display:inline-block; margin-bottom:40px; }
  h1 { font-family:'Cormorant Garamond',serif; font-weight:400; font-size:clamp(30px,5vw,44px); margin:0 0 18px; }
  h1 em { font-style:italic; color:var(--amber); }
  p { color:var(--ink-soft); font-size:17px; margin:0 0 32px; }
  .back { display:inline-block; font-size:12px; letter-spacing:0.18em; text-transform:uppercase; color:var(--ink); text-decoration:none; border:1px solid var(--ink); padding:13px 26px; transition:background .3s,color .3s; }
  .back:hover { background:var(--ink); color:var(--cream); }
</style>
</head>
<body>
  <div class="box">
    <a href="index.html" class="wordmark">HESPYRA</a>
    <h1 class="serif"><?php echo htmlspecialchars($titel, ENT_QUOTES, 'UTF-8'); ?><?php echo $ist_ok ? '<em>.</em>' : ''; ?></h1>
    <p><?php echo htmlspecialchars($text, ENT_QUOTES, 'UTF-8'); ?></p>
    <a href="index.html" class="back">Zur Startseite</a>
  </div>
</body>
</html><?php
}
