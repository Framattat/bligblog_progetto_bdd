            <section>
				<div class="container">
					<div class="row text-center">
						<h1 style="text-transform: uppercase;">
							Domande Frequenti (F.A.Q.)
						</h1>
						<div class="col">
							<p>
                                Ecco le vostre domande e le nostre risposte!
							</p>
						</div>
					</div>
				</div>
			</section>
			<section class="mt-5">
				<div class="container">
                    <?php   
                            $faq = [
                                "Cosa ha portato alla fondazione di blig blog?" => "La necessità di un sito ad hoc per la creazione di blog",
                                "Quando è stato fondato blig blog?" => "Blig blog è nato nel 2021 da un'idea di Mario Rossi",
								"Come posso comprare un abbonamento a blig blog?"=> "Manda un'email a bligblog@info.com per ricevere informazioni sui nostri voucher!",
								"Come mi iscrivo su blig blog?"=>"Clicca sul bottone 'Registrati!' nella homepage o clicca il bottone 'Login' dove verrai re-indirizzato sulla pagina di registrazione!",
								"Quando scade il mio abbonamento a pagamento?" =>"Ogni abbonamento ha valenza annuale, per rinnovarlo manda un'email a bliblog@info.com!",
								"Ci sono limiti al mio account?"=> "Ogni account gratuito ha a disposizione 3 blog con 20 articoli massimi ciascuno, vai alla pagina offerte per saperne di più!",
								"Vorrei lavorare con voi, come posso fare?"=> "Manda un'email a bliblog@info.com con oggetto 'Lavorare con bligblog' e ti faremo sapere se siamo interessati!",
								":)"=>":D"
                            ];
							// genero dinamicamente a partire dall'array qua sopra, le faq
							foreach($faq as $k=>$value ){   
						?>
					<div class="row mt-4 bg-dark text-white fw-bold fs-5 pb-1 pt-1">
						<div class="col-2">
							<h1>
								Q.
							</h1>
						</div>
                        <div class="col-10 align-self-center">
                            <p class="mb-0"> <?=$k?></p>
                        </div>
					</div>
                    <div class="row mb-4 bg-light pb-1 pt-1 border-bottom">
						<div class="col-2">
							<h1>
								A.
							</h1>
						</div>
                        <div class="col-10 align-self-center">
                            <p class="mb-0"> <?=$value?> </p>
                        </div>
					</div>
                    <?php
						}
					?>
				</div>
			</section>