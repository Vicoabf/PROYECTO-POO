<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

$pdo = include 'conexion.php';

$json = file_get_contents("php://input");
$objEmpleado = json_decode($json);

// Actualizar el estado según el valor recibido
$estado = $objEmpleado->nuevoEstado;

if ($estado === 'Aceptado' || $estado === 'Rechazado') {
    try {
        // Actualizar en la tabla solicitud
        $sqlUpdate = "UPDATE solicitud SET estadoActual=:estado WHERE idSolicitud=:idSolicitud";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmtUpdate->bindParam(':idSolicitud', $objEmpleado->idSolicitud, PDO::PARAM_INT);
        $stmtUpdate->execute();

        if (!$stmtUpdate) {
            $jsonRespuesta = array('msg' => 'Error', 'error' => 'Error al actualizar en solicitud');
            header('Content-Type: application/json');
            echo json_encode($jsonRespuesta);
            exit;
        }

        // Actualizar en la tabla solicitudaceptadas
        $sqlUpdateAceptadas = "UPDATE solicitudaceptadas SET estadoActualsol=:estado WHERE idSolicitudasol=:idSolicitud";
        $stmtUpdateAceptadas = $pdo->prepare($sqlUpdateAceptadas);
        $stmtUpdateAceptadas->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmtUpdateAceptadas->bindParam(':idSolicitud', $objEmpleado->idSolicitud, PDO::PARAM_INT);
        $stmtUpdateAceptadas->execute();

        if (!$stmtUpdateAceptadas) {
            $jsonRespuesta = array('msg' => 'Error', 'error' => 'Error al actualizar en solicitudaceptadas');
            header('Content-Type: application/json');
            echo json_encode($jsonRespuesta);
            exit;
        }

        $jsonRespuesta = array('msg' => 'OK');
        header('Content-Type: application/json');
        echo json_encode($jsonRespuesta);
    } catch (PDOException $e) {
        $jsonRespuesta = array('msg' => 'Error', 'error' => $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode($jsonRespuesta);
    }
} else {
    $jsonRespuesta = array('msg' => 'Error', 'error' => 'Estado no válido');
    header('Content-Type: application/json');
    echo json_encode($jsonRespuesta);
}
?>
