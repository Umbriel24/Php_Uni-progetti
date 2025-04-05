<?php
require_once __DIR__ . '/../CartellaDB/database.php';
require_once __DIR__ . '/../ComandiSQL/sqlConvoglio.php';

function getNomeStazioneFromId($id_stazione){
    $query = "SELECT nome FROM progetto1_Stazione WHERE id_stazione = $id_stazione";
    $result = EseguiQuery($query);
    $nomeArray = $result->FetchRow();
    if($nomeArray == null){
        Throw new exception("Errore. Nome stazione non trovato tramite ID");
    } else return $nomeArray["nome"];
}

function getIdStazionefromNome($nome_stazione)
{
    $query = "SELECT id_stazione FROM progetto1_Stazione WHERE nome = '$nome_stazione'";
    $result = EseguiQuery($query);
    $idStazioneArray = $result->FetchRow();
    if($idStazioneArray == null){
        Throw new exception("Errore. Nome stazione non trovato tramite ID");
    } else return $idStazioneArray["id_stazione"];
}