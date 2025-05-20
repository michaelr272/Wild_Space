<?php
$error = "";

$nombre = "";
$email = "";
$contrasena = "";
$confirmar_contrasena = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $host = "localhost:3307"; // Cambia el puerto si es necesario
    $dbname = "wild_data";
    $username = "root";
    $password = "";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Usar ?? para evitar warning si no existe el índice
        $nombre = trim($_POST["nombre"] ?? '');
        $email = trim($_POST["email"] ?? '');
        $contrasena = $_POST["contrasena"] ?? '';
        $confirmar_contrasena = $_POST["confirmar_contrasena"] ?? '';

        if ($contrasena !== $confirmar_contrasena) {
            $error = "⚠️ Las contraseñas no coinciden. ⚠️";
        } else {
            // Aquí uso alias contrasena para evitar ñ en el array
            $stmt = $pdo->prepare("SELECT nombre, email, contraseña AS contrasena FROM usuarios WHERE nombre = :nombre AND email = :email");
            $stmt->execute(['nombre' => $nombre, 'email' => $email]);
            $usuario_db = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario_db) {
                if (password_verify($contrasena, $usuario_db['contrasena'])) {
                    session_start();
                    $_SESSION['usuario'] = $usuario_db['nombre'];
                    $_SESSION['correo'] = $usuario_db['email'];
                    header("Location: pagina_principal.html");
                    exit();
                } else {
                    $error = "❌ Contraseña incorrecta. ❌";
                }
            } else {
                $error = "❌ No existe una cuenta con esos datos. ❌";
            }
        }

    } catch (PDOException $e) {
        $error = "❗ Error de conexión a la base de datos.";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Iniciar Sesión - Wild Space</title>
<link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet" />
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'Press Start 2P', monospace;
        background: url('https://i.redd.it/bf6a66k6j6r31.gif') no-repeat center center fixed;
        background-size: cover;
        color: #fff;
        display: flex;
        justify-content: flex-end;
        height: 100vh;
        overflow: hidden;
    }
    .container {
        background: rgba(45, 42, 74, 0.85);
        width: 50vw;
        height: 100vh;
        padding: 50px 40px 40px 40px;
        display: flex;
        flex-direction: column;
        align-items: center;
        box-shadow: -5px 0 15px rgba(0,0,0,0.6);
        position: relative;
    }
    .settings-wheel {
        position: absolute;
        top: 40px;
        left: 20px;
        width: 60px;
        height: 60px;
        cursor: pointer;
        fill: #ffd700;
        transition: fill 0.3s;
        user-select: none;
    }
    .settings-wheel:hover { fill: #fff; }
    .settings-panel {
        position: absolute;
        top: 110px;
        left: 20px;
        display: flex;
        flex-direction: column;
        gap: 15px;
        opacity: 0;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease, opacity 0.4s ease;
        z-index: 10;
    }
    .settings-panel.active {
        opacity: 1;
        max-height: 500px;
    }
    .settings-box {
        width: 60px;
        height: 60px;
        background: rgba(68, 63, 114, 0.85);
        border-radius: 12px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        user-select: none;
    }
    .settings-box img {
        max-width: 36px;
        max-height: 36px;
        filter: brightness(0) invert(0);
        transition: filter 0.3s;
        cursor: pointer;
    }
    .settings-box img:hover {
        filter: brightness(0) invert(1);
    }
    h1 {
        font-size: 32px;
        margin-bottom: 40px;
        color: #ffd700;
        text-align: center;
    }
    h2 {
        font-size: 22px;
        margin-top: 10px;
        margin-bottom: 50px;
        text-align: center;
    }
    form {
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 400px;
    }
    input {
        padding: 22px;
        margin-bottom: 30px;
        border: none;
        border-radius: 10px;
        font-size: 18px;
        font-family: 'Press Start 2P', monospace;
    }
    button.submit-btn {
        background: #5c59a0;
        border: none;
        padding: 22px;
        border-radius: 10px;
        font-size: 18px;
        color: #fff;
        cursor: pointer;
        font-family: 'Press Start 2P', monospace;
        transition: background 0.3s;
        margin-left: 0px;
    }
    button.submit-btn:hover {
        background: #716bc4;
        color: #ffd700;
    }
    .footer {
        margin-top: auto;
        font-size: 12px;
        text-align: center;
        opacity: 0.6;
    }
    .error-message {
        color: #ff8080;
        font-size: 14px;
        margin-top: 20px;
        text-align: center;
        animation: shake 0.4s ease;
    }
    @keyframes shake {
        0% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        50% { transform: translateX(5px); }
        75% { transform: translateX(-5px); }
        100% { transform: translateX(0); }
    }
    @media screen and (max-width: 768px) {
        body {
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }
        .container {
            width: 100vw;
            height: auto;
            border-left: none;
            border-top: 4px solid rgba(255, 255, 255, 0.1);
            padding-top: 30px;
        }
    }
</style>
</head>
<body>
    <div class="container" role="main">
        <!-- Icono rueda de ajustes -->
        <svg class="settings-wheel" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-label="Ajustes"
            role="button" tabindex="0" id="settingsWheel">
            <path
                d="M19.14 12.94c.04-.31.07-.63.07-.94s-.03-.63-.07-.94l2.03-1.58a.5.5 0 00.11-.63l-1.92-3.32a.5.5 0 00-.6-.22l-2.39.96a7.028 7.028 0 00-1.62-.94l-.36-2.54a.5.5 0 00-.5-.42h-3.84a.5.5 0 00-.5.42l-.36 2.54c-.58.22-1.12.53-1.62.94l-2.39-.96a.5.5 0 00-.6.22l-1.92 3.32a.5.5 0 00.11.63l2.03 1.58c-.04.31-.07.63-.07.94s.03.63.07.94l-2.03 1.58a.5.5 0 00-.11.63l1.92 3.32c.14.24.44.34.7.22l2.39-.96c.5.41 1.04.72 1.62.94l.36 2.54c.04.28.27.48.5.48h3.84c.23 0 .46-.2.5-.48l.36-2.54c.58-.22 1.12-.53 1.62-.94l2.39.96c.26.11.56.02.7-.22l1.92-3.32a.5.5 0 00-.11-.63l-2.03-1.58zM12 15.5A3.5 3.5 0 1112 8.5a3.5 3.5 0 010 7z" />
        </svg>

        <!-- Panel de ajustes -->
        <div class="settings-panel" id="settingsPanel" aria-label="Opciones de Ajustes" role="region" aria-hidden="true">
            <div class="settings-box" title="Hoja y lápiz">
                <a href="ooo.html">
                    <img src="https://cdn-icons-png.flaticon.com/512/1828/1828911.png" alt="Hoja y lápiz" />
                </a>
            </div>
            <div class="settings-box" title="Herramientas">
                <a href="ooo.html">
                    <img src="https://cdn-icons-png.flaticon.com/512/28/28511.png" alt="Herramientas" />
                </a>
            </div>
            <div class="settings-box" title="Papelera">
                <a href="ooo.html">
                    <img src="https://cdn-icons-png.flaticon.com/512/1214/1214428.png" alt="Papelera" />
                </a>
            </div>
        </div>

        <h1>🚀 WILD SPACE 🚀</h1>
        <h2>INICIAR SESIÓN</h2>

        <form method="POST" action="" novalidate>
            <input type="text" placeholder="USUARIO" name="nombre" required value="<?= htmlspecialchars($nombre) ?>" />
            <input type="email" placeholder="CORREO" name="email" required value="<?= htmlspecialchars($email) ?>" />
            <input type="password" placeholder="CONTRASEÑA" name="contrasena" required />
            <input type="password" placeholder="CONFIRMAR CONTRASEÑA" name="confirmar_contrasena" required />
            <button class="submit-btn" type="submit">ENTRAR</button>
        </form>

        <?php if (!empty($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="footer">
            &copy; 2025 Wild Space. Todos los derechos reservados.
        </div>
    </div>

    <script>
        const wheel = document.getElementById('settingsWheel');
        const panel = document.getElementById('settingsPanel');

        wheel.addEventListener('click', () => {
            const active = panel.classList.toggle('active');
            panel.setAttribute('aria-hidden', !active);
        });

        // Permitir abrir con tecla Enter o Space para accesibilidad
        wheel.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                wheel.click();
            }
        });
    </script>
</body>
</html>
