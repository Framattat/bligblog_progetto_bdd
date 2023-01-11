<?php

//funzione che setta l'abbonamento all'abbonamento gratis default del sito
function abbonamento_default(){
    $db = new db();
    $db->update("abbonamenti_utenti",["id_abbonamento"=>10,"data_fine_abbonamento" => date("Y-m-d H:i:s",strtotime("+1 year"))],["id_utente_abbonamento"=>$_SESSION['id_utente']]);

}
//funzione che controlla l'abbbonamento attivo dell'utente loggato
function abbonamento_attivo($ritorna_nome = false){
    if (!utente_loggato()){
        return false;
    } 
    $db = new db();
    $abbonamento = $db->get_one("SELECT id_abbonamento,data_fine_abbonamento FROM abbonamenti_utenti WHERE id_utente_abbonamento = $_SESSION[id_utente] AND data_fine_abbonamento > NOW()");
    if (!$db->errore){
        // se l'utente non ha abbonamento setto abbonamento default come suo abbonamento attivo
        if (is_null($abbonamento)) return abbonamento_default();
        // se ho questo parametro prendo il nome dell'abbonamento e la scadenza che ho in base all'id_abbonamento che viene passato dalla query precedente
        if($ritorna_nome){
            $tipo_abbonamento = $db->select_one("abbonamenti",["id"=>$abbonamento["id_abbonamento"]]);
            $dati_abbonamento = [
                "nome_abbonamento"=>$tipo_abbonamento["nome_abbonamento"],
                "scadenza"=>date("d/m/Y",strtotime($abbonamento["data_fine_abbonamento"]))
            ];
            return $dati_abbonamento;
        }
        return $abbonamento["id_abbonamento"];
    } else {
        logg("error", "C'è stato un errore con la verifica dell'abbonamento attivo dell'utente: ".$db->errore);      
        return abbonamento_default();
    }
}

//funzione per la popolazione dinamica di sottocategorie, in base a quante sottocategorie passo come parametro
function popola_sottocategorie($sottocategorie){
    $db = new db();
    $nome_sottocategorie = [];
    foreach(explode(",",$sottocategorie) as $k=>$value){
        $nome = $db->get_one("SELECT nome_sottocategoria FROM sottocategorie WHERE id = ? ",[$value]);
        echo ("<strong>•".$nome["nome_sottocategoria"]." </strong>"); 
    }
}
?>