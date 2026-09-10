<?php
// Archivo: database.php
// Crear conexión con la base de datos MySQL.
$host = "localhost";
$user = "root";
$password = "";
$database = "test";

$db = new mysqli($host, $user, $password, $database);

if ($db->connect_error) {
    http_response_code(500);
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode(["error" => "No se pudo conectar con la base de datos."]);
    exit;
}
$db->set_charset("utf8mb4");
?>
