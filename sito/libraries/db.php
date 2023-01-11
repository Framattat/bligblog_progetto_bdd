<?php

class db {
    // parametri di connessione, la connessione avviene per oggetti e non con metodo procedurale
    private $host = "localhost";
    private $utente = "root";
    private $password = "";
    private $db_name = "blig_blog";
    public static $conn = false;
    public $errore = false;
    
    function __construct(){
        // se una connessione è già attiva non mi connetto
        if (self::$conn === false) $this->connect();
    }
    //funzione per connettersi
    function connect(){
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $mysqli = new mysqli($this->host,$this->utente,$this->password,$this->db_name); 
        self::$conn=$mysqli;
    }
    // con questa funzione chiudo la connessione (è stata fatto questo metodo per poter chiuderla più facilmente)
    function close(){
        self::$conn->close();
        self::$conn = false;
    }
    //funzione che gestisce le query ed unisce i parametri che vengono passati, questo controllo viene fatto per evitare di inserire dati corrotti o query maligne 
    function query($query,$data=[]){
        try{
            $stmt = self::$conn->prepare($query);
            if (sizeof($data)>0) {
                $tipi = str_repeat('s',count($data));
                $stmt->bind_param($tipi, ...array_values($data));
            }
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
            
        } catch(Exception $e){
            echo "son quadi";
            logg("error", "errore query database: ".$e->getMessage());
            $this->errore=$e->getMessage();
            return false;
        }
    }
    //funzione get, creo una mia query passando i miei dati, la get è pensata per essere una select più specifica
    function get($query,$data=[]){
        $result = $this->query($query,$data);
        $risultati = [];
        // se la query ritorna un errore, passo un array vuoto per evitare errori nel sito
        if (!$result) return [];
        //creo un array con i risultati
        foreach($result as $value){
            $risultati[] = $value;
        }
        return $risultati;
    }
    //funzione che ritorna in base alla mia query una sola riga con un solo indice, come la get, è pensata per select più specifiche
    function get_one($query,$data=[]){
        $result = $this->query($query,$data);
        // se la query ritorna un errore, ritorno un array vuoto altrimenti ritorno l'oggetto
        return (!$result?[]:$result->fetch_assoc());
    }
    //funzione di select 'base', passo tabella e i dati where per restituire tutti i valori che matchano
    function select($tabella, $where_array){
        $keys = [];
        $valori =[];
        foreach ($where_array as $key=>$value){
            $keys[] = $key."=?";
            $valori[] = $value;
        }
        $query = "SELECT * FROM $tabella WHERE ".implode(" AND ",$keys);
        $result = $this->get($query,$valori);
        return $result;
    }
    //funzione di select_one 'base', passo tabella e i dati where per restituire una riga che matcha
    function select_one($tabella, $where_array){
        $keys = [];
        $valori =[];
        foreach ($where_array as $key=>$value){
            $keys[] = $key."=?";
            $valori[] = $value;
        }
        $query = "SELECT * FROM $tabella WHERE ".implode(" AND ",$keys);
        $result = $this->get_one($query,$valori);
        return $result;
    }
    //funzione di insert dati, passo tabella e dati da inserire creo la query dinamicamente da mandare nella funzione query
    function insert($tabella, $dati_insert){
        $keys = [];
        $valori =[];
        foreach ($dati_insert as $key=>$value){
            $keys[] = $key;
            $valori[] = $value;
        }
        $keys = implode(",",$keys);
        $punto_domanda = str_repeat('?,', count($dati_insert)-1).'?';
        $query = "INSERT INTO $tabella ($keys) VALUES ($punto_domanda)";
        $result = $this->query($query,$valori);
        return $result;
    }
    //funzione per cancellare i dati, passo la tabella e i dati da cancellare, creo la query dinamicamente da mandare nella funzione query
    function delete($tabella, $dati_delete){
        $keys = [];
        $valori =[];
        foreach ($dati_delete as $key=>$value){
            $keys[] = $key."=?";
            $valori[] = $value;
        }
        $query = "DELETE FROM $tabella WHERE ".implode(" AND ",$keys);
        $result = $this->query($query,$valori);
        return $result;
    }
    //funzione per aggiornare i dati, passo tabella, dati da modificare e dati per la where, creo la query dinamicamente e la mando alla funzion query
    function update($tabella, $dati_update, $wheres){
        $keys = [];
        $valori =[];
        foreach ($dati_update as $key=>$value){
            $keys[] = $key."=?";
            $valori[] = $value;
        }
        $keys_where = [];
        foreach ($wheres as $key=>$value){
            $keys_where[] = $key."=?";
            $valori[] = $value;
        }
        $query = "UPDATE $tabella SET ".implode(",",$keys)." WHERE ".implode(" AND ", $keys_where);
        $result = $this->query($query,$valori);
        return $result;
    }
    //funzione per la ricerca dei blog, passo tabella le keywords necessarie alla ricerca i campi in cui cerco e la condizione necessaria in questo caso settata ad or
    function massive_search($tabella, $keywords, $campi, $and_or="OR"){
        $where = [];
        foreach($keywords as $chiave){
            foreach($campi as $campo){
                //uso like per cercare ogni parola simile alla chiave che ho scelto nel campo che viene generato dinamicamente
                $where[] = "$campo LIKE '%$chiave%'";
            }
        }
        $query = "SELECT * FROM $tabella WHERE ".implode(" $and_or ",$where);
        $result = $this->get($query);
        return $result;
    }
}

?>