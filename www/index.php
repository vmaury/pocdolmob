<?php
if (empty($noIncMain)) {
	include_once 'includes/0inc.php';
	//$resultFetchUser=$user->fetch('', 'vmaury', '', 1, ($entitytotest > 0 ? $entitytotest : -1));
} else include_once 'includes/config.inc.php';
if (multiCompany) {
	include_once DOL_DOCUMENT_ROOT.'/custom/multicompany/class/actions_multicompany.class.php';
	$actionMC = new ActionsMulticompany($db);
}
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
		<meta name="generator" content="Hugo 0.101.0">
		<title>Connexion</title>

		<link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/sign-in/">
		<link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="assets/signin.css" rel="stylesheet">
		
	</head>
	<body class="text-center">

		<main class="form-signin w-100 m-auto">
			<form id="login" name="login" method="post" action="">
				<input type="hidden" name="token" value="<?= getToken()?>" />
				<input type="hidden" name="actionlogin" value="login">
				<input type="hidden" name="loginfunction" value="loginfunction" />
				<!-- Add fields to send local user information
				<input type="hidden" name="tz" id="tz" value="" />
				<input type="hidden" name="tz_string" id="tz_string" value="" />
				<input type="hidden" name="dst_observed" id="dst_observed" value="" />
				<input type="hidden" name="dst_first" id="dst_first" value="" />
				<input type="hidden" name="dst_second" id="dst_second" value="" />
				<input type="hidden" name="screenwidth" id="screenwidth" value="" />
				<input type="hidden" name="screenheight" id="screenheight" value="" /> -->

				<img class="mb-4" src="assets/brand/doostrap-logo.svg" alt="" width="72" height="57">
				<h1 class="h3 mb-3 fw-normal">Veuillez vous authentifier</h1>

				<div class="form-floating">
					<input type="text" class="form-control" id="username" name="username" placeholder="login">
					<label for="floatingInput">Identifiant</label>
				</div>
				<div class="form-floating">
					<input type="password" class="form-control" id="password" name="password" placeholder="Password">
					<label for="floatingPassword">Mot de passe</label>
				</div>
				<?php if (multiCompany) { ?>
					<div class="form-floating">
						<?=$actionMC->select_entities("","entity","",false,false,false,false,"","form-select", false);?>
						<label for="entity">Entité</label>
					</div>
				<?php } ?>
<!--				<div class="checkbox mb-3">
					<label>
						<input type="checkbox" value="remember-me"> Remember me
					</label>
				</div>-->
				<button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
			</form>
		</main>
	</body>
</html>
