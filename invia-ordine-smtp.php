<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

// ⚙️ CONFIGURAZIONE SMTP
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // O il tuo provider SMTP
    $mail->SMTPAuth = true;
    $mail->Username = 'TUA_EMAIL@gmail.com'; // Tua email
    $mail->Password = 'TUA_PASSWORD'; // Password o app password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // 📥 Leggi dati ordine
    $codice_file = __DIR__ . "/ultimo_codice.txt";
    if (!file_exists($codice_file)) file_put_contents($codice_file, "0100200100");
    $codice = (int)file_get_contents($codice_file);
    $nuovo_codice = $codice + 1;
    file_put_contents($codice_file, $nuovo_codice);

    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $modalita = $_POST['modalita'] ?? '';
    $prodotti = $_POST['prodotti'] ?? [];

    $totale = 0;
    $corpo = "";
    foreach ($prodotti as $item) {
        $q = (int)$item['quantita'];
        $p = (float)$item['prezzo'];
        $rigaTot = $q * $p;
        $totale += $rigaTot;
        $corpo .= "• {$item['nome']} – {$q} × €{$p} = €" . number_format($rigaTot, 2) . "\n";
    }
    $corpo .= "\nTotale: €" . number_format($totale, 2);
    $iban = "IT00Z0000000000000000000000";

    // 🧾 CREA PDF con TCPDF
    require_once('vendor/tecnickcom/tcpdf/tcpdf.php');
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Write(0, "Riepilogo Ordine n. $codice\n\n$corpo\n\nPagamento con IBAN:\n$iban\nCausale: Pagamento ordine n. $codice a Zara Formaggi");
    $pdf_file = tempnam(sys_get_temp_dir(), 'ordine_') . ".pdf";
    $pdf->Output($pdf_file, 'F');

    // ✉️ INVIO AL CLIENTE
    $mail->setFrom('TUA_EMAIL@gmail.com', 'Zara Formaggi');
    $mail->addAddress($email, $nome);
    $mail->Subject = "Conferma Ordine n. $codice – Zara Formaggi";
    $mail->Body = "Ciao $nome,\n\nGrazie per il tuo ordine!\n\n$corpo\n\nModalità: $modalita\n\n📌 IBAN: $iban\nCausale: Pagamento ordine n. $codice a Zara Formaggi\n\nGrazie per la fiducia!\n– Zara Formaggi";
    $mail->addAttachment($pdf_file, "Ricevuta_Ordine_$codice.pdf");
    $mail->send();

    // 📤 INVIO A TE
    $mail->clearAddresses();
    $mail->addAddress('TUA_EMAIL@gmail.com');
    $mail->Subject = "📥 Nuovo Ordine n. $codice da $nome";
    $mail->Body = "Nuovo ordine ricevuto da $nome ($email – $telefono):\n\n$corpo\n\nModalità: $modalita\nTotale: €" . number_format($totale, 2);
    $mail->addAttachment($pdf_file, "Ricevuta_Ordine_$codice.pdf");
    $mail->send();

    echo "<h2>Ordine inviato!</h2><p>Ricevuta PDF inviata via email.</p>";
} catch (Exception $e) {
    echo "Errore nell'invio: {$mail->ErrorInfo}";
}
?>
