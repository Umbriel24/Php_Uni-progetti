<?php
require __DIR__ . '/../CartellaDB/database.php';
require_once __DIR__ . '/../ComandiSQL/sqlConvoglio.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $locomotrice = $_POST["locomotrice"] ?? null;
    $carrozze = $_POST["carrozze"] ?? [];

    //validazione
    if(empty($locomotrice)){
        die("Errore: Nessuna locomotrice selezionata");
    }

    if(empty($carrozze)){
        die("Errore: Nessuna carrozza selezionata. Selezionane almeno 1");
    }

    //Check locomotrice inattiva
    checkLocomotriceInattivaByCodice($locomotrice);

    //Check carrozze inattive
    for($i = 0; $i < count($carrozze); $i++){
        CheckCarrozzaAttività($carrozze[$i]);
    }

    //Se arriva qui vuol dire che è stato validato ogni dato
    echo 'Convoglio valido alla creazione';

    //In ordine
    // Comp.Locomotrice -> in_attività diventa si
    UpdateAttivitàLocomotrice($locomotrice);

    // Aggiungiamo new entry in Convoglio con id_ref_locomotiva precedentemente trovata.
    CreazioneConvoglio($locomotrice);

    $id_locomotrice = getId_locomotrice_By_Codice($locomotrice);
    $id_ref_locomotiva = $id_locomotrice;

    // Ogni singola carrozza viene associata all'id Convoglio
    for($i = 0; $i < count($carrozze); $i++){
        //Updateid_convoglio_Di_Carrozza($carrozze[$i], $id_ref_locomotiva);
        UpdateAttivitàCarrozza('si', $carrozze[$i]);
    }

    //Io ho codice_locomotrice. poso prenderne l'id
}

function checkLocomotriceInattivaByCodice($codice)
{
    $query = "SELECT * FROM progetto1_ComposizioneLocomotrice as c
            LEFT JOIN progetto1_Locomotiva as l on c.riferimentoLocomotiva = l.id_locomotrice
            LEFT JOIN progetto1_Automotrice as a on c.riferimentoAutomotiva = a.id_automotrice";

     $result = EseguiQuery($query);

     while($row = $result->FetchRow()){
         if($row['codice_locomotiva'] == $codice || $row["codice_automotrice"] == $codice){
             //La row è quella
             if($row['in_attività'] == 'si') die("Errore:" . $codice . " Locomotrice già attiva");
         }
     }
}

function CheckCarrozzaAttività($id_carrozza){
    $query = "SELECT * FROM progetto1_Carrozza WHERE codice_carrozza = '$id_carrozza'";
    $result = EseguiQuery($query);
    while($row = $result->FetchRow()){
        if($row['in_attività'] == 'si') die("Errore:  " . $id_carrozza. " Carrozza attiva. Smantella questo convoglio o scegli un'altra carrozza");
    }
}

function Updateid_convoglio_Di_Carrozza($codice_carrozza, $id_convoglio){
    $query = "UPDATE progetto1_Carrozza SET id_convoglio = $id_convoglio WHERE codice_carrozza = '$codice_carrozza'";
    return EseguiQuery($query);

}

function UpdateAttivitàCarrozza($attivita, $id_carrozza)
{
    if($attivita != 'si' && $attivita != 'no') die('Attività settata non consentita.');
    $query = "UPDATE progetto1_Carrozza set in_attività = '$attivita' WHERE codice_carrozza = '$id_carrozza'";
    return EseguiQuery($query);
}

function UpdateAttivitàLocomotrice($codice_locomotrice)
{
    $id_da_updatare = getId_locomotrice_By_Codice($codice_locomotrice);
    if($id_da_updatare != 0){
        $query2 = "UPDATE progetto1_ComposizioneLocomotrice SET in_attività = 'si' WHERE id_locomotrice = $id_da_updatare";
        return EseguiQuery($query2);
    } else die("Errore: Nessuna locomotrice selezionata per l'update.");
}

function CreazioneConvoglio($codice_locomotrice){
    //parte 1: Insert
    $id_locomotrice = getId_locomotrice_By_Codice($codice_locomotrice);

    //Mi sono appena accorto che il fuso orario è sbagliato. porta 2 ore indietro, quindi CET (credo)
    $query = "INSERT INTO progetto1_Convoglio(id_ref_locomotiva, data_ora_creazione) VALUES($id_locomotrice, NOW())";
    EseguiQuery($query);

}
?>