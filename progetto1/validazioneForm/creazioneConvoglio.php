<?php
require __DIR__ . '/../CartellaDB/database.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $locomotrice = $_POST["locomotrice"] ?? null;
    $carrozze = $_POST["carrozze"] ?? null;

    //validazione
    if(empty($locomotrice)){
        die("Errore: Nessuna locomotrice selezionata");
    }

    if(empty($carrozze)){
        die("Errore: Nessuna carrozza selezionata. Selezionane almeno 1");
    }

    checkLocomotriceInattivaByCodice($locomotrice);
    echo $locomotrice;
}

function checkLocomotriceInattivaByCodice($codice)
{
    $query = "SELECT * FROM progetto1_ComposizioneLocomotrice as c
            LEFT JOIN progetto1_Locomotiva as l on c.riferimentoLocomotiva = l.id_locomotrice
            LEFT JOIN progetto1_Automotrice as a on c.riferimentoAutomotiva = a.id_automotrice";

     $result = EseguiQuery($query);

     while($row = $result->FetchRow()){
         if($row['codice_locomotiva'] == $codice || $row["codice_automotrice"]){
             //La row è quella
             if($row['in_attività'] == 'si') die("Errore: Locomotrice già attiva");
         }
     }

}
?>