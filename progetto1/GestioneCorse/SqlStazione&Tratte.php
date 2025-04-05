<?php
require_once __DIR__ . '/../CartellaDB/database.php';
require_once __DIR__ . '/../ComandiSQL/sqlConvoglio.php';

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
    $query = "SELECT km from progetto1_stazione WHERE id_stazione = $id_stazione";
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

function CalcolaPercorsoSubTratte($id_treno, $id_staz_partenza, $id_staz_arrivo, $_dataOra_part, $_dataOra_arri)
{
    //Qua inizia la prima subtratta
    $dataOra_partenzaSubtratta = $_dataOra_part;


    //otteniamo quelle intermedie
    $query = "SELECT * from progetto1_Stazione where id_stazione <= $id_staz_arrivo && id_stazione >= $id_staz_partenza ORDER BY id_stazione ASC;";
    $result = EseguiQuery($query);

    while ($row = $result->fetchRow()) {
        if ($row['id_stazione'] == $id_staz_arrivo) {
            //e' LA STAZIONE FINALE, NON DOBBIAMO CALCOLARE NULLA
        } else {
            $kmTotali = CalcolaKmTotaliSubtratta($row['id_stazione'], $row['id_stazione'] + 1);
            $dataOra_arrivo = "";
            $id_rif_treno = $id_treno;
            $id_stazione_partenzaSUBTRATTA = $row['id_stazione'];
            $id_stazione_arrivoSUBTRATTA = $row['id_stazione'] + 1;

            $querySubtratta = "INSERT INTO progetto1_Subtratta(km_totali, ora_di_partenza, ora_di_arrivo, id_rif_treno, id_stazione_partenza, id_stazione_arrivo)
VALUES($kmTotali, $dataOra_partenzaSubtratta, $dataOra_arrivo, 
       $id_rif_treno, $id_stazione_partenzaSUBTRATTA, $id_stazione_arrivoSUBTRATTA)";

        }

    }



}

function CalcolaKmTotaliSubtratta($id_staz_part, $id_stazione_arr){
    $query = "SELECT SUM(km) FROM progetto1_Stazione where id_stazione <= $id_stazione_arr && id_stazione >= $id_staz_part";
    $result = EseguiQuery($query);
    $row = $result->fetchRow();
    if($row['SUM(km)'] <= 0 || $row['SUM(km)'] == null){
        Throw new Exception("Errore nel calcolo dei km totali. I km calcolati sono: " . $row['SUM(km)']);
    } else return $row['SUM(km)'];
}
function CalcolaTempoArrivoSubtratta(){
    //Il treno va a 50km/h. Sono circa 13,9 m/s.
}