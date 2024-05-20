<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");



$json = file_get_contents("php://input");
$objMateria = json_decode($json);

try {
    $pdo = include 'conexion.php'; 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Utiliza marcadores de posición (?) para evitar problemas de SQL injection
    $stmt = $pdo->prepare("INSERT INTO materias (Nombre, objetivo, idCarrera) VALUES (?, ?, ?)");
    $stmt->execute([$objMateria->Nombre, $objMateria->objetivo, $objMateria->idCarrera]);

    $jsonRespuesta = array('mensaje' => 'Registro agregado con éxito');
    echo json_encode($jsonRespuesta);
} catch (PDOException $e) {
    $jsonRespuesta = array('error' => 'Error al agregar registro: ' . $e->getMessage());
    echo json_encode($jsonRespuesta);
}

$stmt = null;
$pdo = null;
?>
