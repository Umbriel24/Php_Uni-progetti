﻿<?php
require __DIR__ . '/../CartellaDB/database.php';
require_once __DIR__ . '/../ComandiSQL/sqlConvoglio.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_convoglio = $_POST["id_convoglio"] ?? null;

    echo $id_convoglio;

    //Inizia transazione
    try {
        IniziaTransazione();
        echo 'Qui 1';
        // Check se l'id del convoglio esiste.
        if (!CheckEsistenzaConvoglio($id_convoglio)) {
            throw new Exception('Convoglio non trovato');
        }

        echo 'Qui 2';
        // Ogni carrozza collegata -> in_attività = no e id_convoglio = NULL
        ResettaCarrozzaTramite_IdConvoglio($id_convoglio);
        echo 'Qui 3';
        // ComposizioneLocomotrice -> in_attività = no.
        ResetAttivita_CompLocomotrice_ByIdConvoglio($id_convoglio);
        echo 'Qui 4';
        // Elimiamo la tupla in Convoglio.
        EliminaTuplaConvoglioById($id_convoglio);
        echo 'Qui 5';
        CommittaTransazione();
        echo 'Transazione effettuata con successo';

    } catch (Exception $e) {
        RollbackTransazione();
        die("Errore nella query: " . $e->getMessage());
    }

}

function CheckEsistenzaConvoglio($id_convoglio)
{
    $query = "SELECT * FROM progetto1_Convoglio where id_convoglio = $id_convoglio";
    $result = EseguiQuery($query);

    if ($result && !$result->EOF) {
        return true;
    } else throw new Exception("Convoglio non trovato");
}

function ResettaCarrozzaTramite_IdConvoglio($id_convoglio)
{
    $query = "UPDATE progetto1_Carrozza
    SET in_attività = 'no', id_convoglio = null 
    WHERE id_convoglio = $id_convoglio";

    EseguiQuery($query);

}

function ResetAttivita_CompLocomotrice_ByIdConvoglio($id_convoglio)
{
    $query1 = "SELECT * FROM progetto1_Convoglio where id_convoglio = $id_convoglio";
    $result = EseguiQuery($query1);
    $row = $result->FetchRow();

    $id_composizioneLocomotrice = $row['id_ref_locomotiva']; // E' un numero da 1 a 5, indica le 5 locomotive possibili
    if ($id_composizioneLocomotrice == "") {
        die('ComposizioneLocomotrice in riferimento al convoglio non trovata');
    }

    $query2 = "UPDATE progetto1_ComposizioneLocomotrice SET in_attività = 'no' WHERE id_locomotrice = $id_composizioneLocomotrice";
    $result2 = EseguiQuery($query2);
    if ($result2) {
        return true;
    } else throw new Exception('Query update Comp.Locomotrice non riuscita.');
}

function EliminaTuplaConvoglioById($id_convoglio)
{
    $query = "DELETE FROM progetto1_Convoglio WHERE id_convoglio = $id_convoglio";
    $result = EseguiQuery($query);

    if ($result) {
        return true;
    } else throw new Exception("Eliminazione convoglio non riuscita.");
}

?>


