-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-07-2026 a las 05:27:19
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `plataforma_daw`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id_curso` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `docente` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id_curso`, `nombre`, `categoria`, `docente`, `descripcion`, `fecha_creacion`) VALUES
(1, 'Desarrollo Web Frontend', 'Desarrollo Web', 'Ingeniera Responsable', 'Curso completo sobre HTML5, CSS3, Bootstrap 5 y arquitectura MVC.', '2026-07-11 00:09:05'),
(2, 'Bases de Datos SQL', 'Bases de Datos', 'Profesor de Redes', 'Diseño y optimización de bases de datos relacionales utilizando MySQL.', '2026-07-11 00:09:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examenes`
--

CREATE TABLE `examenes` (
  `id_examen` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `curso` varchar(150) NOT NULL,
  `fecha` date NOT NULL,
  `duracion` int(11) NOT NULL,
  `instrucciones` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `examenes`
--

INSERT INTO `examenes` (`id_examen`, `titulo`, `curso`, `fecha`, `duracion`, `instrucciones`, `fecha_creacion`) VALUES
(1, 'Evaluación Parcial de JavaScript', 'Desarrollo Web Frontend', '2026-07-20', 45, 'Lea con atención cada pregunta. El examen cuenta con un temporizador automático. Evite salir de la pestaña.', '2026-07-11 00:17:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `progreso`
--

CREATE TABLE `progreso` (
  `id_progreso` int(11) NOT NULL,
  `nombre_alumno` varchar(100) NOT NULL,
  `curso` varchar(150) NOT NULL,
  `promedio` decimal(4,2) NOT NULL,
  `porcentaje_avance` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `progreso`
--

INSERT INTO `progreso` (`id_progreso`, `nombre_alumno`, `curso`, `promedio`, `porcentaje_avance`, `estado`) VALUES
(1, 'Juan Pérez', 'Desarrollo Web Frontend', 9.50, 100, 'Aprobado'),
(2, 'María Gómez', 'Bases de Datos SQL', 8.00, 60, 'En Curso'),
(3, 'Carlos Ruiz', 'Redes y Comunicaciones', 5.50, 30, 'Riesgo Académico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `id_tarea` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `curso` varchar(150) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_limite` date NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tareas`
--

INSERT INTO `tareas` (`id_tarea`, `titulo`, `curso`, `descripcion`, `fecha_limite`, `fecha_creacion`) VALUES
(1, 'Implementación de MVC y AJAX', 'Desarrollo Web Frontend', 'Migrar el sistema usando PHP, PDO y fetch API.', '2023-12-31', '2026-07-11 00:12:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `rol` enum('Alumno','Docente') NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `correo`, `clave`, `rol`, `fecha_registro`) VALUES
(1, 'Administrador General', 'admin@daw.com', '12345', 'Docente', '2026-07-10 23:59:46');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_curso`);

--
-- Indices de la tabla `examenes`
--
ALTER TABLE `examenes`
  ADD PRIMARY KEY (`id_examen`);

--
-- Indices de la tabla `progreso`
--
ALTER TABLE `progreso`
  ADD PRIMARY KEY (`id_progreso`);

--
-- Indices de la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`id_tarea`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `examenes`
--
ALTER TABLE `examenes`
  MODIFY `id_examen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `progreso`
--
ALTER TABLE `progreso`
  MODIFY `id_progreso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `id_tarea` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;


-- --------------------------------------------------------
-- Estructura de tabla para la tabla `preguntas_examen`
-- --------------------------------------------------------

CREATE TABLE `preguntas_examen` (
  `id_pregunta` int(11) NOT NULL AUTO_INCREMENT,
  `id_examen` int(11) NOT NULL,
  `enunciado` text NOT NULL,
  `orden` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_pregunta`),
  KEY `idx_preguntas_examen` (`id_examen`),
  CONSTRAINT `fk_preguntas_examen`
    FOREIGN KEY (`id_examen`) REFERENCES `examenes` (`id_examen`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `preguntas_examen`
--

INSERT INTO `preguntas_examen` (`id_pregunta`, `id_examen`, `enunciado`, `orden`) VALUES
(1, 1, '¿Qué palabra clave permite declarar una variable cuyo valor puede reasignarse?', 1),
(2, 1, '¿Qué método convierte una cadena JSON en un objeto de JavaScript?', 2),
(3, 1, '¿Qué API de JavaScript permite realizar peticiones HTTP asíncronas?', 3);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `respuestas_examen`
-- --------------------------------------------------------

CREATE TABLE `respuestas_examen` (
  `id_respuesta` int(11) NOT NULL AUTO_INCREMENT,
  `id_pregunta` int(11) NOT NULL,
  `texto_respuesta` text NOT NULL,
  `es_correcta` tinyint(1) NOT NULL DEFAULT 0,
  `orden` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_respuesta`),
  KEY `idx_respuestas_pregunta` (`id_pregunta`),
  CONSTRAINT `fk_respuestas_pregunta`
    FOREIGN KEY (`id_pregunta`) REFERENCES `preguntas_examen` (`id_pregunta`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `respuestas_examen`
-- Una sola respuesta correcta por cada pregunta.
--

INSERT INTO `respuestas_examen` (`id_respuesta`, `id_pregunta`, `texto_respuesta`, `es_correcta`, `orden`) VALUES
(1, 1, 'let', 1, 1),
(2, 1, 'const', 0, 2),
(3, 1, 'function', 0, 3),
(4, 1, 'class', 0, 4),
(5, 2, 'JSON.parse()', 1, 1),
(6, 2, 'JSON.stringify()', 0, 2),
(7, 2, 'JSON.object()', 0, 3),
(8, 2, 'JSON.convert()', 0, 4),
(9, 3, 'fetch()', 1, 1),
(10, 3, 'console.log()', 0, 2),
(11, 3, 'document.write()', 0, 3),
(12, 3, 'alert()', 0, 4);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
