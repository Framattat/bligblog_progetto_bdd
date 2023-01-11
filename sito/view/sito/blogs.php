            <section>
				<div class="container">
                    <div class="row text-center justify-content-center">
						<h1 style="text-transform: uppercase;">
							Blogs di blig blog
						</h1>
						<?= (!empty($parole_ricerca)?"<p>Hai cercato: <strong>$parole_ricerca</strong></p>":"")?>
						<div class="bg-light rounded border py-4 px-3 text-start">
							<form class="mt-4" action="/home/ricerca_blogs" method="GET">
								<div class="row mb-4">
									<div class="col-12 col-lg-auto me-1">
										<h5>Ricerca per:</h5>
									</div>
									<div class="col-12 col-lg-auto px-lg-0 d-inline-flex mb-3">
										<button class="btn btn-outline-primary btn-sm me-2" onclick="mostra_ricerca('categoria')" data-bs-toggle="button" autocomplete="off" style="border-radius:0.75rem;height:fit-content;">Categoria</button>
										<div class="mostra_categoria me-2">
											<select name="categorie" class="form-select" onchange="aggiorna_sottocategorie($(this).val(),'select2_sottocategorie_add');" style="width:auto;">
												<option selected disabled>Cerca una categoria</option>
												<?php foreach($db->get("SELECT * FROM categoria ORDER BY id") as $categoria){ ?>
													<option value="<?=$categoria['id']?>"><?= $categoria['nome_categoria']?></option>
												<?php }?>
											</select>
										</div>
									</div>
									<div class="col-12 col-lg-auto px-lg-0 d-inline-flex">
										<button class="btn btn-outline-primary btn-sm me-2" onclick="mostra_ricerca('sottocategoria')" data-bs-toggle="button" autocomplete="off" style="border-radius:0.75rem;height:fit-content;">Sotto Categoria</button>
										<div class="mostra_sottocategoria">
											<select multiple="multiple" name="sottocategorie[]" id="select2_sottocategorie_add" class="select2 me-1" aria-label="seleziona_sottocategoria" style="width:250px!important;">
												<?php foreach($db->get("SELECT * FROM sottocategorie ORDER BY id") as $sottocategoria){ ?>
													<option disable class="sottocategoria sottocategoria_<?=$sottocategoria['id_categoria']?>" value="<?=$sottocategoria['id']?>"><?= $sottocategoria['nome_sottocategoria']?></option>
												<?php }?>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col d-inline-flex">
										<input name="s" class="form-control" type="search" value="<?=(isset($_GET["s"]) && !empty($_GET["s"]))?$_GET['s']:""?>" placeholder="Cerca il titolo o autore del blog" aria-label="Search" style="border-right:0px;border-radius:0.25rem 0 0 0.25rem;">
										<button type="submit" class="btn btn-primary" style="border-left:0px;border-radius:0 0.25rem 0.25rem 0;"><i class="fa-solid fa-magnifying-glass"></i></button>
									</div>
								</div>
							</form>
						</div>
						<!-- genero dinamicamente i blog che corrispondono alla mia ricerca -->
						<?php if($errore!=true){ 
							foreach($blogs as $k=>$blog){ ?>
								<div class="card py-3 mt-3 col-12 col-md-6 col-lg-4">
									<h1><?=$blog["titolo_blog"]?></h1>
									<h5>Di: <?=$blog["username_utente"]?></h5>
									<h5><?=$blog["nome_categoria"]?></h5>
									<?=($blog["sottocategorie"]!=""?popola_sottocategorie($blog["sottocategorie"]):"")?>					
									<a href="/blog/<?=$blog["indirizzo_blog"]?>" target="_blank" class="nav-link">
										<h5>Visualizza Blog</h5>
									</a>
									<?php if(check_autore($blog["id"])){?>
										<a href="/blog/<?=$blog["indirizzo_blog"]?>/lista_articoli" class="nav-link">
											<h5>Gestione articoli</h5>
										</a>
									<?php } ?>
									<p><?=formatta_data($blog["data_creazione_blog"])?></p>
								</div>
							<?php } 
						} else { ?>
							<div class="col">
								<p class = "fs-5 mt-5 fw-bold">
									Ops! Sembra che la ricerca non abbia portato risultati, prova a cercare ancora!
								</p>
							</div>
						<?php } ?>
					</div>
				</div>
			</section>
<script>
	$(".mostra_categoria").hide();
	$(".mostra_sottocategoria").hide();
	function mostra_ricerca(div){
		$(".mostra_"+div).animate({width:'toggle'},300);
		$(".mostra_"+div+" select").val("");
		if(div=="categoria"){
			$(".sottocategoria").removeAttr("disabled");
		}
	};
</script>