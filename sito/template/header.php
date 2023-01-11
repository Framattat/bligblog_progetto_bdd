<!DOCTYPE html>
<html lang="it">
	<head>
		<title><?=$data["titolo_blog"]?></title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">		
		<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link rel="stylesheet" href="/view/sito/css/stileSito.css"/>
		<link rel="stylesheet" href="/template/assets/css/stile_<?=$data["nome_template"]?>.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	</head>
	<body class="d-flex flex-column h-100">
		<header class="fixed-top">
			<nav class="navbar navbar-expand-md navbar-dark py-3">
				<div class="container">
					<a class="navbar-brand" href="/blog/<?=$data["indirizzo_blog"]?>"><?=$data["titolo_blog"]?></a>
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse justify-content-end" id="navbar">
						<div class="navbar-nav ">
							<a class="nav-link" href="/home/index">Blig Blog</a>
							<a class="nav-link" href="/home/ricerca_blogs">Altri blog</a>
							<?php if(!utente_loggato()){ ?>
								<button type="button" class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#login">
									Sign-In/Login
								</button>
							<?php }else{ ?>
								<a class="nav-link" href="/blog/<?=$data["indirizzo_blog"]?>/lista_articoli">I tuoi articoli</a>
								<a class="nav-link me-2" href="/utente/blogs">I tuoi blog</a>
								<a href="/<?=(utente_loggato_admin()?"admin":"utente")?>/userpage">
								<button type="button" class="btn btn-primary me-2 mb-2 mb-md-0">
									<i class="fas fa-male" style="width: 15px;"></i>
								</button>
								</a>
								<a href="/accesso/logout" onclick="if(!confirm('Vuoi disconnetterti?')){ return false; }">
									<button type="button" class="btn btn-warning">
										<i class="fas fa-power-off" style="width: 15px;"></i>
									</button>
								</a>
							<?php } ?>
						</div>
					</div>
				</div>
			</nav>
		</header>
		<main class="mt-5 py-5">
			<div class="modal fade" id="login" tabindex="-1" aria-labelledby="titoloModale" aria-hidden="true" style="font-family: 'roboto' , sans-serif">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="titoloModale">Effettua il login</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<form action="/accesso/analisi_login" method="POST">
								<div class="mb-3">
									<label for="email_login_header" class="form-label">Username</label>
									<input type="text" name="username" class="form-control" id="email_login_header" aria-describedby="usernamelogin">
								</div>
								<div class="mb-3">
									<label for="pw_login_header" class="form-label">Password</label>
									<input type="password" name="password" class="form-control" id="pw_login_header">
								</div>
								<button type="submit" class="btn btn-primary" style="width: 100%;">Invia</button>
							</form>
						</div>
						<div class="modal-footer" style="justify-content: center;">
							<a class="text-decoration-none" href="/accesso/registrazione"> Non sei ancora registrato? </a> 
						</div>
					</div>
				</div>
			</div>
			