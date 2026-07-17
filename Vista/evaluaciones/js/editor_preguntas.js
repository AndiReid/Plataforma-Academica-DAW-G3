(function () {
    let contadorPreguntas = 0;

    function contenedor() {
        return document.getElementById('contenedorPreguntas');
    }

    function crearPregunta(datos = {}) {
        const idTemporal = `pregunta-${Date.now()}-${contadorPreguntas++}`;
        const tarjeta = document.createElement('div');
        tarjeta.className = 'card border pregunta-card mb-4 shadow-sm';
        tarjeta.dataset.key = idTemporal;

        tarjeta.innerHTML = `
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold text-dark titulo-pregunta">Pregunta</h5>
                <button type="button" class="btn btn-outline-danger btn-sm btn-eliminar-pregunta">
                    Eliminar pregunta
                </button>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary">Enunciado</label>
                    <textarea class="form-control bg-light border-0 campo-enunciado" rows="2"
                        placeholder="Escriba aquí la pregunta" required></textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label fw-bold text-secondary mb-0">Respuestas</label>
                    <small class="text-muted">Seleccione el círculo de la respuesta correcta</small>
                </div>

                <div class="lista-respuestas"></div>

                <button type="button" class="btn btn-outline-primary btn-sm fw-bold btn-agregar-respuesta mt-2">
                    + Agregar respuesta
                </button>
            </div>
        `;

        tarjeta.querySelector('.campo-enunciado').value = datos.enunciado || '';
        tarjeta.querySelector('.btn-eliminar-pregunta').addEventListener('click', function () {
            if (contenedor().querySelectorAll('.pregunta-card').length <= 1) {
                alert('El examen debe conservar al menos una pregunta.');
                return;
            }
            tarjeta.remove();
            actualizarNumeracion();
        });

        tarjeta.querySelector('.btn-agregar-respuesta').addEventListener('click', function () {
            agregarRespuesta(tarjeta);
        });

        contenedor().appendChild(tarjeta);

        const respuestas = Array.isArray(datos.respuestas) && datos.respuestas.length > 0
            ? datos.respuestas
            : [
                { texto: '', es_correcta: true },
                { texto: '', es_correcta: false }
            ];

        respuestas.forEach(function (respuesta, indice) {
            agregarRespuesta(tarjeta, {
                texto: respuesta.texto ?? respuesta.texto_respuesta ?? '',
                es_correcta: Boolean(Number(respuesta.es_correcta)) || respuesta.es_correcta === true
            }, indice);
        });

        actualizarBotonesRespuestas(tarjeta);
        actualizarNumeracion();
        return tarjeta;
    }

    function agregarRespuesta(tarjeta, datos = {}, indice = null) {
        const lista = tarjeta.querySelector('.lista-respuestas');
        const fila = document.createElement('div');
        fila.className = 'input-group mb-2 respuesta-item';

        const radioId = `${tarjeta.dataset.key}-respuesta-${Date.now()}-${lista.children.length}`;
        fila.innerHTML = `
            <span class="input-group-text bg-white" title="Marcar como respuesta correcta">
                <input class="form-check-input mt-0 respuesta-correcta" type="radio"
                    name="correcta-${tarjeta.dataset.key}" id="${radioId}" required>
            </span>
            <input type="text" class="form-control bg-light border-0 texto-respuesta"
                placeholder="Texto de la respuesta" required>
            <button type="button" class="btn btn-outline-danger btn-eliminar-respuesta" title="Eliminar respuesta">×</button>
        `;

        fila.querySelector('.texto-respuesta').value = datos.texto || '';
        fila.querySelector('.respuesta-correcta').checked = datos.es_correcta === true;
        fila.querySelector('.btn-eliminar-respuesta').addEventListener('click', function () {
            if (lista.querySelectorAll('.respuesta-item').length <= 2) {
                alert('Cada pregunta debe tener al menos dos respuestas.');
                return;
            }
            fila.remove();
            actualizarBotonesRespuestas(tarjeta);
        });

        lista.appendChild(fila);

        if (indice === 0 && !lista.querySelector('.respuesta-correcta:checked')) {
            fila.querySelector('.respuesta-correcta').checked = true;
        }

        actualizarBotonesRespuestas(tarjeta);
    }

    function actualizarBotonesRespuestas(tarjeta) {
        const filas = tarjeta.querySelectorAll('.respuesta-item');
        filas.forEach(function (fila) {
            fila.querySelector('.btn-eliminar-respuesta').disabled = filas.length <= 2;
        });
    }

    function actualizarNumeracion() {
        const tarjetas = contenedor().querySelectorAll('.pregunta-card');
        tarjetas.forEach(function (tarjeta, indice) {
            tarjeta.querySelector('.titulo-pregunta').textContent = `Pregunta ${indice + 1}`;
            tarjeta.querySelector('.btn-eliminar-pregunta').disabled = tarjetas.length <= 1;
        });
    }

    function obtenerPreguntas() {
        const preguntas = [];
        const tarjetas = contenedor().querySelectorAll('.pregunta-card');

        tarjetas.forEach(function (tarjeta, indicePregunta) {
            const enunciado = tarjeta.querySelector('.campo-enunciado').value.trim();
            const respuestas = [];

            tarjeta.querySelectorAll('.respuesta-item').forEach(function (fila) {
                respuestas.push({
                    texto: fila.querySelector('.texto-respuesta').value.trim(),
                    es_correcta: fila.querySelector('.respuesta-correcta').checked
                });
            });

            if (!enunciado) {
                throw new Error(`Escriba el enunciado de la pregunta ${indicePregunta + 1}.`);
            }
            if (respuestas.length < 2) {
                throw new Error(`La pregunta ${indicePregunta + 1} debe tener al menos dos respuestas.`);
            }
            if (respuestas.some(respuesta => !respuesta.texto)) {
                throw new Error(`Complete todas las respuestas de la pregunta ${indicePregunta + 1}.`);
            }
            if (respuestas.filter(respuesta => respuesta.es_correcta).length !== 1) {
                throw new Error(`Seleccione exactamente una respuesta correcta en la pregunta ${indicePregunta + 1}.`);
            }

            preguntas.push({ enunciado, respuestas });
        });

        if (preguntas.length === 0) {
            throw new Error('Debe agregar al menos una pregunta.');
        }

        return preguntas;
    }

    function cargarPreguntas(preguntas) {
        contenedor().innerHTML = '';
        if (!Array.isArray(preguntas) || preguntas.length === 0) {
            crearPregunta();
            return;
        }
        preguntas.forEach(pregunta => crearPregunta(pregunta));
    }

    window.EditorPreguntas = {
        crearPregunta,
        obtenerPreguntas,
        cargarPreguntas
    };
})();
