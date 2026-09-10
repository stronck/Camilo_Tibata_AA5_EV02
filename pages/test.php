<?php
// Indicar que la respuesta de prueba se devolverá como JSON.
header("Content-Type: application/json; charset=UTF-8");

// Endpoint sencillo para comprobar que el servicio está disponible.
echo json_encode(["success" => "API funcionando correctamente"]);
?>
