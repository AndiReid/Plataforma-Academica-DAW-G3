let cursos =
JSON.parse(localStorage.getItem("cursos")) || [];

let tabla =
document.getElementById("tablaCursos");

for(let i = 0; i < cursos.length; i++){

    tabla.innerHTML +=
    `
    <tr>

        <td>${cursos[i].nombre}</td>

        <td>${cursos[i].categoria}</td>

        <td>${cursos[i].docente}</td>

        <td>

            <a href="detalle_curso.html"
               class="btn btn-info btn-sm">
               Ver
            </a>

            <a href="editar_curso.html"
               class="btn btn-warning btn-sm">
               Editar
            </a>

            <button
                class="btn btn-danger btn-sm"
                onclick="eliminarCurso(${i})">
                Eliminar
            </button>

        </td>

    </tr>
    `;
}

function eliminarCurso(posicion){

    cursos.splice(posicion,1);

    localStorage.setItem(
        "cursos",
        JSON.stringify(cursos)
    );

    location.reload();

}