<?php 
//carico le librerie o modelli che mi servono
require_once("helper/utente_helper.php");
require_once("libraries/template.php");
require_once("libraries/validator.php");
require_once("models/blog_model.php");
require_once("models/articolo_model.php");
require_once("models/utente_model.php");

class blog{
    //creo delle variabili private, evito contrasti tra altre variabili o possibili infiltrazioni maliziose
    private $blog;
    private $db;
    private $path_blog;
    private $template;
    private $path_redirect;
    //funzione construct che carica i dati del mio blog tramite una view, e direziona l'utente sulle pagina home del proprio blog
    function __construct(){
        global $path;
        $this->blog_model = new blog_model();
        $this->template = new template();
        $this->path_blog = $path;
        $this->db = new db();
        $blog = $this->db->select_one("blog_full",["indirizzo_blog"=>$this->path_blog[1]]);
        if (is_null($blog)){
            mostra_errore("Il blog richiesto non esiste");
        }
        $this->blog = $blog;
        $this->path_redirect = "/blog/".$this->blog["indirizzo_blog"];
        $this->template->use_template_from_blog($blog["id"],$blog);
        // controllo nella url cosa c'è alla seconda posizione /host - 0/blog - 1 /... - 2
        switch (@$this->path_blog[2]){
            case "":
            case "home":
            case "index":
            case "template":
                $this->home();
                break;
            default: 
                if (method_exists($this, $this->path_blog[2]))
                    $this->{$this->path_blog[2]}();
                else 
                    mostra_errore("La pagina richiesta non esiste");
        }
        exit;
    }
    //funzione per la gestione della home di un blog dell'utente
    function home() {
        $blog_id = $this->blog["id"];
        //carico l'archivio di articoli in base al blog
        $lista_archivio = $this->blog_model->popola_archivio($blog_id);
        //gestisco gli ultimi articoli e la query per recuperare tutti gli altri articoli
        if (isset($_GET["data"]) && isset($lista_archivio[$_GET["data"]])){
            $query_articoli = " SELECT * 
                                FROM articoli_full 
                                WHERE id IN(".implode(",",$lista_archivio[$_GET["data"]]).") AND id_blog = $blog_id AND pubblicato = 1 
                                ORDER BY id DESC";
            $ultimo_articolo = null;
            $ultimi_articoli = [];
        } else {
            $ultimi_articoli = $this->db->get("SELECT * FROM articoli_full WHERE id_blog = $blog_id AND pubblicato = 1 ORDER BY id DESC LIMIT 3");
            if (count($ultimi_articoli)){
                $ultimo_articolo = $ultimi_articoli[0];
            } else {
                $ultimo_articolo = null;
            }
            $query_articoli = " SELECT * 
                                FROM articoli_full 
                                WHERE id_blog = $blog_id AND pubblicato = 1 
                                ORDER BY data_pubblicazione_articolo DESC";
        }
        $dati = [
            // richiamerò il modello articolo con le sue funzioni
            "articoli"=>$this->db->get($query_articoli),
            "ultimo_articolo"=> $ultimo_articolo,
            "ultimi_articoli"=>$ultimi_articoli,
            "id_blog"=>$blog_id,
            "categoria"=>$this->blog["nome_categoria"],
            "autore"=>check_autore($blog_id),
            "archivio"=>$lista_archivio
        ];
        //controllo se un blog ha le sottocategorie
        if($this->blog["sottocategorie"]!=""){
            $dati["sottocategorie"]=$this->blog["sottocategorie"];
        }
        $html = $this->template->get_html("home",$dati);
    }
    //funzione per la modifica di articoli
    function article_editor() {
        $id_blog = $this->blog["id"];
        $blog_model = new blog_model();
        $articolo_model = new articolo_model();
        $utente_model = new utente_model();
        // redirect se l'utente ha raggiunto il limite di articoli
        if($utente_model->limite_abbonamento("articoli",$id_blog)){
            redirect("/home/offerte/?msg=Per poter scrivere altri articoli devi eseguire l'upgrade&warning");
        }
        $db= new db();
        if(isset($_GET["m"])){
            $articolo = $db->get_one("SELECT * FROM articoli WHERE id_blog = $id_blog AND id = $_GET[id]");
        }
        //controllo se un utente può scrivere nel blog
        if(check_autore($id_blog)){
            $dati =[
                "blog"=>$blog_model->recupera_blog($id_blog)
            ];
            if(isset($articolo)){
                $dati["articolo"] = $articolo;
            }
            $this->template->get_html("userarticle",$dati);
        } else {
            redirect("/utente/userpage/?msg=Sembra che tu non possa accedere a questo contenuto!");
        }
    }
    //funzione per la gestione degli articoli, controllo utenti maliziosi con la funzione checkautore
    function lista_articoli(){
        $id_blog = $this->blog["id"];
        $db = new db();
        if(!check_autore($id_blog))
            redirect("/utente/userpage/?msg=Sembra che tu non possa accedere a questo contenuto!");
        $this->template->get_html("userarticles",["db"=>$db, "id_blog"=>$id_blog]);
    }
    //funzione per la visualizzazione dell'articolo
    function articolo(){
        $id = explode("_", $this->path_blog[3]);
        if (count($id)>0)
            $id = reset($id);
        else 
            redirect($this->path_blog[0]."/".$this->path_blog[1]."/home");
        $articolo = $this->db->select_one("articoli_full",["id"=>$id, "id_blog"=>$this->blog['id']]);
        if (is_null($articolo))
            mostra_errore("L'articolo richiesto non esiste");
        $autore = check_autore($this->blog["id"]);
        // se l'articolo non è pubblicato, e non sei autore, non puoi vederlo
        if (!$articolo["pubblicato"] && !$autore)
            redirect($this->path_redirect."?msg=Non puoi accedere a questo articolo!");
        $html = $this->template->get_html("articolo",["articolo"=>$articolo,"db"=>$this->db, "autore_valido"=>$autore]);
    }
    //funzione per l'inserimento dati di un articolo, controllo se ci sono problemi nei dati
    function inserisci_articolo(){
        $validator = new validator();
        $controlla_articolo = $validator->articolo_validator($_POST,$_FILES);
        if ($controlla_articolo !== true){
            redirect($this->path_redirect."/article_editor/?msg=".$controlla_articolo."&danger ");
            return;
        }
        $pubblicazione = date_create($_POST["data_pubblicazione"].date(':s'));
        $dati_insert = [
            "titolo_articolo"=>$_POST["titoloA"],
            "testo_articolo"=>$_POST["descrizioneA"],
            "id_utente_articolo"=>$_POST["id_utente_articolo"],
            "id_blog"=>$_POST["id_blog"],
            "data_creazione_articolo"=>date("Y-m-d H:i:s"),
            "data_pubblicazione_articolo"=>date_format($pubblicazione,"Y-m-d H:i:s"),
            "bozza"=>$_POST["bozza"],
            "tags"=>$_POST["tags"]
        ];
        $articolo = new articolo_model();
        $esito_query = $articolo->crea_articolo($dati_insert,$_FILES);
        // controllo che la query sia andata a buon fine
        $redirect_path = $this->path_redirect."/lista_articoli/?msg=";
        esito($esito_query,
            $redirect_path."Articolo creato con successo!&success",
            $redirect_path."Articolo non creato, qualcosa è andato storto!&danger",
        );
    }
    //funzione per la modifica dei dati dell'articolo, controllo che i dati inseriti siano validi
    function modifica_articolo(){
        $validator = new validator();
        $controlla_articolo = $validator->articolo_validator($_POST,$_FILES);
        if ($controlla_articolo !== true){
            redirect($this->path_redirect."/article_editor/?id=".$_POST["id_articolo"]."&m=on&msg=".$controlla_articolo."&danger ");
            return;
        }
        $pubblicazione = date_create($_POST["data_pubblicazione"].date(':s'));
        $dati_update = [
            "titolo_articolo"=>$_POST["titoloA"],
            "testo_articolo"=>$_POST["descrizioneA"],
            "data_modifica_articolo"=>date("Y-m-d H:i:s"),
            "data_pubblicazione_articolo"=>date_format($pubblicazione,"Y-m-d H:i:s"),
            "bozza"=>$_POST["bozza"],
            "tags"=>$_POST["tags"]
        ];
        $dati_where = [
            "id"=>$_POST["id_articolo"],
            "id_blog"=>$_POST["id_blog"]
        ];
        $articolo = new articolo_model();
        $esito_query = $articolo->modifica_articolo($dati_where,$dati_update,$_FILES);
        // in base all'esito della query direziono
        $redirect_path = $this->path_redirect."/lista_articoli/?msg=";
        esito($esito_query,
            $redirect_path."Articolo modificato con successo!&success",
            $redirect_path."Articolo non modificato, qualcosa è andato storto!&danger",
        );
    }
    //funzione per la cancellazione di un articolo, controllo se l'utente che sta facendo la richiesta è il proprietario del blog o articolo
    function cancella_articolo(){
        $db = new db();
        $articolo = new articolo_model();
        if(check_autore($this->blog["id"])){
            $esito_query = $articolo->cancella_articolo($_GET['id_utente'],$_GET['id']);
            $redirect_path = $this->path_redirect."/lista_articoli/?msg=";
        esito($esito_query,
            $redirect_path."Articolo Rimosso&warning",
            $redirect_path."Articolo non rimosso, qualcosa è andato storto!&danger",
        );
        } else {
            redirect($this->path_redirect."/?msg=Non hai i permessi per cancellare questo articolo&danger");
        }
    }
    //funzione per inserimento di commenti, la funzione esito controlla il corretto funzionamento della query
    function inserimento_commento(){
        $articolo = new articolo_model();
        $dati_insert = [
            "id_utente_commento"=> $_POST["id_utente"],
            "id_articolo_commento"=> $_POST["id_articolo"],
            "testo_commento"=> $_POST["commento_testo"],
            "data_commento"=>date("Y-m-d H:i:s")
        ];
        $esito_query = $articolo->inserimento_commento($dati_insert);
        $redirect_path = $this->path_redirect."/articolo/".$_POST["id_articolo"];
        esito($esito_query,
            $redirect_path,
            $redirect_path."/?msg=Commento non inserito, qualcosa è andato storto!&danger",
        );
    }
    //funzione per la cancellazione di un commento, esito controlla se la query ha successo
    function cancella_commento(){
        $articolo = new articolo_model();
        $esito_query = $articolo->cancella_commento(["id"=>$_POST["id_commento"]]);
        $redirect_path = $this->path_redirect."/articolo/".$_POST["id_articolo"];
        esito($esito_query,
            $redirect_path,
            $redirect_path."/?msg=Commento non cancellato, qualcosa è andato storto!&danger",
        );
    }
    //funzione per il voto di un utente, esito controlla se la query ha successo
    function voto(){
        $articolo = new articolo_model();
        $dati = [
            "id_articolo_voto"=>$_POST["id_articolo"],
            "id_utente_voto"=>$_POST["id_utente"]
        ];
        $esito = $this->db->select("voti",$dati);
        //se trovo un voto lo modifico, se non lo trovo lo inserisco
        if(!empty($esito)){
            $esito_query = $articolo->modifica_voto(["voto"=>$_POST["voto"]],$dati);
        } else {
            $dati["voto"] = $_POST["voto"];
            $esito_query = $articolo->inserimento_voto($dati);
        }
        $redirect_path = $this->path_redirect."/articolo/".$_POST["id_articolo"];
        esito($esito_query,
            $redirect_path,
            $redirect_path."/?msg=Voto non inserito o modificato, qualcosa è andato storto!&danger",
        );
    }
    //funzione che gestisce l'archivio e lo popola con la funzione popola archivio
    function archivio(){
        $blog = new blog_model();
        $dati = $blog->popola_archivio();
    }

}

?>