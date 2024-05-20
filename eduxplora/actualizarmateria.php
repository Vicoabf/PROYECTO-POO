<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

$pdo = include 'conexion.php'; 

$json = file_get_contents("php://input");
$objMateria = json_decode($json);

try {
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Utiliza marcadores de posición (?) para evitar problemas de SQL injection
    $stmt = $pdo->prepare("UPDATE materias SET Nombre=?, objetivo=?, idCarrera=? WHERE idMateria=?");
    $stmt->execute([$objMateria->Nombre, $objMateria->objetivo, $objMateria->idCarrera, $objMateria->idMateria]);

    $jsonRespuesta = array('mensaje' => 'Registro actualizado con éxito');
    echo json_encode($jsonRespuesta);
} catch (PDOException $e) {
    $jsonRespuesta = array('error' => 'Error al actualizar registro: ' . $e->getMessage());
    echo json_encode($jsonRespuesta);
}

$stmt = null;
$pdo = null;
?>
