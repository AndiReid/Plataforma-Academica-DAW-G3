<?php
require_once '../config/Conexion.php';

class UsuarioModelo {
    private $conexion;

    public function __construct() {
        $con = new Conexion();
        $this->conexion = $con->conectar();
    }

    public function registrar($nombre, $correo, $clave, $rol) {
        $sqlCheck = "SELECT id_usuario FROM usuarios WHERE correo = :correo";
        $stmtCheck = $this->conexion->prepare($sqlCheck);
        $stmtCheck->bindParam(':correo', $correo);
        $stmtCheck->execute();
        
        if ($stmtCheck->rowCount() > 0) {
            return false;
        }

        $sql = "INSERT INTO usuarios (nombre, correo, clave, rol) VALUES (:nombre, :correo, :clave, :rol)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':clave', $clave);
        $stmt->bindParam(':rol', $rol);
        
        return $stmt->execute();
    }

    public function login($correo, $clave) {
        $sql = "SELECT id_usuario, nombre, correo, rol FROM usuarios WHERE correo = :correo AND clave = :clave";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':clave', $clave);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    public function listar() {
        $sql = "SELECT id_usuario, nombre, correo, rol FROM usuarios ORDER BY id_usuario DESC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminar($id_usuario) {
        $sql = "DELETE FROM usuarios WHERE id_usuario = :id_usuario";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerPorId($id_usuario) {
        $sql = "SELECT id_usuario, nombre, correo, rol FROM usuarios WHERE id_usuario = :id_usuario";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar($id_usuario, $nombre, $correo, $rol) {
        $sql = "UPDATE usuarios SET nombre = :nombre, correo = :correo, rol = :rol WHERE id_usuario = :id_usuario";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':rol', $rol);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>