var uploadedImg = null;
var startX = null;
var startY = null;
var gStream = null;

$(document).ready(function() {
	//when the value of the file input box changes
	$('#fileInput').on('change', photo_newFile);

	// Firefox bug fix
	//	input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
	//	input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });

	$('.overlay').on('mousedown',photo_MouseDownImage);
	$('.overlay').on('touchstart',photo_TouchStartImage);
	$('.overlay').on('touchmove',photo_TouchMoveImage);

	$('#magup').on('click',photo_magup);
	$('#magdown').on('click',photo_magdown);
	$('#rotate').on('click',photo_rotate);
	$('#stars').on('click',switchStars);

	$('#js_startWebcam').on("click",connectWebCam);
	$('#js_takePhoto').on("click",takePhoto);
	$('#js_download').on("click",downloadAvatar);
	//detectWebcam();

	uploadedImg = $('#sample')[0];
	photo_processImage();
});

function detectWebcam() {
	if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
		$('#js_startWebcam').show();
	} else if(navigator.getUserMedia) {
		$('#js_startWebcam').show();
	} else if(navigator.webkitGetUserMedia) {
		$('#js_startWebcam').show();
	} else if(navigator.mozGetUserMedia) {
		$('#js_startWebcam').show();
	} else {
		$('#file-prompt').text("Upload a Photo");
	}
}

//https://davidwalsh.name/browser-camera
function connectWebCam() {
	$("#webcamerror").hide();
	$("#video").show();
	$("#js_takePhoto").show();
	
	var video = $('#video')[0];

	if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
		navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
			gStream = stream;
			video.src = window.URL.createObjectURL(stream);
			video.play();
		}).catch(webcamError);
	} else if(navigator.getUserMedia) { // Standard
		navigator.getUserMedia({ video: true }, function(stream) {
			gStream = stream;
			video.src = stream;
			video.play();
		}, webcamError);
	} else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
		navigator.webkitGetUserMedia({ video: true }, function(stream){
			gStream = stream;
			if(window.URL) video.src = window.URL.createObjectURL(stream);
			else video.src = window.webkitURL.createObjectURL(stream);
			video.play();
		}, webcamError);
	} else if(navigator.mozGetUserMedia) { // Mozilla-prefixed
		navigator.mozGetUserMedia({ video: true }, function(stream){
			gStream = stream;
			video.src = window.URL.createObjectURL(stream);
			video.play();
		}, webcamError);
	}
}

function webcamError(error) {
	$("#webcamerror").show();
	$('#errordetails').html(error.name);
	$("#video").hide();
	$("#js_takePhoto").hide();
}

function takePhoto() {
	var video = document.getElementById('video');

	var w = video.videoWidth;
	var h = video.videoHeight;
	
	var canvas = document.createElement('canvas');
	canvas.width  = w;
	canvas.height = h;
	var context = canvas.getContext('2d');

	context.drawImage(video, 0, 0, w, h, 0, 0, w, h);
	var data = canvas.toDataURL("image/png");
	photo_processImage(data);

	var video = $('#video')[0];
	video.pause();
	if(gStream.stop) gStream.stop();
	if(gStream.getTracks) gStream.getTracks()[0].stop();
	video.src = "";
	closeModal();

	$('#cropLabel').show();
	$('#controls').show();
	$('#downloadbuttons').show();
	$('#file-prompt').text("Upload Another Photo");
}

//When a new file is selected, this does the upload using FileReader
function photo_newFile(e) {
	var file = $('#fileInput')[0].files[0];
	var name = $('#fileInput')[0].value.split('\\').pop();
	var imageType = /image.*/;

	// $('#file-prompt').text("Upload Another");

	//if it is an image
	if (file && file.type.match(imageType)) {
		$('#error').hide();
		$('#cropLabel').show();
		$('#controls').show();
		$('#downloadbuttons').show();
		$('#file-prompt').text("Upload Another Photo");

		var reader = new FileReader();
		
		reader.onload = function(e) {
			photo_processImage(reader.result);
		}

		reader.readAsDataURL(file);	
	} else {
		$('#error').show();
	}
}

//takes data from fileReader and processes it
function photo_processImage(data) {

	//In case loading of image is slower than the 100ms at the end of this function
	$('#sample').on("load", function() {
		putImgIntoDiv();
	});

	if(data!==undefined) {
		uploadedImg.src = data;
	}

	// console.log("DATA",data,uploadedImg);

	setTimeout(function() { 
		putImgIntoDiv();
	},100);
}

//puts the image into the hidden canvas and returns the pixels. starting at x/y in source image
function putImgIntoCanvas(img,x,y,scale) {

	var width = Math.floor($('#canvasCrop').width()/scale); //The width and height of the image region to copy into the canvas
	var height = Math.floor($('#canvasCrop').height()/scale);

	var imgX = Math.floor(x/scale); //The x and y position in the image to start copying from
	var imgY = Math.floor(y/scale);

	var resample = photo_detectSubsampling(img); //iOS has a bug where images larger than 1Megapixel get sampled poorly. This fixes
	var iw = img.naturalWidth;
	var ih = img.naturalHeight;
	if(resample) {
		width = Math.floor(width/2);
		height = Math.floor(height/2);
		imgX = Math.floor(imgX/2);
		imgY = Math.floor(imgY/2);
		iw/=2;
		ih/=2;
	}
	var squash = photo_detectVerticalSquash(img,iw,ih); //iOS has a bug with squashing the image vertically. This fixes
	dimy/=squash;

	// console.log("DETECT",resample, squash);

	if(width+imgX>uploadedImg.width) { //trying to copy more than the size of the image (rounding error?)
		var ratio = (uploadedImg.width-imgX)/width;
		width = uploadedImg.width-imgX;
		height = Math.floor(height*ratio);
	}
	if(height+imgY>uploadedImg.height) { //trying to copy more than the size of the image (rounding error?)
		var ratio = (uploadedImg.height-imgY)/height;
		height = uploadedImg.height-imgY;
		width = Math.floor(width*ratio);
	}

	// console.log("putImgIntoCanvas",x,y,width,height,'=>',imgX,imgY);
	// console.log("Image",img,img.width,img.height);

	var canvas = $('#canvasOut')[0];
	var ct = canvas.getContext('2d');

	//The width and height of the destination canvas without scaling
	var dimx = 500; 
	var dimy = 500;
	var startX = 0;
	var startY = 0;

	ct.save();

	var offsetX = dimx/2;
	var offsetY = dimy/2;
	ct.translate(offsetX+startX,offsetY+startY);
	if($('#canvasContainer').hasClass("rotateUp")) {
		ct.rotate(Math.PI);
	} else if($('#canvasContainer').hasClass("rotateRight")) {
		ct.rotate(Math.PI*.5);
		offsetX = dimx/2;
		offsetY = dimy/2;
	} else if($('#canvasContainer').hasClass("rotateLeft")) {
		ct.rotate(-Math.PI*.5);
		offsetX = dimx/2;
		offsetY = dimy/2;
	}
	// console.log("drawImage",img,imgX,imgY,width,height,-offsetX,-offsetY,dimx,dimy); //scales it down to 32x32
	ct.drawImage(img,imgX,imgY,width,height,-offsetX,-offsetY,dimx,dimy); //scales it down to 32x32
	
	ct.restore();
}

function putOverlayIntoCanvas() {
	var img = $('#overlay2')[0];
	if($("#stars").is(':checked')) img = $('#overlay1')[0];

	var width = 500; //The width and height of the image region to copy into the canvas
	var height = 500;

	var canvas = $('#canvasOut')[0];
	var ct = canvas.getContext('2d');

	ct.drawImage(img,0,0,width,height,0,0,width,height); 
}

function changeImgDimensions() {
	var container = $('#canvasContainer');
	var canvas = $('#canvasCrop');
	var width = uploadedImg.width;
	var height = uploadedImg.height;

	if(cardData.img_w && cardData.img_h) {
		if(container.hasClass("rotateRight") || container.hasClass("rotateLeft")) {
			canvas.width(parseInt(cardData.img_h));
			canvas.height(parseInt(cardData.img_w));
		} else {
			canvas.width(parseInt(cardData.img_w));
			canvas.height(parseInt(cardData.img_h));
		}
	}

	var size = canvas.css("background-size").split(" ");
	var pos = canvas.css("background-position").split(" ");
	if(size.length==2 && size.length==2) {
		var background_w = parseInt(size[0].replace("px",""));
		var background_h = parseInt(size[1].replace("px",""));
		var background_x = parseInt(pos[0].replace("px",""));
		var background_y = parseInt(pos[1].replace("px",""));

		// console.log("W",width, cardData.img_w,background_w,background_x);
		// console.log("H",height, cardData.img_h,background_h,background_y);

		if(background_w+background_x<cardData.img_w) {
			// console.log("image is too narrow");
			photo_magup();
			// putImgIntoDiv();
		}
		if(background_h+background_y<cardData.img_h) {
			// console.log("image is too short");
			photo_magup();
			// putImgIntoDiv();
		}
	}

}

//puts image into the scale/crop div
function putImgIntoDiv() {
	var canvas = $('#canvasCrop');
	var width = uploadedImg.width;
	var height = uploadedImg.height;
	var x = 0;

	//TODO: Why does safari fail to get width of image
	// console.log("putImgIntoDiv",width,height,canvas.width());

	//set image to full width of div
	var ratio = width ? canvas.width()/width : 1;
	width = canvas.width();
	height = Math.floor(height*ratio);
	
	//if that would make it not tall enough, make it a little bigger
	if(height<canvas.height()) {
		var ratio = height ? canvas.height()/height : 1;
		height = canvas.height();
		width = Math.floor(width*ratio);
		x = -(width-canvas.width())/2; //center it
	}


	// console.log("DONE",width,height);
	canvas.css("background-image","url("+uploadedImg.src+")");
	canvas.css("background-position",x+"px 0px");
	canvas.css("background-size",width+"px "+height+"px");

	finishCrop();
}

//when the crop is finished. Need to copy pixels from canvasCrop to canvasIn
function finishCrop() {
	var canvas = $('#canvasCrop');
	var size = photo_getCSSxy(canvas,"background-size");
	var pos = photo_getCSSxy(canvas,"background-position");

	var scale = size[0]/uploadedImg.width;

	if(isNaN(scale)) return; 
	
	putImgIntoCanvas(uploadedImg,-pos[0],-pos[1],scale);
	putOverlayIntoCanvas();
}


/* Mouse/Touch handlers for preview image */

function photo_MouseDownImage(event) {
	event.preventDefault();
	// console.log("Mouse Image",event);

	$('.overlay').on('mousemove', photo_MouseMoveImage);
	$('.overlay').on('mouseup', photo_MouseUpImage);
	$('.overlay').on('mouseout', photo_MouseOutImage);

	startX = event.offsetX;
	startY = event.offsetY;

	var container = $('#canvasContainer');
	if(container.hasClass("rotateUp")) { startX=-startX;startY=-startY; }
	else if(container.hasClass("rotateRight")) { var tmp = startX; startX=startY;startY=-tmp; }
	else if(container.hasClass("rotateLeft")) { var tmp = startX; startX=-startY;startY=tmp; }

	// console.log(startX,startY);
}

function photo_TouchStartImage(event) {
	// console.log("touch start");
	if(event.originalEvent.touches && event.originalEvent.touches.length == 1) {
		// console.log(event.touches);
		event.preventDefault();
		startX = event.originalEvent.touches[0].pageX;
		startY = event.originalEvent.touches[0].pageY;

		var container = $('#canvasContainer');
		var canvas = $('#canvasCrop');
		if(container.hasClass("rotateUp")) { startX=-startX;startY=-startY; }
		else if(container.hasClass("rotateRight")) { var tmp = startX; startX=startY;startY=-tmp; }
		else if(container.hasClass("rotateLeft")) { var tmp = startX; startX=-startY;startY=tmp; }

		$('.overlay').on('touchend', photo_TouchEndImage);
	}
}

function photo_MouseMoveImage(event) {
	var mouseX = event.offsetX;
	var mouseY = event.offsetY;

	var container = $('#canvasContainer');
	if(container.hasClass("rotateUp")) { mouseX=-mouseX;mouseY=-mouseY; }
	else if(container.hasClass("rotateRight")) { var tmp = mouseX; mouseX=mouseY;mouseY=-tmp; }
	else if(container.hasClass("rotateLeft")) { var tmp = mouseX; mouseX=-mouseY;mouseY=tmp; }
	
	photo_moveImage(mouseX,mouseY,startX,startY);

	startX = mouseX;
	startY = mouseY;
}

function photo_TouchMoveImage(event) {
	// console.log("touch move");
	if (event.originalEvent.touches && event.originalEvent.touches.length == 1) {
		// console.log(event.originalEvent.touches);
		event.preventDefault();

		var touchX = event.originalEvent.touches[0].pageX;
		var touchY = event.originalEvent.touches[0].pageY;

		var container = $('#canvasContainer');
		var canvas = $('#canvasCrop');
		if(container.hasClass("rotateUp")) { touchX=-touchX;touchY=-touchY; }
		else if(container.hasClass("rotateRight")) { var tmp = touchX; touchX=touchY;touchY=-tmp; }
		else if(container.hasClass("rotateLeft")) { var tmp = touchX; touchX=-touchY;touchY=tmp; }
		
		photo_moveImage(touchX, touchY, startX, startY);

		startX = touchX;
		startY = touchY;
	}
}

function photo_MouseUpImage(event) {
	$('.overlay').off('mousemove');
	$('.overlay').off('mouseup');
	$('.overlay').off('mouseout');
	finishCrop();
}

function photo_MouseOutImage(event) {
	$('.overlay').off('mousemove');
	$('.overlay').off('mouseup');
	$('.overlay').off('mouseout');
	finishCrop();
}

function photo_TouchEndImage(event) {
	$('.overlay').off('touchend');
	finishCrop();
}

function photo_moveImage(mouseX,mouseY,strtX,strtY) {
	var difX = parseInt(mouseX-strtX);
	var difY = parseInt(mouseY-strtY);

	var canvas = $('#canvasCrop');
	var size = photo_getCSSxy(canvas,"background-size");
	var width = canvas.width();
	var height = canvas.height();
	var pos = photo_getCSSxy(canvas,"background-position");
	var x = pos[0];
	var y = pos[1];

	x += Number(difX);
	y += Number(difY);

	//prevent croping past boundry of image
	var buffer = 0;
	if($("#stars").is(':checked')) buffer = 35;
	if(x>buffer) x = buffer; //this is hard coded to the inner dimensions of the transparent area. Set to 0 if no overlay
	if(y>buffer) y = buffer;
	if(x<-(size[0]-width)-buffer) x = -(size[0]-width)-buffer;
	if(y<-(size[1]-height)-buffer) y = -(size[1]-height)-buffer;

	//console.log(x,y,difX,difY,size);
	$('#canvasCrop').css("background-position",parseInt(x)+"px "+parseInt(y)+"px");	
}

//prevents the image from getting smaller than the constraints
function photo_constrainDimensions(constraintW,constraintH,width,height) {
	// console.log("IN",constraintW,constraintH,width,height);
	//first grow width if neccessary to fit entirely in canvas
	if(width<constraintW) {
		ratio = constraintW/width;
		// console.log("x",size,ratio);
		width = constraintW;
		height = Math.floor(height*ratio);
	}
	//second grow height if still necessary to fit entirely in canvas
	if(height<constraintH) {
		ratio = constraintH/height;
		// console.log("y",size,ratio);
		height = constraintH;
		width = Math.floor(width*ratio);
	}

	// console.log("OUT",width,height);

	return [width,height];
}

function photo_magup(event) {
	if(event) event.preventDefault();
	var canvas = $('#canvasCrop');
	var size = photo_getCSSxy(canvas,"background-size");
	size[0] = Math.floor(size[0]*1.2);
	size[1] = Math.floor(size[1]*1.2);
	canvas.css("background-size",size[0]+"px "+size[1]+"px");
	// console.log("zoomin",size);
	finishCrop();
}

function photo_magdown(event) {
	if(event) event.preventDefault();
	var canvas = $('#canvasCrop');
	var size = photo_getCSSxy(canvas,"background-size");
	size[0] = Math.floor(size[0]*.8);
	size[1] = Math.floor(size[1]*.8);

	var newSize = photo_constrainDimensions(canvas.width(), canvas.height(), size[0], size[1]);

	// console.log("ZOOM",uploadedImg.width,uploadedImg.height);

	canvas.css("background-size",newSize[0]+"px "+newSize[1]+"px");
	// console.log("zoomout",newSize);

	//need to fake a drag to prevent bottom right corner from being off
	startX=startY=0;
	photo_MouseMoveImage({offsetX:0,offsetY:0});
	finishCrop();
}

function photo_rotate(event) {
	if(event) event.preventDefault();
	// console.log("rotate");
	var container = $('#canvasContainer');
	var canvas = $('#canvasCrop');

	if(container.hasClass("rotateUp")) {
		container.removeClass("rotateUp").addClass("rotateLeft");
	} else if(container.hasClass("rotateRight")) {
		container.removeClass("rotateRight").addClass("rotateUp");
	} else if(container.hasClass("rotateLeft")) {
		container.removeClass("rotateLeft");
	} else {
		container.addClass("rotateRight");
	}
	finishCrop();
}

function switchStars() {
	if($("#stars").is(':checked')) {
		$("#overlay2").hide();
		$("#overlay1").show();
	} else {
		$("#overlay1").hide();
		$("#overlay2").show();
	}
	finishCrop();
}


//pass in the name of a property with an px value or x/y pixel value 
//example background-size
//returns an array of the number values
function photo_getCSSxy(canvas,prop) {
	var nums = canvas.css(prop).split(" ");
	
	for(var i=0;i<nums.length;i++) {
		nums[i] = Number(nums[i].replace(/[^0-9-]/g, ''));
	}
	return nums;
}


function downloadAvatar() {
	var canvas = $('#canvasOut')[0];
	var url = canvas.toDataURL("image/png");
	$('#js_download')[0].href = url;
	$('#js_download')[0].download = "swgoh_avatar.png";
}

//=============================================
//FROM https://github.com/stomita/ios-imagefile-megapixel/blob/master/src/megapix-image.js
//=============================================

 /**
	* Detect subsampling in loaded image.
	* In iOS, larger images than 2M pixels may be subsampled in rendering.
	*/
  function photo_detectSubsampling(img) {
	 var iw = img.naturalWidth, ih = img.naturalHeight;
	 if (iw * ih > 1024 * 1024) { // subsampling may happen over megapixel image
		var canvas = document.createElement('canvas');
		canvas.width = canvas.height = 1;
		var ctx = canvas.getContext('2d');
		ctx.drawImage(img, -iw + 1, 0);
		// subsampled image becomes half smaller in rendering size.
		// check alpha channel value to confirm image is covering edge pixel or not.
		// if alpha value is 0 image is not covering, hence subsampled.
		return ctx.getImageData(0, 0, 1, 1).data[3] === 0;
	 } else {
		return false;
	 }
  }

  /**
	* Detecting vertical squash in loaded image.
	* Fixes a bug which squash image vertically while drawing into canvas for some images.
	*/
  function photo_detectVerticalSquash(img, iw, ih) {
	if(ih===0) return false;
	 var canvas = document.createElement('canvas');
	 canvas.width = 1;
	 canvas.height = ih;
	 var ctx = canvas.getContext('2d');
	 ctx.drawImage(img, 0, 0);
	 var data = ctx.getImageData(0, 0, 1, ih).data;
	 // search image edge pixel position in case it is squashed vertically.
	 var sy = 0;
	 var ey = ih;
	 var py = ih;
	 while (py > sy) {
		var alpha = data[(py - 1) * 4 + 3];
		if (alpha === 0) {
		  ey = py;
		} else {
		  sy = py;
		}
		py = (ey + sy) >> 1;
	 }
	 var ratio = (py / ih);
	 return (ratio===0)?1:ratio;
  }