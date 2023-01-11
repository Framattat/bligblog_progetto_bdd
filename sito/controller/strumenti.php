<?php

// controller per testare le funzionalità del sito

require_once("libraries/template.php");

class strumenti {
    // funzione per testare query e connessione al database
    function dbtest(){
        $db = new db();
        $data = $db->query("SELECT * FROM utenti WHERE id=?",["2"]);
        var_dump ($data);
        $db->close();
    }
    //funzione per la query di cancellazione dati
    function cancellatest(){
        $db = new db();
        $data = $db->delete("blog",["id"=>1,"id_blog"=>2,"id_cc"=>5]);
    }
    // funzione per testare i template
    function testrandom(){
        $id= 3;
        $template = new template();
        $dati = $template->use_template($id);
    }
}

?>