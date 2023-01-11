<?php

class template{
    private $id_template;
    private $data_template;
    private $data_blog;
    //funzione che recupera nome e descrizione template, la descrizione non è utilizzata possibile implementazione usata per test
    function use_template($id_template){
        $this->id_template=$id_template;
        $db = new db();
        $this->data_template = $db->select_one("template",["id"=>$id_template]);
        return $this;
    }
    //funzione che recupera il template e i dati del blog 
    function use_template_from_blog($id_blog,$blog_data=null){
        if (is_null($blog_data)){
            $db = new db();
            $blog_data = $db->select_one("blog_full",["id"=>$id_blog]);
        }
        $this->id_template = $blog_data["id_template"];
        $this->data_blog = $blog_data;
        return $this;
    }
    //funzione per la costruzione della pagina, genera la pagina dinamicamente e con dati 'base' da inserire in ogni pagina
    function get_html($pagina, $data=[]){
        if (!is_null($this->data_blog)){
            $data["id_blog"] = $this->data_blog["id"];
            $data["indirizzo_blog"] = $this->data_blog["indirizzo_blog"];
            $data["titolo_blog"] = $this->data_blog["titolo_blog"];
            $data["descrizione_blog"] = $this->data_blog["descrizione_blog"];
            $data["nome_template"] = $this->data_blog["nome_template"];
        }
        // in base a quanti dati ho creo delle variabili da portare nella mia pagina html
        foreach ($data as $key=>$value){
            ${$key}=$value;
        }
        $html = require_once("template/header.php");
        if($pagina == "userarticle" || $pagina == "userarticles"){
            $html.= require_once("template/".$pagina.".php");
        } else {
            $html.= require_once("template/".$this->id_template."/".$pagina.".php");
        }
        $html.= require_once("template/footer.php");
        
    }
}

?>