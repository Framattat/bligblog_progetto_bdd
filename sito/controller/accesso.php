<?php
    
    require_once("models/accesso_model.php");
    require_once("libraries/validator.php");
    
class accesso{
    //funzione per la formattazione pagina, la pagina del controller accesso di default è la pagina di login
    function costruzioneP($pagina="login") {
        if (!file_exists(VIEW_FOLDER.$pagina.".php")) mostra_errore("La pagina $pagina non è stata trovata");
        require_once("view/sito/header.php");
        require_once(VIEW_FOLDER.$pagina.".php");
        require_once("view/sito/footer.php");
    }
    // funzione per settare l'index la pagina login
    function index() {
        return $this->login();
    }
    //funzione per settare url /login/ la pagina html login
    function login() {
        $this->costruzioneP("login");
    }
    //funzione per la validazione asincrona del form della registrazione, mando i dati al validatore
    function async_valida_form_registrazione(){
        $validator = new validator();
        $esito = $validator->registrazione_validator($_POST);
        if ($esito === true){
            echo 1;
            return;
        } 
        echo $esito;
        return;
    }
    // funzione per settare url /registrazione/ la pagina html di registrazione
    function registrazione() {
        $this->costruzioneP("registrazione");
    }
    //funzione per gestione dell'analisi del login
    function analisi_login(){
        $login = new accesso_model();
        $login->check_login();

    }
    //funzione per la gestione della registrazione di un utente
    function inserimento_dati(){
        $registrazione = new accesso_model();
        $registrazione->registrazione();
    }
    //funzione per la gestione del logout
    function logout() {
        $accesso_model = new accesso_model();
        $accesso_model->logout();
    }
}

?>