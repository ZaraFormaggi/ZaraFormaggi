<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $utenti = json_decode(file_get_contents("utenti.json"), true);
    foreach ($utenti as $utente) {
        if ($utente['email'] === $email && password_verify($password, $utente['password'])) {
            $_SESSION['utente'] = $utente;
            header("Location: area_utente.php");
            exit;
        }
    }
    echo "Email o password errati.";
}
?>
<form method="post">
  Email: <input type="email" name="email" required><br>
  Password: <input type="password" name="password" required><br>
  <button type="submit">Accedi</button>
</form>
