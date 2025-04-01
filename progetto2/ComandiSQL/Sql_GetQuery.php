<?php
require __DIR__ .  '/../CartellaDB/database.php';

//GET QUERY
function getMovimentiInAttesa($id_contoCorrente)
{
    $query = "SELECT * FROM progetto2_Transazione WHERE id_conto_acquirente = $id_contoCorrente && esito_transazione = 'in attesa'";
    $risultato = EseguiQuery($query);
    return $risultato;
}

function getMovimentiConfermati($id_contoCorrente){
    $query = "SELECT * FROM progetto2_Transazione WHERE id_conto_acquirente = $id_contoCorrente && esito_transazione = 'confermata'";
    $risultato = EseguiQuery($query);
    return $risultato;
}

function getMovimentiRifiutati($id_contoCorrente)
{
    $query = "SELECT * FROM progetto2_Transazione WHERE id_conto_acquirente = $id_contoCorrente && esito_transazione = 'rifiutata'";
    $risultato = EseguiQuery($query);
    return $risultato;
}

function getIdContoByIdUtente($id_utente)
{
    $query =  "SELECT id_contocorrente FROM progetto2_ContoCorrente WHERE id_utente = $id_utente";
    $risultato = EseguiQuery($query);
    return $risultato->fields['id_contocorrente'];
}
function getSaldoById($id_utente)
{
    $query =  "SELECT saldo from progetto2_ContoCorrente WHERE id_utente = $id_utente";
    $risultato = EseguiQuery($query);
    return $risultato->fields['saldo'];
}

function getUtenteByEmail($email)
{
    $query =  "SELECT * FROM progetto2_Utente WHERE email = $email";
    $risultato = EseguiQuery($query);
    return $risultato->fields['utente'];
}

function getRowUtenteById($email){
    $query = "SELECT * FROM progetto2_Utente WHERE email = '$email'";
    $risultato = EseguiQuery($query);

    if ($risultato && !$risultato->EOF) {
        return $risultato;
    }
    return false;
}

// POST QUERY
function RegistraUtente($nome, $email, $password, $tipo_utente, $codice_fiscale = null, $partita_iva = null){
    //in utente
    $query = "INSERT INTO progetto2_Utente (nome, email, password) VALUES('$nome', '$email', '$password')";
    $risultato = EseguiQuery($query);

    if(!$risultato) return false;

    //Prendiamo l'id.
    $id_utente = getConnessioneDb()->Insert_ID();

    //Registriamo in base al ruolo
    if($tipo_utente == 'acquirente' && !empty($codice_fiscale)) {
        $query2 = "INSERT INTO progetto2_Acquirente (id_acquirente, codice_fiscale) VALUES ($id_utente, '$codice_fiscale')";
    } else if ($tipo_utente == 'esercente' && !empty($partita_iva)) {
        $query2 = "INSERT INTO progetto2_Esercente(id_esercente, partita_iva) VALUES ($id_utente, '$partita_iva')";
    } else {
        return false;
    }
    return EseguiQuery($query2);
}

//Function Count
function Verifica_UtenteEsercente($id_utente): bool
{
    $query = "
    select count(*) as conteggio
    from progetto2_Utente as U
    join progetto2_Esercente as E 
    on U.id_utente = E.id_esercente
    WHERE U.id_utente = $id_utente";

    $risultato = EseguiQuery($query);

    if ($risultato && !$risultato->EOF) {
        $row = $risultato->FetchRow();
        return ($row['conteggio'] > 0);
    }
    return false;
}