<?php
require_once __DIR__ .'/ComandiSQL/comandiSQL.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Evitiamo sql injection mettendo tutto in escape
    $email = trim($_POST["email"]);
    $password = $_POST["password"];


    if (empty($email) || empty($password)) {
        die("Email e password sono obbligatorie");
    }

    $risultato = getRowUtenteById($email);

    if(!$risultato){
        echo "Utente non trovato";
    } else {
        $risultatoRow = $risultato->FetchRow();
        if($risultatoRow['password'] == $password){
            echo 'Login Riuscito';

            switch ($email){
                case 'amministrazione@gmail.com':
                    sleep(1);
                    header("Location:amministrazione.php");
                    exit();
                    break;
                case 'esercizio@gmail.com':
                    sleep(1);
                    header("Location:esercizio.php");
                    exit();
                    break;
                default:
                    $_Session['utente'] = $risultatoRow;
                    $_Session['nome'] = $risultatoRow['nome'];
                    sleep(1);
                    header("Location:prenotaBiglietto.php");
                    exit();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css">
        <title>Società ferrovie Turistiche - SFT</title>
    </head>

<body>
<div>Login</div>
<p>Debug: <br>Email: Test@gmail.com <br>Password: Test</p><br>
<p>Esercizio: <br>Email: amministrazione@gmail.com <br>Password: 1234</p><br>
<p>Amministrazione: <br>Email: esercizio@gmail.com <br>Password: 1234</p>

<form method="POST">
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
</body>
</html>
