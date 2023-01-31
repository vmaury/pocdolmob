<?php 
$title = 'Accueil extranet';
include 'header.php'; ?>

<main class="container">

	<div class="my-3 p-3 bg-body rounded shadow-sm">
		<h6 class="border-bottom pb-2 mb-0"><i class="fa-solid fa-house fa-fw"></i> Bienvenue sur l'extranet</h6>
		<div class="d-flex text-muted pt-3">
			<svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#507b50"/><text x="50%" y="50%" fill="#507b50" dy=".3em">32x32</text></svg>
			<p class="pb-3 mb-0 small lh-sm border-bottom">
				<strong class="d-block text-gray-dark">Scan photo</strong>
				Webapp <a href="pocphoto.php">de prise et transfert des photos</a>
				
			</p>
		</div>
		<div class="d-flex text-muted pt-3">
			<svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#007bff"/><text x="50%" y="50%" fill="#007bff" dy=".3em">32x32</text></svg>
			<p class="pb-3 mb-0 small lh-sm border-bottom">
				<strong class="d-block text-gray-dark">E/S Magasin</strong>
				Utilitaire de <a href="esmagasin.php">scan de (QR, bar) codes de produit</a> permettant par ex. de gérer des tranfert du stock de magasin
			</p>
		</div>
		<div class="d-flex text-muted pt-3">
			<svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#803000"/><text x="50%" y="50%" fill="#803000" dy=".3em">32x32</text></svg>
			<p class="pb-3 mb-0 small lh-sm border-bottom">
				<strong class="d-block text-gray-dark">Pad de Signature</strong>
				Proto de <a href="drawpad.php">dessin direct</a> permettant par ex. signer puis envoyer
			</p>
		</div>
	</div>
</main>

<?php
include 'footer.php';
