<!-- carico tinymce, un editor di testo che permette una personalizzazione migliore dei propri articoli -->
<script src="/view/assets/tinymce/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
      tinymce.init({
        selector: '#descA',
		<?php if(isset($articolo)) { ?>
			setup: function (editor) {
				editor.on('init', function (e) {
					editor.setContent("<?=str_replace(["\r\n",'"'],["","“"],$articolo['testo_articolo'])?>");
				});
			},
		<?php }?>
		language: "it",
        plugins: [
          'advlist','autolink',
          'lists','link','charmap','preview','anchor','searchreplace','visualblocks',
          'fullscreen','insertdatetime','table','help','wordcount'
        ],
        toolbar: 'undo redo | formatpainter casechange blocks | bold italic backcolor | ' +
          'alignleft aligncenter alignright alignjustify | ' +
          'bullist numlist checklist outdent indent | removeformat | a11ycheck code table help'
      });	
    </script>
	<style>
		.tox-notification { display: none !important }
	</style>

<!-- la stessa pagina per la modifica o creazione dell'articolo, in base al campo 'm' che sta per modifica -->
<section style="font-family: 'roboto', sans-serif;">
	<div class="container">
		<h1 class="text-center">
			<?=(!isset($_GET["m"])?"Aggiungi un articolo in:</br>".$blog["titolo_blog"]:"Modifica l'articolo: </br>".$articolo["titolo_articolo"] )?>
		</h1>
		<div class="row">
			<?php 
				if(isset($_GET['msg'])){
					alert($_GET['msg']);
				}
			?>
		</div>
		<form enctype="multipart/form-data" id="form_articolo" action="/blog/<?=$blog["indirizzo_blog"]?>/<?=(!isset($_GET["m"])?"inserisci_articolo":"modifica_articolo" )?>" method="POST">
			<input type="hidden" name="id_blog" value=<?=$blog["id"]?>>
			<input type="hidden" name="id_articolo" value=<?=(isset($articolo)?$articolo["id"]:"")?>>
			<input type="hidden" name="id_utente_articolo" value=<?= $_SESSION["id_utente"]?>>
			<input type="hidden" name="indirizzo_blog" value=<?= $blog["indirizzo_blog"]?>>
			<div class="row mt-5 mb-5">
				<div class="col-12 col-md-5 mb-3">
					<h3>
						Rendi una bozza
					</h3>
					<input type="radio" id="radio_bozza_si" name="bozza" value=1 <?=(isset($articolo)&&$articolo["bozza"]==1?"checked=checked":"")?>>
					<label for="radio_bozza_si" class="me-2">Sì</label>
					<input type="radio" id="radio_bozza_no" name="bozza" value=0 
						<?php if(isset($articolo)&&$articolo["bozza"]==0){ 
							echo "checked=checked";
						} else if(!isset($_GET["m"])){
							echo"checked=checked";
							}?>
					>
					<label for="radio_bozza_no">No</label>
				</div>
				<div class="col-12 col-md-7">
					<h3>
						Quando vuoi pubblicare questo articolo?
					</h3>
					<input id="data_pubblicazione" name="data_pubblicazione" class="form-control" type="datetime-local" value="<?=date("Y-m-d H:i")?>" />
				</div>
			</div>
			<div class="row justify-content-center mt-4 mb-4">
				<div class="col">
					<h2>
						Titolo del tuo articolo
					</h2>
					<div class="form-outline w-100">
						<input type="text" name="titoloA" class="form-control" id="titoloA" placeholder="Scrivi qua il tuo titolo!" value="<?= (!isset($_GET["m"])?"":$articolo["titolo_articolo"])?>">
					</div>
				</div>
			</div>
			<div class="row mt-4">
				<div class="col">
					<h2>
						Contenuto Articolo
					</h2>
					<div class="form-outline w-100">
						<textarea name="descrizioneA" class="form-control" id="descA" rows="4" placeholder="Scrivi qua il tuo articolo!"></textarea>
					</div>
				</div>
			</div>
			<div class="row mt-4">
				<p><em>**Campo facoltativo</em></p>
				<div class="col">
					<label for="tags_form" class="form-label">Tags o parole chiave dell'articolo divise da una virgola (es: <strong>cani</strong>,<strong>gatti</strong>, etc.)**</label>
					<input class="form-control" type="text" id="tags_form" name="tags" value="<?= (!isset($_GET["m"])?"":$articolo["tags"])?>" placeholder="tag1,tag2,tag3...">
				</div>
			</div>
			<div class="row mt-5 mb-5">
				<div class="col-12 col-lg-6 mb-4">
					<label for="immagine_form" class="form-label">Carica un'immagine di copertina**</label>
					<input class="form-control" type="file" id="immagine_form" name="cop" accept="image/jpg" >
				</div>
				<div class="col-12 col-lg-6">
					<label for="immagine_form_2" class="form-label">Carica un'immagine per l'articolo**</label>
					<input class="form-control" type="file" id="immagine_form_2" name="art" accept="image/jpg">
				</div>
			</div>
			<div class="row mt-4 text-center">
				<div class="col">
					<button type="submit" class="btn btn-lg w-50 btn-primary"><?=(!isset($_GET["m"])?"Crea":"Modifica")?> Articolo</button>
				</div>
			</div>
		</form>
	</div>
</section>
