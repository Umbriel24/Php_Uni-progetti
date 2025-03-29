<?php
require 'dbAccess.php';
include 'ADOdb-5.22.8/adodb.inc.php';

$driver = 'mysqli';
$db = newAdoConnection($driver);

$db->connect('localhost', $argUsername, $argPassword, $argDatabase);

if (!$db->isconnected()) {
    echo "Errore di connessione al database: " . $db->ErrorMsg();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "<pre>";
    var_dump($_POST);
    echo "</pre>";

    //Obbligatori di utente
    $email = $_POST['email'];
    $password = $_POST['password'];
    $tipo_utente = $_POST['tipo_utente'];

    //Esclusivi acquirente o Esercente
    $codice_fiscale = $_POST['codice_fiscale'];
    $partita_iva = $_POST['partita_iva'];

    if ($db->isconnected()) {
        //Post in utente
        $query = "INSERT INTO progetto2_Utente (email, password) VALUES (?, ?)";
        $stmt = $db->prepare($query);
        if ($stmt) {
            $result = $db->Execute($stmt, [$email, $password]);

            if ($result) {
                echo "Registrazione avvenuta con successo in Utente.";
            } else {
                echo "Errore durante la registrazione in Utente. Riprova: " . $db->ErrorMsg();
            }


            if ($result) {
                //prendi id utente
                $id_utente = $db->Insert_ID();
                if ($tipo_utente == 'acquirente' && !empty($codice_fiscale)) {
                    $db->Execute("INSERT INTO progetto2_Acquirente (id_acquirente, codice_fiscale) VALUES (?, ?)", [$id_utente, $codice_fiscale]);
                } else if ($tipo_utente == 'esercente') { //Potrebbe essere un else normale, ma per controllo mettiamo else if.
                    $db->Execute("INSERT INTO progetto2_Esercente (id_esercente, partita_iva) VALUES(?, ?)", [$id_utente, $partita_iva]);
                }

                echo " Registrazione avvenuta con successo in Esercente o Acquirente. <a href=index.php>Accedi</a>";

            } else {
                echo "Errore durante la registrazione in fase di post in acquirente o esercente. Riprova";
            }
        } else {
            echo "Errore di connessione al Db.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrazione</title>
    <script>
        function togglePartitaIVA() {
            var checkbox = document.getElementById("tipo_utente");
            var partitaIVAField = document.getElementById("partitaIVAField");
            var codiceFiscaleField = document.getElementById("codicefiscaleField");
            var partitaIVAInput = document.querySelector('[name="partita_iva"]');
            var codiceFiscaleInput = document.querySelector('[name="codice_fiscale"]');

            if (checkbox.checked) {
                partitaIVAField.style.display = "block";
                codiceFiscaleField.style.display = "none";

                partitaIVAInput.setAttribute("required", "required");
                codiceFiscaleInput.removeAttribute("required");
            } else {
                partitaIVAField.style.display = "none";
                codiceFiscaleField.style.display = "block";

                codiceFiscaleInput.setAttribute("required", "required");
                partitaIVAInput.removeAttribute("required");
            }
        }
        // Inizializza gli attributi required al caricamento della pagina
        window.onload = togglePartitaIVA;
    </script>
</head>
<body>

<h2>Registrati</h2>
<p> <a href="index.php">Clicca qui per tornare indietro </a> </p>
<form method="post">
    <label>Email:</label>
    <input type="email" name="email" required><br>
    <label>Password:</label>
    <input type="password" name="password" required><br>

    <input type="hidden" name="tipo_utente" value="acquirente">
    <label>Sei un esercente?</label>
    <input type="checkbox" name="tipo_utente" value="esercente" id="tipo_utente" onclick="togglePartitaIVA()">

    <div id="codicefiscaleField" style="display: block">
        <label>Codice Fiscale:</label>
        <input type="text" name="codice_fiscale"><br>
    </div>

    <div id="partitaIVAField" style="display: none;">
        <label>Partita IVA:</label>
        <input type="text" name="partita_iva"><br>
    </div>


    <input type="submit" value="Registrati">
</form>
</body>
</html>
