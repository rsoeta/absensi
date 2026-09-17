let camera_button = document.querySelector("#start-camera");
let video = document.querySelector("#video_capture");
let click_button = document.querySelector("#click-photo");
let canvas_camera = document.querySelector("#canvas_camera");
let note_capture = document.querySelector(".note-capture-photo");

function stopCamera() {
    if (!video || !video.srcObject) return; // Pengaman tambahan
    let stream = video.srcObject;
    let tracks = stream.getTracks();

    for (let i = 0; i < tracks.length; i++) {
        let track = tracks[i];
        track.stop();
    }
    video.srcObject = null;
}

function changeButtonAbsenState(status) {
    var buttonnya = $('.btn-absen');

    if (status == 'disable') {
        buttonnya.attr('disabled', 'disabled');
        buttonnya.addClass('disabled');
    }

    if (status == 'enable') {
        buttonnya.removeAttr('disabled');
        buttonnya.removeClass('disabled');
    }
}

function startCamera() {
    if (!video) return; // Pengaman tambahan
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
    minutes = minutes < 10 ? '0' + minutes : minutes;
    seconds = seconds < 10 ? '0' + seconds : seconds;
    let strTime = hours + ':' + minutes + ':' + seconds;
    return strTime;
}

function insertTimestampToCanvas(status) {
    if (!canvas_camera) return; // Pengaman tambahan
    let ctx = canvas_camera.getContext("2d");
    let radiusnya = canvas_camera.height / 2;
    radiusnya = radiusnya * 0.90;
    ctx.font = radiusnya * 0.15 + "px arial";
    ctx.fillStyle = 'red';
    // put text to top right corner
    ctx.textAlign = "end";
    // put text to right bottom corner

    var x = canvas_camera.width;
    var y = canvas_camera.height;
    ctx.fillText(status + ' - ' + getTime(), x - 10, y - 10);
}

// ====================================================================
// PERBAIKAN: Bungkus dengan "if" agar tidak error jika elemen tidak ada
// ====================================================================

if (camera_button) {
    camera_button.addEventListener('click', async function() {
        if (canvas_camera) canvas_camera.style.display = "none";
        if (video) video.style.display = "block";
        if (note_capture) note_capture.style.display = "none";
        camera_button.style.display = "none";
        if (click_button) click_button.style.display = "block";
        
        $('.success-indicator').hide();
        changeButtonAbsenState('disable');

        await startCamera();
    });
}

if (click_button) {
    click_button.addEventListener('click', function(e) {
        if (video) video.style.display = "none";
        if (canvas_camera) canvas_camera.style.display = "block";
        if (camera_button) {
            camera_button.style.display = "block";
            camera_button.innerHTML = "Ulang";
        }
        click_button.style.display = "none";

        if (canvas_camera && video) {
            canvas_camera.getContext('2d').drawImage(video, 0, 0, canvas_camera.width, canvas_camera.height);
            $('.success-indicator').show();

            var statusAbsen = $('#btn-act-absen').text();
            insertTimestampToCanvas(statusAbsen);
            changeButtonAbsenState('enable');
            stopCamera();
            
            let image_data_url = canvas_camera.toDataURL('image/png');
            $('#photo').val(image_data_url);
        }
    });
}