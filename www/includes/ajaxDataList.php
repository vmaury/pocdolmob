<?php

/* 
 * Copyright (C) 20xx VMA Vincent Maury <vmaury@timgroup.fr>
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY
 */

include './config.inc.php';
// If user is connected
if (empty($_SESSION['dol_login'])) {
	die('Unconnected');
} else {
	/* ça ça marche pas :-\
	 * if (GETPOST('token') != $_SESSION['token']) {
		die ('Invalid Token');
	} */
	//echo GETPOST("type").'/';
	//echo GETPOST("search");
	$search = addslashes(GETPOST("search"));
	$tbresult = [];
	switch(GETPOST("type")) {
		case 'product':
			$form = new Form($db);
			$tbresult = $form->select_produits_list('', $htmlname = 'productid', $filtertype = '', $limit = 20, $price_level = 0, $search, $status = -1, $finished = 2, $outputmode = 1, $socid = 0, $showempty = '1', $forcecombo = 0, $morecss = '', $hidepriceinlabel = 0, $warehouseStatus = ''); // warehouseinternal si que les entreopots internes
			break;
		
		case 'project':
			require_once DOL_DOCUMENT_ROOT.'/core/class/html.formprojet.class.php';
			$form = new FormProjets($db);
			$tbresult = $form->select_projects_list($socid = -1, $selected = '', $htmlname = 'projectid', $maxlength = 24, $option_only = 0, $show_empty = 1, $discard_closed = 1, $forcefocus = 0, $disabled = 0, $mode = 1, $search);
			break;
		
		case 'user':
			$form = new Form($db);
			//$tbresult = $form->select_users('', $htmlname = 'productid', $filtertype = '', $limit = 20, $price_level = 0, $filterkey = GETPOST("search"), $status = -1, $finished = 2, $outputmode = 1, $socid = 0, $showempty = '1', $forcecombo = 0, $morecss = '', $hidepriceinlabel = 0, $warehouseStatus = ''); // warehouseinternal si que les entreopots internes
			$search = 
			$morefilter = " and (lastname like '%".$search."%' or firstname like '%".$search."%' or login like '%".$search."%') ";
			$tbresbrut = $form->select_dolusers($selected = '', $htmlname = 'userid', $show_empty = 0, $exclude = null, $disabled = 0, $include = '', $enableonly = '', $force_entity = '0', $maxlength = 0, $showstatus = -1, $morefilter , $show_every = 0, $enableonlytext = '', $morecss = '', $noactive = 0, $outputmode = 1, $multiple = false);
			$tbresult = [];
			$userStat = new User($db);
			foreach ($tbresbrut as $id=>$name) {
				$userStat->fetch($id);
				$name = str_ireplace('Actif ', '', $name);
				$tbresult[] = array('value'=>$userStat->login, 'label'=>$name);
			}
			break;
		
		default :
			die('Unknown type');
			
	}
	echo json_encode($tbresult);
}
//print_r($tbp);
