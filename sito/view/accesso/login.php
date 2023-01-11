<section>
    <div class="container">
        <div class="card text-center" style="width: 50%; margin-left: 25%">
            <h5 class="card-header"> Effettua il Login </h5>
            <div class="card-body">
                <?php
                if(isset($_GET['msg'])){
                    alert($_GET['msg']);
                }
                ?>

                <form action="/accesso/analisi_login" method="POST">
                    <div class="mb-3">
                        <label for="inputEmail" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" id="inputEmail" aria-describedby="usernamelogin">
                    </div>
                    <div class="mb-3">
                        <label for="inputPassword" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="inputPassword">
                    </div>
                    <button type="submit" class="btn btn-primary">Invia</button>
                </form>
            </div>
            <div class="card-footer"> 
                <a class="text-decoration-none" href="registrazione"> Non sei ancora registrato? </a> 
            </div>
        </div>
    </div>
</section>


