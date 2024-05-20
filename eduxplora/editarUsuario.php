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

if ($estado === 'Pendiente' || $estado === 'Rechazado') {
    try {
        // Configurar el modo de error a excepciones
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Actualizar el estado
        $sql = "UPDATE solicitud SET estadoActual=:estado WHERE idSolicitud=:idSolicitud";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmt->bindParam(':idSolicitud', $objEmpleado->idSolicitud, PDO::PARAM_INT);
        $stmt->execute();

        // Verificar si el estado cambió a 'Pendiente'
        if ($estado === 'Pendiente') {
            // Insertar en la tabla solicitudaceptadas
            $sqlInsert = "INSERT INTO solicitudaceptadas (idSolicitudasol, idEmpresasol, nombreEmpresasol, gruposol, idUsuario, carrerasol, estadoActualsol)
                          SELECT idSolicitud, idEmpresa, nombreEmpresa, grupo, idUsuario, carrera, :estado
                          FROM solicitud
                          WHERE idSolicitud=:idSolicitud";
            $stmtInsert = $pdo->prepare($sqlInsert);
            $stmtInsert->bindParam(':estado', $estado, PDO::PARAM_STR);
            $stmtInsert->bindParam(':idSolicitud', $objEmpleado->idSolicitud, PDO::PARAM_INT);
            $stmtInsert->execute();

            if (!$stmtInsert) {
                $jsonRespuesta = array('msg' => 'Error', 'error' => 'Error al insertar en solicitudaceptadas');
                header('Content-Type: application/json');
                echo json_encode($jsonRespuesta);
                exit;
            }
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
