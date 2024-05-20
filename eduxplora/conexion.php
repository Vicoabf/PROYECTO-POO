<?php
class Conexion extends PDO 
{
    public function __construct() {
        try {
            $servername = "us-cluster-east-01.k8s.cleardb.net";
            $username = "bef4d69a09d699";
            $password = "8966a326";
            $dbname = "heroku_02f8d5d2c5932d";

            // Llama al constructor de la clase base PDO (padre) para establecer la conexión
            parent::__construct("mysql:host=$servername;dbname=$dbname", $username, $password);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Conexión exitosa";
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}

// Crear una nueva instancia de la clase Conexion para establecer la conexión y retornarla.
$pdo = new Conexion();
return $pdo; // Retorna la instancia de la conexión PDO
?>

mysql://bef4d69a09d699:8966a326@us-cluster-east-01.k8s.cleardb.net/heroku_02f8d5d2c5932d6?reconnect=true

username: bef4d69a09d699
password: 8966a326
host: us-cluster-east-01.k8s.cleardb.net
dbname: heroku_02f8d5d2c5932d