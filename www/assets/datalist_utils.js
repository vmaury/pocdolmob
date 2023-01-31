/* 
 * Copyright (C) 20xx VMA Vincent Maury <vmaury@timgroup.fr>
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY
 */

/** remplit une data list en ajax
 * 
 * @param {str} type product | project | user
 * @param {str} destId html id where to fill the list
 * @param {str} search search text
 * @param {str} token token
 * @returns {void}
 */
function ajaxGetDataList(type, destId, search, token) {
	$.post("includes/ajaxDataList.php",
		{
			// token: '<? = $_SESSION['token']?>', marche pa c bien dommage
			type: type,
			search: search,
			token:token
		},
		function (rawdata, status) {
			//console.log(rawdata);
			if (rawdata == 'Unconnected' || rawdata.indexOf('<html') >= 0) {
				window.location.replace("index.php");
			}
			$('#' + destId + 'Options').html('');
			$('#' + type + 'label').html('');
			setTab(destId, []); // reinit tableau contenant les données
			var data = $.parseJSON(rawdata); //!! indispensable !!
			if (data.length > 0) {
				console.log(data);
				setTab(destId, data);
				var options = data.map(function (o) { // remplit la liste
					return(`<option value="${o.value}">${o.label}</option>`);
				});
				console.log(options);
				if (data.length == 1) {
					$('input#' + destId).val(data[0].value);
					$('#' + type + 'label').html(data[0].label.replace(data[0].value, ''));	// affiche la desc mais sns la ref
					if (destId == 'refproduit') {
						toggleScanner(false);
					}
				}
			} else {
				var options = ['<option>Aucun enregistrement correspondant</option>'];
			}
			$('#' + destId + 'Options').append(options);
			//alert("Data: " + data + "\nStatus: " + status);
		}
	);
}

function setTab(destId, data) {
	if (destId == 'refproduit') {
		tabProdRefVal = data;
	} else if (destId == 'refproject') {
		tabProjRefVal = data;
	} else 
		tabUsers = data;
}

/** remplit une data list en ajax
 * 
 * @param {str} objtype product | project | task
 * @param {str} id object dest id or ref
 * @param {str} rawfile base64 encoded file (photo, canvas)
 * @param {str} name name of the file
 * @param {str} token token
 * @returns {void}
 */
function postRaw(objtype, id, rawfile, name, token) {
	$.post("includes/ajaxDataPost.php",
		{
			// token: '<? = $_SESSION['token']?>', marche pa c bien dommage
			enctype: 'multipart/form-data',
			type: 'POST',
			objtype: objtype,
			id: id,
			rawfile: rawfile,
			name:name,
			token:token
		},
		function (rawdata, status) {
			console.log(rawdata);
			$("div#result").text(rawdata);
			//alert("Data: " + data + "\nStatus: " + status);
		}
	);
}