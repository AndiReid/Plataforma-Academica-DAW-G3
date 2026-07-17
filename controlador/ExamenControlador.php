<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../modelo/ExamenModelo.php';

function responder(string $status, array $datos = [], int $codigoHttp = 200): void {
    http_response_code($codigoHttp);
    echo json_encode(array_merge(['status' => $status], $datos), JSON_UNESCAPED_UNICODE);
    exit;
}

function valorPost(string $campo): string {
    return isset($_POST[$campo]) ? trim((string) $_POST[$campo]) : '';
}

function leerPreguntas(): array {
    $json = $_POST['preguntas'] ?? '';
    $preguntas = json_decode($json, true);

    if (!is_array($preguntas) || count($preguntas) === 0) {
        throw new InvalidArgumentException('Debe agregar al menos una pregunta al examen.');
    }

    $preguntasLimpias = [];

    foreach ($preguntas as $indice => $pregunta) {
        $numero = $indice + 1;
        $enunciado = trim((string) ($pregunta['enunciado'] ?? ''));
        $respuestas = $pregunta['respuestas'] ?? [];

        if ($enunciado === '') {
            throw new InvalidArgumentException("La pregunta {$numero} no tiene enunciado.");
        }

        if (!is_array($respuestas) || count($respuestas) < 2) {
            throw new InvalidArgumentException("La pregunta {$numero} debe tener al menos dos respuestas.");
        }

        $respuestasLimpias = [];
        $cantidadCorrectas = 0;

        foreach ($respuestas as $indiceRespuesta => $respuesta) {
            $texto = trim((string) ($respuesta['texto'] ?? ''));
            $esCorrecta = !empty($respuesta['es_correcta']);

            if ($texto === '') {
                $numeroRespuesta = $indiceRespuesta + 1;
                throw new InvalidArgumentException(
                    "La respuesta {$numeroRespuesta} de la pregunta {$numero} está vacía."
                );
            }

            if ($esCorrecta) {
                $cantidadCorrectas++;
            }

            $respuestasLimpias[] = [
                'texto' => $texto,
                'es_correcta' => $esCorrecta
            ];
        }

        if ($cantidadCorrectas !== 1) {
            throw new InvalidArgumentException(
                "La pregunta {$numero} debe tener exactamente una respuesta correcta."
            );
        }

        $preguntasLimpias[] = [
            'enunciado' => $enunciado,
            'respuestas' => $respuestasLimpias
        ];
    }

    return $preguntasLimpias;
}

function validarDatosExamen(): array {
    $titulo = valorPost('titulo');
    $curso = valorPost('curso');
    $fecha = valorPost('fecha');
    $duracion = (int) valorPost('duracion');
    $instrucciones = valorPost('instrucciones');

    if ($titulo === '' || $curso === '' || $fecha === '' || $instrucciones === '') {
        throw new InvalidArgumentException('Complete todos los datos generales de la evaluación.');
    }

    if ($duracion < 5 || $duracion > 180) {
        throw new InvalidArgumentException('La duración debe estar entre 5 y 180 minutos.');
    }

    return [$titulo, $curso, $fecha, $duracion, $instrucciones];
}

try {
    $accion = $_POST['accion'] ?? $_GET['accion'] ?? '';
    $modelo = new ExamenModelo();

    switch ($accion) {
        case 'registrar':
            [$titulo, $curso, $fecha, $duracion, $instrucciones] = validarDatosExamen();
            $preguntas = leerPreguntas();

            $idExamen = $modelo->registrar(
                $titulo,
                $curso,
                $fecha,
                $duracion,
                $instrucciones,
                $preguntas
            );

            responder('success', [
                'mensaje' => 'Evaluación, preguntas y respuestas guardadas correctamente.',
                'id_examen' => $idExamen
            ]);
            break;

        case 'listar':
            responder('success', ['data' => $modelo->listar()]);
            break;

        case 'eliminar':
            $idExamen = (int) ($_POST['id_examen'] ?? 0);
            if ($idExamen <= 0) {
                throw new InvalidArgumentException('Identificador de evaluación no válido.');
            }

            $modelo->eliminar($idExamen);
            responder('success', ['mensaje' => 'Evaluación eliminada correctamente.']);
            break;

        case 'obtener':
            $idExamen = (int) ($_GET['id_examen'] ?? 0);
            $examen = $modelo->obtenerPorId($idExamen, true);

            if (!$examen) {
                responder('error', ['mensaje' => 'Evaluación no encontrada.'], 404);
            }

            responder('success', ['examen' => $examen]);
            break;

        case 'resolver':
            $idExamen = (int) ($_GET['id_examen'] ?? 0);
            // No se envía es_correcta al navegador del estudiante.
            $examen = $modelo->obtenerPorId($idExamen, false);

            if (!$examen) {
                responder('error', ['mensaje' => 'Evaluación no encontrada.'], 404);
            }

            responder('success', ['examen' => $examen]);
            break;

        case 'actualizar':
            $idExamen = (int) ($_POST['id_examen'] ?? 0);
            if ($idExamen <= 0) {
                throw new InvalidArgumentException('Identificador de evaluación no válido.');
            }

            [$titulo, $curso, $fecha, $duracion, $instrucciones] = validarDatosExamen();
            $preguntas = leerPreguntas();

            $modelo->actualizar(
                $idExamen,
                $titulo,
                $curso,
                $fecha,
                $duracion,
                $instrucciones,
                $preguntas
            );

            responder('success', [
                'mensaje' => 'Evaluación, preguntas y respuestas actualizadas correctamente.'
            ]);
            break;

        case 'calificar':
            $idExamen = (int) ($_POST['id_examen'] ?? 0);
            $respuestas = json_decode($_POST['respuestas'] ?? '{}', true);

            if ($idExamen <= 0 || !is_array($respuestas)) {
                throw new InvalidArgumentException('No fue posible procesar las respuestas enviadas.');
            }

            $resultado = $modelo->calificar($idExamen, $respuestas);
            if ($resultado['total'] === 0) {
                throw new InvalidArgumentException('Esta evaluación todavía no tiene preguntas configuradas.');
            }

            responder('success', [
                'mensaje' => 'Evaluación calificada correctamente.',
                'resultado' => $resultado
            ]);
            break;

        default:
            responder('error', ['mensaje' => 'Acción no válida.'], 400);
    }
} catch (InvalidArgumentException $e) {
    responder('error', ['mensaje' => $e->getMessage()], 422);
} catch (Throwable $e) {
    // En producción conviene registrar $e->getMessage() en un archivo de log.
    responder('error', [
        'mensaje' => 'Ocurrió un error al procesar la evaluación. Verifique que haya ejecutado la actualización de la base de datos.'
    ], 500);
}
?>
