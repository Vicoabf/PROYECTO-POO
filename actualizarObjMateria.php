<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');
header('Allow: GET, POST, OPTIONS, PUT, DELETE');

// Manejar solicitudes OPTIONS (CORS previas al vuelo)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit();
}

if (isset($_REQUEST['Nombre']) &&
    isset($_REQUEST['Objetivo'])) {
		
    include 'conexion.php';
	
	$Nombre = $_REQUEST['Nombre'];
	$Objetivo = $_REQUEST['Objetivo'];
	   
    $Query = "UPDATE materias SET objetivo = ? WHERE Nombre = ?";
    $stmt = $conn->prepare($Query);
    $stmt->bind_param("ss", $Objetivo,$Nombre);
    $stmt->execute();
    
    $Consulta = $stmt->get_result();
    
    if (!$Consulta) {
        die("Error en la consulta: " . mysqli_error($conn));
    }
    
    $arreglo = array();
    
    if (mysqli_num_rows($Consulta) > 0) {
        while ($recibido = mysqli_fetch_assoc($Consulta)) {
            array_push($arreglo, $recibido);
        }
    }
    
    print json_encode($arreglo);
    
    mysqli_close($conn);
}

?>