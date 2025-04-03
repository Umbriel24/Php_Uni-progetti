<?php
require __DIR__ . '/../CartellaDB/database.php';

//GET
function getCarrozzeByAttivita($attivita)
{
    $query = "SELECT * FROM progetto1_Carrozza WHERE in_attività = '$attivita'";
    return EseguiQuery($query);
}

function getLocomotriceByAttivita($attivita)
{
    $query = "SELECT * FROM progetto1_ComposizioneLocomotrice as c
            LEFT JOIN progetto1_Locomotiva as l on c.riferimentoLocomotiva = l.id_locomotrice
            LEFT JOIN progetto1_Automotrice as a on c.riferimentoAutomotiva = a.id_automotrice
            WHERE in_attività = '$attivita'";
    return EseguiQuery($query);
}


//Funzioni echo
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

function stampaLocomotriciInattive($locomotriciInattive){
    if ($locomotriciInattive != null && $locomotriciInattive->RecordCount() > 0) {
        echo '<table>';
        echo '<tr><th>ID</th><th>Numero di serie</th><th>Posti</th></tr>';

        while ($row = $locomotriciInattive->FetchRow()) {
            if($row['id_locomotrice'] != null){
                //Locomotiva senza posti
                echo '<tr>';
                echo '<td>' . $row['codice_Locomotiva'] . '</td>';
                echo '<td>' . $row['nome'] . '</td>';
                echo '<td>' . '0' . '</td>';
                echo '</tr>';
            } else if ($row['id_automotrice'] != null){
                echo '<tr>';
                echo '<td>' . $row['codice_automotrice'] . '</td>';
                echo '<td>' .'N/A' . '</td>';
                echo '<td>' . $row['posti_a_sedere'] . '</td>';
                echo '</tr>';
            }
        }
    }
}

