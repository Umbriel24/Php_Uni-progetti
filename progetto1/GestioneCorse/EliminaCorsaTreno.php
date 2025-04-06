<?php
require_once __DIR__ . '/../CartellaDB/database.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniCarrozze.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniStazione.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniSubtratta.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniTreno.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniConvoglio.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniLocomotrice.php';

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





?>
