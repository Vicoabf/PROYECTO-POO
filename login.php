<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Allow: GET, POST, OPTIONS, PUT, DELETE');

include 'conexion.php';
$pdo = new Conexion();

$response = array('success' => false); // Respuesta inicial con success=false

if (isset($_REQUEST['nombre']) && isset($_REQUEST['contrasena'])) {

    $Usuario = $_REQUEST['nombre'];
    $Password = $_REQUEST['contrasena'];

    // Consulta preparada con PDO
    $Query = "SELECT * FROM login WHERE nombre = :nombre AND contrasena = :contrasena";
    $statement = $pdo->prepare($Query);
    $statement->bindParam(':nombre', $Usuario);
    $statement->bindParam(':contrasena', $Password);

    if ($statement->execute()) {
        $resultados = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($resultados)) {
            // Si el usuario existe, la autenticación es exitosa
            $response['success'] = true;
            $response['user'] = $resultados[0]; // Enviar detalles del usuario si es necesario
        } else {
            // El usuario no existe o las credenciales son incorrectas
            $response['error'] = 'El usuario no existe o las credenciales son incorrectas';
        }
    } else {
        // Error en la ejecución de la consulta
        $response['error'] = 'Error en la ejecución de la consulta';
    }

    // Cerrar la conexión PDO
    $pdo = null;
} else {
    // No se proporcionaron todas las credenciales requeridas
    $response['error'] = 'Por favor, proporcione usuario y contraseña';
}

echo json_encode($response);
?>
