<?php
$title = 'Sélection utilisateur';
include_once 'includes/0inc.php';
$form = new Form($db);
//print_r($_REQUEST);
//print_r($_SESSION);
if (GETPOST('action') == 'seluser') {
	$error = '';
	$submitted = true;
	$newLogin = GETPOST('userid');
	$user2 = new User($db);
	$fe = $user2->fetch('', $newLogin);
	if ($fe > 0) {
		$_SESSION['dol_login'] = $newLogin;
		$info = $user2->firstname.' '.$user2->lastname;
		if ($user2->entity == 0) {
			$entity = GETPOST('entity', 'int');
			if ($entity == 0) {
				$error = "L'utilisateur $info n'est pas rattaché à une entité, et celle-ci n'a pas été choisie";
				$_SESSION['dol_company'] = 'INDEFINIE';
			} else {
				$_SESSION['dol_company'] = $tbentity[$entity];
			}
			$_SESSION['dol_entity'] = $entity;
		} else {
			$_SESSION['dol_company'] = $tbentity[$user2->entity];
			$_SESSION['dol_entity'] = $user2->entity;
		}
		
		$info .= " ({$_SESSION['dol_company']})";
		if (empty($error)) {
			header("location:sel_user.php?submitted=1&info=". urlencode($info));
			die();
		}
		// $tbentity[0] = 'Toutes entités';
		//print_r($user2);
		
		//print_r($tbentity);
	} else {
		$error = "Utilisateur avec login=$newLogin introuvable";
	}
//	print_r($error);
//	print_r($_POST);
} else {
	$submitted = GETPOST('submitted', 'int');
	$info = GETPOST('info');
}
include 'header.php';
?>

<script src="assets/datalist_utils.js"></script>
<main class="container" style="max-width: 600px">
	<div class="my-3 p-3 bg-body rounded shadow-sm">
		<?php if ($submitted && !empty($error)) { ?>
			<span class="badge bg-danger">Erreur(s) : <br/><?=$error?></span>
		<?php } elseif ($submitted && empty($error)) { ?>
			<div class="mb-3">
				<span class="badge bg-success">Utilisateur sélectionné : <small><?=$info?></small></span>
			</div>
			<div class="mb-3">
				<a href="esmagasin.php" class="w-50 btn btn-lg btn-info">E/S Stock Magasin</a>
			</div>
		<?php } ?>
			
		<h5 class="border-bottom pb-2 mb-0">Selection utilisateur</h5>
		
		<script>
			var enableSubmit = false;
			var tabUsers = [];
			$(document).ready(function () {
				checkSubmitOk();
				$('#userid').on('input',function () {
					if (!userSetLabel()) ajaxGetDataList('user', 'userid', $('#userid').val(), '<?=getToken()?>');
					if ($('#userlabel').text().indexOf('AllEntities') >= 0) {
						$('div#selentdiv').show();
					} else $('div#selentdiv').hide();
					checkSubmitOk();
				});
				$("#clearuserid").click(function() {
					$("#userid").val("");
					toggleSubmit(false);
					return false;
				});
			});
			
			function userSetLabel() {
				for(var p of tabUsers){
					if (p.value == $('#userid').val()) {
						$('#userlabel').html(p.label.replace(p.value, ''));
						if ($('#userlabel').text().indexOf('AllEntities') >= 0) {
							$('div#selentdiv').show();
						} else $('div#selentdiv').hide();
						return 1;
						break;
					}
				}
				return 0;
			}
			
			function checkSubmitOk() {
				toggleSubmit($("#userid").val() != '');
			}
			/** enable disable the submit button
			 * @param {type} bool 
			 * @returns {undefined}
			 * */
			function toggleSubmit(bool) {
				enableSubmit = bool;
				$('#submitButton').prop('disabled',!bool);
			}
		</script>
		<div class="bd-example">
			<form id="seluser" name="seluser" method="post" action="">
				<input type="hidden" name="token" value="<?= $_SESSION['token'] ?>" />
				<input type="hidden" name="action" value="seluser">

				<div class="mb-3">
					<label for="userid" class="form-label">Utilisateur</label>
					<div class="input-group">
						<button class="btn btn-danger" title="effacer l'entrée" id="clearuserid">D</button><input class="form-control" list="useridOptions" id="userid" name="userid" placeholder="Entrez votre nom, prénom ou login" value="<?=$user->login?>">
					</div>
					<datalist id="useridOptions">
					</datalist>
					<div>
						&nbsp;&nbsp;<i><span id="userlabel" class="small" style="color: #4d4d4c"></span></i>
					</div>
				</div>
				<?php if (multiCompany) { ?>
				<div class="form-floating mb3" id="selentdiv" style="margin-bottom: 1rem !important">
					<?=$actionMC->select_entities($_SESSION['dol_entity'],"entity","",false,false,false,false,"","form-select", false);?>
					<label for="entity">Entité</label>
				</div>
				<?php } ?>
				<div class="input-group mb-3">
					<button class="w-50 btn btn-lg btn-primary" type="submit" id="submitButton">Sélectionner utilisateur</button>
					<?php if ($_SESSION['main_dol_login'] != $_SESSION['dol_login'] || $_SESSION['main_dol_entity'] != $_SESSION['dol_entity']) { 
						//echo $_SESSION['main_dol_login'].'|'.$_SESSION['main_dol_entity'].$_SESSION['dol_login'].'|'.$_SESSION['dol_entity'];?>
						<a href="sel_user.php?action=seluser&userid=<?=$_SESSION['main_dol_login']?>&entity=<?=$_SESSION['main_dol_entity']?>&token=<?=$_SESSION['token']?>" class="w-50 btn btn-lg btn-secondary" title="Revenir à l'utilisateur initialement authentifié">Utilisateur authentifié</a>
					<?php } ?>
				</div>
			</form>
		</div>
	</div>
</main>

<?php
include 'footer.php';
