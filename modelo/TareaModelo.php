<?php
require_once '../config/Conexion.php';

class TareaModelo {
    private $conexion;

    public function __construct() {
        $con = new Conexion();
        $this->conexion = $con->conectar();
    }

    public function registrar($titulo, $curso, $descripcion, $fecha_limite) {
        $sql = "INSERT INTO tareas (titulo, curso, descripcion, fecha_limite) VALUES (:titulo, :curso, :descripcion, :fecha_limite)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':curso', $curso);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':fecha_limite', $fecha_limite);
        return $stmt->execute();
    }

    public function listar() {
        $sql = "SELECT id_tarea, titulo, curso, descripcion, fecha_limite FROM tareas ORDER BY fecha_limite ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminar($id_tarea) {
        $sql = "DELETE FROM tareas WHERE id_tarea = :id_tarea";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_tarea', $id_tarea, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerPorId($id_tarea) {
        $sql = "SELECT id_tarea, titulo, curso, descripcion, fecha_limite FROM tareas WHERE id_tarea = :id_tarea";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_tarea', $id_tarea, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar($id_tarea, $titulo, $curso, $descripcion, $fecha_limite) {
        $sql = "UPDATE tareas SET titulo = :titulo, curso = :curso, descripcion = :descripcion, fecha_limite = :fecha_limite WHERE id_tarea = :id_tarea";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':curso', $curso);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':fecha_limite', $fecha_limite);
        $stmt->bindParam(':id_tarea', $id_tarea, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>