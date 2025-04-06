<?php
require_once __DIR__ . '/../CartellaDB/database.php';
require_once __DIR__ . '/FunzioniCarrozze.php';
require_once __DIR__ . '/FunzioniStazione.php';
require_once __DIR__ . '/FunzioniSubtratta.php';
require_once __DIR__ . '/FunzioniTreno.php';
require_once __DIR__ . '/FunzioniConvoglio.php';
require_once __DIR__ . '/FunzioniLocomotrice.php';
function getNomeStazioneFromId($id_stazione)
{
    $query = "SELECT nome FROM progetto1_Stazione WHERE id_stazione = $id_stazione";
    $result = EseguiQuery($query);
    $nomeArray = $result->FetchRow();
    if ($nomeArray == null) {
        throw new exception("Errore. Nome stazione non trovato tramite ID");
    } else return $nomeArray["nome"];
}

function getIdStazionefromNome($nome_stazione)
{
    $query = "SELECT id_stazione FROM progetto1_Stazione WHERE nome = '$nome_stazione'";
    $result = EseguiQuery($query);
    $idStazioneArray = $result->FetchRow();
    if ($idStazioneArray == null) {
        throw new exception("Errore. Nome stazione non trovato tramite ID");
    } else return $idStazioneArray["id_stazione"];
}

function getKmStazioneFromId($id_stazione)
{
    $query = "SELECT km from progetto1_Stazione WHERE id_stazione = $id_stazione";
    $result = EseguiQuery($query);
    $kmArray = $result->FetchRow();
    if ($kmArray == null) {
        throw new Exception("Km non trovati della stazione tramite ID");
    } else return $kmArray["km"];
}

function CalcolaDistanzaTotalePercorsa($id_stazione_partenza, $id_stazione_arrivo)
{
    $km1 = getKmStazioneFromId($id_stazione_partenza);
    $km2 = getKmStazioneFromId($id_stazione_arrivo);

    return $distanza = abs($km1 - $km2);

}


?>
