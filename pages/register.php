<?php
// Iniciar sesión porque el servicio forma parte del flujo de autenticación.
session_start();
// Todas las respuestas de este endpoint se entregan como JSON.
header("Content-Type: application/json; charset=UTF-8");

// Cargar la conexión compartida con la base de datos.
require_once __DIR__ . "/../includes/database.php";

// Este endpoint solamente acepta solicitudes POST.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido. Use POST."]);
    exit;
}

// Leer el cuerpo de la solicitud y convertir el JSON recibido en un arreglo.
$json = file_get_contents("php://input");
$data = json_decode($json, true);

// Rechazar solicitudes cuyo cuerpo no contenga un JSON válido.
if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(["error" => "El cuerpo debe contener un JSON válido."]);
    exit;
}

// Comprobar que todos los datos necesarios para registrar el usuario estén presentes.
if (!isset($data["name"], $data["last_name"], $data["user"], $data["password"]) ||
    trim($data["name"]) === "" || trim($data["last_name"]) === "" ||
    trim($data["user"]) === "" || $data["password"] === "") {
    http_response_code(400);
    echo json_encode(["error" => "Todos los campos son obligatorios."]);
    exit;
}

// Extraer y limpiar los valores recibidos para trabajar con ellos.
$name = trim($data["name"]);
$last_name = trim($data["last_name"]);
$user = trim($data["user"]);
$password = $data["password"];

// Consultar si el nombre de usuario ya está registrado.
$sql = "SELECT id FROM users WHERE `user` = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$resultado = $stmt->get_result();

// Evitar registros duplicados y liberar los recursos antes de salir.
if ($resultado->num_rows > 0) {
    http_response_code(409);
    echo json_encode(["error" => "El usuario ya existe."]);
    $stmt->close();
    $db->close();
    exit;
}
$stmt->close();

// Proteger la contraseña mediante un hash antes de almacenarla.
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insertar el nuevo usuario utilizando parámetros para evitar inyección SQL.
$sql = "INSERT INTO users (name, last_name, `user`, password) VALUES (?, ?, ?, ?)";
$stmt = $db->prepare($sql);
$stmt->bind_param("ssss", $name, $last_name, $user, $hashedPassword);

// Informar si el registro se realizó correctamente o si ocurrió un error.
if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode(["success" => "Usuario creado exitosamente."]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error al registrar el usuario."]);
}

// Cerrar la consulta y la conexión para liberar recursos.
$stmt->close();
$db->close();
?>
