<?php
//funzione che porta nella pagina di errore, per ora è supportato solo la pagina 404
function mostra_errore($dettagli="",$codice=404){
    http_response_code($codice);
    defined("VIEW_FOLDER")||define("VIEW_FOLDER","sito/");
    require_once("view/sito/header.php");
    switch($codice){
        case 404: 
            require_once("view/errori/error404.php");
            break;
        default:
            require_once("view/errori/error.php");
    }
    require_once("view/sito/footer.php");
    die; 
}
//funzione di redirect per le pagine passando la url come parametro posso richiamarla ovunque per direzionare l'utente dove voglio
function redirect($url){
    header("Location: $url");die;
}

?>