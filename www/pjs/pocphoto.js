/* 
 * Copyright (C) 20xx VMA Vincent Maury <vmaury@timgroup.fr>
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY
 * 
 * 
 * 
 * Js for zpocscan.php 
 */

const webcamElement = document.getElementById('webcam');
const canvasElement = document.getElementById('canvas');
const snapSoundElement = document.getElementById('snapSound');

const webcam = new Webcam(webcamElement, 'user', canvasElement, snapSoundElement);
var tabProjRefVal = [];
var nbCam = 0;
var file2upl = false;

var wcOn = -1;
webcam.start()
   .then(result =>{
	  wcOn = 1;
      console.log("webcam started");
//		console.log(webcam.webcamList);
//		console.log(webcam.webcamCount);
		nbCam = webcam.webcamCount;

   })
   .catch(err => {
	   alert('Impossible de démarrer la caméra : ' + err);
       console.log(err);
   });

$(document).ready(function () {
	let wcwidth = $('#webcam').parent().width();
	if (wcwidth > 640) wcwidth = 640;
	$('#webcam').width(wcwidth);
	$('#webcam').height($('#webcam').width() * 3/4);
	toggleSubmit(false);
	photoTaken = false;
	//if (wcOn == -1) $('#btflip').hide();
//				console.log(webcam.webcamList); // Pas accesible dans ce contexte
//				console.log(webcam.webcamCount);
	//if (nbCam <= 1) $('#btflip').hide();

	$('#startscan').click(function () {
		toggleCam();
	});

	$('#sendButton').click(function () {
		if (photoTaken) {
			postRaw('project', $('#refproject').val(), picture, '', dolToken);
			camStart();
		}
		if (file2upl) {
			$('#file-upl').show();
		} else return false;
	});

	$('#btflip').click(function () {
		webcam.stop();
		webcam.flip();
		webcam.start();
		console.log($('#file-upl'));
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

	$("#send-file-btn").click(function() {
		$('#file-upl').trigger("click");
		file2upl = true;
		checkSubmitOk();
	});
});
function toggleCam() {
	if (wcOn == 1) {
		picture = webcam.snap();
		//console.log(picture);
		webcam.stop();
		$('#startscan').html('<i class="fa-solid fa-video xxx-large"></i>');
		wcOn = 0;
		photoTaken = true;
		checkSubmitOk();
	} else if (wcOn == 0) { // 
		camStart();
	}
}

function camStart() {
	webcam.start();
	$('#startscan').html('<i class="fa-solid fa-camera xxx-large">');
	wcOn = 1;
	photoTaken = false;
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
	toggleSubmit($("#refproject").val() != '' && (photoTaken || file2upl));
}
/** enable disable the submit button
 * @param {type} bool 
 * @returns {undefined}
 * */
function toggleSubmit(bool) {
	enableSubmit = bool;
	$('#sendButton').prop('disabled',!bool);
}