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



if(!isset($_SESSION['id_utente']) && !isset($_SESSION['nome'])){
    //Se non è loggato l'utente ritorna al login. si evita quindi che cambia pagina in autonomia tramite url? credo...
    header('Location: index.php');
    exit;
}

$id_utente = $_SESSION['id_utente'];
$nome = $_SESSION['nome'];

//Query senza parametri. TODO ricordati di mettere i parametri alla query.
$saldo_Result = $db->Execute("SELECT saldo from progetto2_ContoCorrente WHERE id_utente = $id_utente");



$row = $saldo_Result->FetchRow();
$saldo = $row['saldo'];
?>

<h1>Benvenuto alla homepage, <?php echo $nome; ?></h1>
<p>Se vuoi uscire dalla sessione <a href="login.php">Clicca qui</a> </p>

<p>Ecco i tuoi dati <?php echo $saldo ?></p>