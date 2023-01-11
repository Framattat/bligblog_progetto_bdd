
<section>
	<div class="container">
		<div class="row">
			<?php 
				if(isset($_GET['msg'])){
					alert($_GET['msg']);
				}
			?>
		</div>
		<div class="row mb-2 text-center">
			<h1 class="mb-5" style="text-transform: uppercase;">I tuoi blog</h1>
			<div class="col-12 text-center ps-0">
				<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#blog_crea" >
					<i class="fa fa-plus-circle"></i>  Crea il tuo blog!
				</button>
			</div>
		</div>
		<div class="row mb-5 text-center justify-content-center">
			<?php 
				foreach($db->get("SELECT * FROM blog_full WHERE id_utente = $_SESSION[id_utente] ORDER BY titolo_blog ASC" ) as $k=>$blog ){   
			?>
			<div class="card py-3 m-3 mt-1 col-12 col-lg-3">
				<h1><?=$blog["titolo_blog"]?></h1>
				<h5><?=$blog["nome_categoria"]?></h5>
				<?=(isset($data["sottocategorie"])&&!empty($data["sottocategorie"])?popola_sottocategorie($blog["sottocategorie"]):"")?>					
				<a href="/blog/<?=$blog["indirizzo_blog"]?>" target="_blank" class="nav-link">
					<h5>Visualizza Blog</h5>
				</a>
				<a href="/blog/<?=$blog["indirizzo_blog"]?>/lista_articoli" target="_blank" class="nav-link">
					<h5>Gestione articoli</h5>
				</a>
				<h6 class="text-capitalize"><?=$blog["nome_template"]?></h6>
				<p><?=formatta_data($blog["data_creazione_blog"])?></p>
				<hr>
				<div class="d-inline">
					<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#blog_modifica_<?=$k?>">
						<i class="fa-solid fa-gear me-1"></i> Impostazioni Blog
					</button>
				</div>
			</div>

			<div class="modal fade" id="blog_modifica_<?=$k?>" tabindex="-1" aria-labelledby="modifica_blog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="modifica_blog">Impostazioni del blog</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
								<a href="/utente/cancellare_blog?id=<?=$blog['id']?>" onclick="if(!confirm('Cancellare un blog?')){ return false; }" class="text-decoration-none py-3" title="" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Cancella Blog">
									<button type="button" class="btn btn-danger">
										<i class="fa fa-times-circle"></i> Cancella il blog
									</button>
								</a>
								<hr>
								<h5 class="mb-3">Modifica il blog</h5>
								<form id="form_<?=$k?>" action="/utente/modifica_blog" action_validate="/utente/async_valida_form_blog" method="POST">
									<div class="mb-3">
										<label for="titoloBlog" class="form-label">Titolo Blog</label>
										<input type="text" name="titoloBlog" class="form-control" id="titoloBlog" aria-describedby="titoloblog" value="<?=$blog["titolo_blog"]?>">
									</div>
									<div class="mb-3">
										<label for="descrizione_blog" class="form-label">Descrizione del Blog</label>
										<textarea name="descrizione_blog" class="form-control" id="descrizione_blog" aria-describedby="descrizione_blog"><?=$blog["descrizione_blog"]?></textarea>
									</div>
									<input type="hidden" name="modifica_blog" value="1">
									<input type="hidden" name="id_blog" value=<?=$blog["id"]?>>
									<select name="id_categoria" class="form-select text-center mb-3 pe-1" aria-label="seleziona_categoria" onchange="aggiorna_sottocategorie($(this).val(),'select2_sottocategorie_add_modifica_<?=$k?>');$('#selezione_sottocategoria_modifica_<?=$k?>').slideDown();">
										<option selected disabled>Seleziona la categoria del blog!</option>
										<?php foreach($db->get("SELECT * FROM categoria ORDER BY id") as $categoria){ ?>
											<option value="<?=$categoria['id']?>" <?=($blog["id_categoria"] == $categoria['id']?"selected":"") ?>><?= $categoria['nome_categoria']?></option>
											
										<?php }?> 
									</select>
									<div id="selezione_sottocategoria_modifica_<?=$k?>">
										<label for="sottocategoria[]" class="form-label">Sottocategoria:</label>
										<select multiple="multiple" name="sottocategoria[]" id="select2_sottocategorie_add_modifica_<?=$k?>" class="select2_modale form-select text-center mb-3" aria-label="seleziona_sottocategoria" style="width:100%;">
											<?php 
												foreach($db->get("SELECT * FROM sottocategorie ORDER BY id") as $sottocategoria){
											?>
											<option disable class="sottocategoria sottocategoria_<?=$sottocategoria['id_categoria']?>" value="<?=$sottocategoria["id"]?>" data-categoria="<?=$categoria['id']?>"> <?= $sottocategoria['nome_sottocategoria']?></option>
											<?php }?> 
										</select>
									</div>
									<script>$("#selezione_sottocategoria_modifica_<?=$k?>").hide()</script>
									<select name="id_template" class="form-select text-center mt-3 mb-3 pe-1 text-capitalize" aria-label="seleziona_documento">
										<option selected disabled>Seleziona il template</option>
										<?php 
											foreach($lista_template_disponibili as $k_temp=>$value_temp){
										?>
										<option value="<?=$k_temp?>" <?=($blog["id_template"] == $k_temp?"selected":"") ?>><?= $value_temp?></option>
										<?php }?> 
									</select>
									<div id="box_errori_<?=$k?>" class="text-danger text-center">
									</div>
									<button type="button" onclick="invia_form('<?=$k?>',$(this));" class="btn btn-primary" style="width: 100%;">Modifica!</button>
								</form>
							<hr>
							<div>
								<h5>Collabora con altri utenti</h5>
								<form id="form_<?=$k?>_a" action="/utente/coautore" action_validate="/utente/async_valida_form_coautore" method="POST">
									<div class="mb-3">
										<label for="nome_coautore" class="form-label">Scegli l'utente da rendere coautore</label>
										<input type="text" name="nome_coautore" class="form-control" id="nome_coautore" aria-describedby="nomecoautore">
										<input type="hidden" name="id_blog" value=<?=$blog["id"]?>>
										<input type="hidden" name="id_utente" value=<?=$_SESSION["id_utente"]?>>
									</div>
									<div id="box_errori_<?=$k?>_a" class="text-danger text-center">
									</div>
									<button type="button" onclick="invia_form('<?=$k?>_a',$(this));" class="btn btn-primary ms-2">
										<i class="fa fa-edit"></i> Rendi co-autore
									</button>
								</form>
							</div>
							<?php 
								$coautori=$db->select("autori",["id_blog"=>$blog['id']]);
								if(!empty($coautori)){
								?>
								<div class="mt-4 mb-3">
									<h5>Rimuovi Co-autori:</h5>
									<?php foreach($coautori as $k=>$autore){
										$utente= $db->select_one("utenti",["id"=>$autore["id_utente"]]);
										?>
										<a href="/utente/cancellare_coautore?id=<?=$autore['id']?>&blog=<?=$autore['id_blog']?>" onclick="if(!confirm('Vuoi rimuovere <?=$utente['username_utente']?> come coautore?')){ return false; }" class="text-decoration-none py-3" title="" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Cancella coautore">
											<button type="button" class="btn btn-primary mt-2">
												<i class="fa fa-times-circle"></i> <?=$utente["username_utente"]?>
											</button>
										</a>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
		<hr>
		<div class="row mt-5 mb-5 text-center">
			<h1 style="text-transform: uppercase;">I blog in cui collabori</h1>
		</div>
		<div class="row mb-5 text-center justify-content-center">
			<?php 
				$blog_collabora = $db->get("SELECT titolo_blog, username_utente, indirizzo_blog, nome_categoria, nome_template, data_creazione_blog, sottocategorie, autori.id_blog, autori.id as id_autore 
											FROM blog_full 
											JOIN autori ON autori.id_blog = blog_full.id 
											WHERE autori.id_utente = $_SESSION[id_utente] 
											AND autori.stato_autore = 1 
											ORDER BY titolo_blog ASC");
				if (empty($blog_collabora)){ ?>
				<h3>Al momento non collabori con nessuno!</h3>

			<?php } else {
				foreach( $blog_collabora as $k=>$blog ){  
			?>
				<div class="card py-3 m-3 mt-1 col-12 col-lg-3">
					<h1 class="mt-3"><?=$blog["titolo_blog"]?></h1>
					<a href="/utente/cancellare_coautore?id=<?=$blog['id_autore']?>&blog=<?=$blog['id_blog']?>" onclick="if(!confirm('Vuoi smettere di collaborare con <?=$blog['username_utente']?>?')){ return false; }" class="position-absolute top-0 end-0" >
						<button type="button" class="btn" style="color:#da0000">
							<i class="fa fa-trash"></i>
						</button>
					</a>
					<h5><?=$blog["username_utente"]?></h5>					
					<a href="/blog/<?=$blog["indirizzo_blog"]?>" target="_blank" class="nav-link">
						<h5>Visualizza Blog</h5>
					</a>
					<a href="/blog/<?=$blog["indirizzo_blog"]?>/lista_articoli" target="_blank" class="nav-link">
						<h5>Gestione articoli</h5>
					</a>
					<h6><?=$blog["nome_categoria"]?></h6>
					<?=(isset($blog["sottocategorie"])&&!empty($blog["sottocategorie"])?popola_sottocategorie($blog["sottocategorie"]):"")?>					
					<h6 class="text-capitalize"><?=$blog["nome_template"]?></h6>
					<p><?=formatta_data($blog["data_creazione_blog"])?></p>
				</div>
			<?php }
			} ?>
		</div>
	</div>

	<div class="modal fade" id="blog_crea" tabindex="-1" aria-labelledby="crea_blog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="crea_blog">Crea il tuo blog!</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<?php if(!$limite_blog){?>
						<form id="form_crea" class="form_modale" action="/utente/inserimento_blog" action_validate="/utente/async_valida_form_blog" method="POST">
							<div class="mb-3">
								<label for="titoloBlog" class="form-label">Titolo Blog</label>
								<input type="text" name="titoloBlog" class="form-control" id="titoloBlog" aria-describedby="titoloblog">
							</div>
							<div class="mb-3">
								<label for="indirizzo_blog" class="form-label">Metti una parola chiave per descrivere il tuo blog</label>
								<input type="text" name="indirizzo_blog" class="form-control" id="indirizzo_blog" aria-describedby="indirizzo_blog">
							</div>
							<div class="mb-3">
								<label for="descrizione_blog" class="form-label">Descrivi il tuo Blog</label>
								<textarea name="descrizione_blog" class="form-control" id="descrizione_blog" row="4" aria-describedby="descrizione_blog"></textarea>
							</div>
							<select name="id_categoria" class="form-select text-center mb-3" aria-label="seleziona_categoria" onchange="aggiorna_sottocategorie($(this).val(),'select2_sottocategorie_add');$('#selezione_sottocategoria').slideDown();">
									<option selected disabled>Seleziona la categoria del blog!</option>
									<?php 
										foreach($db->get("SELECT * FROM categoria ORDER BY id") as $categoria){
									?>
									<option value="<?=$categoria['id']?>"><?= $categoria['nome_categoria']?></option>
									<?php }?> 
							</select>
							<div id="selezione_sottocategoria">
								<label for="sottocategoria[]" class="form-label">Sottocategoria:</label>
								<select multiple="multiple" name="sottocategoria[]" id="select2_sottocategorie_add" class="select2_modale form-select text-center mb-3" aria-label="seleziona_sottocategoria" style="width:100%;">
										<?php 
											foreach($db->get("SELECT * FROM sottocategorie ORDER BY id") as $sottocategoria){
										?>
										<option disabled class="sottocategoria sottocategoria_<?=$sottocategoria['id_categoria']?>" value="<?=$sottocategoria["id"]?>" data-categoria="<?=$categoria['id']?>"> <?= $sottocategoria['nome_sottocategoria']?></option>
										<?php }?> 
								</select>
							</div>
							<select name="id_template" class="form-select text-center mt-3 mb-3 text-capitalize" aria-label="seleziona_documento">
									<option selected disabled>Seleziona il template</option>
									<?php 
										foreach($lista_template_disponibili as $k=>$value){
									?>
									<option value="<?=$k?>"><?= $value?></option>
									<?php }?> 
							</select>
							<div id="box_errori_crea" class="text-danger text-center">
							</div>
							<button type="button" class="btn btn-primary mt-3" onclick="invia_form('crea',$(this))" style="width: 100%;">Crealo!</button>
						</form>
					<?php } else {?>
						<h3 class="text-center">Per poter creare un nuovo blog devi effettuare l'upgrade!</h3>
						<a href="/home/offerte" class="btn btn-primary w-100">Scopri le offerte</a>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</section>
<script src="/view/assets/js/form_async.js"></script>
<script>$("#selezione_sottocategoria").hide()</script>

