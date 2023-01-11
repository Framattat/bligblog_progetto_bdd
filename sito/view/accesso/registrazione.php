<section>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-sm-10">
				<div class="card text-center shadow-2-strong card-registration" style="border-radius: 15px;">
					<h5 class="card-header"> Effettua la registrazione </h5>
					<div class="card-body p-4 p-md-5">
						<?php
							if(isset($_GET['msg'])){
								alert($_GET['msg']);
							}
						?>
						<form id="form_registrazione" action="/accesso/inserimento_dati" action_validate="/accesso/async_valida_form_registrazione" method="POST">
							<div class="row">
								<div class="col-md-6 mb-4">
									<input type="text" name="username" class="form-control form-control-lg" id="inputEmail" aria-describedby="usernamelogin">
									<label for="inputEmail" class="form-label">Username*</label>
								</div>
								<div class="col-md-6 mb-4">
									<input type="password" name="password" class="form-control form-control-lg" id="inputPassword">
									<label for="inputPassword" class="form-label">Password*</label>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 mb-4">
									<div class="form-outline">
										<input type="text" name="nomeU" class="form-control form-control-lg" id="nome"/>
										<label class="form-label" for="nome">Nome*</label>
									</div>
								</div>
								<div class="col-md-6 mb-4">
									<div class="form-outline">
										<input type="text" name="cognomeU" class="form-control form-control-lg" id="cognome"/>
										<label class="form-label" for="cognome">Cognome*</label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 mb-4 pb-2">
									<div class="form-outline">
										<input type="email" name="email" class="form-control form-control-lg" id="emailAddress"/>
										<label class="form-label" for="emailAddress">Email*</label>
									</div>
								</div>
								<div class="col-md-6 mb-4 pb-2">
									<div class="form-outline">
										<input type="tel" name="cellulare" class="form-control form-control-lg" id="numeroCell"/>
										<label class="form-label" for="cellulare">Numero di telefono*</label>
									</div>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col-md-8 mb-4 d-flex align-items-center">
									<div class="form-outline w-100">
										<select name="tipo_documento" class="form-select text-center" aria-label="seleziona_documento">
											<option selected disabled>Seleziona il tipo di documento</option>
											<option value="carta_identita">Carta di identità</option>
											<option value="patente">Patente</option>
											<option value="codice_fiscale">Codice Fiscale</option>
										</select>
										<input type="text" name="estremi_documento" class="form-control form-control-lg" id="estremi_documento"/>
										<label for="estremi_documento" class="form-label">Estremi del documento*</label>
									</div>
								</div>
							</div>
							<div class="row text-start">
								<div class="col">
									<p>
										<em>*Campi obbligatori</em>
									</p>
								</div>
							</div>
							<div id="box_errori_registrazione" class="text-danger">
							</div>
							<div class="my-4 pt-2">
								<input class="btn btn-primary btn-lg" type="button" onclick="invia_form('registrazione',$(this));" value="Registrati" />
							</div>
						</form>
						<a class="text-decoration-none mt-5" data-bs-toggle="modal" data-bs-target="#login" style="cursor:pointer;"> Sei già registrato? Effettua il login </a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<script src="/view/assets/js/form_async.js"></script>



	
