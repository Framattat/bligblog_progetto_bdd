<?php
// inizializzo la sessione dell'utente, servirà per tener traccia dell'id utente o capire se è loggato
session_start();
// carico le librerie o funzioni helper necessarie al funzionamento del sito
require_once("libraries/db.php");
require_once ("helper/base_helper.php");
require_once ("helper/response_helper.php");
require_once ("helper/login_helper.php");
define("ROOTPATH",__DIR__);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// crea un array dalla "REQUEST_URI" ovvero tutto quello che è dopo il dominio, la funzione substr() prende una stringa e prende tutto quello dopo il secondo elemento compreso
$get_params = substr($_SERVER["REQUEST_URI"],strpos($_SERVER["REQUEST_URI"],"?"));
$path=explode("/",str_replace($get_params,"",substr($_SERVER["REQUEST_URI"], 1)));

// Verifico se il primo elemento (un controller) è stato settato
if (isset($path[0])){
    // imposto la funzione di base nella $function_called
    $function_called="index";
    // verifico se il secondo elemento (un metodo) è stato settato
    if (isset($path[1])){
        // nel caso lo sovrascrivo
        $function_called=$path[1];
    }
    // se l'utente entra nella url con solo dominio.com lo direziono sulla home
    if(empty($path[0])){
        $path[0] = "home";
    }
    // controllo se esiste il controller|classe inerente
    if (!file_exists("controller/".$path[0].".php")) mostra_errore("Controller non esistente");
    // carico il file di classe
    require_once ("controller/".$path[0].".php");

    try{
        // Creo un'istanza della classe
        $class_instance = new $path[0]();
        // Controllo se esiste i metodi che richiamo nelle istanze
        if (!method_exists($class_instance,$function_called)) mostra_errore("Metodo '$function_called' non esistente");
        // creo delle costanti in base alla classe
        switch($path[0]){
            case "home": 
                defined("VIEW_FOLDER")  |  define("VIEW_FOLDER", "view/sito/");
                break;
            case "accesso":
                defined("VIEW_FOLDER") | define("VIEW_FOLDER", "view/accesso/");
                break;
            case "utente":
                defined("VIEW_FOLDER") | define("VIEW_FOLDER", "view/utenti/");
                break;
            case "admin":
                defined("VIEW_FOLDER") | define("VIEW_FOLDER", "view/admin/");
                break;
            default: 
                defined("VIEW_FOLDER")  |  define("VIEW_FOLDER", "view/");
        }

        unset($path[0]);
        unset($path[1]);
        // eseguo la funzione nella classe scelta con parametri 2 o 3 se esistono
        $class_instance->{$function_called}(@$path[2],@$path[3]);
    } catch(Throwable $e) {
        echo "Errore ".$e;
    }
}

?>