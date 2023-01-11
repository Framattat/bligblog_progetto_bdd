<?php 

class articolo_model{
    private $tabella_blog = "articoli";
    //funzione che crea un articolo, se ci sono file presenti carica anch'essi (immagini articolo o copertina)
    function crea_articolo($dati_insert, $files=null){
        $db = new db();
        if(is_null($files)){
            $db->insert("articoli",$dati_insert);
        } else {
            $dati_insert = $this->inserimento_immagini_articolo($files,$dati_insert);
            $db->insert("articoli",$dati_insert);
        }
        $esito = $db->errore;
        return $esito;
    }
    //funzione che cancella l'articolo e le immagini inerenti ad esso
    function cancella_articolo($id_utente,$id_articolo){
        $db = new db();
        $this->cancella_immagini_articolo($id_utente,$id_articolo);
        $db->delete("articoli",["id" => $id_articolo]);
        $esito = $db->errore;
        return $esito;
    }
    // funzione per la modifica di un articolo, posso avere dei file da modificare
    function modifica_articolo($dati_where, $dati_update,$files=null){
        $db = new db();
        if(is_null($files)){
            $db->update("articoli",$dati_update,$dati_where);
        } else {
            $dati_update = $this->modifica_immagini_articolo($files,$dati_update,$dati_where["id"]);
            $db->update("articoli",$dati_update,$dati_where);
        }
        $esito = $db->errore;
        return $esito;
    }
    // funzione per l'inserimento dei file immagine degli articoli
    function inserimento_immagini_articolo($files,$dati_insert){
        // controllo file per file se il file esiste continuo, muovo il file nella cartella e metto il nome dell'immagine nella tabella
        foreach($files as $k=>$value){
            if($value["size"]>0){
                $estensione = explode("/",$value["type"]);
                $nome_file = $k."_".time()."_".getmypid().".".end($estensione);
                $percorso = "template/assets/immagini/".$nome_file;
                if(move_uploaded_file($value["tmp_name"],$percorso)){
                    $dati_insert["immagine_".$k]= $nome_file;
                } 
            }
        }
        return $dati_insert;
    }
    // funzione per la modifica dei file immagine articoli 
    function modifica_immagini_articolo($files,$dati_update,$id_articolo){
        $db = new db();
        // controllo ogni file, se il file esiste controllo se esiste un file con il nome di quel file, sostituisco con nuovo nome se presente o creo nuovo, sposto il file immagine nella cartella
        foreach($files as $k=>$value){
            if($value["size"]>0){
                $valore_immagine = $db->get("SELECT immagine_".$k." FROM articoli WHERE id = ? ",[$id_articolo])[0]["immagine_".$k];
                if(!is_null($valore_immagine)){ 
                    $nome_file = $valore_immagine;
                } else {
                    $estensione = explode("/",$value["type"]);
                    $nome_file = $k."_".time()."_".getmypid().".".end($estensione);
                }
                $percorso = "template/assets/immagini/".$nome_file;
                if(move_uploaded_file($value["tmp_name"],$percorso)){
                    $dati_update["immagine_".$k]= $nome_file;
                } 
            }
        }
        return $dati_update;
    }
    //funzione per cancellare le immagini di un articolo
    function cancella_immagini_articolo($id_utente,$id_articolo,$id_blog=null,$mass_delete=false){
        $db = new db();
        // cancello tutte le immagini se sto cancellando un blog
        if($mass_delete == true){
            $valore_immagine = $db->get("SELECT immagine_cop, immagine_art FROM articoli WHERE id_utente_articolo = ? AND id_blog = ?",["id_utente_articolo"=>$id_utente,"id_blog"=>$id_blog]);
            foreach($valore_immagine as $k=>$value){
                foreach($value as $chiave=>$valore){
                    unlink("template/assets/immagini/".$valore);
                }
            }
        // cancello le immagini di un articolo scelto
        } else {
            $valore_immagine = $db->get("SELECT immagine_cop, immagine_art 
                                        FROM articoli 
                                        WHERE id = ? 
                                        AND id_utente_articolo = ?",["id"=>$id_articolo,"id_utente_articolo"=>$id_utente])[0];
            foreach($valore_immagine as $k=>$value){
                unlink("template/assets/immagini/".$value);
            }
        }
        return true;
    }
    // funzione per inserire un commento, in base al testo passato da dati_insert
    function inserimento_commento($dati_insert){
        $db = new db();
        $db->insert("commenti",$dati_insert);
        $esito = $db->errore;
        return $esito;
    }
    // funzione per cancellare un commento, in base al commento scelto
    function cancella_commento($dati_delete){
        $db = new db();
        $db->delete("commenti",$dati_delete);
        $esito = $db->errore;
        return $esito;
    }
    //funzione per abilitare un voto, all'interno c'è un valore che stabilisce se è un voto positivo o negativo
    function inserimento_voto($dati_insert){
        $db = new db();
        $db->insert("voti",$dati_insert);
        $esito = $db->errore;
        return $esito;
    }
    //funzione per la modifica di un voto
    function modifica_voto($dati_update,$dati_where){
        $db = new db();
        $db->update("voti",$dati_update,$dati_where);
        $esito = $db->errore;
        return $esito;
    }

}

?>