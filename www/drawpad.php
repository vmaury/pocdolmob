<?php
$title = 'Draw pad';
include 'header.php';
$form = new Form($db);
//print_r($_POST);
//print_r($_SESSION);
$entity = $_SESSION['dol_entity'];
$submitted = false;
if (GETPOST('action') == 'uplcanvas'){
//	print_r($_POST);
//	print_r($_FILES);
//	print_r($error);
//	print_r($_POST);
}
?>
<link href="assets/jquery-drawpad.css" rel="stylesheet">
<script>dolToken = '<?=getToken()?>';</script>
<script src="assets/jquery-drawpad.js"></script>
<script src="assets/datalist_utils.js"></script>
<script src="pjs/drawpad.js"></script>
<main class="container" style="max-width: 1000px">
	<div class="my-3 p-3 bg-body rounded shadow-sm">
		<?php if ($submitted && count($error) > 0) { ?>
			<span class="badge bg-danger">Erreur<?= $error?></span>
		<?php } elseif ($submitted && count($error) == 0 && $info != '') { ?>
			<span class="badge bg-success"><?=$info?></span>
		<?php } ?>
		
		<div class="badge bg-success" id="result"></div>
		<h5 class="border-bottom pb-2 mb-0">Dessiner-envoyer</h5>
		<div id="divdrawpad" class="drawpad-dashed mb-3">
		</div>
				
		<div class="mb-3" style="text-align: right">
			<button class="w-25 btn btn-sm btn-danger" id="btgomme" title="Effacer tout"><i class="fa-solid fa-eraser x-large"></i></button>
		</div>
		
		<div class="bd-example">
			<form id="modstock" name="modstock" method="post" action="" enctype="multipart/form-data">
				<input type="hidden" name="token" value="<?= getToken() ?>" />
				<input type="hidden" name="action" value="uplcanvas">
				<input id="inp_img" name="inp_img" type="hidden" value="">
				<div class="mb-3">
					<label for="refproject" class="form-label">Projet &nbsp;  &nbsp; </label>
					<div class="input-group">
						<button class="btn btn-danger" title="effacer l'entrée" id="clearrefproject">D</button>
						<input class="form-control" list="refprojectOptions" id="refproject" name="refproject" placeholder="Tapez pour chercher..." value="<?=$refProj?>">
					</div>
					<datalist id="refprojectOptions">
					</datalist>
					<div>
					&nbsp;&nbsp;<i><span id="projectlabel" class="small" style="color: #4d4d4c"></span></i>
					</div>
				</div>

				<div class="input-group mb-3">
					<button class="w-50 btn btn-lg btn-primary" id="sendButton">Envoyer</button>
				</div>
			</form>
		</div>
		
	</div>
</main>

<?php
include 'footer.php';
