<?php


require_once __DIR__ . '/CartellaFunzioni/FunzioniCarrozze.php';
require_once __DIR__ . '/CartellaFunzioni/FunzioniStazione.php';
require_once __DIR__ . '/CartellaFunzioni/FunzioniSubtratta.php';
require_once __DIR__ . '/CartellaFunzioni/FunzioniTreno.php';
require_once __DIR__ . '/CartellaFunzioni/FunzioniConvoglio.php';
require_once __DIR__ . '/CartellaFunzioni/FunzioniLocomotrice.php';
?>

<!DOCTYPE html>
<html lang="it">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css">
        <title>Prenota biglietto</title>

        <style>
            .container {
                display: grid;
                grid-template-columns: repeat(2, 1fr); /* 2 colonne di uguale larghezza */
                gap: 20px; /* Spazio tra gli elementi */
                padding: 20px; /* Spaziatura interna */
            }

            /* Stile opzionale per gli elementi figli */
            .container > * {
                background-color: #f5f5f5; /* Colore di sfondo */
                padding: 15px; /* Spaziatura interna elementi */
                border-radius: 8px; /* Bordi arrotondati */
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Ombreggiatura leggera */
            }
        </style>
    </head>


<body>
<h2>Prenota biglietto</h2>

<div class="container">
    <section>

        <form method="POST" action="Biglietto/BigliettoCodice.php">
            <label>Inserisci id stazione di partenza
                <input type="number" name="id_stazione_partenza" required>
            </label>
            <br>
            <label>Inserisci id stazione di arrivo
                <input type="number" name="id_stazione_arrivo" required>
            </label>
            <br>
            <button type="submit">Cerca treno</button>
        </form>

    </section>
    <section>
        <?php StampaListaStazioni(); ?>
    </section>
</div>


<div>
    <section>
        <h3>Lista tutte le  corse:</h3>
        <?php StampaTreniInCorsaPerIClienti() ?>
    </section>
</div>

</body>


</html>
