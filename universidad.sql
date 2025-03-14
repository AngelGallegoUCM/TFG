-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-03-2025 a las 12:18:37
-- Versión del servidor: 10.4.22-MariaDB
-- Versión de PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `universidad`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaturas`
--

CREATE TABLE `asignaturas` (
  `id` int(11) NOT NULL,
  `profesor_id` int(11) NOT NULL,
  `aula_id` int(11) NOT NULL,
  `nombre_asignatura` varchar(100) NOT NULL,
  `grupo` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `asignaturas`
--

INSERT INTO `asignaturas` (`id`, `profesor_id`, `aula_id`, `nombre_asignatura`, `grupo`) VALUES
(1, 1, 1, 'Programación', '1ºA'),
(2, 2, 2, 'Álgebra', '1ºB'),
(6, 5, 1, 'Fal', '4ºB'),
(7, 5, 1, 'SO', '4ºB');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `id` int(11) NOT NULL,
  `asignatura_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `presente` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `asistencias`
--

INSERT INTO `asistencias` (`id`, `asignatura_id`, `fecha`, `presente`) VALUES
(5, 1, '2025-03-03', 0),
(6, 6, '2025-03-14', 0),
(7, 7, '2025-03-14', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aulas`
--

CREATE TABLE `aulas` (
  `id` int(11) NOT NULL,
  `numero_aula` int(11) NOT NULL,
  `capacidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `aulas`
--

INSERT INTO `aulas` (`id`, `numero_aula`, `capacidad`) VALUES
(1, 101, 25),
(2, 102, 25),
(3, 103, 40);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE `departamento` (
  `id` int(11) NOT NULL,
  `nombre_departamento` varchar(100) NOT NULL,
  `jefe_id` int(11) DEFAULT NULL,
  `correo_departamento` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `departamento`
--

INSERT INTO `departamento` (`id`, `nombre_departamento`, `jefe_id`, `correo_departamento`) VALUES
(1, 'Computadores', 1, 'computadores@complutense.es'),
(2, 'Redes', 2, 'redes@complutense.es'),
(3, 'Software', 3, 'software@complutense.es');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id` int(11) NOT NULL,
  `asignatura_id` int(11) NOT NULL,
  `dia_semana` enum('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo') NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `horarios`
--

INSERT INTO `horarios` (`id`, `asignatura_id`, `dia_semana`, `hora_inicio`, `hora_fin`) VALUES
(1, 1, 'Lunes', '09:00:00', '11:00:00'),
(9, 2, 'Martes', '10:00:00', '12:00:00'),
(10, 2, 'Jueves', '15:00:00', '16:00:00'),
(11, 6, 'Viernes', '14:00:00', '16:00:00'),
(12, 7, 'Viernes', '16:00:00', '18:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incidencias`
--

CREATE TABLE `incidencias` (
  `id` int(11) NOT NULL,
  `asistencia_id` int(11) NOT NULL,
  `justificada` tinyint(1) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_incidencia` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `incidencias`
--

INSERT INTO `incidencias` (`id`, `asistencia_id`, `justificada`, `descripcion`, `fecha_incidencia`) VALUES
(8, 6, 1, 'Medico\\r\\n', '2025-03-14 11:56:22'),
(9, 7, 0, 'Falta de asistencia no justificada', '2025-03-14 11:56:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nolectivo`
--

CREATE TABLE `nolectivo` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` varchar(255) DEFAULT 'Día restringido'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `nolectivo`
--

INSERT INTO `nolectivo` (`id`, `fecha`, `descripcion`) VALUES
(2, '2025-03-11', 'Prueba');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `CorreoPropio` varchar(100) DEFAULT NULL,
  `departamento_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`id`, `nombre`, `apellidos`, `CorreoPropio`, `departamento_id`) VALUES
(1, 'Raquel', 'Díaz Sánchez', 'raquel.diaz@complutense.es', 1),
(2, 'Carlos', 'López García', 'carlos.lopez@complutense.es', 2),
(3, 'Maríant', 'Pérez Gómez', 'maria.perez@complutense.es', 3),
(5, 'Ángel', 'Gallego', 'anggal02@ucm.es', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aula_id` (`aula_id`),
  ADD KEY `asignaturas_ibfk_1` (`profesor_id`);

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asignatura_id` (`asignatura_id`);

--
-- Indices de la tabla `aulas`
--
ALTER TABLE `aulas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asignatura_id` (`asignatura_id`);

--
-- Indices de la tabla `incidencias`
--
ALTER TABLE `incidencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `incidencias_ibfk_1` (`asistencia_id`);

--
-- Indices de la tabla `nolectivo`
--
ALTER TABLE `nolectivo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fecha` (`fecha`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departamento_id` (`departamento_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `aulas`
--
ALTER TABLE `aulas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `departamento`
--
ALTER TABLE `departamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `incidencias`
--
ALTER TABLE `incidencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `nolectivo`
--
ALTER TABLE `nolectivo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `profesores`
--
ALTER TABLE `profesores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  ADD CONSTRAINT `asignaturas_ibfk_1` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `asignaturas_ibfk_2` FOREIGN KEY (`aula_id`) REFERENCES `aulas` (`id`);

--
-- Filtros para la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`);

--
-- Filtros para la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`);

--
-- Filtros para la tabla `incidencias`
--
ALTER TABLE `incidencias`
  ADD CONSTRAINT `incidencias_ibfk_1` FOREIGN KEY (`asistencia_id`) REFERENCES `asistencias` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD CONSTRAINT `profesores_ibfk_1` FOREIGN KEY (`departamento_id`) REFERENCES `departamento` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
