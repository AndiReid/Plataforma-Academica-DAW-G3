<?php
header('Content-Type: application/json');
require_once '../modelo/CursoModelo.php';

$accion = isset($_POST['accion']) ? $_POST['accion'] : (isset($_GET['accion']) ? $_GET['accion'] : '');
$modelo = new CursoModelo();

if ($accion == 'registrar') {
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $docente = $_POST['docente'];
    $descripcion = $_POST['descripcion'];

    $resultado = $modelo->registrar($nombre, $categoria, $docente, $descripcion);
    if ($resultado) {
        echo json_encode(["status" => "success", "mensaje" => "Curso catalogado correctamente"]);
    } else {
        echo json_encode(["status" => "error", "mensaje" => "No se pudo registrar el curso"]);
    }
} 
else if ($accion == 'listar') {
    $cursos = $modelo->listar();
    echo json_encode(["status" => "success", "data" => $cursos]);
} 
else if ($accion == 'eliminar') {
    $id_curso = $_POST['id_curso'];
    $resultado = $modelo->eliminar($id_curso);
    if ($resultado) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "mensaje" => "Error al eliminar el curso"]);
    }
} 
else if ($accion == 'obtener') {
    $id_curso = $_GET['id_curso'];
    $curso = $modelo->obtenerPorId($id_curso);
    if ($curso) {
        echo json_encode(["status" => "success", "curso" => $curso]);
    } else {
        echo json_encode(["status" => "error", "mensaje" => "Curso no encontrado"]);
    }
} 
else if ($accion == 'actualizar') {
    $id_curso = $_POST['id_curso'];
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $docente = $_POST['docente'];
    $descripcion = $_POST['descripcion'];

    $resultado = $modelo->actualizar($id_curso, $nombre, $categoria, $docente, $descripcion);
    if ($resultado) {
        echo json_encode(["status" => "success", "mensaje" => "Curso actualizado correctamente"]);
    } else {
        echo json_encode(["status" => "error", "mensaje" => "No se pudieron actualizar los datos"]);
    }
} 
else {
    echo json_encode(["status" => "error", "mensaje" => "Acción no válida"]);
}
?>