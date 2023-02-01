<?php
/* 
 * Copyright (C) 20xx VMA Vincent Maury <vmaury@timgroup.fr>
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY
 */

/**
 *      \file       /www/logout.php
 *      \brief      Page called to disconnect a user, copied and modified from htdocs/user/logout.php
 */
include_once 'includes/config.inc.php.php';
//if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL','1'); // Uncomment creates pb to relogon after a disconnect
if (!defined('NOREQUIREMENU'))  define('NOREQUIREMENU', '1');
if (!defined('NOREQUIREHTML'))  define('NOREQUIREHTML', '1');
if (!defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX', '1');
//if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');	// We need company to get correct logo onto home page
if (!defined('EVEN_IF_ONLY_LOGIN_ALLOWED'))  define('EVEN_IF_ONLY_LOGIN_ALLOWED', '1');


global $conf, $langs, $user;

// Call trigger
$result = $user->call_trigger('USER_LOGOUT', $user);
if ($result < 0) $error++;
// End call triggers

// Hooks on logout
$action = '';
$hookmanager->initHooks(array('logout'));
$parameters = array();
$reshook = $hookmanager->executeHooks('afterLogout', $parameters, $user, $action); // Note that $action and $object may have been modified by some hooks
if ($reshook < 0) { $error++; }

// Define url to go
$url = "index.php"; // By default go to login page

// Destroy session
dol_syslog("End of session ".session_id());
if (session_status() === PHP_SESSION_ACTIVE)
{
	session_destroy();
}

// Not sure this is required
unset($_SESSION['dol_login']);
unset($_SESSION['dol_entity']);
unset($_SESSION['urlfrom']);


header("Location:".$url); // Default behaviour is redirect to index.php page