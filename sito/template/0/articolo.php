<section>
	<div class="container">
		<?php
			if(isset($_GET['msg'])){
				alert($_GET['msg']);
			}
		?>
		<div class="row text-center">
			<div class="col-3">
				<?php if(!$articolo["pubblicato"]){
					echo("<h4>Anteprima articolo</h4>");
					}
				?>
				<?php if(!is_null($articolo["immagine_cop"])){ ?>
					<img class="rounded img-fluid " src="/template/assets/immagini/<?=$articolo["immagine_cop"]?>" alt="Immagine copertina">
				<?php } ?>
				<hr>
				<h5>Scritto da: <br/> <?=$articolo["username_utente"]?></h5>
				<h6 class="mb-5">Data: <?=formatta_data($articolo["data_pubblicazione_articolo"])?></h6>
				<h5>Ti è piaciuto questo articolo?</h5>
				<form class="d-inline-block" action="/blog/<?=$data["indirizzo_blog"]?>/voto" method="POST">
					<input type="hidden" name="id_utente" value="<?=(utente_loggato()?$_SESSION["id_utente"]:"")?>">
					<input type="hidden" name="id_articolo" value="<?=$articolo["id"]?>">
					<button class="btn" type="submit" name="voto" value=-1 <?=(!utente_loggato()?"disabled":"")?> ><i class="fa-solid fa-circle-arrow-down"></i></button>
					<h5 class="d-inline-block"><?=$articolo["voto_totale"]?></h5>
					<button class="btn" type="submit" name="voto" value=1 <?=(!utente_loggato()?"disabled":"")?> ><i class="fa-solid fa-circle-arrow-up"></i></button>
				</form>		
			</div>
			<div class="col-9">
				<?php if(!is_null($articolo["immagine_art"])){ ?>
					<img class="rounded img-fluid mb-3" src="/template/assets/immagini/<?=$articolo["immagine_art"]?>" alt="Immagine Articolo">
				<?php } ?>
				<div class="row">
					<div class="col-12 mb-2">
						<h2 style="text-transform: uppercase;">
							<?=$articolo["titolo_articolo"]?>
						</h2>
						<span>
							<?=$articolo["testo_articolo"]?>
						</span>
					</div>
					<?php if(isset($articolo["tags"]) && $articolo["tags"] !=""){?>
						<div class="row mb-4">
							<div class="col">
								<p>Tags:</p>
								<?php foreach(explode(",",$articolo["tags"]) as $k=>$value){?>
									<button type="button" class="btn btn-secondary btn-sm" disabled><?=$value?></button>
								<?php }?>
							</div>
						</div>
					<?php } ?>
					<hr>
					<div class="col-12 text-start mt-4">
						<h4 class="d-inline-block me-2">Commenti</h4>
						<i class="far fa-comment me-2"></i>
						<?php if(utente_loggato()){?>
							<form class="d-inline-block" action="/blog/<?=$data["indirizzo_blog"]?>/inserimento_commento/" method="POST">
								<input type="hidden" name="id_utente" value="<?=$_SESSION["id_utente"]?>">
								<input type="hidden" name="id_articolo" value="<?=$articolo["id"]?>">
								<input type="text" name="commento_testo" class="form-control" style="width: auto!important; display:inline!important" placeholder="Inserisci un commento">
								<button class="btn btn-outline-primary" type="submit">Inserisci*</button>
							</form>
							<p class="mt-2"><em>*massimo 150 caratteri</em></p>
						<?php } ?>
					</div>

					<?php foreach($db->get("SELECT commenti.* , utenti.username_utente 
											FROM commenti 
											LEFT JOIN utenti ON commenti.id_utente_commento=utenti.id 
											WHERE id_articolo_commento = $articolo[id]") as $k=>$commento){
						?>
					<div class="col-4 mt-3">
						<div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
							<div id="box_commenti" class="card-body text-center">
								<h5 class="card-title"><?=$commento["username_utente"]?></h5>
								<p class="card-text"><?=$commento["testo_commento"]?></p>
								<?php if(utente_loggato()) {?>
									<?php if($autore_valido == true || $commento["id_utente_commento"] == $_SESSION["id_utente"]){?>
										<form action="/blog/<?=$data["indirizzo_blog"]?>/cancella_commento/" method="POST">
											<input type="hidden" name="id_commento" value=<?=$commento["id"]?>>
											<input type="hidden" name="id_articolo" value=<?=$articolo["id"]?>>
											<button class="btn position-absolute top-0 end-0 text-white "><i class="fa fa-trash"></i></button>
										</form>
									<?php } ?>
								<?php } ?>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</section>



