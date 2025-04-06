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
    <title>Società ferrovie Turistiche - Account esercizio SFT</title>

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
<nav>
    <a href="PaginaEsercizio.php">Gestione esercizio</a>
    <a href="PaginaGestioneCorse.php">Gestione Corse</a>
    <a href="index.php">Esci</a>
</nav>
<h1>Gestione Corse</h1>
<section>
    <h3>Convogli creati liberi</h3>
    <?php StampaConvogliLiberi(); ?>
</section>

<section>
    <h3>Convogli in attività</h3>
    <?php StampaConvogliInAttivita(); ?>
</section>

<br>
<div>
    <section>
        <h3>Treni - Orario - Partenza e arrivo</h3>
        <?php StampaTreniInCorsa() ?>
    </section>
</div>


<div class="container">
<h3>Suggerisci dei treni all'esercizio</h3>
</div>
</body>
</html>
