<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

// Incluir el archivo de conexión a la base de datos.
require_once __DIR__ . "/../includes/database.php";

// Verificar que la solicitud sea POST.
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
if (!isset($data["name"], $data["last_name"], $data["user"], $data["password"]) ||
    trim($data["name"]) === "" || trim($data["last_name"]) === "" ||
    trim($data["user"]) === "" || $data["password"] === "") {
    http_response_code(400);
    echo json_encode(["error" => "Todos los campos son obligatorios."]);
    exit;
}

// Obtener los datos del JSON.
$name = trim($data["name"]);
$last_name = trim($data["last_name"]);
$user = trim($data["user"]);
$password = $data["password"];

// Verificar si el usuario ya existe en la base de datos.
$sql = "SELECT id FROM users WHERE `user` = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    http_response_code(409);
    echo json_encode(["error" => "El usuario ya existe."]);
    $stmt->close();
    $db->close();
    exit;
}
$stmt->close();

// Hash de la contraseña para no almacenarla en texto plano.
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insertar el nuevo usuario en la base de datos.
$sql = "INSERT INTO users (name, last_name, `user`, password) VALUES (?, ?, ?, ?)";
$stmt = $db->prepare($sql);
$stmt->bind_param("ssss", $name, $last_name, $user, $hashedPassword);

if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode(["success" => "Usuario creado exitosamente."]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error al registrar el usuario."]);
}

$stmt->close();
$db->close();
?>
