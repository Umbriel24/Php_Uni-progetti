<?php

require_once __DIR__ . '/../SQLProgetto2/Sql_GetQuery.php';
require_once __DIR__ . '/../SQLProgetto2/SQL_PostQuery.php';
require_once __DIR__ .  '/../CartellaDB/database.php';


//Approva le transazioni in attesa
if (!isset($_SESSION['id_utente']) && !isset($_SESSION['nome'])) {
    //Se non è loggato l'utente ritorna al login.
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['azione'])) {
    $id_transazione = $_POST['id'] ?? null;
    $azione = $_POST['azione'] ?? null; // confermato - rifiutato;


    if ($azione == 'conferma' && CheckSaldoAcquirente($id_transazione)) {
        UpdateTransazione($id_transazione, $azione);
    } else if ($azione == 'rifiuta') {
        UpdateTransazione($id_transazione, $azione);
    }
}