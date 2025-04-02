<?php
require __DIR__ .  '/../CartellaDB/database.php';

function getRowUtenteById($email){
    $query = "SELECT * FROM progetto1_Utente WHERE email = '$email'";
    $risultato = EseguiQuery($query);

    if ($risultato && !$risultato->EOF) {
        return $risultato;
    }
    return false;
}

function RegistraUtente($nome, $email, $password)
{
    $query = "INSERT INTO progetto1_Utente (nome, email, password) VALUES('$nome', '$email', '$password')";
    $risultato = EseguiQuery($query);

    if(!$risultato) return false;
    return true;

}