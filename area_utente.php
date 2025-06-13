<?php
session_start();
if (!isset($_SESSION['utente'])) {
    header("Location: login.php");
    exit;
}
$utente_email = $_SESSION['utente']['email'];
$ordini = json_decode(file_get_contents("ordini.json"), true) ?? [];
echo "<h2>Benvenuto {$_SESSION['utente']['nome']}</h2>";
echo "<a href='logout.php'>Esci</a><hr>";
foreach ($ordini as $ordine) {
    if ($ordine['email'] === $utente_email) {
        echo "<p><strong>Ordine:</strong> {$ordine['codice']} - {$ordine['data']}<br>";
        echo "<pre>{$ordine['dettaglio']}</pre><strong>Totale:</strong> €{$ordine['totale']}<hr>";
    }
}
?>
