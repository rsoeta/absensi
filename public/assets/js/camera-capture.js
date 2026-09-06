let camera_button = document.querySelector("#start-camera");
let video = document.querySelector("#video_capture");
let click_button = document.querySelector("#click-photo");
let canvas_camera = document.querySelector("#canvas_camera");
let note_capture = document.querySelector(".note-capture-photo");


function stopCamera() {
	let stream = video.srcObject;
	let tracks = stream.getTracks();

	for (let i = 0; i < tracks.length; i++) {
		let track = tracks[i];
		track.stop();
	}

	video.srcObject = null;
}

function changeButtonAbsenState(status) {
	var buttonnya = $('.btn-absen')

	if(status == 'disable'){
		buttonnya.attr('disabled', 'disabled');
		buttonnya.addClass('disabled');
	}

	if(status == 'enable'){
		buttonnya.removeAttr('disabled');
		buttonnya.removeClass('disabled');
	}
}
function startCamera() {
	navigator.mediaDevices.getUserMedia({ video: true, audio: false })
		.then(function(stream) {
			video.srcObject = stream;
		})
		.catch(function(err) {
			console.log("An error occurred: " + err);
		});
}

function getTime() {
	let date = new Date();
	let hours = date.getHours();
	let minutes = date.getMinutes();
	let seconds = date.getSeconds();
	// 24 hour format
	hours = hours % 12;
	hours = hours ? hours : 12; // the hour '0' should be '12'
	minutes = minutes < 10 ? '0'+minutes : minutes;
	seconds = seconds < 10 ? '0'+seconds : seconds;
	let strTime = hours + ':' + minutes + ':' + seconds;
	return strTime;

}

function insertTimestampToCanvas(status) {
	let ctx = canvas_camera.getContext("2d");
	let radiusnya = canvas_camera.height / 2;
	radiusnya = radiusnya * 0.90
	ctx.font = radiusnya*0.15 + "px arial";
	ctx.fillStyle = 'red';
	// put text to top right corner
	ctx.textAlign = "end";
	// put text to right bottom corner

	var x = canvas_camera.width
	var y = canvas_camera.height
	ctx.fillText(status + ' - ' + getTime(), x - 10, y - 10);
	
}

camera_button.addEventListener('click', async function() {
	canvas_camera.style.display = "none";
	video.style.display = "block";
	note_capture.style.display = "none";
	camera_button.style.display = "none";
	click_button.style.display = "block";
	$('.success-indicator').hide();
	changeButtonAbsenState('disable')

	await startCamera();
});

click_button.addEventListener('click', function(e) {
		video.style.display = "none";
		canvas_camera.style.display = "block";
		camera_button.style.display = "block";
		camera_button.innerHTML = "Ulang";
		click_button.style.display = "none";	

   	canvas_camera.getContext('2d').drawImage(video, 0, 0, canvas_camera.width, canvas_camera.height);
		 $('.success-indicator').show();
   	// data url of the image

		var statusAbsen = $('#btn-act-absen').text()

		insertTimestampToCanvas(statusAbsen)
		changeButtonAbsenState('enable')
		stopCamera();
   	let image_data_url = canvas_camera.toDataURL('image/png');
   	console.log(image_data_url);
		$('#photo').val(image_data_url)
});
