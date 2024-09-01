<?php
header('Content-Type: application/json'); 

$servidor = "https://demo.phpmyadmin.net/master-config/public/index.php?route=/sql&db=prueba&table=tareas&pos=0"; 
$usuario = "usuario";   
$contraseña = "contraseña";
$nombre_bd = "prueba";   

$conexion = new mysqli($servidor, $usuario, $contraseña, $nombre_bd);

if ($conexion->connect_error) {
    echo json_encode(["error" => "Conexión fallida: " . $conexion->connect_error]);
    exit;
}

$sql = "SELECT * FROM tareas"; 
$resultado = $conexion->query($sql);

$tareas = [];
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $tareas[] = $fila;
    }
}

echo json_encode($tareas);

$conexion->close();
?>
