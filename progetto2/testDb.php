<?php
require 'dbAccess.php';
include 'ADOdb-5.22.8/adodb.inc.php';


$driver = 'mysqli';
$db = newAdoConnection($driver);

$db->connect('localhost', $argUsername, $argPassword, $argDatabase);
if($db->isConnected()) {
    echo "Connessione riuscita";
} else {
    echo "Errore di connessionee";
}
?>