<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Allow: GET, POST, OPTIONS, PUT, DELETE');

include 'conexion.php';
$pdo = new Conexion();

if (isset($_REQUEST['Usuario']) && isset($_REQUEST['Password'])) {

    $Usuario = $_REQUEST['Usuario'];
    $Password = $_REQUEST['Password'];

    // Consulta preparada con PDO
    $Query = "SELECT Usuario, Rol FROM login WHERE Usuario = :usuario AND Password = :password";
    $statement = $pdo->prepare($Query);
    $statement->bindParam(':usuario', $Usuario);
    $statement->bindParam(':password', $Password);

    $arreglo = array();
    if ($statement->execute()) {
        while ($recibido = $statement->fetch(PDO::FETCH_ASSOC)) {
            array_push($arreglo, $recibido);
        }

        if (empty($arreglo)) {
            // El usuario no existe o las credenciales son incorrectas
            $error_response = array('error' => 'El usuario no existe o las credenciales son incorrectas');
            echo json_encode($error_response); // Responder con el mensaje de error
        } else {
            // Convertir resultado a JSON y enviarlo
            echo json_encode($arreglo);
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
