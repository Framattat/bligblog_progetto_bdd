<?php

require_once("models/utente_model.php");

class validator{
    // array di regex, commenterò le regex più 'complesse'
    private $regole_regex = [
        'username'=>'[a-zA-Z0-9]{3,50}',
        // regex almeno un numero e una lettera, almeno 8 caratteri
        'password'=>'(?=.*[0-9])(?=.*[a-zA-Z])(?=\S+$).{8,50}',
        'nome'=>'[a-z A-Z]{2,50}',
        'cognome'=>'[a-z A-Z]{2,50}',
        'cellulare'=>'[0-9]{8,15}',
        // regex per carta d'identità, vecchia o nuova
        'carta'=>'([A-Z]{2}[0-9]{5}[A-Z]{2})|(^[A-Z]{2}[0-9]{7})',
        // regex per patenti del 2013 (la prima) e patenti antecedenti al 2013
        'patente'=>'([A-Z]{2}[0-9]{7}[A-Z])|(^[U]1[BCDEFGHLMNPRSTUWYXZ]\w{6}[A-Z])',
        // regex base per codice fiscale di persona fisica
        'codice'=>'[A-Z]{6}[0-9]{2}[A-Z]\d{2}[A-Z]\d{3}[A-Z]',
        // regex per creazione blog con lettere numeri o simboli
        'titolo_blog'=> '[A-z À-ú 0-9 !?,;:._-£$€]{3,200}',
        'descrizione_blog'=> '(.*){0,200}',
        'indirizzo_blog'=> '[a-z0-9_-]{5,30}',
        // regex per creazione di articoli
        'tags'=>'[a-zA-Z]{2,10}' 
    ];

    //funzione che valida i dati di registrazione in base alle regex chiamate
    function registrazione_validator($dati){
        $errori = [];
        if(isset($dati['password'])){
            if (!$this->controllo_regex('password',$dati['password'])){
                $errori[]="Il campo 'Password' deve contenere almeno otto caratteri, di cui almeno una lettera e un numero"; 
            }
            // se entro qua vuol dire che non ho la password, nella modifica del profilo non posso modificare la password, quindi vuole dire che sto modificando il profilo
        } else {
            //controllo se i campi univoci sono uguali, se lo sono non li controllo successivamente
            $utente = new utente_model();
            $utente_modifica = $utente->recupera_utente($_SESSION['id_utente']);
            if ($dati['username'] == $utente_modifica['username_utente']){
                unset($dati['username']);
            }
            if ($dati['cellulare'] == $utente_modifica["telefono_utente"]){
                unset($dati['cellulare']);
            }
            if ($dati['estremi_documento'] == $utente_modifica["estremi_documento_utente"]){
                unset($dati['estremi_documento']);
            }
        }

        if (!$this->controllo_regex('nome',$dati['nomeU'])){
            $errori[]="Il campo 'Nome' deve contenere almeno due lettere"; 
        }
        if (!$this->controllo_regex('cognome',$dati['cognomeU'])){
            $errori[]="Il campo 'Cognome' deve contenere almeno due lettere"; 
        }
        //funzione php per controllare le email
        if (!filter_var($dati['email'], FILTER_VALIDATE_EMAIL)){
            $errori[]="Il campo 'Email' deve contenere un indirizzo email valido"; 
        }
        //controllo i dati univoci, non sono settati se non cambiati (l'utente non sta modificando il proprio dato)
        if(isset($dati['username'])){
            if (!$this->controllo_regex('username',$dati['username'])){
                $errori[]="Il campo 'Username' deve contenere almeno tre caratteri alfanumerici senza spazi"; 
            } else {
                $db = new db();
                $utenza_esistente = $db->select_one("utenti",["username_utente"=>$dati['username']]);
                if(isset($utenza_esistente)&&!empty($utenza_esistente)){
                    $errori[]= "Username già esistente";
                }
            }
        }
        if(isset($dati['cellulare'])){
            if (!$this->controllo_regex('cellulare',$dati['cellulare'])){
                $errori[]="Il campo 'Telefono' deve contenere tra le otto e quindici cifre numeriche"; 
            } else {
                $db = new db();
                $telefono_esistente = $db->select_one("utenti",["telefono_utente"=>$dati['cellulare']]);
                if(isset($telefono_esistente)&&!empty($telefono_esistente)){
                    $errori[]= "Numero Cellulare già esistente";
                }
            }
        }
        //in base al tipo di documento, restituisco risposta corretta
        if(isset($dati['estremi_documento'])){
            if(isset($dati['tipo_documento'])){
                switch($dati['tipo_documento']){
                    case "carta_identita":
                        if (!$this->controllo_regex('carta',$dati['estremi_documento'])){
                            $errori[]="Il campo 'Estremi documento' deve contenere un numero di carta d'identità valido"; 
                        } else {
                            $db = new db();
                            $carta_i = $db->select_one("utenti",["estremi_documento_utente"=>$dati['estremi_documento']]);
                            if(isset($carta_i)&&!empty($carta_i)){
                                $errori[]= "Carta d'identità già esistente";
                            }
                        }
                        break;
                    case "patente":
                        if (!$this->controllo_regex('patente',$dati['estremi_documento'])){
                            $errori[]="Il campo 'Estremi documento' deve contenere un numero di patente valido"; 
                        } else {
                            $db = new db();
                            $patente = $db->select_one("utenti",["estremi_documento_utente"=>$dati['estremi_documento']]);
                            if(isset($patente)&&!empty($patente)){
                                $errori[]= "Patente già esistente";
                            }
                        }
                        break;
                    case "codice_fiscale":
                        if (!$this->controllo_regex('codice',$dati['estremi_documento'])){
                            $errori[]="Il campo 'Estremi documento' deve contenere un codice fiscale valido"; 
                        } else {
                            $db = new db();
                            $codice_f = $db->select_one("utenti",["estremi_documento_utente"=>$dati['estremi_documento']]);
                            if(isset($codice_f)&&!empty($codice_f)){
                                $errori[]= "Codice fiscale già esistente";
                            }
                        }
                        break;
                    default:
                        if ($dati['estremi_documento']==""){
                            $errori[]="Il campo 'Estremi documento' è vuoto";
                        } else {
                            $errori[]="Selezione non valida del campo 'Estremi documento'";
                        }
                }
            } else {
                $errori[] = "Seleziona un documento!";
            }
        }
        if (!sizeof ($errori)){
            return true;
        }
        return implode("<br/>",$errori);
    }
    //funzione per controllare i dati di un blog, la funzione accetta la creazione o modifica di dati di blog
    function blog_validator($dati){
        $errori = [];
        if (!$this->controllo_regex('titolo_blog',$dati['titoloBlog'])){
            $errori[]='Il campo "Titolo" deve contenere almeno tre caratteri alfanumeri e/o questi simboli !?,;:._-£€$ '; 
        }

        if (!$this->controllo_regex('descrizione_blog',$dati['descrizione_blog'])){
            $errori[]="Il campo 'Descrizione' deve contenere al massimo 200 caratteri"; 
        }
        // controllo se modifica blog è settato, mi permette di fare il controllo sulla "chiave" del blog quando il blog sta venendo creato
        if(!isset($dati["modifica_blog"])){
            if (!$this->controllo_regex('indirizzo_blog',$dati['indirizzo_blog'])){
                $errori[]="La parola chiave deve contenere almeno 5 caratteri alfanumerici senza accento, simboli o spazi"; 
            } else {
                $db = new db();
                $indirizzo_esistente = $db->select_one("blog",["indirizzo_blog"=>$dati['indirizzo_blog']]);
                if(isset($indirizzo_esistente)){
                    $errori[]= "La parola chiave è già esistente";
                }
            }
        }

        if (!isset($dati['id_categoria']) || $dati['id_categoria'] == ""){
            $errori[]="Selezione non valida del campo 'Categoria'";
        }

        if (!isset($dati['id_template']) || $dati['id_template'] == ""){
            $errori[]="Selezione non valida del campo 'Template'";
        }
        if (!sizeof ($errori)){
            return true;
        }
        return implode("<br/>",$errori);
    }
    //funzione per il controllo dei dati coautore, controlla se il coautore esiste, è già coautore o il campo è vuoto
    function coautore_validator($dati){
        $errori = [];
        $db = new db();
        if(!empty($dati["nome_coautore"])){
            $check_autore = $db->select("utenti",["username_utente"=>$dati["nome_coautore"]]);
            if(!empty($check_autore)){
                $id_utente_autore = $db->get_one("SELECT id FROM utenti WHERE username_utente = ? ",[$_POST["nome_coautore"]])["id"];
                $esito_doppione = $db->select("autori",["id_utente"=>$id_utente_autore,"id_blog"=>$dati["id_blog"]]);
                if(empty($esito_doppione)){
                    return true;
                } else {
                    $errori = ["Hai già come coautore ".$dati["nome_coautore"]];
                }
            } else {
                $errori = ["Sembra che l'utente non esista"];
            }
        } else {
            $errori = ["Inserisci l'username dell'utente prima"];
        }
        return implode("<br/>",$errori); 
    }
    //funzione che matcha le regex con il campo scelto
    function controllo_regex($campo,$valore){
        $re = '/^'.$this->regole_regex[$campo].'$/mi';
        preg_match($re, trim($valore), $matches, PREG_OFFSET_CAPTURE, 0);
        if (!sizeof($matches))
            return false;
        return true;
    }
    //funzione che controlla i voucher e restituisce un risultato in base al controllo 
    function voucher_validator($voucher){ 
        $db = new db();
        // uso FOR UPDATE per evitare inserimenti di voucher contemporanei
        $check_voucher = $db->get_one("SELECT contatore_voucher, utilizzi_massimi_voucher,id_abbonamento FROM voucher WHERE id_abbonamento = ? AND codice_voucher = ? FOR UPDATE ", $voucher);
        if (empty($check_voucher)){
            // errore voucher non trovato    
            return 2;
        } else {
            // controllo che il codice voucher non sia esaurito e mi assicuro di star selezionando il voucher corretto
            if($check_voucher["contatore_voucher"]<$check_voucher["utilizzi_massimi_voucher"] && $check_voucher["id_abbonamento"] == $voucher["id_abbonamento"]){
                $db->update("voucher",["contatore_voucher"=>($check_voucher["contatore_voucher"]+1)], ["codice_voucher"=>$voucher["voucher"]]);
                return 1;
            } else {
                //avendo inserito il FOR UPDATE devo completare l'update, reinserisco semplicemente i dati
                $db->update("voucher",["contatore_voucher"=>$check_voucher["contatore_voucher"]], ["codice_voucher"=>$voucher["voucher"]]);
                return 3;
            }
        }
    }
    //funzione che controlla un articolo, di default non ci sono file da controllare
    function articolo_validator($dati,$files=null){
        $errori = [];
        if (!$this->controllo_regex('titolo_blog',$dati['titoloA'])){
            $errori[]="Il Titolo dell'articolo deve contenere almeno tre caratteri"; 
        }
        if (!isset($dati["descrizioneA"])||$dati['descrizioneA'] == ""){
            $errori[]="Scrivi qualcosa nel tuo articolo!"; 
        }
        if (!isset($dati['data_pubblicazione'])||$dati['data_pubblicazione'] == ""){
            $errori[]="Inserisci una data di pubblicazione!"; 
        } else {
            if($dati['data_pubblicazione'] < date('Y-m-d')){
                $errori[]="Non puoi pubblicare qualcosa nel passato!"; 
            }
        }
        // non essendo i tag obbligatori, li controllo se sono settati
        if(isset($dati['tags']) && $dati['tags']!="")
            foreach(explode(",",$dati['tags']) as $k=>$value){
                if (!$this->controllo_regex('tags',$value)){
                    $errori[]="Problemi nel tag ".$k."! I tag devono essere dalle 2 alle 10 lettere soltanto"; 
                }
            }
        // non essendo i file/immagini obbligatori, controllo le immagini se settati 
        if (!is_null($files)){
            $estensioni_controllo = ["jpg","jpeg"];
            foreach($files as $k=>$value){
                if($files[$k]["size"]>0){
                    $estensione = explode("/",$value["type"]);
                    if(!in_array(end($estensione),$estensioni_controllo)){
                        $errori[]="Il file ".$value["name"]." non è valido, carica un'immagine jpg";
                    }
                }
            }
        }
        if (!sizeof ($errori)){
            return true;
        }
        return implode("<br/>",$errori); 
    }
}

?>