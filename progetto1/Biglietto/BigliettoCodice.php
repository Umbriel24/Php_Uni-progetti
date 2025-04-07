<?php
require_once __DIR__ . '/../CartellaDB/database.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniCarrozze.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniStazione.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniSubtratta.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniTreno.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniConvoglio.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniLocomotrice.php';
require_once __DIR__ . '/../CartellaFunzioni/FunzioniTratta.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_stazione_partenza = $_POST["id_stazione_partenza"];
    $id_stazione_arrivo = $_POST["id_stazione_arrivo"];
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css">
    <title>Società ferrovie Turistiche - SFT</title>

</head>
<body>
<div class="container">
<?php
try {
    IniziaTransazione();

    $trenoIndividuato = CheckEsistenzaTratta($id_stazione_partenza, $id_stazione_arrivo);
    if($trenoIndividuato == 0){
        echo 'Non esiste nessun treno con quelle fermate.';
        return;
    }


    $convoglio_id = getConvoglioById_Treno($trenoIndividuato);
    $bigliettiTotali = getPostiASedereFromConvoglio($convoglio_id);

    if($bigliettiTotali > 0){

        echo 'Ci sono biglietti disponibili. ';
        echo '<br>';
        echo 'Il prezzo è di : ' . round(CalcolaKmTotaliSubtratta($id_stazione_partenza, $id_stazione_arrivo) * 0.10, 1) . '€';
        echo '<br>';
        echo '<br>';
        echo '<button>Acquista biglietto con PayStream</button>';
    }

    CommittaTransazione();

} catch (Exception $e){
    RollbackTransazione();
    die("Errore nel trovare un treno: " . $e->getMessage());
}
?>
</div>
</body>
</html>