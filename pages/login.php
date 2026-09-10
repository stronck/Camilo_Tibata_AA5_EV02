<?php
// Iniciar la sesión para guardar el usuario autenticado.
session_start();
// Devolver siempre las respuestas del servicio en formato JSON.
header("Content-Type: application/json; charset=UTF-8");

// Reutilizar la conexión centralizada con la base de datos.
require_once __DIR__ . "/../includes/database.php";

// El inicio de sesión solamente se permite mediante POST.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido. Use POST."]);
    exit;
}

// Leer y convertir los datos JSON enviados por el cliente.
$json = file_get_contents("php://input");
$data = json_decode($json, true);

// Verificar que el cuerpo recibido tenga una estructura JSON válida.
if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(["error" => "El cuerpo debe contener un JSON válido."]);
    exit;
}

// Validar que se hayan enviado usuario y contraseña.
if (!isset($data["user"], $data["password"]) ||
    trim($data["user"]) === "" || $data["password"] === "") {
    http_response_code(400);
    echo json_encode(["error" => "El usuario y la contraseña son obligatorios."]);
    exit;
}

// Obtener los valores necesarios para comprobar las credenciales.
$user = trim($data["user"]);
$password = $data["password"];

// Buscar el usuario mediante una consulta preparada.
$sql = "SELECT * FROM users WHERE `user` = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$resultado = $stmt->get_result();

// Continuar solamente cuando existe un único usuario con ese nombre.
if ($resultado->num_rows === 1) {
    // Recuperar el registro y el hash de contraseña almacenado.
    $fila = $resultado->fetch_assoc();
    $hashAlmacenado = $fila["password"];

    // Comparar la contraseña recibida con el hash almacenado.
    if (password_verify($password, $hashAlmacenado)) {
        // Regenerar el identificador de sesión después de autenticar al usuario.
        session_regenerate_id(true);
        $_SESSION["user"] = $user;

        // Informar al cliente que la autenticación fue correcta.
        echo json_encode(["success" => "Inicio de sesión exitoso"]);
        $stmt->close();
        $db->close();
        exit;
    }
}

// Si las credenciales no coinciden, devolver un error de autenticación.
http_response_code(401);
echo json_encode(["error" => "Credenciales incorrectas"]);

// Liberar los recursos utilizados por la consulta y la conexión.
$stmt->close();
$db->close();
exit;
?>
