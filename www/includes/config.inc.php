<?php

/* 
 * Copyright (C) 20xx VMA Vincent Maury <vmaury@timgroup.fr>
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY
 */
define("debug", true);
define("multiCompany", false);
if (debug) {
	ini_set("display_errors", true);
	error_reporting(E_ALL & ~E_NOTICE);
}
//define('redirPage', 'sel_user.php');
//define('redirPage', 'esmagasin.php');
define('redirPage', 'welcome.php');
define('selUserActive', false); // Active le changement utilisateur
define('entity4warehouse', '5,6'); // id des entités pour lesquelles on gère les transferts entre magasins

/* si cette constante existe, on y définit quel entrepot (qui doit exister) joue le rôle du magasin */
/* Si non, les magasins doivent etre flaggués avec l'extrafield boolean 'magasin' (à créer) */
define('idEntrepotMagas', 1);

$res = include_once __DIR__."/../../../../main.inc.php";
if (!$res && file_exists("../main.inc.php")) $res = @include "../main.inc.php";
if (!$res && file_exists("../../main.inc.php")) $res = @include "../../main.inc.php";
if (!$res && file_exists("../../../main.inc.php")) $res = @include "../../../main.inc.php";
if (!$res) die("Include of main fails");

