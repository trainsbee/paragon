<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Interfaz de Detección de Movimientos</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      background-color: #f0f0f0;
      margin: 0;
      padding: 20px;
    }
    #videoContainer {
      position: relative;
      width: 640px;
      height: 480px;
      margin-bottom: 20px;
    }
    #video {
      width: 100%;
      height: 100%;
      border: 2px solid #333;
      border-radius: 8px;
    }
    #canvas {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
    }
    #status {
      font-size: 24px;
      color: #333;
      margin-top: 10px;
    }
    #posture {
      font-size: 18px;
      color: #555;
      margin-top: 10px;
    }
    button {
      padding: 10px 20px;
      font-size: 16px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 10px;
    }
    button:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <h1>Detección de Movimientos y Postura</h1>
  <div id="videoContainer">
    <video id="video" autoplay></video>
    <canvas id="canvas"></canvas>
  </div>
  <button onclick="startCamera()">Iniciar Cámara</button>
  <div id="status">Estado: Esperando inicio...</div>
  <div id="posture">Postura: ---</div>

  <script src="https://unpkg.com/ml5@0.12.2/dist/ml5.min.js"></script>
  <script>
    let video;
    let poseNet;
    let poses = [];
    let canvas;
    let ctx;
    let sleepTimer = 0;
    const SLEEP_THRESHOLD = 3000; // 3 segundos para considerar "durmiendo"

    function startCamera() {
      video = document.getElementById('video');
      canvas = document.getElementById('canvas');
      ctx = canvas.getContext('2d');

      navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
          video.srcObject = stream;
          video.play();
          setupPoseNet();
        })
        .catch(err => {
          document.getElementById('status').innerText = 'Error al acceder a la cámara: ' + err;
        });
    }

    function setupPoseNet() {
      canvas.width = video.width;
      canvas.height = video.height;
      poseNet = ml5.poseNet(video, modelLoaded);
      poseNet.on('pose', gotPoses);
    }

    function modelLoaded() {
      document.getElementById('status').innerText = 'Modelo cargado, detectando...';
    }

    function gotPoses(results) {
      poses = results;
      drawPoses();
      analyzePose();
    }

    function drawPoses() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      if (poses.length > 0) {
        let pose = poses[0].pose;
        ctx.fillStyle = 'red';
        ctx.strokeStyle = 'red';
        ctx.lineWidth = 2;

        // Dibujar puntos clave
        pose.keypoints.forEach(point => {
          if (point.score > 0.5) {
            ctx.beginPath();
            ctx.arc(point.position.x, point.position.y, 5, 0, 2 * Math.PI);
            ctx.fill();
          }
        });

        // Dibujar esqueleto
        let skeleton = poses[0].skeleton;
        skeleton.forEach(bone => {
          let [point1, point2] = bone;
          if (point1.score > 0.5 && point2.score > 0.5) {
            ctx.beginPath();
            ctx.moveTo(point1.position.x, point1.position.y);
            ctx.lineTo(point2.position.x, point2.position.y);
            ctx.stroke();
          }
        });
      }
    }

    function analyzePose() {
      if (poses.length > 0) {
        let pose = poses[0].pose;
        let nose = pose.nose;
        let leftWrist = pose.leftWrist;
        rightWrist = pose.rightWrist;
        let leftShoulder = pose.leftShoulder;
        let rightShoulder = pose.rightShoulder;

        // Detectar postura
        let posture = 'Desconocida';
        let headLowered = false;
        if (leftShoulder.score > 0.5 && rightShoulder.score > 0.5) {
          let shoulderAngle = Math.abs(leftShoulder.position.y - rightShoulder.position.y);
          if (shoulderAngle < 20) {
            posture = 'Sentado/Erguido';
          } else {
            posture = 'Inclinado';
          }
          // Detectar si la cabeza está agachada (nariz por debajo de los hombros)
          if (nose.score > 0.5) {
            let avgShoulderY = (leftShoulder.position.y + rightShoulder.position.y) / 2;
            if (nose.position.y > avgShoulderY + 50) { // Nariz 50px por debajo de hombros
              headLowered = true;
              posture = 'Cabeza agachada';
            }
          }
        }

        // Detectar manos en la cabeza o cara
        let handsOnHead = false;
        if (nose.score > 0.5 && (leftWrist.score > 0.5 || rightWrist.score > 0.5)) {
          let headArea = 150; // Área alrededor de la cabeza (en píxeles)
          let headTopY = nose.position.y - 100; // Estimar la parte superior de la cabeza
          if ((leftWrist.score > 0.5 &&
               Math.abs(leftWrist.position.x - nose.position.x) < headArea &&
               leftWrist.position.y < nose.position.y &&
               leftWrist.position.y > headTopY) ||
              (rightWrist.score > 0.5 &&
               Math.abs(rightWrist.position.x - nose.position.x) < headArea &&
               rightWrist.position.y < nose.position.y &&
               rightWrist.position.y > headTopY)) {
            handsOnHead = true;
          }
        }

        // Detectar si está "durmiendo"
        let status = '';
        if ((handsOnHead || posture === 'Cabeza agachada') && posture === 'Cabeza agachada') {
          sleepTimer += 1000 / 30; // Aproximar tiempo en ms por frame (30 fps)
          if (sleepTimer > SLEEP_THRESHOLD) {
            status = 'Posiblemente durmiendo';
          } else {
            status = handsOnHead ? 'Manos en la cabeza' : 'Cabeza agachada';
          }
        } else {
          sleepTimer = 0;
          status = 'Activo';
        }

        document.getElementById('status').innerText = `Estado: ${status}`;
        document.getElementById('posture').innerText = `Postura: ${posture}`;
      }
    }
  </script>
</body>
</html>