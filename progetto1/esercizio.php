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

</head>
<body>
<div class="container">
    <h1>Account backoffice esercizio</h1>
    <div>
        <p> Carrozze libere:</p>
        <?php stampaCarrozzeInattive(getCarrozzeByAttivita('No'));?>
    </div>
</div>
<div class="container">
    <div>
    <?php stampaLocomotriciInattive(getLocomotriceByAttivita('No'));?>
    </div>
</div>
<div class="container">
    <div>
        <p>Ecco i convogli wfwfwf:</p>
        <?php StampaConvogliCreati();?>
    </div>
</div>


</body>
</html>