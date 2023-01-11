<div class="container">
	<?php
		if(isset($_GET['msg'])){
			alert($_GET['msg']);
		}
	?>
	<?php if(!is_null($ultimo_articolo)){?>
		<h2>Ultimi articoli:</h2>
		<div class="p-4 p-md-5 mb-4 text-white rounded bg-dark">
			<div class="col-md-6 px-0">
				<h1 class="display-4 fst-italic text-capitalize"><?=$ultimo_articolo["titolo_articolo"]?></h1>
				<h4 class="fst-italic">Di: <?=$ultimo_articolo["username_utente"]?></h4>
				<a href="/blog/<?=$data["indirizzo_blog"]?>/articolo/<?=$ultimo_articolo["id"]?>"> 
					Leggi l'articolo		
				</a>
			</div>
		</div>
	<?php } ?>
	<div class="row mb-2">
		<?php foreach($ultimi_articoli as $k=>$value){
			if($k==0){
				continue;
			}
		?>
		<div class="col-md-6">
			<div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
				<div class="col p-4 d-flex flex-column position-static">
					<h3 class="mb-0 text-capitalize"><?=$value["titolo_articolo"]?></h3>
					<h5>Di: <?=$value["username_utente"]?></h5>
					<div class="mb-1 text-muted"><?=formatta_data($value["data_pubblicazione_articolo"])?></div>
					<span>
					<?=strip_tags(implode(" ", array_slice( explode(" ", $value["testo_articolo"]), 0, 10)))?>
					<br/>
					<a href="/blog/<?=$data["indirizzo_blog"]?>/articolo/<?=$value["id"]?>"> 
						Continua a leggere...		
					</a>
					</span>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>

	<div class="row mt-2 g-5">
		<?php if(!empty($articoli)){ ?>
			<div class="col-md-8">
				<h3 class="pb-4 mb-4 fst-italic border-bottom">
					Articoli pubblicati su: <?=$data["titolo_blog"]?>
				</h3>
				<?php foreach($articoli as $k=>$valore){?>
					<article class="rounded bg-light p-4" style="box-shadow: 2px 2px 4px 1px #ccc;">
						<div class="row">
							<div class="col">
								<a href="/blog/<?=$data["indirizzo_blog"]?>/articolo/<?=$valore["id"]?>" class="text-decoration-none text-black text-capitalize"> 
									<h2 class="d-inline-block me-4"><?=$valore["titolo_articolo"]?></h2>
									<p class="d-inline-block ms-1">
										di <strong><?=$valore["username_utente"]?></strong> il 
										<strong><?=formatta_data($valore["data_pubblicazione_articolo"])?></strong>
									</p>
								</a>
								<?php if(!is_null($valore["immagine_cop"])){ ?>
									<img class="rounded img-fluid " src="/template/assets/immagini/<?=$valore["immagine_cop"]?>" alt="Immagine copertina">
								<?php } ?>
							</div>
						</div>
						<div class="row mt-2 mb-2">
							<div class="col">
								<p>
									Trend: <?=$valore["voto_totale"]?> likes
								</p>
							</div>
							<?php if ($valore["commenti_totali"]>0){ ?>
								<div class="col">
									<?=($valore["commenti_totali"]>1?$valore["commenti_totali"]." commenti totali":"1 commento totale")?>
								</div>
							<?php } ?>
						</div>
						<?php if(isset($valore["tags"]) && $valore["tags"] !=""){?>
							<div class="row">
								<div class="col">
									<p>Tags:</p>
									<?php foreach(explode(",",$valore["tags"]) as $k=>$value){?>
										<button type="button" class="btn btn-secondary btn-sm" disabled><?=$value?></button>
									<?php }?>
								</div>
							</div>
						<?php } ?>
						<div class="row">
							<div class="col">
								<span class="ps-4 pe-4">
									<hr>
									<?=strip_tags(implode(" ", array_slice( explode(" ", $valore["testo_articolo"]), 0, 30)))?>
									<br/>
									<a href="/blog/<?=$data["indirizzo_blog"]?>/articolo/<?=$valore["id"]?>" > 
										Continua a leggere...		
									</a>
								</span>
							</div>
						</div>
					</article>
					<hr style="border: none;height: 1px;">
				<?php } ?>
			</div>
			<div class="col-md-4">
				<div class="position-sticky" style="top: 6rem;">
					<div class="p-4 mb-3 bg-light rounded">
						<h4 class="fst-italic">Cosa puoi trovare su <?=$data["titolo_blog"]?></h4>
						<h5> Categoria blog: <?=$data["categoria"]?></h5>
						<?php if(isset($data["sottocategorie"])&&!empty($data["sottocategorie"])){
							echo "<p><strong>Sottocategorie: </strong>";
							popola_sottocategorie($data["sottocategorie"]);
							echo"</p>";
						} ?>
						<p class="mb-0"><?= $data["descrizione_blog"]?></p>
					</div>
					<?php if($autore){ ?>
						<div class="row">
							<div class="col-12 col-lg-6 mb-3 mb-lg-1">
								<a class="btn btn-outline-primary" href="/blog/<?=$data["indirizzo_blog"]?>/article_editor">Scrivi un articolo!</a>
							</div>
							<div class="col-12 col-lg-6">
								<a class="btn btn-outline-primary" href="/blog/<?=$data["indirizzo_blog"]?>/lista_articoli">Vedi i tuoi articoli!</a>
							</div>
						</div>
					<?php }?>
					<?php if(!empty($articoli)){ ?>
						<div class="p-4">
							<h4 class="fst-italic">Archivio articoli</h4>
							<ol class="list-unstyled mb-0">
								<?php foreach($archivio as $data_archivio=>$lista_articoli){?>
									<li>
										<a href="/blog/<?=$data["indirizzo_blog"]?>/?data=<?=$data_archivio?>"><?=$data_archivio?></a>
									</li>
								<?php } ?>
							</ol>
						</div>
					<?php } ?>

					<!-- BOX SOCIAL, IMPLEMENTAZIONE FUTURA
						<div class="p-4">
						<h4 class="fst-italic">Informazioni utente e link utili</h4>
						<ol class="list-unstyled">
							<li><a href="#">GitHub</a></li>
							<li><a href="#">Twitter</a></li>
							<li><a href="#">Facebook</a></li>
						</ol>
					</div> -->
				</div>
			</div>
		<?php } else {  ?>
			<div class="col-12 text-center">
					<?php if($autore){ 
						echo '<h1 class="text-center mb-5"> Pubblica qualcosa nel tuo blog ora! </h1>';
						echo '<a class="btn btnl-lg btn-outline-primary me-5" href="/blog/'.$data["indirizzo_blog"].'/article_editor">Scrivi un articolo!</a>';
						echo '<a class="btn btn-outline-primary" href="/blog/'.$data["indirizzo_blog"].'/lista_articoli">Vedi i tuoi articoli!</a>';
					} else {
						echo '<h1 class="text-center mb-5"> Questo blog non contiene ancora articoli! </h1>';
						echo '<a class="btn btn-lg btn-outline-primary" href="/home/ricerca_blogs">Cercane un altro!</a>';
					} 
				} ?> 
			</div>	
	</div>
</div>
