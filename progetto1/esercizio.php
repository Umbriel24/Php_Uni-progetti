<?php
require 'ComandiSQL/creazioneConvoglio.php';
//Compone e scompone convogli
//Costruisce le corse con le tratte e orari.
//Ogni corsa può essere modificata o cancellata.

?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css">
    <title>Società ferrovie Turistiche - Account esercizio SFT</title>

    <style>
            /* Stile per allineare il testo sopra il checkbox */
        .checkbox-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .checkbox-container label {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .checkbox-container input {
            margin-bottom: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h1>Account backoffice esercizio</h1>
<section>
    <p> Carrozze libere:</p>
    <?php stampaCarrozzeInattive(getCarrozzeByAttivita('No')); ?>
</section>

<section>
    <p>Locomotrici libere</p>
    <?php stampaLocomotriciInattive(getLocomotriceByAttivita('No')); ?>
</section>

<section>
    <?php StampaConvogliCreati(); ?>
    <p>Fine lista convogli creati liberi</p>
</section>

<br>
<section>
    <h2>Crea convoglio</h2>
    <p>Regole: <br>Una locomotrice senza posti deve avere carrozze. <br>
        Una locomotrice con posti può viaggiare da sola </p>


    <form method="POST" action="">
        <div>
            <h3>Seleziona Locomotrice:</h3>
            <div class="radio-container">
                <!-- Radio button statici (Dinamici è da pazzi, chiedo venia. non so bene ne js ne php) -->
                <label>
                    <input type="radio" name="tipo_selezionato" value="AN56.2">
                    AN56.2
                </label>

                <label>
                    <input type="radio" name="tipo_selezionato" value="AN56.4">
                    AN56.4
                </label>

                <label>
                    <input type="radio" name="tipo_selezionato" value="SFT.3">
                    SFT.3
                </label>

                <label>
                    <input type="radio" name="tipo_selezionato" value="SFT.4">
                    SFT.4
                </label>

                <label>
                    <input type="radio" name="tipo_selezionato" value="SFT.6">
                    SFT.6
                </label>
            </div>
        </div>

        <h3>Seleziona carrozze:</h3>
        <div class="checkbox-container">
            <label>
                <div>B1</div>
                <input type="checkbox" name="carrozze[]" value="B1">
            </label>
            <label>
                <div>B2</div>
                <input type="checkbox" name="carrozze[]" value="B2">
            </label>
            <label>
                <div>B3</div>
                <input type="checkbox" name="carrozze[]" value="B3">
            </label>
            <label>
                <div>C12</div>
                <input type="checkbox" name="carrozze[]" value="C12">
            </label>
            <label>
                <div>C6</div>
                <input type="checkbox" name="carrozze[]" value="C6">
            </label>
            <label>
                <div>C9</div>
                <input type="checkbox" name="carrozze[]" value="C9">
            </label>
            <label>
                <div>CD1</div>
                <input type="checkbox" name="carrozze[]" value="CD1">
            </label>
            <label>
                <div>CD2</div>
                <input type="checkbox" name="carrozze[]" value="CD2">
            </label>
        </div>
        <button type="submit">Crea il convoglio</button>
    </form>

</section>
</body>
</html>