<?php

/* 
 * Copyright (C) 20xx VMA Vincent Maury <vmaury@timgroup.fr>
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY
 */
include_once __DIR__."/config.inc.php"; // include of main.inc.php is there

if (multiCompany) {
	include_once DOL_DOCUMENT_ROOT.'/custom/multicompany/class/actions_multicompany.class.php';
	$actionMC = new ActionsMulticompany($db);
	$tbentity = $actionMC->getEntitiesList();
}


// If user is connected
if (!empty($_SESSION['dol_login'])) {
//	print_r($_SESSION);
//	die();
	if (GETPOST('actionlogin') == 'login') {
		$_SESSION['main_dol_login'] = $_SESSION['dol_login'];
		$_SESSION['main_dol_entity'] = $_SESSION['dol_entity'];
	}
	if (strstr($_SERVER['SCRIPT_FILENAME'], 'index.php')) {
		header('location:'.redirPage);
		exit();
	}
	require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';

	// Load translation files required by the page
	$langs->loadLangs(array("axs4all@axs4all"));

//	print_r($user);
} 

/** !!! l'existence de cette fonction fait que ça ne ramène pas à la pagde de cnx de dolibarr par défaut !!!
 * 
 */
function dol_loginfunction() {
	global $db, $user, $conf, $langs;
	$noIncMain = 1;
	include 'index.php';
}

/** retourne le premier entrepot définit comme magasin pour l'entité 
 * 
 * @param int $entity
 * @return class Entrepot 
 */
function getMagas($entity) {
	
	global $db;
	require_once DOL_DOCUMENT_ROOT.'/product/stock/class/entrepot.class.php';
	$entrepot = new Entrepot($db);
	if (defined('idEntrepotMagas')) {
		$entrepot->fetch(idEntrepotMagas); 
		return $entrepot;
	}
	$tbWh = $entrepot->list_array();
	// print_r($tbWh);
	foreach ($tbWh as $idwh=>$labwh) {
		$entrepot->fetch($idwh);
		//print_r($entrepot);
		if ($entrepot->array_options['options_magasin'] == 1) {
			return $entrepot;
		}
	}
	return new stdClass;
}

function getToken() {
	if (isset($_SESSION['token']) && !empty($_SESSION['token'])) {
		return $_SESSION['token'];
	} else return newToken ();
}