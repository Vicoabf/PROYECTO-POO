<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");



$json = file_get_contents("php://input");
$objId = json_decode($json);

try {
    $pdo = include 'conexion.php'; 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Utiliza marcadores de posición (?) para evitar problemas de SQL injection
    $stmt = $pdo->prepare("DELETE FROM materias WHERE idMateria = ?");
    $stmt->execute([$objId->idMateria]);

    if ($stmt->rowCount() > 0) {
        $jsonRespuesta = array('mensaje' => 'Registro eliminado con éxito');
    } else {
        $jsonRespuesta = array('error' => 'No se encontró el registro para eliminar');
    }

    echo json_encode($jsonRespuesta);
} catch (PDOException $e) {
    $jsonRespuesta = array('error' => 'Error al eliminar registro: ' . $e->getMessage());
    echo json_encode($jsonRespuesta);
}

$stmt = null;
$pdo = null;
?>
