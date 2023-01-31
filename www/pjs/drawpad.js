/* 
 * Copyright (C) 20xx VMA Vincent Maury <vmaury@timgroup.fr>
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY
 * 
 * 
 * 
 * Js for drawpad.php 
 */
canvasDrawn = false;
var tabProjRefVal = [];

$(document).ready(function () {
	
	$("#divdrawpad").height($("#divdrawpad").width())  ; // $("#divdrawpad").height ||* (3 / 4)
	$("#divdrawpad").drawpad();
	$("#divdrawpad canvas")[0].click(function() {
		canvasDrawn = true; // approximatif
	});
	$('#btgomme').click(function() {
	   clearAllCanvas();
	});
	toggleSubmit(false);

	$('#sendButton').click(function () {
		//$('#inp_img').val ($("#divdrawpad canvas")[0].toDataURL());
		postRaw('project', $('#refproject').val(), $("#divdrawpad canvas")[0].toDataURL(), '', dolToken);
		clearAllCanvas();
		return false;
	});

	$("#clearrefproject").click(function() {
		$("#refproject").val("");
		$('#projectlabel').html('');
		toggleSubmit(false);
		return false;
	});

	$('#refproject').on('input',function () {
		 if (!projSetLabel()) ajaxGetDataList('project', 'refproject', $('#refproject').val(), dolToken);
		 checkSubmitOk();
	});
});
function clearAllCanvas() {
	//console.log ($("#divdrawpad canvas"));
	const context = $("#divdrawpad canvas")[0].getContext('2d');
	context.clearRect(0, 0, $("#divdrawpad").width(),$("#divdrawpad").height());
	canvasDrawn = false;
	checkSubmitOk();
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
function checkSubmitOk() {
	toggleSubmit($("#refproject").val() != '' && canvasDrawn);
}
/** enable disable the submit button
 * @param {type} bool 
 * @returns {undefined}
 * */
function toggleSubmit(bool) {
	enableSubmit = bool;
	$('#sendButton').prop('disabled',!bool);
}