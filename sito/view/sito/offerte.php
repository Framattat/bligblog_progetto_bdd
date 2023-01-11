<section>
    <div class="container">
        <div class="row text-center">
            <h1 style="text-transform: uppercase;">
                Offerte per l'accesso premium
            </h1>
            <div class="col">
                <p>
                    Abbiamo pacchetti per ogni tipo di utente e blogger, scegli il più adatto a te ed entra far parte di Blig Blog premium!
                    <br>Per richiedere un codice voucher mandare un'email a: bligblog@info.com
                </p>
            </div>
        </div>
    </div>
</section>

<section class="mt-5" >
    <div class="container">
        <div class="row text-center justify-content-center">
            <?php
                if(isset($_GET['msg'])){
                    alert($_GET['msg']);
                }
            ?>
            <?php 
                foreach($db->get("SELECT * FROM abbonamenti ORDER BY id ASC") as $abbonamento ){   
            ?>
            <div class="col">
                <div class="card mb-4 rounded-3 shadow-sm">
                    <div class="card-header py-3">
                        <h4 class="my-0 fw-normal"><?=$abbonamento["nome_abbonamento"]?></h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title"><?=$abbonamento["prezzo_abbonamento"]?>€ <small class="text-muted fw-light" <?=(!$abbonamento["prezzo_abbonamento"]?"hidden":"")?>>/anno</small></h1>
                        <ul class="list-unstyled mt-3 mb-4">
                            <?=$abbonamento["descrizione_abbonamento"]?>
                        </ul>
                        <?php if(!utente_loggato()){ ?>
                            <a href="/accesso/registrazione">
                                <button type="button" class="w-100 btn btn-lg btn-primary">Attiva <?=$abbonamento["nome_abbonamento"]?>!</button>
                            </a>
                        <?php }else{ 
                            if($abbonamento_attivo<$abbonamento["id"]){
                                ?> 
                                    <button type="button" class="w-100 btn btn-lg btn-primary" onclick="$('#box_voucher_<?=$abbonamento['id']?>').slideDown(); $(this).slideUp(); return false;">
                                        Attiva <?=$abbonamento["nome_abbonamento"]?>!
                                    </button>
                                    <div id="box_voucher_<?=$abbonamento["id"]?>" class="box_inserimento_voucher">
                                        <hr>
                                        <form action="/utente/cambia_offerta" method="POST">
                                            <input name="id_abbonamento" type="hidden" value="<?=$abbonamento["id"]?>" >
                                            <input name="voucher" class="form-control mt-3 my-1 text-center" type="text" placeholder="Inserisci qui il tuo codice voucher"/>
                                            <p>Per attivare l'offerta, devi inserire un codice voucher valido in tuo possesso</p>
                                            <input class="mb-1 btn btn-lg btn-primary" type="submit" value="Conferma il voucher"/>
                                        </form>
                                    </div>
                                <?php 
                                } else if($abbonamento_attivo==$abbonamento["id"]) { ?>
                                    <button type="disabled" class="w-100 btn btn-lg btn-secondary">Piano Attuale</button>
                                    <?php
                                } else {?>
                                    <form action="/utente/cambia_offerta" method="POST">
                                        <input name="id_abbonamento" type="hidden" value="<?=$abbonamento["id"]?>" >
                                        <button type="submit" class="w-100 btn btn-lg btn-primary">Effettua il downgrade</button>
                                    </form>
                            <?php   }
                            } ?>   
                    </div>
                </div>
            </div>
            <?php
                }
            ?>
        </div>
    </div>
</section>
<script>
    $(".box_inserimento_voucher").hide();
</script>