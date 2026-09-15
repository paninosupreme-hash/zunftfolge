<?php
declare(strict_types=1);

/**
 * Generischer Formular-Handler für alle Formulare auf zunftfolge.de.
 *
 * WICHTIG VOR DEM HOCHLADEN:
 * Stelle sicher, dass info@zunftfolge.de als Postfach bei Hostinger
 * eingerichtet ist (oder als Weiterleitung auf eine erreichbare Adresse
 * zeigt) — sonst gehen die Formulareingaben ins Leere.
 */
const RECIPIENT_EMAIL = 'info@zunftfolge.de';
const SITE_NAME = 'Zunftfolge';

function redirect_with_error(string $message): void {
    $target = 'https://' . ($_SERVER['HTTP_HOST'] ?? 'zunftfolge.de') . '/' . basename($_SERVER['HTTP_REFERER'] ?? 'index.html');
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../index.html') . '?fehler=' . urlencode($message));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.html');
    exit;
}

// Honeypot-Feld gegen einfache Spam-Bots: für Menschen unsichtbar (siehe CSS),
// bleibt normalerweise leer. Ist es ausgefüllt, war es vermutlich ein Bot.
if (!empty($_POST['website'] ?? '')) {
    header('Location: ../danke.html');
    exit;
}

if (trim(RECIPIENT_EMAIL) === '' || str_contains(RECIPIENT_EMAIL, 'BITTE-DEINE-ECHTE-EMAIL')) {
    redirect_with_error('Das Formular ist noch nicht fertig eingerichtet (fehlende Empfänger-E-Mail in php/send-form.php).');
}

function clean(string $value): string {
    // Entfernt Zeilenumbrüche etc., damit niemand über ein Formularfeld
    // zusätzliche Mail-Header einschleusen kann (Header-Injection).
    $value = str_replace(["\r", "\n"], ' ', $value);
    return trim(strip_tags($value));
}

$formType = clean($_POST['form_type'] ?? 'Formular');
$name     = clean($_POST['name'] ?? '');
$email    = clean($_POST['email'] ?? '');

if ($name === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_with_error('Bitte Name und eine gültige E-Mail-Adresse angeben.');
}

// Alle übrigen Felder werden generisch mit übernommen, egal aus welchem
// Formular sie kommen (Kontakt, Registrierung Inhaber/Nachfolger, Mandatsannahme).
$skipFields = ['form_type', 'website'];
$lines = [];
foreach ($_POST as $key => $value) {
    if (in_array($key, $skipFields, true)) {
        continue;
    }
    if (is_array($value)) {
        $value = implode(', ', array_map('clean', $value));
    } else {
        $value = clean((string) $value);
    }
    if ($value === '') {
        continue;
    }
    $label = ucwords(str_replace('_', ' ', $key));
    $lines[] = $label . ': ' . $value;
}

$body = "Neue Einsendung über " . SITE_NAME . " (" . $formType . ")\n\n" . implode("\n", $lines) . "\n";

$subject = '=?UTF-8?B?' . base64_encode(SITE_NAME . ' – ' . $formType) . '?=';

$headers = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';
$headers[] = 'From: ' . SITE_NAME . ' Website <no-reply@' . ($_SERVER['HTTP_HOST'] ?? 'zunftfolge.de') . '>';
$headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';

$sent = @mail(RECIPIENT_EMAIL, $subject, $body, implode("\r\n", $headers));

if (!$sent) {
    redirect_with_error('Die Nachricht konnte nicht versendet werden. Bitte kontaktieren Sie uns direkt per E-Mail.');
}

header('Location: ../danke.html');
exit;
