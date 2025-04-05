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

function CalcolaPercorsoSubTratte($id_treno, $id_staz_partenza, $id_staz_arrivo, $_dataOra_part)
{
    $_dataOra_part = RendiDateTimeCompatibile($_dataOra_part);
    //Qua inizia la prima subtratta
    $dataOra_partenzaSubtratta = $_dataOra_part;


    //otteniamo quelle intermedie
    $query = "SELECT * from progetto1_Stazione 
         where id_stazione BETWEEN  $id_staz_partenza AND $id_staz_arrivo
         ORDER BY id_stazione ASC;";
    $resultStazioni = EseguiQuery($query);

    $i = 0;

    echo $query . ' E la prima query ' ;

    while ($row = $resultStazioni->fetchRow()) {
        echo $row['id_stazione'] . " In confronto con " . $id_staz_arrivo;
        echo '<br>' ;
        echo $i;
        echo '<br>' ;
        $i++;
        if ($row['id_stazione'] == $id_staz_arrivo) {
            //e' LA STAZIONE FINALE, NON DOBBIAMO CALCOLARE NULLA
            continue;
        } else {
            $id_stazione_partenzaSUBTRATTA = $row['id_stazione'];
            $id_stazione_arrivoSUBTRATTA = $row['id_stazione'] + 1;

            $kmTotaliSUBTRATTA = CalcolaKmTotaliSubtratta($id_stazione_partenzaSUBTRATTA, $id_stazione_arrivoSUBTRATTA);
            $dataOra_arrivoSUBTRATTA = CalcolaTempoArrivoSubtratta($dataOra_partenzaSubtratta, $kmTotaliSUBTRATTA);

            $id_rif_treno = $id_treno;

            echo 'Arriva al rigo 66';

            $querySubtratta = "INSERT INTO progetto1_Subtratta(
                                km_totali, ora_di_partenza, ora_di_arrivo, id_rif_treno, 
                                id_stazione_partenza, id_stazione_arrivo)
            VALUES($kmTotaliSUBTRATTA, '$dataOra_partenzaSubtratta', '$dataOra_arrivoSUBTRATTA', 
            $id_rif_treno, $id_stazione_partenzaSUBTRATTA, $id_stazione_arrivoSUBTRATTA)";
            echo 'Arriva al rigo 76';

            //L'arrivo diventa orario di andata della prossima subtratta.

            $resultQueryInserimento = EseguiQuery($querySubtratta);
            echo 'Arriva al rigo 80';
            if(!$resultQueryInserimento) {
                Throw new Exception("Errore nella query rigo 77: " . $querySubtratta . '\n');
            }

            //SI SUPPONE IL TRENO STIA FERMO 2 MINUTI IN STAZIONE
            echo 'Arriva al rigo 89';
            $dataOra_partenzaSubtratta = date("y-m-d H:i:s", strtotime($dataOra_arrivoSUBTRATTA . ' +2 minutes'));
            echo 'Arriva al rigo 91';
        }

    }
}

function CalcolaKmTotaliSubtratta($id_staz_part, $id_stazione_arr){


    $query = "SELECT ABS(s4.km - s3.km) as kmSubtratta FROM progetto1_Stazione s3, progetto1_Stazione s4 
                                        WHERE s3.id_stazione = $id_staz_part AND s4.id_stazione = $id_stazione_arr";


    $result = EseguiQuery($query);
    $row = $result->fetchRow();
    if($row['kmSubtratta'] <= 0 || $row['kmSubtratta'] == null){
        Throw new Exception("Errore nel calcolo dei km totali. I km calcolati sono: " . $row['SUM(km)']);
    } else return $row['kmSubtratta'];
}
function CalcolaTempoArrivoSubtratta($dataOra_Partenza, $kmTotaliSubtratta){
    //Il treno va a 50km/h. Sono circa 13,9 m/s.
    $V_kmh = 50;
    $oreTotali = $kmTotaliSubtratta / $V_kmh;
    $secondiTotali = round($oreTotali * 3600);

    try {
        $data_Partenza = new DateTime($dataOra_Partenza);
        $interval = new DateInterval("PT{$secondiTotali}S");

        $data_Partenza->add($interval);

        $data_Partenza->setTime(
            $data_Partenza->format('H'),
            $data_Partenza->format('i'),
            0  // Secondi a zero
        );

        return $data_Partenza->format('y-m-d H:i:s');
    } catch (Exception $e) {
        die("Errore nel calcolo del tempo . " . $e->getMessage() . "\n");
    }



}