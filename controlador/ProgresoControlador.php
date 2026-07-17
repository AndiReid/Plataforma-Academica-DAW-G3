<?php
require_once '../config/Conexion.php';
require_once '../modelo/ProgresoModelo.php';

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';
$modelo = new ProgresoModelo();

switch ($accion) {
    case 'listar_todo':
        $datos = $modelo->listarTodo();
        echo json_encode(['status' => 'success', 'data' => $datos]);
        break;
    
    case 'listar_alumno':
        $nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
        $datos = $modelo->listarPorAlumno($nombre);
        echo json_encode(['status' => 'success', 'data' => $datos]);
        break;
        
    default:
        echo json_encode(['status' => 'error', 'mensaje' => 'Acción no válida']);
        break;
}
?>