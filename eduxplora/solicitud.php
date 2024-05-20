<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');

if (isset($_REQUEST['idEmpresa']) && isset($_REQUEST['nombreEmpresa']) && isset($_REQUEST['grupo']) && isset($_REQUEST['idUsuario']) && isset($_REQUEST['carrera'])) {
    $pdo = include 'conexion.php'; // Incluir el archivo y obtener la instancia de la conexión PDO
    
	
    $idEmpresa = $_REQUEST['idEmpresa'];
    $nombre = $_REQUEST['nombreEmpresa'];
    $grupo = $_REQUEST['grupo'];
    $idUsuario = $_REQUEST['idUsuario'];
    $carrera = $_REQUEST['carrera'];

    // Consulta preparada con PDO
    $query = "INSERT INTO solicitud (idEmpresa, nombreEmpresa, grupo, idUsuario, carrera) VALUES (:idEmpresa, :nombreEmpresa, :grupo, :idUsuario, :carrera)";
    $statement = $pdo->prepare($query);

    $statement->bindParam(':idEmpresa', $idEmpresa);
    $statement->bindParam(':nombreEmpresa', $nombre);
    $statement->bindParam(':grupo', $grupo);
    $statement->bindParam(':idUsuario', $idUsuario);
    $statement->bindParam(':carrera', $carrera);

    $arreglo = array();
    
    if ($statement->execute()) {
        $arreglo['success'] = true;
    } else {
        $arreglo['error'] = 'Error al ejecutar la consulta';
    }

    // Convertir resultado a JSON y enviarlo
    echo json_encode($arreglo);
    // Cerrar la conexión PDO
    $pdo = null;
}
?>
