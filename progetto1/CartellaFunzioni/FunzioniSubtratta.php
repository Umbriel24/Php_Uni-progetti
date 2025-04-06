<?php
require_once __DIR__ . '/../CartellaDB/database.php';
require_once __DIR__ . '/FunzioniCarrozze.php';
require_once __DIR__ . '/FunzioniStazione.php';
require_once __DIR__ . '/FunzioniSubtratta.php';
require_once __DIR__ . '/FunzioniTreno.php';
require_once __DIR__ . '/FunzioniConvoglio.php';
require_once __DIR__ . '/FunzioniLocomotrice.php';

function CalcolaPercorsoSubTratte($id_treno, $id_staz_partenza, $id_staz_arrivo, $_dataOra_part)
{


    $_dataOra_part = RendiDateTimeCompatibile($_dataOra_part);
    //Qua inizia la prima subtratta
    $dataOra_partenzaSubtratta = $_dataOra_part;


    if ($id_staz_partenza <= 0 || $id_staz_arrivo <= 1 || $id_staz_partenza == $id_staz_arrivo
        || $id_staz_partenza > 9 || $id_staz_arrivo > 10) {
        throw new Exception("Impossibile creare percorso con le seguenti stazioni");
    }

    if ($id_staz_partenza < $id_staz_arrivo) {

        //otteniamo quelle intermedie
        $query = "SELECT * from progetto1_Stazione 
         where id_stazione BETWEEN  $id_staz_partenza AND $id_staz_arrivo
         ORDER BY id_stazione ASC;";
        $resultStazioni = EseguiQuery($query);


        while ($row = $resultStazioni->fetchRow()) {


            if ($row['id_stazione'] == $id_staz_arrivo) {
                //e' LA STAZIONE FINALE, NON DOBBIAMO CALCOLARE NULLA
                continue;
            }

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
            if (!$resultQueryInserimento) {
                throw new Exception("Errore nella query rigo 77: " . $querySubtratta . '\n');
            }

            //SI SUPPONE IL TRENO STIA FERMO 2 MINUTI IN STAZIONE
            echo 'Arriva al rigo 89';
            $dataOra_partenzaSubtratta = date("y-m-d H:i:s", strtotime($dataOra_arrivoSUBTRATTA . ' +2 minutes'));
            echo 'Arriva al rigo 91';

        }

    } else if ($id_staz_partenza > $id_staz_arrivo) {


        //otteniamo quelle intermedie
        $query = "SELECT * from progetto1_Stazione 
         where id_stazione BETWEEN  $id_staz_arrivo  AND $id_staz_partenza
         ORDER BY id_stazione DESC";
        echo $query;
        $resultStazioni = EseguiQuery($query);


        while ($row = $resultStazioni->fetchRow()) {
            echo $row['id_stazione'] . ' E la prima stazione';
            if ($row['id_stazione'] == $id_staz_arrivo) {
                continue;
            }

            $id_stazione_partenzaSUBTRATTA = $row['id_stazione'];
            $id_stazione_arrivoSUBTRATTA = $row['id_stazione'] - 1;

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
            if (!$resultQueryInserimento) {
                throw new Exception("Errore nella query rigo 77: " . $querySubtratta . '\n');
            }

            //SI SUPPONE IL TRENO STIA FERMO 2 MINUTI IN STAZIONE
            echo 'Arriva al rigo 89';
            $dataOra_partenzaSubtratta = date("y-m-d H:i:s", strtotime($dataOra_arrivoSUBTRATTA . ' +2 minutes'));
            echo 'Arriva al rigo 91';
        }
    }
}

function CalcolaKmTotaliSubtratta($id_staz_part, $id_stazione_arr)
{


    $query = "SELECT ABS(s4.km - s3.km) as kmSubtratta FROM progetto1_Stazione s3, progetto1_Stazione s4 
                                        WHERE s3.id_stazione = $id_staz_part AND s4.id_stazione = $id_stazione_arr";


    $result = EseguiQuery($query);
    $row = $result->fetchRow();
    if ($row['kmSubtratta'] <= 0 || $row['kmSubtratta'] == null) {
        throw new Exception("Errore nel calcolo dei km totali. I km calcolati sono: " . $row['SUM(km)']);
    } else return $row['kmSubtratta'];
}

function CalcolaTempoArrivoSubtratta($dataOra_Partenza, $kmTotaliSubtratta)
{
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

function EliminaCorsaSubtrattaByIdTreno($id_treno)
{

    $query = "DELETE FROM progetto1_Subtratta WHERE id_rif_treno = $id_treno";
    $result = EseguiQuery($query);
    if (!$result) {
        throw new Exception("Errore nella query: " . $query . " Impossibile eliminare le corse subtratta");
    } else return $result;
}

function CheckEsistenzaSubtrattaByIdTreno($id_treno)
{
    $query = "SELECT * FROM progetto1_Subtratta WHERE id_rif_treno = $id_treno";
    $result = EseguiQuery($query);
    if (!$result) {
        throw new Exception("Non esistono percorsi del treno specificato. ");
    } else return true;
}

?>
