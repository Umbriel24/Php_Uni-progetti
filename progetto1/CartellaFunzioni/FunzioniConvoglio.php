<?php
require_once __DIR__ . '/../CartellaDB/database.php';
require_once __DIR__ . '/FunzioniCarrozze.php';
require_once __DIR__ . '/FunzioniStazione.php';
require_once __DIR__ . '/FunzioniSubtratta.php';
require_once __DIR__ . '/FunzioniTreno.php';
require_once __DIR__ . '/FunzioniConvoglio.php';
require_once __DIR__ . '/FunzioniLocomotrice.php';
function getConvogliCreati()
{
    $query = "SELECT * FROM progetto1_Convoglio";
    return EseguiQuery($query);
}

function StampaConvogliLiberi()
{
    $ConvogliList = getConvogliCreati();

    echo '<table>';
    echo '<tr><th>ID Convoglio </th><th>Locomotrice</th><th>Posti a sedere</th><th>Carrozze usate</th><th>Data/Ora creazione</th></tr>';
    while ($row = $ConvogliList->FetchRow()) {

        $id_temp = $row["id_convoglio"];

        if (CheckConvoglioAttivita($id_temp) == true) {
            continue;
        }

        $locomotrice = getlocomotriceBy_ref_locomotrice($row['id_ref_locomotiva']);
        $dataOraTemp = $row['data_ora_creazione'];
        $tempListCarrozze = getCarrozzeByIdConvoglioAssociato($id_temp);

        $posti_a_sedere_temp = 0;
        $codici_carrozze = "";

        //Fare in modo che convoglio abbia posti a sedere totali che verranno sottratti dai biglietti
        if ($locomotrice == 'AN56.2' || $locomotrice == 'AN56.4') $posti_a_sedere_temp += 56;
        while ($row2 = $tempListCarrozze->FetchRow()) {
            //Abbiamo ogni carrozza associata all'id convoglio qui
            $posti_a_sedere_temp += $row2["posti_a_sedere"];
            $codici_carrozze .= $row2["codice_carrozza"] . ", ";
        }

        echo '<tr>';
        echo '<td>' . $id_temp . '</td>';
        echo '<td>' . $locomotrice . '</td>';
        echo '<td>' . $posti_a_sedere_temp . '</td>';
        echo '<td>' . $codici_carrozze . '</td>';
        echo '<td>' . $dataOraTemp . '</td>';
        echo '</tr>';

    }
    echo '</table>';
}

function StampaConvogliInAttivita()
{
    $ConvogliList = getConvogliCreati();

    echo '<table>';
    echo '<tr><th>ID Convoglio </th><th>Locomotrice</th><th>Posti a sedere</th><th>Carrozze usate</th><th>Data/Ora creazione</th></tr>';
    while ($row = $ConvogliList->FetchRow()) {
        $id_temp = $row["id_convoglio"];

        if (CheckConvoglioAttivita($id_temp) == false) {
            continue;
        }

        $locomotrice = getlocomotriceBy_ref_locomotrice($row['id_ref_locomotiva']);
        $dataOraTemp = $row['data_ora_creazione'];
        $tempListCarrozze = getCarrozzeByIdConvoglioAssociato($id_temp);

        $posti_a_sedere_temp = 0;
        $codici_carrozze = "";

        //Fare in modo che convoglio abbia posti a sedere totali che verranno sottratti dai biglietti
        if ($locomotrice == 'AN56.2' || $locomotrice == 'AN56.4') $posti_a_sedere_temp += 56;
        while ($row2 = $tempListCarrozze->FetchRow()) {
            //Abbiamo ogni carrozza associata all'id convoglio qui
            $posti_a_sedere_temp += $row2["posti_a_sedere"];
            $codici_carrozze .= $row2["codice_carrozza"] . ", ";
        }

        echo '<tr>';
        echo '<td>' . $id_temp . '</td>';
        echo '<td>' . $locomotrice . '</td>';
        echo '<td>' . $posti_a_sedere_temp . '</td>';
        echo '<td>' . $codici_carrozze . '</td>';
        echo '<td>' . $dataOraTemp . '</td>';
        echo '</tr>';

    }
    echo '</table>';
}

function CheckConvoglioAttivita($id_convoglio)
{
    $query = "SELECT * FROM progetto1_Treno t
LEFT JOIN progetto1_Convoglio c on c.id_convoglio  = t.id_ref_convoglio";

    $result = EseguiQuery($query);
    while ($row = $result->FetchRow()) {
        if ($row['id_ref_convoglio'] == $id_convoglio) {
            //Quel convoglio è in attività in un treno
            return true;
        }
    }
    return false;
}
//---------

?>
