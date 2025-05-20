<?php

include 'conexion_db.php';

$correo = $_POST['correo'];
$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];

$query = "INSERT INTO usuarios(correo,usuarios,contrasena) 
VALUES('$correo','$usuario','$contrasena')";

$ejecutar = mysqli_query($conexion, $query);
if($ejecutar){
    echo'
    <script>
    alert("Usuario almacenado exitosamente");
    window.location = "../inicio_sesion.php";
    </script>
    ';
}else{
    echo'Intentalo de nuevo, usuario no almacenado'
}
?>