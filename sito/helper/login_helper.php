<?php

//funzione per controllare se l'utente è loggato
function utente_loggato(){
    return isset($_SESSION["utente_loggato"]);
}
//funzione per controllare se l'utente loggato è un admin
function utente_loggato_admin(){
    if (utente_loggato() && isset($_SESSION["admin"]) && $_SESSION["admin"]==1) return true;
    return false;
}
//funzione che setta l'abbonamento per un utente registrato a quello di default
function abbonamento_registrazione($id){
    $dati_default = [
        "id_abbonamento"=>10,
        "id_utente_abbonamento"=>$id,
        "data_fine_abbonamento"=>date("Y-m-d H:i:s", strtotime("+0 year"))
    ];
    $db = new db();
    $db->insert("abbonamenti_utenti",$dati_default);
}

?>