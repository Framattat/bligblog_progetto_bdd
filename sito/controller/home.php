<?php

require_once("helper/utente_helper.php");
require_once("models/blog_model.php");

class home{

    // funzione che costruisce una view del sito in base alla pagina richiesta
    function costruzioneP($pagina="homepage",$variabili=[],$errore=false) {
        foreach ($variabili as $key=>$value){
            ${$key}=$value;
        }
        if (!file_exists(VIEW_FOLDER.$pagina.".php")) mostra_errore("La pagina $pagina non è stata trovata");
        require_once(VIEW_FOLDER."header.php");
        require_once(VIEW_FOLDER.$pagina.".php");
        require_once(VIEW_FOLDER."footer.php");
    }
    // funzione che restituisce come index la homepage del sito
    function index() {
        return $this->homepage();
    }
    // funzione che crea la home page
    function homepage() {
        $this->costruzioneP("homepage");
    }
    //funzione che crea la pagina chi siamo
    function chisiamo() {
        $this->costruzioneP("chisiamo");
    }
    //funzione della ricerca blog
    function ricerca_blogs() {
        $db = new db();
        $blog_model = new blog_model();
        $errore = false;
        $parole_ricerca = "";
        $query_search = [];
        // valori per la ricerca s (search) è quella generale
        $valori_search = ["s","categorie","sottocategorie"];
        $contatore = 0;
        // se ho una get settata e non vuota procedo altrimenti seleziono randomicamente i blog del sito
        if(isset($_GET) && !empty($_GET)){
            // per ogni elemento presente nella get (posso fare diverse ricerche concatenate e non)
            foreach($_GET as $k=>$value){
                // se la chiave get è settata ma è vuota interrompo subito per risparmiare processi
                if($value == ""){
                    break;
                } else if(in_array($k,$valori_search)){
                    $contatore++;
                }
                // in base alla chiave della mia get costruisco per pezzi la mia query di ricerca
                switch($k){
                    case "s":
                        $campi = [
                            "username_utente",
                            "titolo_blog"
                        ];
                        $where = [];
                        foreach(explode(" ",$_GET["s"]) as $chiave){
                            foreach($campi as $campo){
                                $where[] = "$campo LIKE '%$chiave%'";
                            }
                        }
                        $query_search[] = implode(" OR ",$where);
                        $barra_ricerca[] = $_GET["s"];
                        break;
                    case "categorie":
                        $query_search[] = "id_categoria = ".$value;
                        // prendo la categoria così da poterla impostare nel html come parola chiave di ricerca
                        $nome_categoria = $db->get_one("SELECT nome_categoria as c FROM categoria WHERE id = ?",[$value])["c"];
                        $barra_ricerca[] = ucwords($nome_categoria);
                        break;
                    case "sottocategorie":
                        $sottocategorie_query = [];
                        foreach($_GET["sottocategorie"] as $k=>$value){
                            $sottocategorie_query[] = "FIND_IN_SET(".$value.",sottocategorie)";
                            //per ogni sottocategoria, prendo il suo nome per poterla impostare nell'html nella ricerca che l'utente vede
                            $nome_sottocategoria = $db->get_one("SELECT nome_sottocategoria as c FROM sottocategorie WHERE id = ?",[$value])["c"];
                            $sottocategorie_ricerca [] = ucwords($nome_sottocategoria);
                        }
                        $barra_ricerca[] = implode("-",$sottocategorie_ricerca);
                        $query_search[] = implode(" OR ",$sottocategorie_query);
                        break;
                    // array di debug interno
                    default:
                        $array_esiste[$k] = ["non c'è"];
                }
            }
            // se le chiavi sono tutte vuote, restituisco casualmente i blog del sito
            if($contatore == 0){
                $blogs = $db->get("SELECT * FROM blog_full ORDER BY RAND()"); 
            }
        } else {
            $blogs = $db->get("SELECT * FROM blog_full ORDER BY RAND()"); 
        }
        // se la query search ha un qualsiasi elemento, procedo ad effettuare la ricerca
        if(isset($query_search) && !empty($query_search)){
            $blogs = $db->get("SELECT * FROM blog_full WHERE (".implode(") AND (",$query_search).")");
        }
        // se la ricerca è avvenuta e all'interno di ogni get c'era un valore, avrò l'array con le parole della ricerca che passerò come stringa
        if(isset($barra_ricerca)){
            $parole_ricerca = implode(", ",$barra_ricerca);
        }
        // se la variabile blog è vuota, la ricerca non ha avuto successo
        if(empty($blogs)){
            $errore = true;
        }
        $this->costruzioneP("blogs", ["blogs"=>$blogs,"db"=>$db, "errore"=>$errore, "parole_ricerca"=>$parole_ricerca, "blog_model"=>$blog_model]);
    }
    //funzione per la creazione della pagina delle offerte, carico l'abbonamento attivo da far vedere all'utente
    function offerte() {
        $db= new db();
        $abbonamento_attivo = abbonamento_attivo();
        $this->costruzioneP("offerte",["abbonamento_attivo"=>$abbonamento_attivo, "db"=>$db]);
    }
    // funzione per la creazione della pagina faq
    function faq() {
        $this->costruzioneP("faq");
    }
}

?>