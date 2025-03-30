<?php
session_start();

if(!isset($_SESSION['id_utente'])){
    //Se non è loggato l'utente ritorna al login. si evita quindi che cambia pagina in auotonomia tramite url? credo...
    header('Location: login.php');
    exit;
}

$id_utente = $_SESSION['id_utente'];
?>

<h1>Benvenuto alla homepage, <?php echo $id_utente; ?></h1>
