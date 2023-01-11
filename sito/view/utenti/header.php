<!DOCTYPE html>
<html lang="it">
	<head>
		<title>BligBlog</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">	
			<!--carico le librerie di bootstrap, il mio css e i miei font  -->
		<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="/view/sito/css/stileSito.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	</head>
	<body class="d-flex flex-column h-100">
		<header class="fixed-top">
			<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
				<div class="container">
					<a class="navbar-brand" href="/home/homepage">Blig Blog</a>
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse justify-content-end" id="navbar">
						<div class="navbar-nav ">
							<a class="nav-link me-2" href="/home/ricerca_blogs">Blogs</a>
							<a class="nav-link" href="/utente/blogs">I tuoi blog</a>
							<a class="nav-link me-2" href="/utente/template">Templates</a>
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
						</div>
					</div>
				</div>
			</nav>
		</header>
		<main class="mt-5 py-5">
			