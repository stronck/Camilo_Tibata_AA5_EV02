<?php
header("Content-Type: application/json; charset=UTF-8");
// Endpoint para comprobar que el servicio está funcionando.
echo json_encode(["success" => "API funcionando correctamente"]);
?>
