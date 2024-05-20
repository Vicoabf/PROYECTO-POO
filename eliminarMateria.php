<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');
header('Allow: GET, POST, OPTIONS, PUT, DELETE');

// Manejar solicitudes OPTIONS (CORS previas al vuelo)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit();
}

if (isset($_REQUEST['Nombre'])) {
    include 'conexion.php';
    $Nombre = $_REQUEST['Nombre'];

    // Utiliza marcadores de posición (?) para evitar problemas de SQL injection
    $Query = "DELETE FROM materias WHERE Nombre = ?";
    $stmt = $conn->prepare($Query);

    // Verifica si la preparación de la consulta tuvo éxito
    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . $conn->error);
    }

    $stmt->bind_param("s", $Nombre);
    $stmt->execute();

    // Verifica si la ejecución de la consulta tuvo éxito
    if ($stmt->affected_rows > 0) {
        $respuesta = array("mensaje" => "Registros eliminados con éxito");
    } else {
        $respuesta = array("error" => "No se encontraron registros para eliminar");
    }

    print json_encode($respuesta);

    // Cierra la conexión
    $stmt->close();
    mysqli_close($conn);
}
?>
