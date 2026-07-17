<?php
require_once '../config/Conexion.php';

class CursoModelo {
    private $conexion;

    public function __construct() {
        $con = new Conexion();
        $this->conexion = $con->conectar();
    }

    public function registrar($nombre, $categoria, $docente, $descripcion) {
        $sql = "INSERT INTO cursos (nombre, categoria, docente, descripcion) VALUES (:nombre, :categoria, :docente, :descripcion)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':docente', $docente);
        $stmt->bindParam(':descripcion', $descripcion);
        return $stmt->execute();
    }

    public function listar() {
        $sql = "SELECT id_curso, nombre, categoria, docente, descripcion FROM cursos ORDER BY id_curso DESC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminar($id_curso) {
        $sql = "DELETE FROM cursos WHERE id_curso = :id_curso";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerPorId($id_curso) {
        $sql = "SELECT id_curso, nombre, categoria, docente, descripcion FROM cursos WHERE id_curso = :id_curso";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar($id_curso, $nombre, $categoria, $docente, $descripcion) {
        $sql = "UPDATE cursos SET nombre = :nombre, categoria = :categoria, docente = :docente, descripcion = :descripcion WHERE id_curso = :id_curso";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':docente', $docente);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>