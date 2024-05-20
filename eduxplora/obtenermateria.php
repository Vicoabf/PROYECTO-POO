<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");



try {
    $pdo = include 'conexion.php'; 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM materias";
    $stmt = $pdo->query($sql);

    $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($datos);
} catch (PDOException $e) {
    echo json_encode(array("error" => $e->getMessage()));
}
?>
