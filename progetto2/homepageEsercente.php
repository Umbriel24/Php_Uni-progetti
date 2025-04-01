<?php
require 'ComandiSQL/Sql_GetQuery.php';


if(!isset($_SESSION['id_utente']) && !isset($_SESSION['nome'])){
    //Se non è loggato l'utente ritorna al login.
    header('Location: index.php');
    exit;
}

?>

<body>
<p>Stai in homepage Esercente</p>
</body>


