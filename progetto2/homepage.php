<?php
require 'ComandiSQL/Sql_GetQuery.php';


if(!isset($_SESSION['id_utente']) && !isset($_SESSION['nome'])){
    //Se non è loggato l'utente ritorna al login. si evita quindi che cambia pagina in autonomia tramite url? credo...
    header('Location: index.php');
    exit;
}

//printa il saldo
$saldo = getSaldoById($_SESSION['id_utente']);
//printaMovimenti

//Ottieni id conto da id utente

$id_contoCorrente = getIdContoByIdUtente($_SESSION['id_utente']);

//Ottieni movimenti da conto
$queryMovimenti = getMovimentiInAttesa(getidcontoByIdUtente($_SESSION['id_utente']));


?>

<h1>Benvenuto alla homepage, <?php echo $_SESSION['id_utente']; ?></h1>
<p>Se vuoi uscire dalla sessione <a href="login.php">Clicca qui</a> </p>

<p>Ecco i tuoi dati <br> Saldo: <?php echo $saldo ?>€</p>
<p>Ciao. Il tuo id conto corrente è:  <?php echo $id_contoCorrente ?></p>
<div>
    <?php
    if($queryMovimenti != null && $queryMovimenti->RecordCount() > 0){
        echo '<table>';
        echo '<tr><th>ID</th><th>Importo</th><th>Data</th><th>Stato</th></tr>';

        while ($row = $queryMovimenti->FetchRow()) {
            echo '<tr>';
            echo '<td>' . $row['id_transazione'] . '</td>';
            echo '<td>' . number_format($row['importo'], 2) . ' €</td>';
            echo '<td>' . date('d/m/Y H:i', strtotime($row['data_e_ora'])) . '</td>';
            echo '<td>' . $row['esito_transazione'] . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo '<div class="alert">Nessuna transazione in attesa</div>';
    }
    ?>
</div>
