<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

$pdo = include 'conexion.php';

$sql = "SELECT s.*, e.Nombre AS nombreEmpresa, e.Contacto AS contactoEmpresa, l.Usuario AS nombreUsuario
        FROM solicitud s
        LEFT JOIN empresas e ON s.idEmpresa = e.idEmpresa
        LEFT JOIN login l ON s.idUsuario = l.idUsuario";

try {
    $query = $pdo->query($sql);

    $datos = array();

    while ($resultado = $query->fetch(PDO::FETCH_ASSOC)) {
        // Agregar el resultado al array de datos
        $datos[] = $resultado;
    }

    echo json_encode($datos);
} catch (PDOException $e) {
    // Loguear el error o manejarlo de alguna otra manera apropiada
    error_log('Error en la consulta: ' . $e->getMessage());
    
    // Devolver una respuesta genérica en caso de error
    echo json_encode(array('error' => 'Error al procesar la solicitud.'));
}
?>
