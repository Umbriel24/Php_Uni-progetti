<?php
require_once __DIR__ . '/../CartellaDB/database.php';
require_once __DIR__ . '/../ComandiSQL/sqlConvoglio.php';

function StampaTreniInCorsa()
{

    echo '<table>';
    echo '<tr><th>Id Treno </th><th>Convoglio riferimento</th><th>Ora di partenza</th><th>Ora di arrivo</th><th>Stazione di partenza</th><th>Stazione di arrivo</th></tr>';

    $query = "SELECT * FROM progetto1_Treno";
    $result = EseguiQuery($query);
    while ($row = $result->FetchRow()) {
        $id_treno = $row["id_treno"];
        $Convoglio_rif = $row['id_ref_convoglio'];
        $Ora_partenza = $row['ora_di_partenza'];
        $Ora_arrivo = $row['ora_di_arrivo'];
        $stazione_partenza = $row['nome_stazione_partenza'];
        $stazione_arrivo = $row['nome_stazione_arrivo'];

        echo '<tr>';
        echo '<td>' . $id_treno . '</td>';
        echo '<td>' . $Convoglio_rif . '</td>';
        echo '<td>' . $Ora_partenza . '</td>';
        echo '<td>' . $Ora_arrivo . '</td>';
        echo '<td>' . $stazione_partenza . '</td>';
        echo '<td>' . $stazione_arrivo . '</td>';
        echo '</tr>';
    }

    echo '</table>';

}

function CalcolaArrivoByTempoPartenzaEKMTotali($OraPartenza, $kmTotali)
{
    //Ricordando che va a 50km/h
    $v_kmh = 50;

    $oraTotalePercorrenza = $kmTotali / $v_kmh;
    $secondiTotali = round($oraTotalePercorrenza * 3600);

    try {
        $data_Partenza = new Datetime($OraPartenza);
        $interval = new DateInterval("PT{$secondiTotali}S");

        $data_Arrivo = $data_Partenza->add($interval);

        $data_Arrivo->setTime(
            $data_Arrivo->format('H'),
            $data_Arrivo->format('i'),
            0
        );
        return $data_Arrivo->format('y-m-d H:i:s');
    } catch (Exception $e) {
        die("Errore nel calcolo del tempo di arrivo " . $e->getMessage());
    }
}

function getIdTrenoFromConvoglioRef($id_convoglio)
{
    $query = "SELECT id_treno from progetto1_Treno where id_ref_convoglio = $id_convoglio";
    $result = EseguiQuery($query);
    $resultArray = $result->FetchRow();
    if (!$resultArray) {
        throw new Exception("Errore nella query: Treno non trovato tramite id_convoglio");
    } else return $resultArray["id_treno"];
}

?>

