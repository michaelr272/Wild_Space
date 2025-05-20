<?php
// Parámetros de conexión (XAMPP por defecto)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wild_data";

// Crear conexión
$conexion = new mysqli($servername, $username, $password, $dbname, 3307);

// Verificar conexión
if ($conexion->connect_error) {
    die("❌ Error de conexión: " . $conexion->connect_error);
}

// Recibir datos del formulario
$nombre = $_POST['nombre'] ?? '';
$email = $_POST['email'] ?? '';
$contraseña = $_POST['contraseña'] ?? '';
$confirmcontraseña = $_POST['confirmcontraseña'] ?? '';

// Validar que las contraseñas coincidan
if ($contraseña !== $confirmcontraseña) {
    die("⚠️ Las contraseñas no coinciden.");
}

// Cifrar la contraseña
$contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);

// Insertar en la base de datos
$sql = "INSERT INTO usuarios (nombre, email, contraseña) VALUES (?, ?, ?)";
$stmt = $conexion->prepare($sql);

$registroExitoso = false;
if ($stmt) {
    $stmt->bind_param("sss", $nombre, $email, $contraseña_hash);
    if ($stmt->execute()) {
        $registroExitoso = true;  echo "✅ Registro exitoso para el usuario: $nombre";
    } else {
        die("❌ Error al registrar: " . $stmt->error);
    }
    $stmt->close();
} else {
    die("❌ Error al preparar la consulta: " . $conexion->error);
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cuenta agregada</title>
  <style>
    :root {
      --bg-color: #1a1a1a;
      --text-color: #ffffff;
    }

    body {
      background: var(--bg-color);
      color: var(--text-color);
      font-family: 'Segoe UI', sans-serif;
      text-align: center;
      padding-top: 60px;
      margin: 0;
    }

    h1 {
      font-size: 30px;
      margin-bottom: 60px;
    }

    .scene {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 300px;
      margin-bottom: 40px;
    }

    .smiley {
      width: 160px;
      height: 160px;
      animation: popIn 1.5s ease-out;
    }

    @keyframes popIn {
      0% {
        transform: scale(0);
        opacity: 0;
      }
      60% {
        transform: scale(1.2);
        opacity: 1;
      }
      100% {
        transform: scale(1);
      }
    }

    .countdown-message {
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

  <?php if ($registroExitoso): ?>
    <h1>✅ Cuenta creada exitosamente ✅</h1>
    <div class="scene">
      <img src="https://images.emojiterra.com/google/noto-emoji/unicode-15/animated/1f60a.gif" alt="Carita feliz" class="smiley">
    </div>
    <div class="countdown-message">
      Serás redirigido al inicio de sesión en <span id="countdown">5</span> segundos...
    </div>
  <?php endif; ?>

</body>
</html>
