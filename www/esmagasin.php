<?php
$title = 'Entrées-sorties magasin';
include 'header.php';
$form = new Form($db);
//print_r($_POST);
//print_r($_SESSION);
$entity = $_SESSION['dol_entity'];
$mvtEntreEntrepot = in_array($entity, explode(',',entity4warehouse));
require_once DOL_DOCUMENT_ROOT.'/product/stock/class/entrepot.class.php';

$entrepotOr = getMagas($entity);
if ($entrepotOr->id >0) $idMagas = $entrepotOr->id;
//$entrepotOr->fetch($idMagas);
$entrepotLabel = $entrepotOr->label;

if (GETPOST('action') == 'modstock'){
	$error = [];
	$submitted = true;
	require_once DOL_DOCUMENT_ROOT.'/product/class/product.class.php';
	$prod = new Product($db);
	$prodref = GETPOST('refproduit');
	$way = GETPOST('way');
	$qte = (int)GETPOST("qte", 'int');
	$prodid = $prod->fetch('', $prodref);
	$prod->load_stock();
	//echo "stock reel : ".$prod->stock_reel;
	/*print_r($prod->stock_warehouse);
	[50051] => stdClass Object
        (
            [real] => 40
            [id] => 3752
        )*/
//	print_r($prod);
	$qteMagas = $prod->stock_warehouse[$idMagas]->real;
	//echo "qta magas $qteMagas, way $way";
	if ($way == 'out' && $qte > $qteMagas) {
		$error[] = "La qté à sortir ($qte) est > au stock ($qteMagas) dans $entrepotLabel";
	}
	//die();
	if ($prodid <= 0) $error[] = "Produit avec la ref $prodref introuvable";
	$prodid = $prod->id;
	if (!in_array($way, ['in', 'out'])) $error[] = "Sens $way incohérent";
	if ($mvtEntreEntrepot) { 
		$idwarehouse = GETPOST('idwarehouse', 'int');
		require_once DOL_DOCUMENT_ROOT.'/product/stock/class/entrepot.class.php';
		$entrepotDest = new Entrepot($db);
		$pw = $entrepotDest->fetch($idwarehouse);
		if ($pw <= 0) 	$error[] = "Entreprot n° $idwarehouse introuvable";
	} else {
		$refproject = GETPOST('refproject');
		require_once DOL_DOCUMENT_ROOT.'/projet/class/project.class.php';
		$proj = new Project($db);
		$idproj = $proj->fetch('', $refproject);
		if ($idproj <= 0) 	$error[] = "Projet avec la ref $refproject introuvable";
		$idproj = $proj->id;
	}
	
	if ($way == 'in') $qte = 0 - $qte;
	if ($qte == 0) $error[] = "Quantité nulle";
	//if (empty($idMagas))  $error[] = "L'id du magasin de stockage pour cette entité n'a pas été définie";
	
	if (count($error == 0)) { // C'est parti
		require_once DOL_DOCUMENT_ROOT.'/product/stock/class/mouvementstock.class.php';
		$mvtStock = new MouvementStock($db);
		/*$type				Direction of movement:
		0=input (stock increase by a stock transfer), 1=output (stock decrease by a stock transfer),
		2=output (stock decrease), 3=input (stock increase)
	       	Note that qty should be > 0 with 0 or 3, < 0 with 1 or 2. ($qty = 0 - $qte )*/
		$fxButNV = '<span class="btvamstk" data-id="#####">BT</span> | ';
		if ($mvtEntreEntrepot) {
			//$label = ($qte > 0 ? "Sortie de" : "Remise en")." magasin ".($qte > 0 ? "vers" : "depuis")." projet par appli ESmagasin par ".$user->firstname.' '.$user->lastname;
			$label = "$fxButNV Transfert de stock entre entrepôts (appli ESmagasin)"; // par ".$user->firstname.' '.$user->lastname;
			$type = $qte > 0 ? 1 : 0;
			$type2 = $qte > 0 ? 0 : 1;
		} else { // vers ou de projet
			$label = $fxButNV.($qte > 0 ? "Sortie de" : "Remise en")." magasin ".($qte > 0 ? "vers" : "depuis")." projet (appli ESmagasin)"; // par ".$user->firstname.' '.$user->lastname;
			$type = $qte > 0 ? 2 : 3;
			//$mvtStock->fk_project = $idproj;
//			$mvtStock->fk_origin = $idproj;
//			$mvtStock->origintype = 'project';
			$mvtStock->origin = $proj;
		}
		$sm1 = $mvtStock->_create($user, $prodid, $idMagas, 0 - $qte, $type, $prod->pmp, $label);
		$sm2 = 0;
		if ($sm1 <=0) {
			$error[] = "Impossible d'effectuer le mvt de stock 1 ($sm1): ". implode('; ',$mvtStock->errors);
		} else {
			$info[] = "id mvt 1 = $sm1";
			if ($mvtEntreEntrepot) {
				$sm2 = $mvtStock->_create($user, $prodid, $idwarehouse, $qte, $type2, $prod->pmp, $label);
				if ($sm2 <=0) {
					$error[] = "Impossible d'effectuer le mvt de stock 2 ($sm2): ".implode('; ',$mvtStock->errors);
				} else $info[] = "id mvt 2 = $sm2";
			}
			$dataid = $sm1;
			if ($sm2 > 0) $dataid .= ",".$sm2;
			// rajoute les id des mouvements
			$sql = "update ".MAIN_DB_PREFIX."stock_mouvement set label=REPLACE(label, '#####', '$dataid') where rowid in ($dataid)";
			$result = $db->query($sql);
			if ($result <= 0) $error[] = "Erreur de mise à jour des labels des mvt de stock ($sql)";
		}
	}
	
//	print_r($error);
//	print_r($_POST);
}
?>
<script src="assets/html5-qrcode.min.js"></script>
<script src="assets/datalist_utils.js"></script>
<main class="container" style="max-width: 600px">
	<div class="my-3 p-3 bg-body rounded shadow-sm">
		<?php if ($submitted && count($error) > 0) { ?>
		<span class="badge bg-danger">Erreur(s) : <br/><?=implode('<br/> &#149; ', $error)?></span>
		<?php } elseif ($submitted && count($error) == 0) { ?>
		<span class="badge bg-success">Mouvement(s) de stock enregistré(s) <br/><small><?=implode('<br/>', $info)?></small></span>
		<?php } ?>
		<?php if($idMagas != 0) { ?>
			<h5 class="border-bottom pb-2 mb-0">Entrées Sorties du magasin <?=$tbentity[$entity]?> <small>(<?=$entrepotLabel?>)</small></h5>
			<div id="reader">
			</div>
			<script>
				var enableScanRes = true;
				var enableSubmit = false;
				var tabProdRefVal = tabProjRefVal = [];
				function onScanSuccess(decodedText, decodedResult) {
					// Handle on success condition with the decoded text or result.
					console.log(`Scan result: ${decodedText}`, decodedResult);
					$('#result').text(decodedText);
					//$('#refproduit').val(decodedText);
					if (enableScanRes) ajaxGetDataList('product', 'refproduit', decodedText, '<?=getToken()?>');
	//				$('#reader').hide();
	//				$('#startscan').show();
				}

				var html5QrcodeScanner = new Html5QrcodeScanner(
						"reader", 
						{fps: 5,
						qrbox: 250
						}
				);
				html5QrcodeScanner.render(onScanSuccess);

				$(document).ready(function () {
					// DIv inits
					toggleScanner(true);
					toggleSubmit(false);
					$('div#reader button').addClass('w-50 btn btn-lg .btn-warning');
					$('#reader__dashboard_section_swaplink').parent().hide();
					$('#reader__camera_permission_button').text('Cliquer pour autoriser la caméra');
					// Events
					$('#refproduit').on('input',function () {
						if (!prodSetLabel()) ajaxGetDataList('product', 'refproduit', $('#refproduit').val(), '<?=getToken()?>');
						checkSubmitOk();
					});
					$("#clearrefproject").click(function() {
						$("#refproject").val("");
						$('#projectlabel').html('');
						toggleSubmit(false);
						return false;
					});
					$("#clearrefproduit").click(function() {
						$("#refproduit").val("");
						$('#productlabel').html('');
						toggleSubmit(false);
						return false;
					});
		
					$('#refproject').on('input',function () {
						 if (!projSetLabel()) ajaxGetDataList('project', 'refproject', $('#refproject').val(), '<?=getToken()?>');
						 checkSubmitOk();
					});
					
					$('#idwarehouse').on('input',function () {
						checkSubmitOk();
					});
					$('#inc').click(function () {
						if (Number($('input#qte').val()) == 0) $('input#qte').val(1);
						$('input#qte').val(Number($('input#qte').val()) + 1);
						return false;
					});
					$('#dec').click(function () {
						$('input#qte').val(Number($('input#qte').val()) - 1);
						if ($('input#qte').val() < 1)
							$('input#qte').val(1);
						return false;
					});
					$('#startscan').click(function () {
						toggleScanner(true);
					});

				});

				/** enable disable the scanner
				 * @param {type} bool 
				 * @returns {undefined}
				 * */
				function toggleScanner(bool) {
					enableScanRes = bool;
					if (bool) {
						$('#reader').show();
						$('#startscan').hide();
						$("#refproduit").val('');
						toggleSubmit(false);
					} else {
						$('#reader').hide();
						$('#startscan').show();
					}
				}
				function checkSubmitOk() {
					toggleSubmit($("#refproduit").val() != '' && $("#" + imput).val() != '' && $("#qte").val() != 0);
				}
				/** enable disable the submit button
				 * @param {type} bool 
				 * @returns {undefined}
				 * */
				function toggleSubmit(bool) {
					enableSubmit = bool;
					$('#submitButton').prop('disabled',!bool);
				}
				function prodSetLabel() {
					for(var p of tabProdRefVal){
						if (p.value == $('#refproduit').val()) {
							$('#productlabel').html(p.label.replace(p.value, ''));
							return 1;
							break;
						}
					}
					return 0;
				}
				function projSetLabel() {
					for(var p of tabProjRefVal){
						if (p.value == $('#refproject').val()) {
							$('#projectlabel').html(p.label.replace(p.value, ''));
							return 1;
							break;
						}
					}
					return 0;
				}
			</script>
			<div class="mb-3"><button class="w-50 btn btn-lg btn-primary" id="startscan">Scan</button></div>
			<div class="small">Resultat scan code : <span id="result"></span></div>
			<div class="bd-example">
				<form id="modstock" name="modstock" method="post" action="">
					<input type="hidden" name="token" value="<?= $_SESSION['token'] ?>" />
					<input type="hidden" name="action" value="modstock">

					<div class="mb-3">
						<label for="refproduit" class="form-label">Produit</label>
						<div class="input-group">
							<button class="btn btn-danger" title="effacer l'entrée" id="clearrefproduit">D</button><input class="form-control" list="refproduitOptions" id="refproduit" name="refproduit" placeholder="Scanner un code qr/barre ou saisir une ref pour chercher...">
						</div>
						<datalist id="refproduitOptions">
						</datalist>
						<div>
							&nbsp;&nbsp;<i><span id="productlabel" class="small" style="color: #4d4d4c"></span></i>
						</div>
					</div>

					<div class="input-group mb-3">
						Sens &nbsp;&nbsp;
						<div class="form-check">
							<input class="form-check-input" type="radio" name="way" id="out" value="out" checked>
							<label class="form-check-label" for="out">
								Sortie &nbsp;
							</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="radio" name="way" value="in" id="in">
							<label class="form-check-label" for="in">
								Retour
							</label>
						</div>
					</div>

					<div class="input-group mb-3">
						<label for="qte" class="form-label">Quantité &nbsp;  &nbsp; </label>
						<input class="form-control mb-1" list="qteOptions" id="qte" name="qte" value="1" style="width:10em">&nbsp;&nbsp; 
						<datalist id="qteOptions">
							<option>1</option>
							<option>2</option>
							<option>3</option>
							<option>4</option>
							<option>5</option>
							<option>6</option>
							<option>7</option>
							<option>8</option>
							<option>9</option>
							<option>10</option>
							<option>11</option>
							<option>12</option>
							<option>13</option>
							<option>14</option>
							<option>15</option>
							<option>16</option>
							<option>17</option>
							<option>18</option>
							<option>19</option>
							<option>20</option>
							<option>21</option>
							<option>22</option>
							<option>23</option>
							<option>24</option>
							<option>25</option>
						</datalist>
						&nbsp; <strong><span class="btn btn-primary btn-lg" id="dec">-</span>&nbsp; <span class="btn btn-primary btn-lg" id="inc">+</span></strong>
					<?php //$form->select_produits_list($selected = '', $htmlname = 'productid', $filtertype = '', $limit = 20, $price_level = 0, $filterkey = '', $status = 1, $finished = 2, $outputmode = 0, $socid = 0, $showempty = '1', $forcecombo = 1, $morecss = '', $hidepriceinlabel = 0, $warehouseStatus = ''); ?>
					</div>
					<?php
					if (in_array($_SESSION['dol_entity'], explode(',',entity4warehouse))) { // entrepot pr Innov et APLV 
						require_once DOL_DOCUMENT_ROOT . '/product/class/html.formproduct.class.php';
						$formprod = new FormProduct($db);
						?>
						<div class="input-group mb-3">
							<script>var imput='idwarehouse';</script>
							<label for="idwarehouse" class="form-label">Entrepôt &nbsp;  &nbsp; </label>
							<?=$formprod->selectWarehouses($selected = '', $htmlname = 'idwarehouse', $filterstatus = '', $empty = 0, $disabled = 0, $fk_product = 0, $empty_label = '', $showstock = 0, $forcecombo = 1, $events = array(), $morecss = 'minwidth200 form-select', $exclude = '', $showfullpath = 1, $stockMin = false, $orderBy = 'e.ref'); ?>
						</div>
					<?php } else { ?>
						<script>var imput='refproject';</script>
						<div class="mb-3">
							<label for="refproject" class="form-label">Projet &nbsp;  &nbsp; </label>
							<div class="input-group">
								<button class="btn btn-danger" title="effacer l'entrée" id="clearrefproject">D</button><input class="form-control" list="refprojectOptions" id="refproject" name="refproject" placeholder="Tapez pour chercher...">
							</div>
							<datalist id="refprojectOptions">
							</datalist>
							<div>
							&nbsp;&nbsp;<i><span id="projectlabel" class="small" style="color: #4d4d4c"></span></i>
							</div>
						</div>
					<?php } ?>

					<div class="input-group mb-3">
						<button class="w-50 btn btn-lg btn-primary" type="submit" id="submitButton">Enregistrer mouvement</button>
						<?php if (defined('selUserActive') && selUserActive === true) { ?>
							<a href="sel_user.php" class="w-50 btn btn-lg btn-secondary">Changement utilisateur</a>
						<?php } ?>
					</div>
				</form>
			</div>
		<?php } else { ?>
			<h5 class="border-bottom pb-2 mb-0 danger">L'entrepôt par défaut de l'entité <?=$tbentity[$entity]?> n'est pas défini, impossible d'utiliser cette application pour cette entité.<br/>
				Vous devez définir cet entrepôt par défaut en cochant la case "magasin" d'un entrepôt de cette entité</h5>
		<?php }?>
	</div>
</main>

<?php
include 'footer.php';
