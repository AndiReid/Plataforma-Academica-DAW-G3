<?php
require_once '../config/Conexion.php';

class ExamenModelo {
    private PDO $conexion;

    public function __construct() {
        $con = new Conexion();
        $this->conexion = $con->conectar();
    }

    public function registrar($titulo, $curso, $fecha, $duracion, $instrucciones, array $preguntas): int {
        try {
            $this->conexion->beginTransaction();

            $sql = "INSERT INTO examenes (titulo, curso, fecha, duracion, instrucciones)
                    VALUES (:titulo, :curso, :fecha, :duracion, :instrucciones)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([
                ':titulo' => $titulo,
                ':curso' => $curso,
                ':fecha' => $fecha,
                ':duracion' => (int) $duracion,
                ':instrucciones' => $instrucciones
            ]);

            $idExamen = (int) $this->conexion->lastInsertId();
            $this->guardarPreguntas($idExamen, $preguntas);

            $this->conexion->commit();
            return $idExamen;
        } catch (Throwable $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            throw $e;
        }
    }

    public function listar(): array {
        $sql = "SELECT e.id_examen, e.titulo, e.curso, e.fecha, e.duracion, e.instrucciones,
                       COUNT(p.id_pregunta) AS total_preguntas
                FROM examenes e
                LEFT JOIN preguntas_examen p ON p.id_examen = e.id_examen
                GROUP BY e.id_examen, e.titulo, e.curso, e.fecha, e.duracion, e.instrucciones
                ORDER BY e.id_examen DESC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminar($idExamen): bool {
        // Las preguntas y respuestas se eliminan automáticamente por ON DELETE CASCADE.
        $sql = "DELETE FROM examenes WHERE id_examen = :id_examen";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':id_examen' => (int) $idExamen]);
    }

    public function obtenerPorId($idExamen, bool $incluirCorrectas = true): ?array {
        $sql = "SELECT id_examen, titulo, curso, fecha, duracion, instrucciones
                FROM examenes
                WHERE id_examen = :id_examen";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id_examen' => (int) $idExamen]);
        $examen = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$examen) {
            return null;
        }

        $examen['preguntas'] = $this->obtenerPreguntas((int) $idExamen, $incluirCorrectas);
        return $examen;
    }

    public function actualizar($idExamen, $titulo, $curso, $fecha, $duracion, $instrucciones, array $preguntas): bool {
        try {
            $this->conexion->beginTransaction();

            $sql = "UPDATE examenes
                    SET titulo = :titulo,
                        curso = :curso,
                        fecha = :fecha,
                        duracion = :duracion,
                        instrucciones = :instrucciones
                    WHERE id_examen = :id_examen";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([
                ':titulo' => $titulo,
                ':curso' => $curso,
                ':fecha' => $fecha,
                ':duracion' => (int) $duracion,
                ':instrucciones' => $instrucciones,
                ':id_examen' => (int) $idExamen
            ]);

            // Se reemplaza el banco de preguntas completo para mantener el orden y simplificar la edición.
            // Las respuestas se eliminan por cascada al borrar sus preguntas.
            $stmtEliminar = $this->conexion->prepare(
                "DELETE FROM preguntas_examen WHERE id_examen = :id_examen"
            );
            $stmtEliminar->execute([':id_examen' => (int) $idExamen]);

            $this->guardarPreguntas((int) $idExamen, $preguntas);

            $this->conexion->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            throw $e;
        }
    }

    public function calificar($idExamen, array $respuestasSeleccionadas): array {
        $sql = "SELECT p.id_pregunta, r.id_respuesta AS id_respuesta_correcta
                FROM preguntas_examen p
                INNER JOIN respuestas_examen r
                    ON r.id_pregunta = p.id_pregunta AND r.es_correcta = 1
                WHERE p.id_examen = :id_examen
                ORDER BY p.orden, p.id_pregunta";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id_examen' => (int) $idExamen]);
        $correctas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total = count($correctas);
        $aciertos = 0;
        $contestadas = 0;

        foreach ($correctas as $registro) {
            $idPregunta = (string) $registro['id_pregunta'];
            if (array_key_exists($idPregunta, $respuestasSeleccionadas)) {
                $contestadas++;
                if ((int) $respuestasSeleccionadas[$idPregunta] === (int) $registro['id_respuesta_correcta']) {
                    $aciertos++;
                }
            }
        }

        $porcentaje = $total > 0 ? round(($aciertos / $total) * 100, 2) : 0;
        $notaSobreDiez = $total > 0 ? round(($aciertos / $total) * 10, 2) : 0;

        return [
            'total' => $total,
            'contestadas' => $contestadas,
            'correctas' => $aciertos,
            'incorrectas' => max(0, $total - $aciertos),
            'porcentaje' => $porcentaje,
            'nota_sobre_10' => $notaSobreDiez
        ];
    }

    private function guardarPreguntas(int $idExamen, array $preguntas): void {
        $sqlPregunta = "INSERT INTO preguntas_examen (id_examen, enunciado, orden)
                        VALUES (:id_examen, :enunciado, :orden)";
        $stmtPregunta = $this->conexion->prepare($sqlPregunta);

        $sqlRespuesta = "INSERT INTO respuestas_examen (id_pregunta, texto_respuesta, es_correcta, orden)
                         VALUES (:id_pregunta, :texto_respuesta, :es_correcta, :orden)";
        $stmtRespuesta = $this->conexion->prepare($sqlRespuesta);

        foreach ($preguntas as $indicePregunta => $pregunta) {
            $stmtPregunta->execute([
                ':id_examen' => $idExamen,
                ':enunciado' => $pregunta['enunciado'],
                ':orden' => $indicePregunta + 1
            ]);

            $idPregunta = (int) $this->conexion->lastInsertId();

            foreach ($pregunta['respuestas'] as $indiceRespuesta => $respuesta) {
                $stmtRespuesta->execute([
                    ':id_pregunta' => $idPregunta,
                    ':texto_respuesta' => $respuesta['texto'],
                    ':es_correcta' => !empty($respuesta['es_correcta']) ? 1 : 0,
                    ':orden' => $indiceRespuesta + 1
                ]);
            }
        }
    }

    private function obtenerPreguntas(int $idExamen, bool $incluirCorrectas): array {
        $sqlPreguntas = "SELECT id_pregunta, enunciado, orden
                         FROM preguntas_examen
                         WHERE id_examen = :id_examen
                         ORDER BY orden, id_pregunta";
        $stmtPreguntas = $this->conexion->prepare($sqlPreguntas);
        $stmtPreguntas->execute([':id_examen' => $idExamen]);
        $preguntas = $stmtPreguntas->fetchAll(PDO::FETCH_ASSOC);

        $camposRespuesta = $incluirCorrectas
            ? "id_respuesta, texto_respuesta, es_correcta, orden"
            : "id_respuesta, texto_respuesta, orden";

        $sqlRespuestas = "SELECT {$camposRespuesta}
                          FROM respuestas_examen
                          WHERE id_pregunta = :id_pregunta
                          ORDER BY orden, id_respuesta";
        $stmtRespuestas = $this->conexion->prepare($sqlRespuestas);

        foreach ($preguntas as &$pregunta) {
            $stmtRespuestas->execute([':id_pregunta' => (int) $pregunta['id_pregunta']]);
            $pregunta['respuestas'] = $stmtRespuestas->fetchAll(PDO::FETCH_ASSOC);
        }
        unset($pregunta);

        return $preguntas;
    }
}
?>
