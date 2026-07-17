<?php
header('Content-Type: application/json');
require_once '../modelo/TareaModelo.php';

$accion = isset($_POST['accion']) ? $_POST['accion'] : (isset($_GET['accion']) ? $_GET['accion'] : '');
$modelo = new TareaModelo();

if ($accion == 'registrar') {
    $titulo = $_POST['titulo'];
    $curso = $_POST['curso'];
    $descripcion = $_POST['descripcion'];
    $fecha_limite = $_POST['fecha_limite'];

    $resultado = $modelo->registrar($titulo, $curso, $descripcion, $fecha_limite);
    if ($resultado) {
        echo json_encode(["status" => "success", "mensaje" => "Tarea asignada correctamente"]);
    } else {
        echo json_encode(["status" => "error", "mensaje" => "No se pudo registrar la tarea"]);
    }
} 
else if ($accion == 'listar') {
    $tareas = $modelo->listar();
    echo json_encode(["status" => "success", "data" => $tareas]);
} 
else if ($accion == 'eliminar') {
    $id_tarea = $_POST['id_tarea'];
    $resultado = $modelo->eliminar($id_tarea);
    if ($resultado) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "mensaje" => "Error al eliminar la tarea"]);
    }
} 
else if ($accion == 'obtener') {
    $id_tarea = $_GET['id_tarea'];
    $tarea = $modelo->obtenerPorId($id_tarea);
    if ($tarea) {
        echo json_encode(["status" => "success", "tarea" => $tarea]);
    } else {
        echo json_encode(["status" => "error", "mensaje" => "Tarea no encontrada"]);
    }
} 
else if ($accion == 'actualizar') {
    $id_tarea = $_POST['id_tarea'];
    $titulo = $_POST['titulo'];
    $curso = $_POST['curso'];
    $descripcion = $_POST['descripcion'];
    $fecha_limite = $_POST['fecha_limite'];

    $resultado = $modelo->actualizar($id_tarea, $titulo, $curso, $descripcion, $fecha_limite);
    if ($resultado) {
        echo json_encode(["status" => "success", "mensaje" => "Tarea actualizada correctamente"]);
    } else {
        echo json_encode(["status" => "error", "mensaje" => "No se pudieron actualizar los datos"]);
    }
} 
else {
    echo json_encode(["status" => "error", "mensaje" => "Acción no válida"]);
}
?>