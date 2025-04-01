<?php
require 'ComandiSQL/Sql_GetQuery.php';


if(!isset($_SESSION['id_utente']) && !isset($_SESSION['nome'])){
    //Se non è loggato l'utente ritorna al login. si evita quindi che cambia pagina in autonomia tramite url? credo...
    header('Location: index.php');
    exit;
}

//printa il saldo
$saldo = getSaldoById($_SESSION['id_utente']);
//printaMovimenti

//Ottieni id conto da id utente

$id_contoCorrente = getIdContoByIdUtente($_SESSION['id_utente']);

//Ottieni movimenti da conto


?>

<h1>Benvenuto alla homepage, <?php echo $_SESSION['id_utente']; ?></h1>
<p>Se vuoi uscire dalla sessione <a href="login.php">Clicca qui</a> </p>

<p>Ecco i tuoi dati <?php echo $saldo ?>€</p>
<p>Ciao  <?php echo $id_contoCorrente ?></p>
