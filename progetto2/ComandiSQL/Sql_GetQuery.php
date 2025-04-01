<?php
require __DIR__ .  '/../CartellaDB/database.php';

function getMovimentiEsercente($id_conto_esercente)
{
    //tramite id utente prendi id conto in contocorrente
    //tramite id contocorrente stampo tutti i movimenti in id conto esercente
    return "SELECT * FROM progetto2_Transazione WHERE id_conto_esercente = $id_conto_esercente";
}

function getMovimentiAcquirente($id_conto_acquirente)
{
    return  "SELECT * FROM progetto2_Transazione WHERE id_conto_acquirente = $id_conto_acquirente";
}

function getIdContoByIdUtente($id_utente)
{
    $query =  "SELECT id_contocorrente FROM progetto2_ContoCorrente WHERE id_utente = $id_utente";
    $risultato = EseguiQuery($query);
    return $risultato->fields['id_contocorrente'];
}
function getSaldoById($id_utente)
{
    $query =  "SELECT saldo from progetto2_ContoCorrente WHERE id_utente = $id_utente";
    $risultato = EseguiQuery($query);
    return $risultato->fields['saldo'];
}

function getTuplaUtenteByEmail($email)
{
    return "SELECT * FROM progetto2_Utente WHERE email = $email";
}

