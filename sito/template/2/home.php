<div class="container text-white">
	<?php
		if(isset($_GET['msg'])){
			alert($_GET['msg']);
		}
	?>
	<?php if(!empty($articoli)){ ?>
		<div class="col mb-5">
			<div class="p-4 mb-3 text-white rounded" style="background-color: #774360;;">
				<h1 class="text-capitalize"><?=$data["titolo_blog"]?> - il blog dove si parla di <?=$data["categoria"]?></h1>
				<h5 class="mb-0"><?= $data["descrizione_blog"]?></h5>
				<?php if(isset($data["sottocategorie"])&&!empty($data["sottocategorie"])){
					echo "<p>Sottocategorie: ";
					popola_sottocategorie($data["sottocategorie"]);
					echo"</p>";
				} ?>
			</div>
		</div>
	<?php } ?>
	<?php if(!is_null($ultimo_articolo)){?>
		<h2>Ultimi articoli:</h2>
		<div class="p-4 p-md-5 mb-4 text-white rounded" style="background-color:#774360;">
			<div class="col-md-6 px-0">
				<h1 class="display-4 fst-italic text-capitalize"><?=$ultimo_articolo["titolo_articolo"]?></h1>
				<h4 class="fst-italic">Di: <?=$ultimo_articolo["username_utente"]?></h4>
				<a href="/blog/<?=$data["indirizzo_blog"]?>/articolo/<?=$ultimo_articolo["id"]?>" style="color:white;" > 
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
			<div class="row g-0 rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative" style="background-color:#774360;">
				<div class="col p-4 d-flex flex-column position-static">
					<h3 class="mb-0 text-capitalize"><?=$value["titolo_articolo"]?></h3>
					<h5>Di: <?=$value["username_utente"]?></h5>
					<div class="mb-1"><?=formatta_data($value["data_pubblicazione_articolo"])?></div>
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

	<div class="row mt-2 g-5 justify-content-between">
		<?php if(!empty($articoli)){ ?>
			<div class="col-md-3 pt-5">
				<div class="position-sticky text-center" style="top: 6rem;">
					<?php if($autore){ ?>
						<div class="row mb-4">
							<div class="col-12 mb-3">
								<a class="btn btn-primary" href="/blog/<?=$data["indirizzo_blog"]?>/article_editor">Scrivi un articolo!</a>
							</div>
							<div class="col-12">
								<a class="btn btn-primary" href="/blog/<?=$data["indirizzo_blog"]?>/lista_articoli">Vedi i tuoi articoli!</a>
							</div>
						</div>
					<?php }?>
					<h4>Archivio articoli</h4>
					<hr>
					<ol class="list-unstyled mb-0">
						<?php foreach($archivio as $data_archivio=>$lista_articoli){?>
							<li>
								<a href="/blog/<?=$data["indirizzo_blog"]?>/?data=<?=$data_archivio?>"><?=$data_archivio?></a>
							</li>
						<?php } ?>
					</ol>
				</div>
			</div>
			<div class="col-md-9">
				<h3 class="pb-4 mb-4 fst-italic border-bottom">
					Articoli pubblicati su: <?=$data["titolo_blog"]?>
				</h3>
				<?php foreach($articoli as $k=>$valore){?>
					<article class="rounded  text-white p-4" style="box-shadow: 2px 3px 6px 4px #61364E; background-color: #774360;">
						<div class="row">
							<div class="col">
								<a href="/blog/<?=$data["indirizzo_blog"]?>/articolo/<?=$valore["id"]?>" class="text-decoration-none text-white text-capitalize"> 
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
								<span class="ps-4 pe-4 ">
									<hr>
									<?=strip_tags(implode(" ", array_slice( explode(" ", $valore["testo_articolo"]), 0, 30)))?>
									<br/>
									<a href="/blog/<?=$data["indirizzo_blog"]?>/articolo/<?=$valore["id"]?>" style="color:white;"> 
										Continua a leggere...		
									</a>
								</span>
							</div>
						</div>
					</article>
					<hr style="border: none;height: 1px;">
				<?php } ?>
			</div>
		<?php } else {  ?>
			<div class="col-12 text-center">
					<?php if($autore){ 
						echo '<h1 class="text-center mb-5">Pubblica qualcosa nel tuo blog ora! </h1>';
						echo '<a class="btn btnl-lg btn-outline-primary me-3" href="/blog/'.$data["indirizzo_blog"].'/article_editor">Scrivi un articolo!</a>';
						echo '<a class="btn btn-outline-primary" href="/blog/'.$data["indirizzo_blog"].'/lista_articoli">Vedi i tuoi articoli!</a>';
					} else {
						echo '<h1 class="text-center mb-5"> Questo blog non contiene ancora articoli! </h1>';
						echo '<a class="btn btn-lg btn-outline-primary" href="/home/ricerca_blogs">Cercane un altro!</a>';
					} 
			} 
		?> 
			</div>	
	</div>
</div>
