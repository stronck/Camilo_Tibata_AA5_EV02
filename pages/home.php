<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

// Verificar si existe una sesión autenticada.
if (isset($_SESSION["user"])) {
    echo json_encode([
        "success" => "Sesión activa",
        "user" => $_SESSION["user"]
    ]);
    exit;
}

http_response_code(401);
echo json_encode(["error" => "No hay una sesión activa"]);
?>
