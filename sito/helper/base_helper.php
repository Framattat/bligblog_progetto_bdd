<?php
//funzione per gli alert sparsi nel sito, il primo parametro è il messaggio da presentare, il secondo il colore 
function alert($messaggio="",$colore="danger"){
    //$colore default rosso, setto varie parole così da poter scriverle senza avere errori in caso sbagliassi
    if(in_array($colore,["rosso","red","danger"])) $colore = "danger";
    if(in_array($colore,["verde","ok","success"])) $colore = "success";
    if(in_array($colore,["giallo","avviso","warning"])) $colore = "warning";
    if(in_array($colore,["grigio","disabilitato","disabled"])) $colore = "secondary";

    if(isset($_GET['success'])) $colore = "success"; //verde
    if(isset($_GET['warning'])) $colore = "warning"; //giallo
    if(isset($_GET['disabled']) || isset($_GET['grigio'])) $colore = "secondary"; //grigio

    echo '
        <div class="alert alert-'.$colore.' alert-dismissible fade show" role="alert">
            '.$messaggio.'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    ';
}
//funzione personale per creare log, crea un file nuovo se non presente o modifica uno se esistente
function logg($livello, $messaggio){
    
    $livello = strtoupper($livello);
    if(!is_string($messaggio)) $messaggio = json_encode($messaggio); 
    $log = date("Y-m-d H:i:s")." - $livello => $messaggio".PHP_EOL ;
    $dir = ROOTPATH."/logs/";
    $path = $dir."log-".date("Y-m-d").".log";
    file_put_contents($path,$log,FILE_APPEND);
}
//funzione per la formattazione delle date
function formatta_data($data){
    return date("d/m/Y H:i",strtotime($data));
}
//funzione che controlla se un utente è autore nel blog passato (può essere coautore), se id utente null controlla l'utente corrente
function check_autore($id_blog,$id_utente=null){
    if (is_null($id_utente)){
        if (utente_loggato()){
            $id_utente = $_SESSION['id_utente'];
        } else {
            return false;
        }
    }
    $db = new db();
    $query = ("SELECT blog.id FROM blog LEFT JOIN autori ON autori.id_blog = blog.id WHERE (autori.id_utente = $id_utente OR blog.id_utente = $id_utente) AND blog.id = $id_blog LIMIT 1");
    $check = $db->get($query);
    if (!sizeof($check))
        return false;
    return true;
}
//funzione per controllare i possibili errori dati dalle query del db
function esito($esito,$url_true, $url_false){
    if(!$esito){
        redirect($url_true);
    } else {
        redirect($url_false);
    }
}

?>