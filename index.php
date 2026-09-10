<!DOCTYPE html>
<html lang="es">
<head>
<!-- Configuración básica de la página y adaptación a dispositivos móviles. -->
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Web Services</title>
<!-- Cargar los estilos generales del proyecto. -->
<link rel="stylesheet" href="assets/style.css">
</head><body>
<!-- Presentación principal del servicio web. -->
<h1>Login Web Services</h1><p>API para registro, autenticación y manejo de sesión.</p>

<!-- Lista de las rutas disponibles para realizar las pruebas de la API. -->
<h2>Endpoints</h2><ul>
<li><code>POST /pages/register.php</code> — registrar usuario.</li>
<li><code>POST /pages/login.php</code> — iniciar sesión.</li>
<li><code>GET /pages/home.php</code> — consultar sesión.</li>
<li><code>POST /pages/logout.php</code> — cerrar sesión.</li>
<li><code>GET /pages/test.php</code> — comprobar el servicio.</li>
</ul><p>Las pruebas están preparadas para Postman.</p>

<!-- Cargar el comportamiento JavaScript después del contenido de la página. -->
<script src="assets/script.js"></script>
</body></html>
