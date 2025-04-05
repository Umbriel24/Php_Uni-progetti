<?php
session_start();
require_once 'dbAccess.php';
include  __DIR__ . '/../ADOdb-5.22.8/adodb.inc.php';

//Fuso orario sia benedetta sta funzione
date_default_timezone_set('Europe/Rome');

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

function IniziaTransazione(){
    $db = getConnessioneDb();
    $db->BeginTrans();
}

function CommittaTransazione()
{
    $db = getConnessioneDb();
    if (!$db->CommitTrans()) {
        throw new Exception("Eccezione trovata. " . $db->ErrorMsg());
    }
}

function RollbackTransazione()
{
 $db = getConnessioneDb();
 $db->rollbackTrans();
}
function EseguiQueryConParametri($query, $parametri = []){
    $db = getConnessioneDb();

    $risultato = $db->Execute($query, $parametri);
    if (!$risultato) {
        Throw new Exception("Errore nella query: " . $db->ErrorMsg());
    }
    return $risultato;
}

function EseguiQuery( $query){
    $db = getConnessioneDb();

    $risultato = $db->Execute($query);
    if (!$risultato) {
        Throw new Exception("Errore nella query: " . $db->ErrorMsg());
    }
    return $risultato;
}