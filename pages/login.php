<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

// Incluir el archivo de conexión a la base de datos.
require_once __DIR__ . "/../includes/database.php";

// Verificar si se ha enviado una solicitud POST para iniciar sesión.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido. Use POST."]);
    exit;
}

// Recuperar los datos enviados en formato JSON.
$json = file_get_contents("php://input");
$data = json_decode($json, true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(["error" => "El cuerpo debe contener un JSON válido."]);
    exit;
}

// Verificar si los datos están completos.
if (!isset($data["user"], $data["password"]) ||
    trim($data["user"]) === "" || $data["password"] === "") {
    http_response_code(400);
    echo json_encode(["error" => "El usuario y la contraseña son obligatorios."]);
    exit;
}

$user = trim($data["user"]);
$password = $data["password"];

// Consultar la base de datos para verificar las credenciales.
$sql = "SELECT * FROM users WHERE `user` = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$resultado = $stmt->get_result();

// Verificar si se encontró un usuario con el nombre proporcionado.
if ($resultado->num_rows === 1) {
    // Obtener el hash de la contraseña almacenado en la base de datos.
    $fila = $resultado->fetch_assoc();
    $hashAlmacenado = $fila["password"];

    // Verificar si la contraseña ingresada coincide con el hash almacenado.
    if (password_verify($password, $hashAlmacenado)) {
        // Autenticación exitosa: establecer la sesión del usuario.
        session_regenerate_id(true);
        $_SESSION["user"] = $user;

        // Responder con un mensaje de éxito en formato JSON.
        echo json_encode(["success" => "Inicio de sesión exitoso"]);
        $stmt->close();
        $db->close();
        exit;
    }
}

// Credenciales incorrectas: responder con un mensaje de error en formato JSON.
http_response_code(401);
echo json_encode(["error" => "Credenciales incorrectas"]);

$stmt->close();
$db->close();
exit;
?>
