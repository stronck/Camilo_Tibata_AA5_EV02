<?php
// Recuperar la sesión actual para poder eliminar sus datos.
session_start();
// Entregar la confirmación del cierre en formato JSON.
header("Content-Type: application/json; charset=UTF-8");

// Vaciar las variables almacenadas en la sesión.
$_SESSION = [];

// Si la sesión utiliza cookies, eliminar también la cookie de sesión del navegador.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), "", time() - 42000, $params["path"],
        $params["domain"], $params["secure"], $params["httponly"]);
}

// Destruir la sesión en el servidor y confirmar la operación.
session_destroy();
echo json_encode(["success" => "Sesión cerrada exitosamente"]);
?>
