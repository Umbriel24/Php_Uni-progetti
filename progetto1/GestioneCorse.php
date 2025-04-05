<?php
require 'ComandiSQL/sqlConvoglio.php';
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
    <a href="esercizio.php">Gestione esercizio</a>
    <a href="GestioneCorse.php">Gestione Corse</a>
    <a href="index.php">Esci</a>
</nav>
<h1>Gestione Corse</h1>
<section>
    <h3>Convogli creati liberi</h3>
    <?php StampaConvogliCreati(); ?>
</section>

<section>
    <h3>Treni con corsa programmata</h3>
    <p>IN CORSO</p>
</section>

<div class="container">
    <section>
        <h3>Crea un treno da un convoglio libero</h3>
        <form method="POST" action="GestioneCorse/CodiceGestioneCorse.php">
            <label> Id Convoglio
                <input type="number" name="id_convoglio" required>
            </label>
            <br>
            <label> ID Stazione di partenza
                <input type="number" name="id_stazione_partenza" required>
            </label>

            <label> ID Stazione di arrivo
                <input type="number" name="id_stazione_arrivo" required>
            </label>
            <br>
            <label> Orario di partenza
                <input type="datetime-local" name="dataOra_partenza" required>
            </label>

            <label> Orario di arrivo
                <input type="datetime-local" name="dataOra_arrivo" required>
            </label>
            <button type="submit">Conferma</button>
        </form>
    </section>
    <div>
        <p>Lista Stazioni</p>
        <ol>
            <li>Torre Spaventa km 0,000</li>
            <li>Prato Terra km 2,700</li>
            <li>Rocca Pietrosa km 7.580</li>
            <li>Villa Pietrosa km 12,680</li>
            <li>Villa Santa Maria km 16,900</li>
            <li>Pietra Santa Maria km 23,950</li>
            <li>Castro Marino km 31,500</li>
            <li>Porto spigola km 39,500</li>
            <li>Porto San Felice km 46,000</li>
            <li>Villa San Felice km 54,680</li>
        </ol>
    </div>
</div>


</body>
