<?php
// Iniciar la sesión para consultar si existe un usuario autenticado.
session_start();
// Mantener el formato JSON para que el endpoint pueda ser consumido como API.
header("Content-Type: application/json; charset=UTF-8");

// Comprobar si la sesión contiene el usuario autenticado.
if (isset($_SESSION["user"])) {
    // Devolver la información básica de la sesión activa.
    echo json_encode([
        "success" => "Sesión activa",
        "user" => $_SESSION["user"]
    ]);
    exit;
}

// Si no existe sesión, indicar que el acceso requiere autenticación.
http_response_code(401);
echo json_encode(["error" => "No hay una sesión activa"]);
?>
