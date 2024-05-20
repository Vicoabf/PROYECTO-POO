<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Allow: GET, POST, OPTIONS, PUT, DELETE');

include 'conexion.php';
$pdo = new Conexion();

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

        if (empty($resultados)) {
            // El usuario no existe o las credenciales son incorrectas
            $error_response = array('error' => 'El usuario no existe o las credenciales son incorrectas');
            echo json_encode($error_response); // Responder con el mensaje de error
        } else {
            // Convertir resultado a JSON y enviarlo
            echo json_encode($resultados);
        }
    } else {
        // Error en la ejecución de la consulta
        $error_response = array('error' => 'Error en la ejecución de la consulta');
        echo json_encode($error_response); // Responder con el mensaje de error
    }

    // Cerrar la conexión PDO
    $pdo = null;
} else {
    // No se proporcionaron todas las credenciales requeridas
    $error_response = array('error' => 'Por favor, proporcione usuario y contraseña');
    echo json_encode($error_response); // Responder con el mensaje de error
}
?>
