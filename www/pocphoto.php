<?php
$title = 'POC Photo';
include 'header.php';
$form = new Form($db);
//print_r($_POST);
//print_r($_SESSION);
$entity = $_SESSION['dol_entity'];
$submitted = false;
if (GETPOST('action') == 'uplphotos'){
	$refProj = GETPOST('refproject');
	$submitted = true;
	require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
	require_once DOL_DOCUMENT_ROOT.'/projet/class/project.class.php';
	$proj = new Project($db);
	if ($proj->fetch($refProj) >0 || $proj->fetch(null, $refProj) >0) {
		$refProj = $proj->ref;
		$upload_dir = $conf->project->dir_output."/".dol_sanitizeFileName($refProj);
		dol_add_file_process($upload_dir, $allowoverwrite = 0, $donotupdatesession = 1, 'file-upl');
		$info = 'Fichier enregistré avec succès';
	} else $error.= "Projet $refProj introuvable";
	
//	print_r($_POST);
//	print_r($_FILES);
//	print_r($error);
//	print_r($_POST);
}
?>
<?php //<script src="https://unpkg.com/webcam-easy/dist/webcam-easy.min.js"></script>?>
<script>dolToken = '<?=getToken()?>';</script>
<script src="assets/webcam-easy.js"></script>
<script src="assets/datalist_utils.js"></script>
<main class="container" style="max-width: 1000px">
	<div class="my-3 p-3 bg-body rounded shadow-sm">
		<?php if ($submitted && count($error) > 0) { ?>
			<span class="badge bg-danger">Erreur<?= $error?></span>
		<?php } elseif ($submitted && count($error) == 0 && $info != '') { ?>
			<span class="badge bg-success"><?=$info?></small></span>
		<?php } ?>
		<h5 class="border-bottom pb-2 mb-0">Prise ou envoi photo</h5>
		<video id="webcam" autoplay playsinline ></video>
		<canvas id="canvas" class="d-none"></canvas>
		<audio id="snapSound" src="assets/snap.wav" preload = "auto"></audio>
		
		<script src="pjs/pocphoto.js"></script>
		
		<div class="mb-3" >
			<button class="w-25 btn btn-lg btn-primary" id="btflip" title="changer la caméra"><i class="fa-solid fa-camera-rotate xxx-large"></i></button>
			<button class="w-25 btn btn-lg btn-primary" id="startscan" title="Prendre une photo !"><i class="fa-solid fa-camera xxx-large"></i></button>
			<button class="w-25 btn btn-lg btn-primary" id="send-file-btn" title="Importer un fichier photo	"><i class="fa-solid fa-file-video xxx-large"></i></button>
		</div>
		<div id="result"></div>
		
		<div class="bd-example">
			<form id="modstock" name="modstock" method="post" action="" enctype="multipart/form-data">
				<input type="hidden" name="token" value="<?= getToken() ?>" />
				<input type="hidden" name="action" value="uplphotos">
				
				<div class="input-group mb-3">
					<input type="file" id="file-upl" name="file-upl" class="input-file" multiple="multiple" style="display:none"/><!-- -->
				</div>
				
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
