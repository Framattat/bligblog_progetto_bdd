<section>
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 mb-5">
				<div class="card text-center">
					<div>
						<h1 class="mt-2"><?=$utente["username_utente"]?></h1>
						<p><?=$utente["email_utente"]?></p>
					</div>
					<ul class="list-group list-group-flush">
						<li class="list-group-item"><a class="nav-link" href="/utente/blogs"> <i class="fa fa-user"></i> Blog</a> </li>
						<li class="list-group-item"><a class="nav-link" data-bs-toggle="modal" data-bs-target="#profilo"> <i class="fa fa-edit"></i> Modifica il profilo</a></li>
						<li class="list-group-item"><a class="nav-link" href="/home/offerte"><i class="fa fa-tachometer"></i> Cambia piano!</a></li>
						<li class="list-group-item "><a class="nav-link" style="color: red !important" data-bs-toggle="modal" data-bs-target="#utentecanc"><i class="fa fa-times-circle"></i> Cancella il tuo account</a></li>
					</ul>
				</div>
			</div>
			<div class="col-lg-9">
				<div class="card text-center mb-5 mb-md-3">
					<div class="card-header">
						<?php
							if(isset($_GET['msg'])){
								alert($_GET['msg']);
							}
						?>
						<h1>Dati utente</h1>
						<p><?=($utente["descrizione_utente"] == "" ? "Descrizione utente personalizzabile" : $utente["descrizione_utente"])?></p>
					</div>
					<div class="card-body">
						<div class="row text-start px-4">
							<div class="col-lg-4">
								<p>Nome: <strong><?=$utente["nome_utente"]?></strong></p>
								<p>Cognome: <strong><?=$utente["cognome_utente"]?></strong></p>
							</div>
							<div class="col-lg-8">
								<p>Telefono: <strong><?=$utente["telefono_utente"]?></strong></p>
								<p>
									<?php 
										switch($utente["tipo_documento"]){
											case("carta_identita"):
												echo "Numero della carta d'identità: ";
												break;
											case("patente"):
												echo "Numero della patente: ";
												break;
											case("codice_fiscale"):
												echo "Numero del codice fiscale: ";
										}
										?><strong><?=$utente["estremi_documento_utente"]?></strong> 
								</p>
								<p>Email: <strong><?=$utente["email_utente"]?></strong></p>	
							</div>
						</div>
					</div>
				</div>
				<div class="card text-center">
					<div class="card-header py-3">
						<h2>Piano attuale: <?=(isset($abbonamento["nome_abbonamento"])?$abbonamento["nome_abbonamento"]:"")?></h2>
						<h4>Scade il: <?=(isset($abbonamento["scadenza"]))?$abbonamento["scadenza"]:""?></h4>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="utentecanc" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="cancellare utente" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="container">
					<div class="row text-center mt-3">
						<div class="col-12 text-end ">
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
						</div>
						<div class="col-12">
							<h2 class="modal-title" style="text-transform: uppercase;" id="titoloModale">Avvertenza!</h2>
						</div>
					</div>
					<hr>
					<div class="modal-body text-center">
						<p>
							Attento, questa azione è irreversibile e comporta il cancellamento del tuo profilo <strong>DEFINITIVAMENTE!</strong><br/>Se sei sicuro premi il bottone rosso e prosegui al cancellamento del tuo profilo e i tuoi blog
						</p>
					</div>
					<hr>
					<div class="row text-center mb-4 mt-4">
						<div class="col-12 col-md-6">
							<a href="/utente/cancella_utente">
								<button type="button" class="btn btn-lg btn-danger">Si voglio cancellarmi</button>
							</a>
						</div>
						<div class="col-12 col-md-6">
							<button type="button" class="btn btn-lg btn-primary" data-bs-dismiss="modal" aria-label="close">No ci ho ripensato</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="profilo" tabindex="-1" aria-labelledby="form profilo" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="titoloModale">Modifica il profilo</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body text-center">
					<form id="form_profilo" action="/utente/modifica_profilo" action_validate="/utente/async_valida_form_modifica_profilo" method="POST">
						<div class="row">
							<div class="col-md-6 mb-4">
								<div class="form-floating">
									<input type="text" name="nomeU" class="form-control form-control-lg" id="nome" value="<?=$utente["nome_utente"]?>"/>
									<label class="form-label" for="nome">Nuovo nome</label>
								</div>
							</div>
							<div class="col-md-6 mb-4">
								<div class="form-floating">
									<input type="text" name="cognomeU" class="form-control form-control-lg" id="cognome" value="<?=$utente["cognome_utente"]?>"/>
									<label class="form-label" for="cognome">Nuovo cognome</label>
								</div>
							</div>
							
						</div>
						<div class="row">
							<div class="col-md-6 mb-4">
								<div class="form-floating">
									<input type="email" name="email" class="form-control form-control-lg" id="emailAddress" value="<?=$utente["email_utente"]?>"/>
									<label class="form-label" for="emailAddress">Nuova email</label>
								</div>
							</div>
							<div class="col-md-6 mb-4 pb-2">
								<div class="form-floating">
									<input type="tel" name="cellulare" class="form-control form-control-lg" id="numeroCell" value="<?=$utente["telefono_utente"]?>"/>
									<label class="form-label" for="cellulare">Nuovo numero di telefono</label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-4 mb-4">
								<div class="form-floating">								
									<input type="text" name="username" class="form-control form-control-lg" id="username" value="<?=$utente["username_utente"]?>"/>
									<label for="username" class="form-label">Nuovo username</label>
								</div>
							</div>
							<div class="col-lg-3 mb-4">
								<div class="form-floating">
									<select name="tipo_documento" class="form-select" aria-label="seleziona_documento" id="documento">
										<option value="carta_identita" <?=($utente["tipo_documento"] == "carta_identita"?"selected":"") ?>>Carta di identità</option>
										<option value="patente" <?=($utente["tipo_documento"] == "patente"?"selected":"") ?>>Patente</option>
										<option value="codice_fiscale" <?=($utente["tipo_documento"] == "codice_fiscale"?"selected":"") ?>>Codice Fiscale</option>
									</select>
									<label for="documento">Documento</label>
								</div>
							</div>
							<div class="col-lg-5 mb-4">
								<div class="form-floating">								
									<input type="text" name="estremi_documento" class="form-control form-control-lg" id="estremi_documento" value="<?=$utente["estremi_documento_utente"]?>"/>
									<label for="estremi_documento" class="form-label">Inserisci gli estremi del documento</label>
								</div>
							</div>
						</div>
						<div class="row justify-content-center">
							<div class="col mb-4 d-flex align-items-center">
								<div class="form-outline w-100">
									<textarea name="descrizioneU" class="form-control" id="descU" rows="4" placeholder="Nuova descrizione profilo"><?=$utente["descrizione_utente"]?></textarea>
								</div>
							</div>
						</div>
					</form>
				<div id="box_errori_profilo" class="text-danger">
				</div>
				</div>
				<div class="modal-footer" style="justify-content: center;">
					<button type="button" class="btn btn-primary" onclick="invia_form('profilo',$(this))" style="width: 100%;">Modifica</button>
				</div>
			</div>
		</div>
	</div>
</section>

<script src="/view/assets/js/form_async.js"></script>

