<?php
// carico la libreria di validazione dati
require_once("libraries/validator.php");

class utente_model {
    private $tabella_utente = "utenti";
    
    // funzione che ritorna i dati dell'utente dal database in base al parametro id_utente
    function recupera_utente($id_utente){
        $db = new db();
        $utente= $db->select_one($this->tabella_utente,["id"=>$id_utente]);
        return $utente;
    }
    // funzione per la modifica dei dati utente,
    function modifica_utente($dati_update){
        $db = new db();
        //aggiorno i dati
        $modifica=$db->update($this->tabella_utente, $dati_update,["id"=>$_SESSION['id_utente']]);
        $esito = $db->errore;
        return $esito;
    }
    //funzione per cancellare l'utente
    function cancella_utente(){
        $db = new db();
        // cancello l'utente corrente
        $cancellazione = $db->delete("utenti", ["id"=>$_SESSION["id_utente"]]);
        $esito = $db->errore;
        return $esito;
    }
    // funzione per rendere un utente co-autore di un blog
    function rendi_coautore($dati_insert){
        $db = new db();
        // inserisco l'utente in base ai dati passati in dati_insert
        $db->insert("autori", $dati_insert);
        $esito = $db->errore;
        return $esito;
    }
    // funzione per cancellare un utente co-autore di un blog
    function cancella_coautore($id){
        $db = new db();
        // cancello l'utente in base all'id della tabella coautore passata
        $db->delete("autori", ["id"=>$id]);
        $esito = $db->errore;
        return $esito;
    }
    //funzione per cambiare l'abbonamento dell'utente
    function cambio_abbonamento(){
        $dati=$_POST;
        $db = new db();
        // controllo se è inserito un codice voucher
        if (!empty($dati["voucher"])){
            $validator = new validator();
            $esito = $validator->voucher_validator($dati);
            // eseguo l'update se il codice voucher è valido
            if ($esito == 1){
                $modifica=$db->update("abbonamenti_utenti", [
                    "id_abbonamento"=>$dati["id_abbonamento"],
                    "data_inizio_abbonamento"=>date("Y-m-d H:i:s"),
                    "data_fine_abbonamento"=>date("Y-m-d H:i:s",strtotime("+1 year"))
                ], ["id_utente_abbonamento"=>$_SESSION['id_utente']]);
                return $esito;
                // codice non valido
            } else if($esito == 2) {
                return $esito;
            }
            // codice già utilizzato
            return $esito;
            // eseguo il downgrade se non è presente un codice voucher (perché l'utente sta effettuando il downgrade)
        } else if($dati["id_abbonamento"]<abbonamento_attivo()){
            $modifica=$db->update("abbonamenti_utenti", ["id_abbonamento"=>$dati["id_abbonamento"]],["id_utente_abbonamento"=>$_SESSION['id_utente']]);
            return 1;
        }
    }
    // funzione per i limit di ogni abbonamento, parametro "tipo" di limite, $param1 serve come parametro specifico alle funzioni al suo interno
    function limite_abbonamento($tipo,$param1=null){
        // funzione generica, non voglio parametri specifici
        $db = new db();
        $limita = false; // un limite a false vuol dire che non è stato raggiunto
        switch($tipo){
            //limite del blog
            case "blog":
                // se non passo id utente, prendo l'utente connesso
                if (is_null($param1)){
                    $param1=$_SESSION["id_utente"];
                }
                $lista_limiti_blog = $db->select_one("abbonamenti_limiti",["id_utente_abbonamento"=>$param1]);
                if(is_null($lista_limiti_blog)){
                    return true;
                }
                $query = "SELECT count(id) as blog_creati FROM blog WHERE id_utente = ?";
                $n_blog = $db->get_one($query,[$_SESSION["id_utente"]])["blog_creati"];
                // dopo aver preso i dati, controllo se rispettano i limiti
                if($lista_limiti_blog["blog_max"]>=0 && $n_blog>=$lista_limiti_blog["blog_max"])
                    $limita=true;
                break;
            //limite degli articoli
            case "articoli":
                $query_articoli= "  SELECT articoli_max 
                                    FROM abbonamenti_limiti 
                                    JOIN blog ON blog.id_utente = abbonamenti_limiti.id_utente_abbonamento
                                    WHERE blog.id = ?";
                $lista_limiti_articoli= $db->get_one($query_articoli,[$param1]);
                if(is_null($lista_limiti_articoli)){
                    return true;
                }
                $query = "SELECT count(id) as articoli_creati FROM articoli WHERE id_blog = ? ";
                $n_articoli = $db->get_one($query,[$param1])["articoli_creati"];
                // blog recupero id utente risalgo agli abbonamenti limiti e verifico i limiti per quell'utente
                if($lista_limiti_articoli["articoli_max"]>=0 && $n_articoli>=$lista_limiti_articoli["articoli_max"])
                    $limita=true;
                break;
            //limite dei template
            case "template":
                if (is_null($param1)){
                    $param1=$_SESSION["id_utente"];
                }
                $lista_limiti_template = $db->select_one("abbonamenti_limiti",["id_utente_abbonamento"=>$param1]);
                if(is_null($lista_limiti_template)){
                    return true;
                }
                // ritorno i template disponibili in base all'utente connesso o id passato
                $lista_limiti_template = explode(",",$lista_limiti_template["template_disponibili"]);
                return $lista_limiti_template;
                break;
        } 
        // ritorno il limite se è stato raggiunto per blog o articolo, per template ritorno i template disponibili
        return $limita;
    }
}

?>