<?php
// Iniciar la configuración de conexión con MySQL.
$host = "localhost";
$user = "root";
$password = "";
$database = "test";

// Crear la conexión que utilizarán los diferentes endpoints de la API.
$db = new mysqli($host, $user, $password, $database);

// Detener la solicitud si no es posible conectarse a la base de datos.
if ($db->connect_error) {
    http_response_code(500);
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode(["error" => "No se pudo conectar con la base de datos."]);
    exit;
}

// Establecer UTF-8 para manejar correctamente caracteres especiales.
$db->set_charset("utf8mb4");
?>
