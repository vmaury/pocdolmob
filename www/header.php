<?php include_once 'includes/0inc.php';?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="Vince Moore, Mark Otto, Jacob Thornton, and Bootstrap contributors">
		<meta name="generator" content="Hugo 0.101.0">
		<title><?=$title?></title>

		<link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/offcanvas-navbar/">
		<link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="assets/fontawesome/all.css" rel="stylesheet">
		<!-- javascripts -->
		<script src="assets/dist/js/jquery-3.6.1.min.js"></script>
		

		<?php /**  cf js includes in main_inc.php ~l 1500 
		<script src="../includes/jquery/js/jquery-ui.min.js?layout=classic&amp;version=13.0.5"></script>
		<script src="../includes/jquery/plugins/select2/dist/js/select2.full.min.js"></script>
		<script src="../includes/jquery/plugins/multiselect/jquery.multi-select.js?layout=classic&amp;version=13.0.5"></script>
		<!-- Includes JS of Dolibarr -->	
		<script src="../core/js/lib_head.js.php?lang=fr_FR&layout=classic"></script> **/?>
		
		<!-- Custom styles for this template -->
		<link href="assets/common.css" rel="stylesheet">
	</head>
	<body class="bg-light">
		<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark" aria-label="Main navigation">
			<div class="container-fluid">
				<a class="navbar-brand" href="welcome.php">Accueil</a>
				<button class="navbar-toggler p-0 border-0" type="button" id="navbarSideCollapse" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
					<ul class="navbar-nav me-auto mb-2 mb-lg-0">
						<?php if (defined('selUserActive') && selUserActive === true) { ?>
							<li class="nav-item">
								<a class="nav-link active" aria-current="page" href="sel_user.php">Chgt utilisateur</a>
							</li>
						<?php } ?>
						<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="pocphoto.php" title="Prise directe de photo, ou upload">Photo</a>
						</li>	
						<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="esmagasin.php">QR Code Scan</a>
						</li>
						<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="drawpad.php">Draw Pad</a>
						</li>
						<!--<li class="nav-item">
							<a class="nav-link" href="#">Notifications</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">Profile</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">Switch account</a>
						</li>-->
						<?php if(isset($_SESSION['user2'])) { 
							//print_r($_SESSION['user2']);?>
							
							<li class="nav-item dropdown" title="Utilisateur sélectionné">
							<a class="nav-link active dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false"><?=$_SESSION['user2']->login?> (<?=$_SESSION['user2']->dol_company?>)</a>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="sel_user.php">Changer</a></li>
							</ul>
						</li>
						<?php } ?>
						<li class="nav-item dropdown" title="Utilisateur connecté">
							<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-user fa-fw"></i> <?=$_SESSION['dol_login']?> (<?=$_SESSION['dol_company']?>)</a>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="logout.php">Déconnecter</a></li>
							</ul>
						</li>
						
					</ul>
					<!--<form class="d-flex" role="search">
						<input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
						<button class="btn btn-outline-success" type="submit">Search</button>
					</form>-->
				</div>
			</div>
		</nav>