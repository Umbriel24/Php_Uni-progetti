<?php
session_start();
require 'dbAccess.php';
include  __DIR__ . '/../ADOdb-5.22.8/adodb.inc.php';


//Staticamente otteniamo il $db
function getConnessioneDb(){
    static $db = null;

    if($db === null){
        $driver = 'mysqli';
        $db = newAdoConnection($driver);

        global $argUsername, $argPassword, $argDatabase;
        $db->connect('localhost', $argUsername, $argPassword, $argDatabase);

        if (!$db->isconnected()) {
            echo "Errore di connessione al database: " . $db->ErrorMsg();
            exit;
        }
    }
    return $db;
}

function EseguiQueryConParametri($query, $parametri = []){
    $db = getConnessioneDb();

    $risultato = $db->Execute($query, $parametri);
    if (!$risultato) {
        die("Errore nella query: " . $db->ErrorMsg());
    }
    return $risultato;
}

function EseguiQuery( $query){
    $db = getConnessioneDb();

    $risultato = $db->Execute($query);
    if (!$risultato) {
        die("Errore nella query: " . $db->ErrorMsg());
    }
    return $risultato;
}