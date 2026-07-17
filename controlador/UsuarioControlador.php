<?php
header('Content-Type: application/json');
require_once '../modelo/UsuarioModelo.php';

$accion = isset($_POST['accion']) ? $_POST['accion'] : (isset($_GET['accion']) ? $_GET['accion'] : '');
$modelo = new UsuarioModelo();

if ($accion == 'registro') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $clave = $_POST['clave'];
    $rol = $_POST['rol'];

    $resultado = $modelo->registrar($nombre, $correo, $clave, $rol);
    if ($resultado) {
        echo json_encode(["status" => "success", "mensaje" => "Usuario registrado correctamente"]);
    } else {
        echo json_encode(["status" => "error", "mensaje" => "El correo electrónico ya existe"]);
    }
} 
else if ($accion == 'login') {
    $correo = $_POST['correo'];
    $clave = $_POST['clave'];

    $usuario = $modelo->login($correo, $clave);
    if ($usuario) {
        echo json_encode(["status" => "success", "usuario" => $usuario]);
    } else {
        echo json_encode(["status" => "error", "mensaje" => "Credenciales incorrectas"]);
    }
} 
else if ($accion == 'listar') {
    $usuarios = $modelo->listar();
    echo json_encode(["status" => "success", "data" => $usuarios]);
} 
else if ($accion == 'eliminar') {
    $id_usuario = $_POST['id_usuario'];
    $resultado = $modelo->eliminar($id_usuario);
    if ($resultado) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "mensaje" => "No se pudo eliminar el usuario"]);
    }
} 
else if ($accion == 'obtener') {
    $id_usuario = $_GET['id_usuario'];
    $usuario = $modelo->obtenerPorId($id_usuario);
    if ($usuario) {
        echo json_encode(["status" => "success", "usuario" => $usuario]);
    } else {
        echo json_encode(["status" => "error", "mensaje" => "Usuario no encontrado"]);
    }
} 
else if ($accion == 'actualizar') {
    $id_usuario = $_POST['id_usuario'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $rol = $_POST['rol'];

    $resultado = $modelo->actualizar($id_usuario, $nombre, $correo, $rol);
    if ($resultado) {
        echo json_encode(["status" => "success", "mensaje" => "Usuario actualizado correctamente"]);
    } else {
        echo json_encode(["status" => "error", "mensaje" => "No se pudieron actualizar los datos"]);
    }
} 
else {
    echo json_encode(["status" => "error", "mensaje" => "Acción no válida"]);
}
?>