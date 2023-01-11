<!-- carico le librerie di datatables, mi permettono di visualizzare gli articoli in modo efficace ed intuitivo -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
<script>
$(document).ready( function () {
    $('#articoli').DataTable({
        "language":{
            "url":"//cdn.datatables.net/plug-ins/1.12.1/i18n/it-IT.json"
        }
    });
} );
</script>

<div class="container" style="font-family: 'roboto', sans-serif;">
    <?php
		if(isset($_GET['msg'])){
			alert($_GET['msg']);
		}
	?>
    <div class="row mt-3 mb-5 text-center">
        <h1 class="text-uppercase">Gestione articoli</h1>
        <div class="col-12">
            <a class="btn btn-outline-primary mt-3" href="/blog/<?=$indirizzo_blog?>/article_editor">Scrivi un articolo!</a>
        </div>
    </div>
    <table id="articoli" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Azioni</th>
                <th>Titolo</th>
                <th>Autore</th>
                <th>Creazione</th>
                <th>Tags</th>
                <th>Pubblicato</th>
            </tr>
        </thead>
        <tbody>
            <!-- genero dinamicamente righe, in base a quanti articoli ho per il blog corrente -->
            <?php foreach($db->select("articoli_full",["id_blog"=>$id_blog]) as $k=>$value){
                ?> 
                <tr>
                    <td><a href="/blog/<?=$indirizzo_blog?>/articolo/<?=$value['id']?>" target="_blank" ><i class="fa fa-eye text-black"></i></a> <a href="/blog/<?=$indirizzo_blog?>/article_editor/?id=<?=$value["id"]?>&m=on"><i class="fa fa-edit text-black"></i></a> <a href="/blog/<?=$value["indirizzo_blog"]?>/cancella_articolo?id=<?=$value['id']?>&id_utente=<?=$value['id_utente_articolo']?>" onclick="if(!confirm('Cancellare un articolo?')){ return false; }"><i class="fa fa-trash text-black"></i></a></td>
                    <td><?=$value["titolo_articolo"]?></td>
                    <td><?=$value["username_utente"]?></td>
                    <td><?=formatta_data($value["data_creazione_articolo"])?></td>
                    <td><?=$value["tags"]?></td>
                    <td><?=($value["pubblicato"]?"Sì":"No")?></td>
                </tr>
            <?php }?>
        </tbody>
        <tfoot>
            <tr>
                <th>Azioni</th>
                <th>Titolo</th>
                <th>Autore</th>
                <th>Creazione</th>
                <th>Tags</th>
                <th>Pubblicato</th>
            </tr>
        </tfoot>
    </table>
</div>