<?php
$conexion = mysqli_connect("localhost", "root", "", "wild_data", 3307);

if (!$conexion) {
    die("❌ Error: " . mysqli_connect_error());
}

echo "✅ ¡Conexión exitosa!";
?>
