# Login Web Services — GA7-220501096-AA5-EV02

## Actividad

Construcción y prueba de una API en PHP para registro e inicio de sesión. El servicio recibe datos en JSON, valida las credenciales, almacena contraseñas usando hash y responde en formato JSON. El testing está preparado para realizarse con Postman.

## Despliegue

1. Instalar XAMPP (Apache + MySQL) y Postman.
2. Copiar la carpeta `Camilo_Tibata_AA5_EV02` dentro de `C:\xampp\htdocs\`.
3. Iniciar **Apache** y **MySQL** desde XAMPP.
4. Abrir phpMyAdmin y ejecutar `database.sql`.
5. Verificar en `includes/database.php` los datos de conexión de MySQL.
6. Abrir `http://localhost/Camilo_Tibata_AA5_EV02/`.
7. Importar en Postman `postman/GA7-220501096-AA5-EV02.postman_collection.json`.
8. Ejecutar las solicitudes de la colección para comprobar registro, autenticación, sesión y cierre de sesión.

