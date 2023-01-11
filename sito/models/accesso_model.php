<?php

require_once("libraries/validator.php");
require_once("helper/login_helper.php");
require_once("helper/utente_helper.php");


class accesso_model{

    private $tabella_utente = "utenti";
    //funzione che controlla il login di un utente, prendo in post username e password per controllare
    function check_login(){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $db = new db();
        //Utilizzo la bind parameters per sicurezza
        $dati_utente = $db->select_one($this->tabella_utente,["username_utente"=> $username]);
        if (!is_null($dati_utente)){
            // controllo la password
            $password_check = password_verify($password,$dati_utente['password_utente']);
            if ($password_check) {
                // in base alla tipologia di utente
                $this->esegui_accesso($dati_utente);
                 //implementazione futura, per pannello admin
                if($dati_utente['admin']){
                    redirect("/admin?msg=Login avvenuto con successo!&success ");
                }
                redirect("/utente/userpage?msg=Login avvenuto con successo!&success ");
            }else{
                redirect("/accesso/login?msg=Password non valida&danger");
            }
        }else{
            //errore utente non registrato 
            redirect("/accesso/login?msg=Username non valido&danger");
        }
    }
    // funzione per la registrazione utente, controllo i dati prima e procedo ad inserirli nel database
    function registrazione(){
        $validator = new validator();
        $esito = $validator->registrazione_validator($_POST);
        if ($esito !== true){
            redirect("/accesso/registrazione?msg=Registrazione fallita&danger ");
            return;
        } 
        $dati_insert = [
            "username_utente" => trim($_POST['username']),
            //Creo un hash monodirezionale della password
            "password_utente" => password_hash(trim($_POST['password']),PASSWORD_BCRYPT),
            "nome_utente" => trim($_POST['nomeU']),
            "cognome_utente" => trim($_POST['cognomeU']),
            "email_utente" => trim($_POST['email']),
            "telefono_utente" => trim($_POST['cellulare']),
            "tipo_documento" => trim($_POST['tipo_documento']),
            "estremi_documento_utente" => strtoupper(trim($_POST['estremi_documento']))
        ];
        $db = new db();
        $registrato=$db->insert($this->tabella_utente, $dati_insert);
        $utente = $db->select_one($this->tabella_utente, ["username_utente"=> $dati_insert["username_utente"]]);
        // setto l'abbonamento base per l'utente
        abbonamento_registrazione($utente["id"]);
        $this->esegui_accesso($utente);
        redirect("/utente/userpage?msg=Registrazione avvenuta con successo!&success ");
    }
    //funzione per il salvataggio di dati per l'utente che logga, implementazione futura con admin
    function esegui_accesso($dati_utente){
        $_SESSION['utente_loggato'] = json_encode($dati_utente);
        $_SESSION['admin'] = $dati_utente['admin'];
        $_SESSION['id_utente'] = $dati_utente['id'];
        abbonamento_attivo();
    }
    //funzione per il logout, 'distruggo' la sessione corrente ed effettuo il lougout
    function logout(){
        session_destroy();
        redirect("/accesso/login?msg=Logout avvenuto con successo!&success");
    }
}
?>