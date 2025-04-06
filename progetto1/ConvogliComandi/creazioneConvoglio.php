<?php
require __DIR__ . '/../CartellaDB/database.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniCarrozze.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniStazione.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniSubtratta.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniTreno.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniConvoglio.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniLocomotrice.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $locomotrice = $_POST["locomotrice"] ?? null;
    $carrozze = $_POST["carrozze"] ?? [];


    try{
        IniziaTransazione();
        if(empty($locomotrice)){
            Throw new Exception("Errore: Nessuna locomotrice selezionata");
        }
        if(empty($carrozze)){
            if($locomotrice != 'AN56.2' && $locomotrice != 'AN56.4')
            {
                Throw new Exception("Errore: Nessuna carrozza selezionata. Selezionane almeno 1 - " . $locomotrice);
            }
        }

        //Check locomotrice inattiva
        checkLocomotriceInattivaByCodice($locomotrice);

        //Check carrozze inattive
        if(!empty($carrozze)){
            for($i = 0; $i < count($carrozze); $i++){
                CheckCarrozzaAttività($carrozze[$i]);
            }
            //Se arriva qui vuol dire che è stato validato ogni dato
            echo 'Convoglio valido alla creazione';
        }




        //In ordine
        // Comp.Locomotrice -> in_attività diventa si
        UpdateAttivitàLocomotrice($locomotrice);
        echo 'Attività locomotice updatata nel db';

        // Aggiungiamo new entry in Convoglio con id_ref_locomotiva precedentemente trovata.
        CreazioneConvoglio($locomotrice);

        echo 'Convoglio creato nel db';

        $id_locomotrice = getId_locomotrice_By_Codice($locomotrice);
        $id_ref_locomotiva = $id_locomotrice;

        if(!empty($carrozze)){
            // Ogni singola carrozza viene associata all'id Convoglio
            for($i = 0; $i < count($carrozze); $i++){

                echo '<br>';
                echo $carrozze[$i];
                echo '<br>';
                echo $id_ref_locomotiva;
                echo '<br>';

                $id_temp = Convoglio_getIdconvoglio_By_refLocomotiva($id_ref_locomotiva);
                $convoglio_row = $id_temp->FetchRow();
                $convoglio_id = $convoglio_row['id_convoglio'];
                echo '<br>';
                Updateid_convoglio_Di_Carrozza($carrozze[$i], $convoglio_id);
                UpdateAttivitàCarrozza('si', $carrozze[$i]); //QUA
            }
            echo 'Attività carrozze updatate nel db';
            echo '<br>';
            sleep(1);
        }
        CommittaTransazione();
        echo 'Creazione convoglio con update nei table correttamente effettuate';


    } catch (Exception $e){
        RollbackTransazione();
        die("Errore nella creazione convoglio " . $e->getMessage());
    }
    //validazione







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
             if($row['in_attività'] == 'si') Throw new Exception("Errore:" . $codice . " Locomotrice già attiva");
         }
     }
}

function CheckCarrozzaAttività($id_carrozza){
    $query = "SELECT * FROM progetto1_Carrozza WHERE codice_carrozza = '$id_carrozza'";
    $result = EseguiQuery($query);
    while($row = $result->FetchRow()){
        if($row['in_attività'] == 'si') Throw new Exception("Errore:  " . $id_carrozza. " Carrozza attiva. Smantella questo convoglio o scegli un'altra carrozza");
    }
}

function Updateid_convoglio_Di_Carrozza($codice_carrozza, $id_convoglio){
    //codice_carrozza = CD2
    //Id_convoglio = 3

    $query = "UPDATE progetto1_Carrozza 
            SET id_convoglio = $id_convoglio 
            WHERE codice_carrozza = '$codice_carrozza'";

    echo $query;
    return EseguiQuery($query);

}

function UpdateAttivitàCarrozza($attivita, $id_carrozza)
{
    echo '<br>' . $id_carrozza . ' Problema QUI';
    if($attivita != 'si' && $attivita != 'no') die('Attività settata non consentita.');
    $query = "UPDATE progetto1_Carrozza SET in_attività = '$attivita' WHERE codice_carrozza = '$id_carrozza'";
    return EseguiQuery($query);
}

function UpdateAttivitàLocomotrice($codice_locomotrice)
{
    $id_da_updatare = getId_locomotrice_By_Codice($codice_locomotrice);
    if($id_da_updatare != 0){
        $query2 = "UPDATE progetto1_ComposizioneLocomotrice SET in_attività = 'si' WHERE id_locomotrice = $id_da_updatare";
        return EseguiQuery($query2);
    } else Throw new Exception("Errore: Nessuna locomotrice selezionata per l'update. " . $id_da_updatare . " E' l'id da updatare e " . $codice_locomotrice . " ");
}

function CreazioneConvoglio($codice_locomotrice){
    //parte 1: Insert
    $id_locomotrice = getId_locomotrice_By_Codice($codice_locomotrice);

    //Mi sono appena accorto che il fuso orario è sbagliato. porta 2 ore indietro, quindi CET (credo)
    $query = "INSERT INTO progetto1_Convoglio(id_ref_locomotiva, data_ora_creazione) VALUES($id_locomotrice, NOW())";
    EseguiQuery($query);
}

function Convoglio_getIdconvoglio_By_refLocomotiva($id_ref_locomotiva){
    $query = "SELECT * FROM progetto1_Convoglio WHERE id_ref_locomotiva = $id_ref_locomotiva";
    echo $query;
    echo "<br>";
    return EseguiQuery($query);
}
?>