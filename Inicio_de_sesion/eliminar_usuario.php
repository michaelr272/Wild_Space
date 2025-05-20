<?php
$resultado = "";
$eliminado = false;

// Conexión
$conexion = new mysqli("localhost", "root", "", "wild_data", 3307);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$email = $_POST['email'] ?? '';
$password = $_POST['contraseña'] ?? '';

// Validación
$sql = "SELECT contraseña FROM usuarios WHERE email = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($hashGuardado);
    $stmt->fetch();

    if (password_verify($password, $hashGuardado)) {
        $sqlDelete = "DELETE FROM usuarios WHERE email = ?";
        $stmtDelete = $conexion->prepare($sqlDelete);
        $stmtDelete->bind_param("s", $email);
        if ($stmtDelete->execute()) {
            $resultado = "✅ Cuenta eliminada exitosamente.";
            $eliminado = true;
        } else {
            $resultado = "❌ Error al eliminar la cuenta.";
        }
        $stmtDelete->close();
    } else {
        $resultado = "❌ Contraseña incorrecta.";
    }
} else {
    $resultado = "❌ Usuario no encontrado.";
}

$stmt->close();
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cuenta eliminada</title>
  <style>
    :root {
      --bg-color: #1a1a1a;
      --text-color: #ffffff;
      --shredder-body: #555;
      --shredder-slot: #000;
      --paper-color: #ffffff;
      --shred-color: #bbbbbb;
    }

    @media (prefers-color-scheme: light) {
      :root {
        --bg-color: #2c2c2c;
        --text-color: #fff;
        --shredder-body: #666;
        --shredder-slot: #111;
        --paper-color: #fff;
        --shred-color: #ccc;
      }
    }

    body {
      background: var(--bg-color);
      color: var(--text-color);
      font-family: 'Segoe UI', sans-serif;
      text-align: center;
      padding-top: 60px;
    }

    h1 {
      font-size: 30px;
      margin-bottom: 100px;
    }

    .scene {
      position: relative;
      width: 400px;
      height: 500px;
      margin: 0 auto;
    }

    .paper {
      position: absolute;
      top: -150px;
      left: 50%;
      width: 110px;
      height: 160px;
      background: var(--paper-color);
      border-radius: 6px;
      transform: translateX(-50%);
      z-index: 2;
      animation: dropAndShred 2.5s ease-in-out forwards;
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }

    .shredder {
      position: absolute;
      top: 180px;
      left: 50%;
      transform: translateX(-50%);
      width: 240px;
      height: 100px;
      background: var(--shredder-body);
      border-radius: 14px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.4);
      z-index: 1;
    }

    .slot {
      position: absolute;
      top: 30px;
      left: 20px;
      width: 200px;
      height: 14px;
      background: var(--shredder-slot);
      border-radius: 3px;
    }

    .shreds {
      position: absolute;
      top: 280px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 8px;
      z-index: 0;
    }

    .shred {
      width: 10px;
      height: 0;
      background: var(--shred-color);
      opacity: 0;
      animation: shredFall 1.2s ease-out forwards;
    }

    .shred:nth-child(1) { animation-delay: 1.7s; }
    .shred:nth-child(2) { animation-delay: 1.8s; }
    .shred:nth-child(3) { animation-delay: 1.9s; }
    .shred:nth-child(4) { animation-delay: 2.0s; }
    .shred:nth-child(5) { animation-delay: 2.1s; }
    .shred:nth-child(6) { animation-delay: 2.2s; }
    .shred:nth-child(7) { animation-delay: 2.3s; }
    .shred:nth-child(8) { animation-delay: 2.4s; }

    @keyframes dropAndShred {
      0%   { top: -150px; opacity: 0; }
      40%  { top: 170px; opacity: 1; }
      60%  { top: 175px; }
      100% { top: 180px; height: 0px; opacity: 0; }
    }

    @keyframes shredFall {
      0% { height: 0; opacity: 0; transform: translateY(0); }
      30% { opacity: 1; }
      100% { height: 60px; opacity: 1; transform: translateY(30px); }
    }

    .countdown-message {
      margin-top: 40px;
      font-size: 20px;
      color: var(--text-color);
      opacity: 0.8;
    }
  </style>

  <script>
    let seconds = 5;
    const redirectURL = "login.php";

    window.onload = () => {
      const countdown = document.getElementById("countdown");
      const interval = setInterval(() => {
        seconds--;
        if (seconds <= 0) {
          clearInterval(interval);
        }
        countdown.textContent = seconds;
      }, 1000);

      setTimeout(() => {
        window.location.href = redirectURL;
      }, 5000);
    };
  </script>
</head>
<body>

  <h1>✅ Cuenta eliminada exitosamente ✅</h1>

  <div class="scene">
    <div class="paper"></div>

    <div class="shredder">
      <div class="slot"></div>
    </div>

    <div class="shreds">
      <div class="shred"></div>
      <div class="shred"></div>
      <div class="shred"></div>
      <div class="shred"></div>
      <div class="shred"></div>
      <div class="shred"></div>
      <div class="shred"></div>
      <div class="shred"></div>
    </div>
  </div>

  <div class="countdown-message">
    Serás redirigido al inicio de sesión en <span id="countdown">5</span> segundos...
  </div>

</body>
</html>
