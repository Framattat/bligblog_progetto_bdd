<?php 
//carico il modello degli articoli, mi servirà per usare una funzione che cancella i file immagini
require_once("models/articolo_model.php");

class blog_model{
    private $tabella_blog = "blog";
    //funzione che inserisce un blog
    function inserire_blog($dati_insert){
        $db = new db();
        $db->insert($this->tabella_blog, $dati_insert);
        $esito = $db->errore;
        return $esito;
    }
    //funzione che cancella un blog, cancello anche i file immagini inerenti al blog
    function cancellare_blog($id_utente,$id_blog){
        $db = new db();
        $articolo = new articolo_model();
        $articolo->cancella_immagini_articolo($id_utente,null,$id_blog,true);
        $db->delete($this->tabella_blog,["id" => $id_blog]);
        $esito = $db->errore;
        return $esito;
    }
    //funzione per la modifica di un blog, in base all'id passato
    function modifica_blog($dati_update,$id_blog){
        $db = new db();
        $db->update($this->tabella_blog,$dati_update,["id"=>$id_blog]);
        $esito = $db->errore;
        return $esito;
    }
    //funzione usata per popolare i blog, non dovrebbe servire ma è rimasta per sicurezza
    function popola_blog(){
        $db = new db();
        $blog= $db->select_one($this->tabella_blog,["id_utente"=>$_SESSION["id_utente"]]);
        return $blog;
    }
    // funzione che prende i dati di un blog, in base al id passato, se full è true, si cerca nella view blog_full
    function recupera_blog($id_blog, $full=false){
        $db = new db();
        $blog = $db->select_one($this->tabella_blog.($full?"_full":""),["id"=>$id_blog]);
        return $blog;
    }
    // funzione che controlla se il blog appartiene all'utente e ritorna un esito (valore booleano)
    function check_blog($id_blog,$id_utente){
        $db = new db();
        $esito = $db->select_one($this->tabella_blog,["id_utente"=>$id_utente,"id"=>$id_blog]);
        if (!empty($esito)){
            return true;
        } else {
            return false;
        }
    }
    //funzione per il popolamento dati dell'archivio
    function popola_archivio($id_blog) {
        $db = new db();
        // query per il recupero di al massimo 10 dati
        $query = "  SELECT id, data_pubblicazione_articolo 
                    FROM articoli_full 
                    WHERE data_pubblicazione_articolo >= '2021-01-01' AND pubblicato = 1 AND id_blog = $id_blog
                    ORDER BY data_pubblicazione_articolo DESC
                    LIMIT 10";
        $articoli = $db->get($query);
        $lista = [] ;
        // per ogni dato dalla query costruisco una lista con date per ogni articolo
        foreach($articoli as $k=>$v){
            $data = date("Y-m",strtotime($v['data_pubblicazione_articolo']));
            if(!isset($lista[$data]))
                $lista[$data] = [];
            $lista[$data][] = $v['id'];
        }
        return $lista;
    }
}

?>