<?php
require_once __DIR__ . '/../CartellaDB/database.php';
require_once __DIR__ . '/../CartellaDB/database.php';
require_once __DIR__ . '/FunzioniCarrozze.php';
require_once __DIR__ . '/FunzioniStazione.php';
require_once __DIR__ . '/FunzioniSubtratta.php';
require_once __DIR__ . '/FunzioniTreno.php';
require_once __DIR__ . '/FunzioniConvoglio.php';
require_once __DIR__ . '/FunzioniLocomotrice.php';

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


?>