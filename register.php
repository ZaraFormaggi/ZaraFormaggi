<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $utente = ['nome' => $nome, 'email' => $email, 'password' => $password];

    $utenti = file_exists("utenti.json") ? json_decode(file_get_contents("utenti.json"), true) : [];
    foreach ($utenti as $u) {
        if ($u['email'] === $email) {
            echo "Email già registrata.";
            exit;
        }
    }
    $utenti[] = $utente;
    file_put_contents("utenti.json", json_encode($utenti, JSON_PRETTY_PRINT));
    echo "Registrazione completata. <a href='login.php'>Accedi</a>";
    exit;
}
?>
<form method="post">
  Nome: <input name="nome" required><br>
  Email: <input type="email" name="email" required><br>
  Password: <input type="password" name="password" required><br>
  <button type="submit">Registrati</button>
</form>
