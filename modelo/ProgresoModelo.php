<?php
require_once '../config/Conexion.php';

class ProgresoModelo {
    private $conexion;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->conectar();
    }

    public function listarTodo() {
        $stmt = $this->conexion->prepare("SELECT * FROM progreso ORDER BY id_progreso DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarPorAlumno($nombre) {
        $stmt = $this->conexion->prepare("SELECT * FROM progreso WHERE nombre_alumno = :nombre");
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>