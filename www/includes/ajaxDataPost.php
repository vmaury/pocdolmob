<?php

/* 
 * Copyright (C) 20xx VMA Vincent Maury <vmaury@timgroup.fr>
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY
 */
header('Content-Type: text/html; charset=utf-8');
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
	$id = GETPOST('id');
	$base = GETPOST('rawfile', 'none');
	$name = GETPOST('name', 'san_alpha');
	$base = str_ireplace('data:image/png;base64,', '', $base);
	switch(GETPOST("objtype")) {
		case 'project':
			require_once DOL_DOCUMENT_ROOT.'/projet/class/project.class.php';
			$proj = new Project($db);
			if ($proj->fetch($id) >0 || $proj->fetch(null, $id) >0) {
				//$upload_dir = DOL_DATA_ROOT.'/projet/'.$proj->ref;
				$upload_dir = $conf->project->dir_output."/".dol_sanitizeFileName($proj->ref);
				$result = dol_mkdir($upload_dir);
				//write the content to the server
				if (empty($name)) $name = 'img_'.date('YmdHis');
				$binary = base64_decode($base);
				$path = $upload_dir.'/'.$name.'.png';
				if(!$file = fopen($path, 'wb')){
					die ('Image upload Fail!');
				} else {
					fwrite($file, $binary);
					fclose($file);
					die ("Image uploaded as $name.jpg in ".$proj->ref);
				}
			} else die("Projet id $id inexistant");
			break;
		
		default :
			die('Unknown action type');
			
	}
	//echo json_encode($tbresult);
}
