<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

try {
    $pdo = include 'conexion.php';

    $sql = "SELECT solicitudaceptadas.*, empresas.Nombre AS nombreEmpresa, empresas.Contacto
            FROM solicitudaceptadas
            LEFT JOIN empresas ON solicitudaceptadas.idEmpresasol = empresas.idEmpresa";

    $query = $pdo->query($sql);

    $datos = array();

    while ($resultado = $query->fetch(PDO::FETCH_ASSOC)) {
        // Obtener el nombre de usuario desde la tabla 'login'
        $idUsuario = $resultado['idUsuario'];
        $sqlLogin = "SELECT Usuario FROM login WHERE idUsuario = '$idUsuario'";
        $queryLogin = $pdo->query($sqlLogin);
        $resultadoLogin = $queryLogin->fetch(PDO::FETCH_ASSOC);

        // Agregar el nombre de usuario al resultado
        $resultado['nombreUsuario'] = $resultadoLogin['Usuario'];

        // Agregar el resultado al array de datos
        $datos[] = $resultado;
    }

    echo json_encode($datos);
} catch (PDOException $e) {
    echo json_encode(array('error' => $e->getMessage()));
}
?>
