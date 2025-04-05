<?php
require_once __DIR__ . '/../CartellaDB/database.php';
require_once __DIR__ . '/../ComandiSQL/sqlConvoglio.php';
require_once __DIR__ . '/SqlStazione&Tratte.php';
require_once __DIR__ . '/FunzioniTreno.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_treno = $_POST["id_treno"];

    try {
        IniziaTransazione();

        EliminaCorsaSubtrattaByIdTreno($id_treno);


        EliminaTreno($id_treno);
        CommittaTransazione();

    } catch (exception $e) {
        RollbackTransazione();
        die("Errore nella query: " . $e->getMessage());
    }

}

function EliminaTreno($id_treno)
{
    $query = "DELETE FROM progetto1_Treno WHERE id_treno = $id_treno";


    $result = EseguiQuery($query);
    if (!$result) {
        throw new Exception("Errore nella query: " . $query);
    } else return $result;
}

function EliminaCorsaSubtrattaByIdTreno($id_treno)
{

    $query = "DELETE FROM progetto1_Subtratta WHERE id_rif_treno = $id_treno";
    $result = EseguiQuery($query);
    if (!$result) {
        throw new Exception("Errore nella query: " . $query . " Impossibile eliminare le corse subtratta");
    } else return $result;
}

?>
