<?php
$conexion = new mysqli("localhost", "root", "", "wild_data", 3307);
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
$conexion->set_charset("utf8mb4");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevo_nombre = $_POST["nuevo_nombre"];
    $nuevo_email = $_POST["nuevo_email"];
    $nueva_contraseña = $_POST["nueva_contraseña"];
    $confirmar_nueva_contraseña = $_POST["confirmar_nueva_contraseña"];

    if ($nueva_contraseña !== $confirmar_nueva_contraseña) {
        echo "Las contraseñas no coinciden.";
    } else {
        $contraseña_hashed = password_hash($nueva_contraseña, PASSWORD_DEFAULT);

        $query = "UPDATE usuarios SET nombre=?, contraseña=? WHERE email=?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("sss", $nuevo_nombre, $contraseña_hashed, $nuevo_email);

        if ($stmt->execute()) {
            echo "¡Cuenta modificada exitosamente!";
        } else {
            echo "Error al modificar la cuenta: " . $stmt->error;
        }
        $stmt->close();
    }
}
$conexion->close();
?>
<?php
$usuario_modificado = true;
$cuenta_regresiva = 5;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Cuenta modificada</title>
<style>
  :root {
    --bg-color: #1a1a1a;
    --text-color: #fff;
  }

  body {
    background: var(--bg-color);
    color: var(--text-color);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    text-align: center;
    padding: 60px 20px 20px 20px;
  }

  h1 {
    font-size: 36px;
    margin-bottom: 20px;
    user-select: none;
  }

  .scene {
    position: relative;
    width: 320px;
    height: 160px;
    margin-bottom: 20px;
  }

  .paper {
    position: absolute;
    width: 100%;
    height: 100%;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    overflow: visible;
  }

  .pencil {
    position: absolute;
    bottom: 50px;
    left: -60px;
    width: 80px;
    height: 80px;
    animation: movePencil 1.8s forwards;
    z-index: 10;
    transform-origin: center;
  }

  @keyframes movePencil {
    0% {
      left: -60px;
      transform: rotate(-15deg);
    }
    80% {
      left: 240px;
      transform: rotate(-15deg);
    }
    100% {
      left: 240px;
      transform: rotate(-15deg);
    }
  }

  .checkmark-gif {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 120px;
    height: 120px;
    opacity: 0;
    transform: translate(-50%, -50%);
    animation: fadeIn 0.5s 2s forwards;
    z-index: 20;
  }

  @keyframes fadeIn {
    to {
      opacity: 1;
    }
  }

  .countdown-message {
    font-size: 22px;
    color: var(--text-color);
    opacity: 0.85;
    user-select: none;
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

<h1>✅ Cuenta modificada exitosamente ✅</h1>

<div class="scene">
  <div class="paper"></div>

  <div class="pencil" aria-label="Lápiz">
    <!-- SVG de lápiz típico -->
    <svg viewBox="0 0 64 64" width="80" height="80" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#f4a261" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" >
      <path d="M2 54L44 12l8 8-42 42H2v-8z" fill="#f4a261" stroke="#b26900"/>
      <path d="M44 12l8 8-6 6-8-8 6-6z" fill="#d97f00" stroke="#b26900"/>
      <path d="M2 54h10v8H2v-8z" fill="#a36800" stroke="#6a4b00"/>
    </svg>
  </div>

  <img src="https://media1.tenor.com/m/BSY1qTH8g-oAAAAd/check.gif" alt="Check animado" class="checkmark-gif" />

</div>

<div class="countdown-message">
  Serás redirigido al inicio de sesión en <span id="countdown">5</span> segundos...
</div>

</body>
</html>


