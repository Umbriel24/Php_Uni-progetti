<?php
include __DIR__ . '/../../config.php';
include  __DIR__ . '/../ADOdb-5.22.8/adodb.inc.php';

//Staticamente otteniamo il $db

if(!function_exists('getConnessioneDb')){



    date_default_timezone_set('Europe/Rome');

    function getConnessioneDb(){

        static $db = null;

        if($db === null){
            $driver = 'mysqli';
            $db = newAdoConnection($driver);


            $db->connect('db', 'um.gargiulo', 'gU0zHwf3', 'um_gargiulo');

            if (!$db->isconnected()) {
                echo "Errore di connessione al database: " . $db->ErrorMsg();
                exit;
            }
        }
        return $db;
    }
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
    try {


    $db = getConnessioneDb();

    $risultato = $db->Execute($query);
    if (!$risultato) {
        Throw new Exception("Errore nella query: " . $db->ErrorMsg());
    }
    return $risultato;

    } catch (Exception $e) {
        echo $e->getMessage();
        die();
    }
}