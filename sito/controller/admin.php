<?php
   // controller per pagina e gestione admin, sviluppi futuri
require_once("models/accesso_model.php");
require_once("helper/login_helper.php");
    
class admin{

    function __construct(){
        if(!utente_loggato_admin()){
            redirect("/accesso/login?msg=Sessione scaduta o utente non autorizzato&warning");
        }
    }

    function costruzioneP($pagina="login") {
        if (!file_exists(VIEW_FOLDER.$pagina.".php")) mostra_errore("La pagina (view) $pagina non è stata trovata");
        require_once("view/sito/header.php");
        require_once(VIEW_FOLDER.$pagina.".php");
        require_once("view/sito/footer.php");
    }

    function index() {
        echo "CIAO SONO LA PAGINA ADMIN: SEI AUTORIZZATO A VEDERMI<br/>
        <a href='/accesso/logout'>ESCI</a>";return;
    }

    function login() {
        $this->costruzioneP("loginz");
    }

    function registrazione() {
        $this->costruzioneP("registrazione");
    }
}

?>