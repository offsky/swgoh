'use strict';
//https://css-tricks.com/drag-and-drop-file-uploading/

// feature detection for drag&drop upload
var isAdvancedUpload = function() {
	var div = document.createElement('div');
	return ( ( 'draggable' in div ) || ( 'ondragstart' in div && 'ondrop' in div ) ) && 'FormData' in window && 'FileReader' in window;
}();

function setupFileUpload() {
	var theForm	= $('#uploadForm');
	var droppedFiles = false;

	// automatically submit the form on file select
	$('#file').on('change', function(e) {
		submitForm();
	});

	// drag&drop files if the feature is available
	if(isAdvancedUpload) {
		theForm.addClass('has-advanced-upload' ); // letting the CSS part to know drag&drop is supported by the browser
		theForm.on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
			// preventing the unwanted behaviours
			e.preventDefault();
			e.stopPropagation();
		});
		theForm.on('dragover dragenter', function() {
			theForm.addClass('is-dragover');
		});
		theForm.on('dragleave dragend drop', function() {
			theForm.removeClass('is-dragover');
		});
		theForm.on('drop', function(e) {
			droppedFiles = e.originalEvent.dataTransfer.files; // the files that were dropped
			submitForm(); // automatically submit the form on file drop
		});
	} else {
		$('#uploadForm').removeClass('is-uploading');
		$('#uploadForm').addClass('is-error');
		$('#uploadForm .box__error span').text("File upload requires JS to work.");
	}

	// if the form was submitted
	var submitForm = function() {
		// preventing the duplicate submissions if the current one is in progress
		if(theForm.hasClass('is-uploading')) return false;
		theForm.addClass('is-uploading').removeClass('is-error');

		// gathering the form data
		var files = $('#file')[0].files;
		if(droppedFiles) files = droppedFiles;
		uploadPic(files);	
	};

	// Firefox focus bug fix for file input
	$('#file').on('focus', function(){ $('#file').addClass( 'has-focus' ); });
	$('#file').on('blur', function(){ $('#file').removeClass( 'has-focus' ); });
};

function uploadedPhotoError(err) {
	console.log('There was an error uploading your photo: ', err.message);
	$('#uploadForm').removeClass('is-uploading');
	$('#uploadForm').addClass('is-error');
	$('#uploadForm .box__error span').text(err.message);
}

function uploadedPhotoSuccess(data) {
	console.log("Success",data);
	$('#uploadForm').removeClass('is-uploading');
	$('#uploadForm').addClass('is-success');

	$('#file').off();
	$('#uploadForm').off();

	$('#photo').attr('src',data.Location);
	setTimeout(function() {
		$('.box__input').hide();
		$('.box__success').hide();
		$('#photo').addClass('uploaded');
		$('#uploadForm').addClass('uploaded');
		$('#details').show();
	},1000);
}