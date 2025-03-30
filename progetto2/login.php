<?php
session_start();
require 'dbAccess.php';
include 'ADOdb-5.22.8/adodb.inc.php';

$driver = 'mysqli';
$db = newAdoConnection($driver);

$db->connect('localhost', $argUsername, $argPassword, $argDatabase);

if (!$db->isconnected()) {
    echo "Errore di connessione al database: " . $db->ErrorMsg();
    exit;
}

//non usiamo get per problemi di sicurezza (anche se è pura esercitazione universitaria, quindi ambiente sicuro)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Evitiamo sql injection mettendo tutto in escape
    $email = $db->qStr($_POST["email"]);
    $password = $_POST["password"];

    $query = "SELECT * FROM progetto2_Utente where email = $email";

    $result = $db->Execute($query);

    if (!$result) {
        echo "Errore nella query al primo passaggio. " . $db->ErrorMsg();
    } else {
        if(!$result->EOF) {
            $row = $result->FetchRow();
            if($row['password'] == $password) {
                echo "Login riuscito!";
                //Sessione e "trasportiamo" l'id utente.
                $_SESSION['id_utente'] = $row['id_utente'];
                $_SESSION['nome'] = $row['nome'];
                sleep(1);
                header('Location: homepage.php');
                exit;

            } else {
                echo "Login non valido!";
            }
        } else echo "Login non valido - debug in result->EOF";
    }
}
?>

<!-- Form HTML -->
<p><a href="index.php">Torna all'index</a></p>

<p>DEBUG: email umberto1@gmail.com</p>
<p>pass 1112</p>

<form method="POST" action="">
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit">Accedi</button>
</form>


