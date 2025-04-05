<?php
require_once __DIR__ . '/../CartellaDB/database.php';

//CARROZZE
function getCarrozzeByAttivita($attivita)
{
    $query = "SELECT * FROM progetto1_Carrozza WHERE in_attività = '$attivita'";
    return EseguiQuery($query);
}

function getCarrozzeByIdConvoglioAssociato($id_convoglio)
{
    $query = "SELECT codice_carrozza, posti_a_sedere from progetto1_Carrozza where id_convoglio = $id_convoglio";
    return EseguiQuery($query);
}

function stampaCarrozzeInattive($carrozeInattive)
{
    if ($carrozeInattive != null && $carrozeInattive->RecordCount() > 0) {
        echo '<table>';
        echo '<tr><th>ID</th><th>Numero di serie</th><th>Posti</th></tr>';

        //corrisponde al foreach di c#
        while ($row = $carrozeInattive->FetchRow()) {
            echo '<tr>';
            echo '<td>' . $row['codice_carrozza'] . '</td>';
            echo '<td>' . $row['numero_di_serie'] . '</td>';
            echo '<td>' . $row['posti_a_sedere'] . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo '<div class="alert">Nessuna convoglio inattivo.</div>';
    }
}
//---------




//LOCOMOTRICI
function getLocomotriceByAttivita($attivita)
{
    $query = "SELECT * FROM progetto1_ComposizioneLocomotrice as c
            LEFT JOIN progetto1_Locomotiva as l on c.riferimentoLocomotiva = l.id_locomotrice
            LEFT JOIN progetto1_Automotrice as a on c.riferimentoAutomotiva = a.id_automotrice
            WHERE in_attività = '$attivita'";
    return EseguiQuery($query);
}

function getId_locomotrice_By_Codice($codice_locomotrice){

    $query = "SELECT * FROM progetto1_ComposizioneLocomotrice as c
            LEFT JOIN progetto1_Locomotiva as l on c.riferimentoLocomotiva = l.id_locomotrice
            LEFT JOIN progetto1_Automotrice as a on c.riferimentoAutomotiva = a.id_automotrice";

    $result = EseguiQuery($query); //sono 5 record (ne sono sicuro)


    //Chiedo scusa all'umanità, penso sia il codice più brutto abbia mai scritto
    while($row = $result->FetchRow()){
        if($row['codice_locomotiva'] == $codice_locomotrice){
                return $row["id_locomotrice"];

        } else if ($row["codice_automotrice"] == $codice_locomotrice){
                return $row["Id_locomotrice"]; //Colonna con lo stesso nome, la mette in mauscolo...
        }

    }
    Throw new Exception("Errore, locomotrice non trovata con quel codice");
}

function getLocomotriceBy_ref_locomotrice($ref_locomotrice)
{
    $query = "SELECT * FROM progetto1_ComposizioneLocomotrice as c
            LEFT JOIN progetto1_Locomotiva as l on c.riferimentoLocomotiva = l.id_locomotrice
            LEFT JOIN progetto1_Automotrice as a on c.riferimentoAutomotiva = a.id_automotrice
            WHERE c.id_locomotrice = $ref_locomotrice";
    $result = EseguiQuery($query);

    while ($row = $result->FetchRow()){
        if ($row['codice_locomotiva'] != null)
        {
            return $row['codice_locomotiva'];
        } else return $row['codice_automotrice'];

    }


}

function stampaLocomotriciInattive($locomotriciInattive)
{
    if ($locomotriciInattive != null && $locomotriciInattive->RecordCount() > 0) {

        echo '<table>';
        echo '<tr><th>ID</th><th>Numero di serie</th><th>Posti</th></tr>';

        while ($row = $locomotriciInattive->FetchRow()) {
            if ($row['id_locomotrice'] != null) {
                //Locomotiva senza posti
                echo '<tr>';
                echo '<td>' . $row['codice_locomotiva'] . '</td>';
                echo '<td>' . $row['nome'] . '</td>';
                echo '<td>' . '0' . '</td>';
                echo '</tr>';
            } else if ($row['id_automotrice'] != null) {
                echo '<tr>';
                echo '<td>' . $row['codice_automotrice'] . '</td>';
                echo '<td>' . 'N/A' . '</td>';
                echo '<td>' . $row['posti_a_sedere'] . '</td>';
                echo '</tr>';
            }
        }
    }
}
//---------


//CONVOGLI
function getConvogliCreati()
{
    $query = "SELECT * FROM progetto1_Convoglio";
    return EseguiQuery($query);
}


function StampaConvogliCreati()
{
    $ConvogliList = getConvogliCreati();

    /*
    while ($row = $ConvogliList->FetchRow()) {
        foreach ($row as $key => $value) {
            echo $key . ": " . $value . "\n";
        }
    }
    return;
    */


    echo '<table>';
    echo '<tr><th>ID Convoglio </th><th>Locomotrice</th><th>Posti a sedere</th><th>Carrozze usate</th><th>Data/Ora creazione</th></tr>';
    while ($row = $ConvogliList->FetchRow()) {
        $id_temp = $row["id_convoglio"];
        $locomotrice = getlocomotriceBy_ref_locomotrice($row['id_ref_locomotiva']);
        $dataOraTemp = $row['data_ora_creazione'];
        $tempListCarrozze = getCarrozzeByIdConvoglioAssociato($id_temp);

        $posti_a_sedere_temp = 0;
        $codici_carrozze = "";

        //Fare in modo che convoglio abbia posti a sedere totali che verranno sottratti dai biglietti
        if($locomotrice == 'AN56.2' || $locomotrice == 'AN56.4') $posti_a_sedere_temp += 56;
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
//---------







