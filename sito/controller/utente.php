<?php 

require_once("helper/utente_helper.php");
require_once("models/accesso_model.php");
require_once("models/articolo_model.php");
require_once("models/blog_model.php");
require_once("models/utente_model.php");


class utente{

    private $redirect_path_blog = "/utente/blogs/";
    // controllo che l'utente sia loggato per poter accedere
    function __construct(){
        if (!utente_loggato()){
            redirect("/accesso/login?msg=Accedi per poter vedere questo contenuto");
        }
    }
    //funzione per la costruzione della pagina html, recupero header footer e la pagina di riferimento  
    function costruzioneP($pagina="userpage",$variabili=[]) {
        // per ogni variabile passata entro la pagina con un array e setto le variabili con il nome della chiave associate al proprio valore
        foreach ($variabili as $key=>$value){
            ${$key}=$value;
        }
        if (!file_exists(VIEW_FOLDER.$pagina.".php")) mostra_errore("La pagina $pagina non è stata trovata");
        require_once(VIEW_FOLDER."header.php");
        require_once(VIEW_FOLDER.$pagina.".php");
        require_once(VIEW_FOLDER."footer.php");
        
    }
    //funzione che definisce la pagina index, in questo caso la pagina userpage
    function index() {
        return $this->userpage();
    }
    //funzione per la creazione della pagina utente, carico la pagina con i dati dell'utente
    function userpage() {
        $utente_model = new utente_model();
        $abbonamento = abbonamento_attivo(true);
        $utente = $utente_model->recupera_utente($_SESSION['id_utente']);
        $this->costruzioneP("userpage",["utente"=>$utente,"abbonamento"=>$abbonamento]);
    }
    //funzione per la creazione della pagina dei template
    function template(){
        $this->costruzioneP("template");
    }
    //funzione per la validazione asincrona della modifica del profilo utente
    function async_valida_form_modifica_profilo(){
        $validator = new validator();
        $esito = $validator->registrazione_validator($_POST);
        if ($esito === true){
            echo 1;
            return;
        } 
        echo $esito;
        return;
    }
    //funzione per la validazione asicrona dei dati del blog
    function async_valida_form_blog(){
        $validator = new validator();
        $esito = $validator->blog_validator($_POST);
        if ($esito === true){
            echo 1;
            return;
        } 
        echo $esito;
        return;
    }
    //funzione per la validazione asincrona dei dati del coautore
    function async_valida_form_coautore(){
        $validator = new validator();
        $esito = $validator->coautore_validator($_POST);
        if ($esito === true){
            echo 1;
            return;
        } 
        echo $esito;
        return;
    }
    //funzione per rendere coautori altri utenti del sito, controllo se i dati sono validi in sincrono
    function coautore(){
        $db = new db();
        $validator = new validator();
        $esito = $validator->coautore_validator($_POST);
        if ($esito !== true){
            redirect($this->redirect_path_blog."?msg=Inserimento coautore fallito&danger ");
            return;
        }
        // recupero l'id utente in base al suo username
        $id_utente = $db->get_one("SELECT id FROM utenti WHERE username_utente = ? ",[$_POST["nome_coautore"]]);
        $dati_insert = [
            "stato_autore"=>1,
            "id_utente"=>$id_utente["id"],
            "id_blog"=>$_POST["id_blog"],
        ];
        //in base all'esito della query direziono l'utente
        $utente = new utente_model();
        $esito_query = $utente->rendi_coautore($dati_insert);
        esito($esito_query,
            $this->redirect_path_blog."?msg=Coautore aggiunto&success",
            $this->redirect_path_blog."?msg=Coautore non aggiunto, qualcosa è andato storto&danger",
        );
    }
    //funzione per cancellare un coautore dal blog, i coautori stessi possono cancellarsi se volessero
    function cancellare_coautore(){
        $id = $_GET["id"];
        $id_blog = $_GET["blog"];
        //controllo se un autore è abilitato nel blog
        if(check_autore($id_blog)){
            $utente = new utente_model();
            $esito_query = $utente->cancella_coautore($id);
            esito($esito_query,
                $this->redirect_path_blog."?msg=Coautore rimosso&warning",
                $this->redirect_path_blog."?msg=Coautore non rimosso, qualcosa è andato storto&danger",
            );
        } else {
            redirect($this->redirect_path_blog."?msg=Non hai i permessi per cancellare questo coautore&danger");
        }
    }
    //funzione per la modifica dei dati del profilo utente
    function modifica_profilo() {
        $utente = new utente_model();
        //controllo se i dati che ha mandato l'utente sono validi
        $validator = new validator();
        $esito = $validator->registrazione_validator($_POST);
        if ($esito !== true){
            redirect("/utente/userpage?msg=Modifica dati non riuscita&danger ");
            return;
        } 
        $dati_update = [
            "nome_utente" => trim($_POST['nomeU']),
            "cognome_utente" => trim($_POST['cognomeU']),
            "email_utente" => trim($_POST['email']),
            "telefono_utente" => trim($_POST['cellulare']),
            "username_utente"=> trim($_POST['username']),
            "tipo_documento" => $_POST['tipo_documento'],
            "estremi_documento_utente" => trim($_POST['estremi_documento']),
            "descrizione_utente" => trim($_POST['descrizioneU'])
        ];
        $esito_query = $utente->modifica_utente($dati_update);
        // in base all'esito dell'update ridireziono l'utente
        esito($esito_query,
            "/utente/userpage?msg=Modifica dati riuscita&success ",
            "/utente/userpage?msg=Modifica dati non riuscita, qualcosa è andato storto&danger ",
        );           
    }
    //funzione per la cancellazione di un utente
    function cancella_utente() {
        $utente = new utente_model();
        $esito_query = $utente->cancella_utente();
        // in base all'esito della cancel ridireziono l'utente
        esito($esito_query,
            "/accesso/logout?msg=Cancellazione eseguita&warning ",
            "/utente/userpage?msg=Cancellazione non eseguita, qualcosa è andato storto!&danger ",
        ); 
    }
    //funzione per la gestione della pagina dei blog dell'utente, la popolo con i dati inerenti
    function blogs() {
        $db = new db();
        $blog = new blog_model();
        $utente = new utente_model();
        $limite = $utente->limite_abbonamento("blog");
        $limite_articoli = $utente->limite_abbonamento("template");
        $userblogs = $blog->popola_blog();   
        $this->costruzioneP("userblogs",["blog"=>$userblogs,"db"=>$db,"limite_blog"=>$limite,"lista_template_disponibili"=>$limite_articoli]);
    }
    //funzione per l'inserimento di un blog controllo in sincrono se i dati sono corretti
    function inserimento_blog(){
        $utente_model = new utente_model();
        //controllo se l'utente ha raggiunto il numero massimo di blog
        if($utente_model->limite_abbonamento("blog")){
            redirect("/home/offerte/?msg=Per poter creare altri blog devi eseguire l'upgrade&warning");
        }
        $validator = new validator();
        $esito = $validator->blog_validator($_POST);
        if ($esito !== true){
            redirect($this->redirect_path_blog."?msg=Creazione blog non riuscita&danger ");
            return;
        } 
        $blog = new blog_model();
        $dati_insert = [
            "id_utente" => $_SESSION['id_utente'],
            "indirizzo_blog"=>strtolower($_POST["indirizzo_blog"]) ,
            "titolo_blog"  => $_POST["titoloBlog"],
            "descrizione_blog"=>$_POST["descrizione_blog"],
            "id_template" => $_POST["id_template"],
            "id_categoria" => $_POST["id_categoria"]
        ];
        // setto le categorie se presenti, non sono obbligatorie
        if(isset($_POST['sottocategoria'])){
            $dati_insert["sottocategorie"]= implode(",",($_POST['sottocategoria']));
        };
        $esito_query = $blog->inserire_blog($dati_insert);
        esito($esito_query,
            $this->redirect_path_blog."?msg=Nuovo blog creato!&success ",
            $this->redirect_path_blog."?msg=Blog non creato, qualcosa è andato storto!&danger",
        );
    }
    //funzione per la modifica dei dati di un blog, controllo in sincrono se i dati sono corretti
    function modifica_blog(){
        $db = new db();
        $validator = new validator();
        $esito = $validator->blog_validator($_POST);
        if ($esito !== true){
            redirect($this->redirect_path_blog."?msg=Modifica blog non riuscita&danger ");
            return;
        } 
        $blog = new blog_model();
        $dati_update = [
            "titolo_blog"=>$_POST["titoloBlog"],
            "descrizione_blog"=>$_POST["descrizione_blog"],
            "id_categoria"=>$_POST["id_categoria"],
            "id_template"=>$_POST["id_template"]
        ];
        $id_blog = $_POST["id_blog"];
        // se le sottocategorie sono settate le aggiorno se non sono settate ed ho modificato la categoria le annullo
        if(isset($_POST['sottocategoria'])){
            $dati_update["sottocategorie"]= implode(",",($_POST['sottocategoria']));
        } else {
            $categoria_database = $db->get("SELECT id_categoria FROM blog WHERE id = ?",[$id_blog])[0];
            if ($categoria_database["id_categoria"] != $dati_update["id_categoria"]){
                $dati_update["sottocategorie"]= null;
            }
        };
        // verifico l'esito della query e ridireziono
        $esito_query = $blog->modifica_blog($dati_update,$id_blog);
        esito($esito_query,
            $this->redirect_path_blog."?msg=Blog modificato!&success ",
            $this->redirect_path_blog."?msg=Blog non modificato, qualcosa è andato storto!&danger",
        );
    }
    //funzione per la cancellazione di blog
    function cancellare_blog(){
        $id_blog = $_GET['id'];
        $blog = new blog_model();
        // controllo se un utente ha i permessi per cancellare il suo blog
        $controllo = $blog->check_blog($id_blog, $_SESSION["id_utente"]);
        if($controllo){
            // in base all'esito della query ridireziono
            $esito_query = $blog->cancellare_blog($_SESSION['id_utente'],$id_blog);
            esito($esito_query,
                $this->redirect_path_blog."?msg=Blog cancellato!&warning",
                $this->redirect_path_blog."?msg=Blog non cancellato, qualcosa è andato storto!&danger",
            );
        } else {
            redirect($this->redirect_path_blog."?msg=Non hai i permessi per cancellare questo blog!&danger");
        }
    }
    //funzion che gestisce il cambiamento di offerte
    function cambia_offerta(){
        $utente= new utente_model();
        // in base all'esito del cambio abbonamento ho 4 casi
        $esito=$utente->cambio_abbonamento();
        switch($esito){
            case 1:
                redirect("/utente/userpage?msg=Cambio offerta avvenuto con successo!&success");
                break;
            case 2:
                redirect("/home/offerte?msg=Codice voucher non valido!&danger");
                break;
            case 3:
                redirect("/home/offerte?msg=Codice voucher già utilizzato!&warning");
                break;
            default:
                redirect("/home/offerte?msg=Cambio offerta fallito&danger");       
        }
    }
}

?>