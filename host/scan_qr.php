<!DOCTYPE html>
<html lang="en">
<head>
    <title>Scan QR Code</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsqr/1.0.0/jsQR.min.js"></script>
</head>
<body>
    <h2>Scan QR Code</h2>
    <button id="startScan">Start Scanning</button>
    <video id="camera" width="300" height="200" autoplay playsinline style="display: none;"></video>
    <p id="result"></p>

    <script>
    document.getElementById('startScan').addEventListener('click', async function() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
            const video = document.getElementById('camera');
            video.srcObject = stream;
            video.style.display = 'block';

            video.onloadedmetadata = () => {
                video.play();
                scanQRCode(video);
            };
        } catch (error) {
            document.getElementById('result').innerText = "Camera access denied!";
            console.error("Error accessing camera: ", error);
        }
    });

    function scanQRCode(video) {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        function scan() {
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, canvas.width, canvas.height);

                if (code) {
                    sendVerificationCode(code.data);
                    stopCamera(video);
                } else {
                    requestAnimationFrame(scan);
                }
            } else {
                requestAnimationFrame(scan);
            }
        }
        scan();
    }

    function stopCamera(video) {
        video.srcObject.getTracks().forEach(track => track.stop());
        video.style.display = 'none';
    }

    function sendVerificationCode(code) {
        fetch('verify_qr.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'verification_code=' + encodeURIComponent(code)
        })
        .then(response => response.text())
        .then(data => document.body.innerHTML = data)
        .catch(error => console.error('Error:', error));
    }
    </script>
</body>
</html>
