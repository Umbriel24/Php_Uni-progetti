<?php
require_once __DIR__ . '/../CartellaDB/database.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniCarrozze.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniStazione.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniSubtratta.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniTreno.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniConvoglio.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniLocomotrice.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniTratta.php';

function CreaBigliettoDaiDati($prezzo, $id_rif_utente, $id_convoglio) {


    try {
        IniziaTransazione();
        $posto_biglietto = UpdataPostiConvoglio($id_convoglio);
        $id_rif_treno = getIdTrenoFromConvoglioRef($id_convoglio);

        Insert_progetto1_Biglietto($posto_biglietto, $prezzo, $id_rif_utente, $id_rif_treno);

        CommittaTransazione();
    } catch (Exception $e){
        RollbackTransazione();
        die("Errore, impossibile creare biglietto " . $e->getMessage());
    }



}

function Insert_progetto1_Biglietto($posto_biglietto, $prezzo, $id_rif_utente, $id_rif_treno) {

    $query = "INSERT INTO progetto1_Biglietto(posto_biglietto, prezzo, id_rif_utente, id_rif_treno) 
    VALUES($posto_biglietto, $prezzo, $id_rif_utente, $id_rif_treno)";

    EseguiQuery($query);
}

function UpdataPostiConvoglio($id_convoglioT){

    $query = "SELECT * FROM progetto1_Convoglio c
    JOIN progetto1_Treno t on c.id_convoglio = t.id_ref_convoglio 
    WHERE c.id_convoglio = $id_convoglioT";

    $result = EseguiQuery($query);
    if($result->RecordCount() == 0){
        Throw new Exception("Errore: Convoglio non trovato con il treno");
    }

    $row = $result->FetchRow();
    $posti_a_sedere = $row['posti_a_sedere'];
    $id_convoglio = $row['id_convoglio'];

    $posti_a_sedere -= 1;

    $query2 = "UPDATE progetto1_Convoglio SET posti_a_sedere =  $posti_a_sedere WHERE id_convoglio = $id_convoglio";
    EseguiQuery($query2);

    $posti_a_sedere += 1;
    return $posti_a_sedere;

}
?>

