class SistemaNavegacion {
  constructor() {
    this.base = '/' + window.location.pathname.split('/')[1];
    this.usuario = JSON.parse(localStorage.getItem("usuarioActivo"));
    this.verificarSesion();
    this.inicializarNavbar();
  }

  verificarSesion() {
    const ruta = window.location.pathname;
    if (!this.usuario && !ruta.includes('index.html') && !ruta.includes('registro.html') && !ruta.endsWith('/')) {
        window.location.replace(this.base + "/index.html");
    }
  }

  inicializarNavbar() {
    const ruta = window.location.pathname;
    if (ruta.includes('index.html') || ruta.includes('registro.html') || ruta.endsWith('/')) return;

    const navHTML = `
      <nav class="navbar navbar-expand-lg navbar-dark sticky-top px-4 py-3 shadow" style="background-color: #0f172a !important; border-bottom: 3px solid #3b82f6;">
        <div class="container-fluid">
          
          <a class="navbar-brand fw-bold text-white fs-4" href="${this.base}/Vista/dashboard.html">
            Plataforma <span style="color: #3b82f6;">DAW</span>
          </a>
          
          <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-medium gap-1">
              <li class="nav-item">
                <a class="nav-link px-3 rounded-pill text-light opacity-75 hover-menu" href="${this.base}/Vista/dashboard.html">Dashboard</a>
              </li>
              <li class="nav-item">
                <a class="nav-link px-3 rounded-pill text-light opacity-75 hover-menu" href="${this.base}/Vista/usuarios/lista_usuarios.html">Usuarios</a>
              </li>
              <li class="nav-item">
                <a class="nav-link px-3 rounded-pill text-light opacity-75 hover-menu" href="${this.base}/Vista/cursos/lista_cursos.html">Cursos</a>
              </li>
              <li class="nav-item">
                <a class="nav-link px-3 rounded-pill text-light opacity-75 hover-menu" href="${this.base}/Vista/tareas/lista_tareas.html">Tareas</a>
              </li>
              <li class="nav-item">
                <a class="nav-link px-3 rounded-pill text-light opacity-75 hover-menu" href="${this.base}/Vista/evaluaciones/lista_examenes.html">Evaluaciones</a>
              </li>
              <li class="nav-item">
                <a class="nav-link px-3 rounded-pill text-light opacity-75 hover-menu" href="${this.base}/Vista/seguimiento/progreso.html">Seguimiento</a>
              </li>
            </ul>

            <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                <div class="d-flex flex-column text-end d-none d-lg-block">
                    <span class="text-white fw-bold d-block" style="font-size: 0.9rem; line-height: 1.2;">${this.usuario?.nombre || 'Invitado'}</span>
                    <span class="badge rounded-pill mt-1" style="font-size: 0.75rem; background-color: rgba(59, 130, 246, 0.2); color: #60a5fa;">${this.usuario?.rol || ''}</span>
                </div>
                
                <div class="avatar-circle shadow-sm">
                    ${this.usuario?.nombre ? this.usuario.nombre.charAt(0).toUpperCase() : 'U'}
                </div>
                
                <button onclick="cerrarSesion()" class="btn btn-outline-danger btn-sm rounded-pill fw-bold px-4 ms-2">Salir</button>
            </div>
          </div>
        </div>
      </nav>

      <style>
        .hover-menu {
            transition: all 0.2s ease-in-out;
        }
        .hover-menu:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
            opacity: 1 !important;
        }
        .avatar-circle {
            width: 40px;
            height: 40px;
            background-color: #3b82f6;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 700;
            font-size: 1.1rem;
            text-align: center;
            border: 2px solid rgba(255, 255, 255, 0.2);
            user-select: none;
        }
      </style>
    `;
    
    document.body.insertAdjacentHTML('afterbegin', navHTML);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  new SistemaNavegacion();
});

function cerrarSesion() {
  if (confirm('¿Está seguro de que desea cerrar sesión en la plataforma?')) {
    localStorage.removeItem("usuarioActivo");
    localStorage.clear();
    let base = '/' + window.location.pathname.split('/')[1];
    window.location.replace(base + "/index.html");
  }
}

document.addEventListener("DOMContentLoaded", function () {
    let rutaActual = window.location.pathname;
    let esPaginaPublica = rutaActual.includes("index.html") || rutaActual.includes("registro.html") || rutaActual.endsWith("/");
    let usuarioActivo = JSON.parse(localStorage.getItem("usuarioActivo"));
    let base = '/' + window.location.pathname.split('/')[1];

    if (usuarioActivo && esPaginaPublica) {
        window.location.replace(base + "/Vista/dashboard.html");
    }
});