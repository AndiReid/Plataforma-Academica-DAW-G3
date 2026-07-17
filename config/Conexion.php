<?php
class Conexion {
    private $host = "127.0.0.1"; 
    private $puerto = "3306"; 
    private $dbname = "plataforma_daw";
    private $usuario = "root";
    private $clave = "";
    private $conexion;

    public function conectar() {
        $this->conexion = null;
        try {
            $this->conexion = new PDO(
                "mysql:host=" . $this->host . ";port=" . $this->puerto . ";dbname=" . $this->dbname . ";charset=utf8", 
                $this->usuario, 
                $this->clave
            );
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch(PDOException $e) {
            
            die(json_encode(["status" => "error", "mensaje" => "Error crítico de Base de Datos. Revisa el puerto de MySQL en XAMPP o verifica que la BD exista."]));
        }
        return $this->conexion;
    }
}
?>