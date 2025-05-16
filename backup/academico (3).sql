-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-05-2025 a las 23:00:48
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `academico`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`lady`@`localhost` PROCEDURE `asignar_proxima_rotacion` (IN `p_estudiante_id` INT)   BEGIN
    DECLARE ultimo_orden INT;
    DECLARE proxima_especialidad_id INT;
    
    SELECT MAX(e.orden) INTO ultimo_orden
    FROM historial_rotaciones hr
    JOIN especialidades e ON hr.especialidad_id = e.id
    WHERE hr.estudiante_id = p_estudiante_id AND hr.estado = 'completada';
    
    IF ultimo_orden IS NULL THEN
        SET ultimo_orden = 0;
    END IF;
    
    SELECT id INTO proxima_especialidad_id
    FROM especialidades
    WHERE orden > ultimo_orden
    ORDER BY orden ASC
    LIMIT 1;
    
    IF proxima_especialidad_id IS NOT NULL THEN
        INSERT INTO historial_rotaciones (estudiante_id, especialidad_id, fecha_inicio, fecha_fin, estado)
        VALUES (p_estudiante_id, proxima_especialidad_id, CURDATE(), 
                DATE_ADD(CURDATE(), INTERVAL (SELECT duracion_dias FROM especialidades WHERE id = proxima_especialidad_id) DAY), 
                'en_curso');
        
        SELECT CONCAT('Rotación asignada: ', (SELECT nombre FROM especialidades WHERE id = proxima_especialidad_id)) AS mensaje;
    ELSE
        SELECT 'El estudiante ha completado todas las rotaciones requeridas' AS mensaje;
    END IF;
END$$

CREATE DEFINER=`lady`@`localhost` PROCEDURE `completar_rotacion` (IN `p_rotacion_id` INT)   BEGIN
    DECLARE v_estudiante_id INT;
    
    UPDATE historial_rotaciones 
    SET estado = 'completada', fecha_fin = CURDATE()
    WHERE id = p_rotacion_id;
    
    SELECT estudiante_id INTO v_estudiante_id
    FROM historial_rotaciones
    WHERE id = p_rotacion_id;
    
    CALL asignar_proxima_rotacion(v_estudiante_id);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anio_escolar`
--

CREATE TABLE `anio_escolar` (
  `id` int(4) NOT NULL,
  `inicio` datetime NOT NULL,
  `fin` datetime NOT NULL,
  `id_institucion` int(4) NOT NULL,
  `estado` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `anio_escolar`
--

INSERT INTO `anio_escolar` (`id`, `inicio`, `fin`, `id_institucion`, `estado`) VALUES
(1, '2025-01-01 00:00:00', '2025-12-31 00:00:00', 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion_docente`
--

CREATE TABLE `asignacion_docente` (
  `id` int(4) NOT NULL,
  `id_usuario_docente` int(4) NOT NULL,
  `id_anio_escolar` int(4) NOT NULL,
  `id_asignatura` int(4) NOT NULL,
  `id_grupo` int(4) NOT NULL,
  `link_clase_virtual` text DEFAULT NULL,
  `intensidad_horaria` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignacion_docente`
--

INSERT INTO `asignacion_docente` (`id`, `id_usuario_docente`, `id_anio_escolar`, `id_asignatura`, `id_grupo`, `link_clase_virtual`, `intensidad_horaria`) VALUES
(6, 2, 1, 5, 26, 'https://meet.google.com/stj-zhbz-svc', 6),
(7, 8, 1, 3, 28, 'https://meet.google.com/stj-zhbz-svc', 10),
(8, 11, 1, 2, 27, 'https://meet.google.com/stj-zhbz-svc', 30),
(9, 2, 1, 3, 27, 'https://meet.google.com/wzu-uaoa-rgy', 30),
(10, 12, 1, 2, 26, 'https://meet.google.com/aeq-pwjx-tyc', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignatura`
--

CREATE TABLE `asignatura` (
  `id` int(4) NOT NULL,
  `nombre_asignatura` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignatura`
--

INSERT INTO `asignatura` (`id`, `nombre_asignatura`) VALUES
(1, 'Giecología'),
(2, 'Medicina Interna'),
(3, 'Urgencias'),
(4, 'Neonatos'),
(5, 'Cirugía'),
(6, 'Ortopedia'),
(7, 'ELECTIVA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `orden` int(11) NOT NULL,
  `duracion_dias` int(11) DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `especialidades`
--

INSERT INTO `especialidades` (`id`, `nombre`, `orden`, `duracion_dias`) VALUES
(1, 'Urgencias', 1, 30),
(2, 'Ortopedia', 2, 30),
(3, 'Ginecología', 3, 30),
(4, 'Neonatos', 4, 30),
(5, 'Medicina Interna', 5, 30),
(6, 'Cirugía', 6, 30),
(7, 'Electiva', 7, 30),
(8, 'Extramural', 8, 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gestion_cupos`
--

CREATE TABLE `gestion_cupos` (
  `id` int(11) NOT NULL,
  `especialidad` varchar(100) NOT NULL,
  `numero_estudiantes` int(11) NOT NULL,
  `institucion_educativa_id` int(4) DEFAULT NULL,
  `nombre_estudiante` varchar(100) NOT NULL,
  `turno` enum('mañana','tarde') NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `gestion_cupos`
--

INSERT INTO `gestion_cupos` (`id`, `especialidad`, `numero_estudiantes`, `institucion_educativa_id`, `nombre_estudiante`, `turno`, `fecha_registro`) VALUES
(1, 'medicina', 1, 1, 'Carlos arcos', 'mañana', '2025-04-01 05:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grado`
--

CREATE TABLE `grado` (
  `id` int(4) NOT NULL,
  `nombre_grado` varchar(30) NOT NULL,
  `id_institucion` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grado`
--

INSERT INTO `grado` (`id`, `nombre_grado`, `id_institucion`) VALUES
(13, 'Internos Mayores', 2),
(14, 'Internos Menores', 2),
(15, 'Corte 1', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo`
--

CREATE TABLE `grupo` (
  `id` int(4) NOT NULL,
  `nombre_grupo` varchar(30) NOT NULL,
  `id_grado` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grupo`
--

INSERT INTO `grupo` (`id`, `nombre_grupo`, `id_grado`) VALUES
(25, 'A', 13),
(26, 'A', 14),
(27, 'B', 13),
(28, 'B', 14),
(31, 'A', 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo_estudiante`
--

CREATE TABLE `grupo_estudiante` (
  `id` int(4) NOT NULL,
  `id_usuario_estudiante` int(4) NOT NULL,
  `id_grupo` int(4) NOT NULL,
  `id_anio_escolar` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grupo_estudiante`
--

INSERT INTO `grupo_estudiante` (`id`, `id_usuario_estudiante`, `id_grupo`, `id_anio_escolar`) VALUES
(1, 4, 25, 1),
(4, 9, 31, 1),
(5, 4, 27, 1),
(6, 4, 25, 1),
(7, 4, 25, 1),
(8, 4, 28, 1),
(9, 10, 25, 1),
(11, 4, 25, 1),
(13, 9, 26, 1),
(14, 10, 26, 1),
(16, 13, 27, 1),
(17, 10, 26, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_rotaciones`
--

CREATE TABLE `historial_rotaciones` (
  `id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `especialidad_id` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` enum('en_curso','completada') DEFAULT 'en_curso'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_rotaciones`
--

INSERT INTO `historial_rotaciones` (`id`, `estudiante_id`, `especialidad_id`, `fecha_inicio`, `fecha_fin`, `estado`) VALUES
(1, 4, 1, '2025-05-15', '2025-06-14', 'en_curso'),
(2, 4, 1, '2025-05-15', '2025-06-14', 'en_curso'),
(3, 4, 1, '2025-05-15', '2025-06-14', 'en_curso'),
(4, 4, 1, '2025-05-15', '2025-06-14', 'en_curso'),
(5, 4, 1, '2025-05-15', '2025-06-14', 'en_curso'),
(6, 4, 2, '2025-05-15', '2025-06-14', 'en_curso'),
(7, 10, 1, '2025-05-15', '2025-06-14', 'en_curso'),
(8, 16, 1, '2025-05-15', '2025-06-14', 'en_curso'),
(9, 4, 3, '2025-05-15', '2025-06-14', 'en_curso'),
(10, 29, 2, '2025-05-16', '2025-06-16', 'en_curso'),
(11, 4, 3, '2025-05-16', '2025-06-16', 'en_curso'),
(12, 29, 6, '2025-05-16', '2025-05-19', 'en_curso');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inasistencias`
--

CREATE TABLE `inasistencias` (
  `id` int(4) NOT NULL,
  `cantidad` int(4) NOT NULL,
  `justificacion` text DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_modificacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_asignatura` int(4) NOT NULL,
  `registrado_a_estudiante` int(4) NOT NULL,
  `creado_por_docente` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inasistencias`
--

INSERT INTO `inasistencias` (`id`, `cantidad`, `justificacion`, `fecha_creacion`, `fecha_modificacion`, `id_asignatura`, `registrado_a_estudiante`, `creado_por_docente`) VALUES
(1, 2, 'Estudiante falta al internado por accidente laboral.', '2025-04-28 14:51:49', '2025-04-28 14:51:49', 5, 4, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `institucion_educativa`
--

CREATE TABLE `institucion_educativa` (
  `id` int(4) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `email` varchar(60) NOT NULL,
  `nombre_directora` varchar(80) DEFAULT NULL,
  `pagina_web` varchar(100) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT 'Universidad',
  `programas` text DEFAULT NULL,
  `especialidades_medicas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `institucion_educativa`
--

INSERT INTO `institucion_educativa` (`id`, `nombre`, `direccion`, `telefono`, `email`, `nombre_directora`, `pagina_web`, `tipo`, `programas`, `especialidades_medicas`) VALUES
(1, 'INEC', 'Cl. 17 #19-126, Pasto, Nariño', '7310955', 'info@inec.edu.co', 'Claudia Martínez', 'https://institutoinec.com/', 'Instituto', '[{\"tipo\":\"Técnico\",\"nombre\":\"Enfermeria\"}]', 'Auxiliar de enfermería'),
(2, 'Universidad Cooperativa De Colombia campus Pasto', ' Calle 18. 47 - 150 Torobajo, Pasto, Nariño', ' (602) 7370660', 'correspondencia.pas@ucc.edu.co', 'Víctor Hugo Villota Alvarado', 'https://ucc.edu.co/campus-pasto?srsltid=AfmBOookv7S2e-4E5299_v6cvppu-R6Zdn5Bw4HVCJgNKg8nPOC6jRiy', 'Universidad', '[{\"tipo\":\"Pregrado\",\"nombre\":\"Medicina\"}]', 'Medicina General'),
(3, 'Universidad Mariana', 'Calle 18 No. 34 - 104 Pasto (N)', '(602) + 7244460', 'notificacionesjudiciales@umariana.edu.co', 'Liliana Isabel Díaz Cabrera', 'https://www.umariana.edu.co/index.html', 'Universidad', '[{\"tipo\":\"Pregrado\",\"nombre\":\"Nutriciu00f3n\"}]', 'Nutrición'),
(4, 'Hospital Universitario Departamental de Nariño', 'Calle 22 No. 7-93 Parque Bolivar', '6027333400', 'notificacionesjudiciales@hosdenar.gov.co', 'Antonio José Veira del Castillo', 'https://www.hosdenar.gov.co/', 'Hospital', '[{\"tipo\":\"Residencias medicas\",\"nombre\":\"Residencias medicas\"}]', 'Medicina General');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `id` int(4) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `ruta` varchar(200) DEFAULT NULL,
  `tipo` int(2) NOT NULL,
  `es_hijo` int(4) DEFAULT NULL,
  `posicion` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`id`, `nombre`, `ruta`, `tipo`, `es_hijo`, `posicion`) VALUES
(1, 'Institucion', '#', 1, NULL, 1),
(2, 'Universidad', 'principal.php?CONTENIDO=layout/components/institucion/lista-institucion.php', 2, 1, 2),
(3, 'Año escolar', 'principal.php?CONTENIDO=layout/components/anio-escolar/lista-anio.php', 2, 1, 3),
(4, 'Periodo Academico', 'principal.php?CONTENIDO=layout/components/periodo-academico/lista-periodo.php', 2, 1, 4),
(5, 'Grados', 'principal.php?CONTENIDO=layout/components/grado/lista-grado.php', 2, 1, 5),
(6, 'Grupos', 'principal.php?CONTENIDO=layout/components/grupo/lista-grupo.php', 2, 1, 6),
(7, 'Asignatura', 'principal.php?CONTENIDO=layout/components/asignatura/lista-asignatura.php', 1, NULL, 7),
(8, 'Docentes', '#', 1, NULL, 8),
(9, 'Personal Docente', 'principal.php?CONTENIDO=layout/components/docente/lista-docente.php', 2, 8, 9),
(10, 'Asignacion Docente', 'principal.php?CONTENIDO=layout/components/docente/lista-asignacion-docente.php', 2, 8, 10),
(11, 'Estudiantes', '#', 1, NULL, 11),
(12, 'Listado', 'principal.php?CONTENIDO=layout/components/estudiante/lista-estudiante.php', 2, 11, 12),
(13, 'Listado de Grupos', 'principal.php?CONTENIDO=layout/components/estudiante/lista-estudiante-grupo.php', 2, 11, 13),
(14, 'Listar Inasistencias', 'principal.php?CONTENIDO=layout/components/inasistencias/lista-inasistencias.php', 2, 11, 14),
(15, 'Gestionar Inasistencias', 'principal.php?CONTENIDO=layout/components/inasistencias/lista-inasistencias-total.php', 2, 11, 15),
(16, 'Notas', '#', 1, NULL, 16),
(17, 'Gestionar Notas', 'principal.php?CONTENIDO=layout/components/notas/lista-notas.php', 2, 16, 17),
(18, 'Consultar Notas', 'principal.php?CONTENIDO=layout/components/notas/lista-notas-total.php', 2, 16, 18),
(19, 'Imprimir Notas', 'principal.php?CONTENIDO=layout/components/notas/lista-notas-imprimir.php', 2, 16, 19),
(20, 'Tipo de Actividades', 'principal.php?CONTENIDO=layout/components/tipo-actividad/lista-tipo-actividad.php', 2, 16, 20),
(21, 'Gestión de Cupos', '#', 1, NULL, 21),
(22, 'Registro de Cupos', 'principal.php?CONTENIDO=layout/components/gestion-cupos/lista-gestion-cupos.php', 2, 21, 22),
(23, 'Recepción Aulas', '#', 1, NULL, 23),
(24, 'Registro de Aulas', 'principal.php?CONTENIDO=layout/components/recepcion-aulas/lista-recepcion-aulas.php', 2, 23, 24),
(25, 'Registro Biblioteca', 'principal.php?CONTENIDO=layout/components/recepcion-biblioteca/lista-recepcion-biblioteca.php', 2, 23, 25);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nota`
--

CREATE TABLE `nota` (
  `id` int(4) NOT NULL,
  `id_usuario_estudiante` int(4) NOT NULL,
  `id_periodo_academico` int(4) NOT NULL,
  `id_asignatura` int(4) NOT NULL,
  `id_tipo_actividad` int(4) NOT NULL,
  `nota` double DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_modificacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `nota`
--

INSERT INTO `nota` (`id`, `id_usuario_estudiante`, `id_periodo_academico`, `id_asignatura`, `id_tipo_actividad`, `nota`, `fecha_creacion`, `fecha_modificacion`) VALUES
(27, 13, 1, 2, 1, 4, '2025-05-05 11:55:17', '2025-05-05 11:55:17'),
(28, 13, 1, 2, 2, 4, '2025-05-05 11:55:17', '2025-05-05 11:55:17'),
(29, 13, 1, 2, 3, 4.5, '2025-05-05 11:55:17', '2025-05-05 11:55:17'),
(30, 13, 1, 2, 4, 4, '2025-05-05 11:55:17', '2025-05-05 11:55:17'),
(31, 13, 1, 2, 5, 4.5, '2025-05-05 11:55:17', '2025-05-05 11:55:17'),
(32, 10, 1, 1, 1, 4, '2025-05-05 16:12:13', '2025-05-05 16:12:13'),
(33, 10, 1, 1, 2, 4, '2025-05-05 16:12:13', '2025-05-05 16:12:13'),
(34, 10, 1, 1, 3, 4, '2025-05-05 16:12:13', '2025-05-05 16:12:13'),
(35, 10, 1, 1, 4, 4, '2025-05-05 16:12:13', '2025-05-05 16:12:13'),
(36, 10, 1, 1, 5, 4, '2025-05-05 16:12:13', '2025-05-05 16:12:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodo_academico`
--

CREATE TABLE `periodo_academico` (
  `id` int(4) NOT NULL,
  `inicio_periodo` datetime NOT NULL,
  `finalizacion_periodo` datetime NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `id_anio_escolar` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `periodo_academico`
--

INSERT INTO `periodo_academico` (`id`, `inicio_periodo`, `finalizacion_periodo`, `nombre`, `id_anio_escolar`) VALUES
(1, '2025-01-01 00:00:00', '2025-06-30 00:00:00', 'Periodo 1', 1),
(2, '2025-07-01 00:00:00', '2025-12-31 00:00:00', 'Periodo 2', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` int(4) NOT NULL,
  `id_rol` int(4) NOT NULL,
  `id_menu` int(4) NOT NULL,
  `estado` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `id_rol`, `id_menu`, `estado`) VALUES
(1, 6, 1, 1),
(2, 6, 2, 1),
(3, 6, 3, 1),
(4, 6, 4, 1),
(5, 6, 5, 1),
(6, 6, 6, 1),
(7, 6, 7, 1),
(8, 6, 8, 1),
(9, 6, 9, 1),
(10, 6, 10, 1),
(11, 6, 11, 1),
(12, 6, 12, 1),
(13, 6, 13, 1),
(14, 6, 14, 1),
(15, 6, 15, 1),
(16, 6, 16, 1),
(17, 6, 17, 1),
(18, 6, 18, 1),
(19, 6, 19, 1),
(20, 6, 20, 1),
(21, 1, 1, 1),
(22, 1, 2, 1),
(23, 1, 3, 1),
(24, 1, 4, 1),
(25, 1, 5, 1),
(26, 1, 6, 1),
(27, 1, 7, 1),
(28, 1, 8, 1),
(29, 1, 9, 1),
(30, 1, 10, 1),
(31, 1, 11, 1),
(32, 1, 12, 1),
(33, 1, 13, 1),
(34, 1, 14, 1),
(35, 1, 15, 1),
(36, 1, 16, 1),
(37, 1, 17, 1),
(38, 1, 18, 1),
(39, 1, 19, 1),
(40, 1, 20, 1),
(42, 2, 9, 1),
(43, 2, 10, 1),
(44, 2, 11, 1),
(45, 2, 12, 1),
(46, 2, 13, 1),
(47, 2, 14, 1),
(49, 2, 16, 1),
(50, 2, 19, 1),
(51, 2, 20, 1),
(59, 6, 21, 1),
(60, 6, 22, 1),
(61, 1, 21, 1),
(62, 1, 22, 1),
(63, 6, 23, 1),
(64, 6, 24, 1),
(65, 1, 23, 1),
(66, 1, 24, 1),
(69, 6, 25, 1),
(70, 1, 25, 1),
(71, 2, 23, 1),
(72, 2, 24, 1),
(73, 2, 25, 1),
(74, 4, 11, 1),
(76, 4, 16, 1),
(77, 4, 18, 1),
(78, 4, 19, 1),
(79, 4, 23, 1),
(80, 4, 24, 1),
(81, 4, 25, 1),
(82, 3, 11, 1),
(83, 3, 14, 1),
(84, 3, 15, 1),
(85, 3, 16, 1),
(86, 3, 18, 1),
(87, 3, 19, 1),
(88, 3, 14, 1),
(89, 4, 14, 1),
(90, 2, 17, 1),
(91, 2, 20, 1),
(93, 2, 12, 1),
(94, 2, 13, 1),
(95, 2, 15, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recepcion_aulas`
--

CREATE TABLE `recepcion_aulas` (
  `id` int(11) NOT NULL,
  `nombre_aula` varchar(50) NOT NULL,
  `nombre_estudiante` varchar(100) NOT NULL,
  `nombre_docente` varchar(100) NOT NULL,
  `institucion_educativa_id` int(4) DEFAULT NULL,
  `nombre_tema` varchar(200) NOT NULL,
  `semestre` varchar(20) NOT NULL,
  `tiempo_asignado` int(11) DEFAULT NULL,
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recepcion_aulas`
--

INSERT INTO `recepcion_aulas` (`id`, `nombre_aula`, `nombre_estudiante`, `nombre_docente`, `institucion_educativa_id`, `nombre_tema`, `semestre`, `tiempo_asignado`, `fecha_solicitud`, `hora_inicio`, `hora_fin`) VALUES
(1, 'Aula 1', 'Andrés Ceron', 'Martín Caicedo ', 2, 'Proyecto Accidente Cerebro-Vascular', '9° Semestre', 1, '2025-04-25 05:00:00', '07:00:00', '09:00:00'),
(2, 'Aula 2', 'Viviana Montenegro', 'Martin Caicedo', 2, 'acv', '10° Semestre', 0, '2025-04-25 05:00:00', '09:30:00', '11:00:00'),
(3, 'aula 1', 'Angie', 'Pedro', 2, 'maternidad', '9° Semestre', 0, '2025-04-25 05:00:00', '08:00:00', '09:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recepcion_biblioteca`
--

CREATE TABLE `recepcion_biblioteca` (
  `id` int(11) NOT NULL,
  `numero_computadores` int(11) NOT NULL,
  `nombre_proyecto` varchar(200) NOT NULL,
  `numero_estudiantes` int(11) NOT NULL,
  `nombre_estudiantes` text NOT NULL,
  `institucion_educativa_id` int(4) DEFAULT NULL,
  `tiempo_asignado` int(11) DEFAULT NULL,
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recepcion_biblioteca`
--

INSERT INTO `recepcion_biblioteca` (`id`, `numero_computadores`, `nombre_proyecto`, `numero_estudiantes`, `nombre_estudiantes`, `institucion_educativa_id`, `tiempo_asignado`, `fecha_solicitud`, `hora_inicio`, `hora_fin`) VALUES
(1, 5, 'SIDGA - SISTEMA INTEGRAL DE DOCENCIA Y GESTIÓN ACADÉMICA', 2, 'Yamile Revelo, Jairo Arteaga', 2, 2, '2025-04-28 05:00:00', '09:30:00', '11:30:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(4) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `valor` char(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `valor`) VALUES
(1, 'Secretaria', 'S'),
(2, 'Docente', 'D'),
(3, 'Acudiente', 'A'),
(4, 'Estudiante', 'E'),
(5, 'Desconocido', 'N'),
(6, 'Root', 'R');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_actividad`
--

CREATE TABLE `tipo_actividad` (
  `id` int(4) NOT NULL,
  `nombre_actividad` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_actividad`
--

INSERT INTO `tipo_actividad` (`id`, `nombre_actividad`) VALUES
(1, 'Competencia Cognitiva'),
(2, 'Competencia Aptitudinal'),
(3, 'Competencia Actitudinal'),
(4, 'Competencia Comunicativa'),
(5, 'Competencia Cognitiva Examen Final');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(4) NOT NULL,
  `identificacion` varchar(15) NOT NULL,
  `nombres` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `telefono` varchar(10) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `direccion` varchar(30) DEFAULT NULL,
  `hoja_vida` varchar(100) DEFAULT NULL,
  `documentos` varchar(100) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `clave` varchar(40) DEFAULT NULL,
  `rol_id` int(4) NOT NULL,
  `institucion_educativa_id` int(4) DEFAULT NULL,
  `estado` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `identificacion`, `nombres`, `apellidos`, `telefono`, `email`, `direccion`, `hoja_vida`, `documentos`, `foto`, `clave`, `rol_id`, `institucion_educativa_id`, `estado`) VALUES
(1, '100100', 'Martha', 'Ordoñez', '12344321', 'test0@gmail.com', 'Cll. 19 # 3 - 1', 'hv_martha.pdf', 'doc_martha.pdf', 'foto_martha.jpg', '202cb962ac59075b964b07152d234b70', 1, NULL, 1),
(2, '100101', 'Carlos', 'González', '3123456789', 'carlos@correo.com', 'Calle 15 # 5-10', '', '', '', '202cb962ac59075b964b07152d234b70', 2, 2, 1),
(3, '100102', 'Luisa', 'Peralta', '12344321', 'test2@gmail.com', 'Cll. 19 # 3 - 1', 'hv_tipo.pdf', 'doc_luisa.pdf', 'foto_tipo.jpg', '202cb962ac59075b964b07152d234b70', 3, NULL, 1),
(4, '100103', 'Julian', 'Zambrano', '12344321', 'test3@gmail.com', 'Cll. 19 # 3 - 1', '100103.pdf', '100103.pdf', '4_100103.jpg', 'd9b1d7db4cd6e70935368a1efb10e377', 4, 3, 1),
(5, '100104', 'Desconocido', 'Desconocido', '12344321', 'test4@gmail.com', 'Cll. 19 # 3 - 1', 'hv_desconocido.pdf', 'doc_datos.pdf', 'foto_desconocido.jpg', '202cb962ac59075b964b07152d234b70', 5, NULL, 1),
(6, '100105', 'Super', 'Admin', '12344321', 'test5@gmail.com', 'Cll. 19 # 3 - 1', 'hv_admin.pdf', 'doc_archivos.pdf', 'foto_admin.jpg', '202cb962ac59075b964b07152d234b70', 6, NULL, 1),
(8, '30456879', 'Alvaro', 'Portilla', '3105462532', 'AndresReina@gmail.com', 'Carrera 15 # 21-34 Centro', '', '', '', '05a4a94dbc5f4df89dcf8beb8db10313', 2, 2, 1),
(9, '12895457', 'Carlos ', 'Torres', '3114875496', 'CarlosT@hotmail.com', 'Carrera 27 con 15', '12895457.pdf', '12895457.pdf', '9_12895457.png', '97c0009bd282eaeeb7995e4c50bd9b52', 4, 4, 1),
(10, '123456789', 'Camila', 'Benavides', '3125469874', 'Cami@gmail.com', 'carrera 21 # 12-34', '123456789.pdf', '123456789.pdf', '10_123456789.jpg', '70873e8580c9900986939611618d7b1e', 4, 2, 1),
(11, '57897650', 'David Alejandro', 'Perez', '3234586957', 'Alejandro123@gmail.com', 'Mz 23 casa 9 Barrio Mariluz', '', '', '', '5cc6865775b888a633b561efd33a0afd', 2, 3, 1),
(12, '100200300', 'Diego', 'Taramuel', '3156458789', 'TaramuelDiego@gmail.com', 'Calle 23 # 15-51 Barrio Chapal', '', '', '', '6dbd4bf032fe78279a0fd303a0470b33', 2, 0, 1),
(13, '12992835', 'Jairo', 'Bastidas', '3165546040', 'jair@gmail.com', 'call 10 23-21', '12992835.pdf', '12992835.pdf', '13_12992835.png', 'e08312d04c12b63cd2737b4eef245a63', 4, 3, 1),
(16, '12865547', 'Tulio', 'Rosero', '3156882754', 'servio@gmail.com', 'Calle 23 # 17-09', '12865547.pdf', '12865547.pdf', '16_12865547.png', 'c8009a2f3ee98350d835986f5a120280', 4, 1, 1),
(18, '30734646', 'Carmen', 'Revelo', '3176374890', 'reveloarteaga@gmail.com', 'calle23 # 17-06', '30734646.pdf', '30734646.pdf', '18_30734646.png', '273acb19e8f60ed57272f0df48f1cb41', 4, 4, 1),
(29, '1085382742', 'Yamile', 'Revelo', '3168155993', 'yamiler@gmail.com', 'Calle 17 Cantro', '1085382742.pdf', '1085382742.pdf', '29_1085382742.jpeg', 'eb918ca0b5c7e2401deaaabce761f5a1', 4, 4, 1),
(30, '57852451', 'Maria', 'Botina', '3102564585', 'maria@gmail.com', 'laguna de la cocha', '57852451.pdf', '57852451.pdf', '30_57852451.jpg', '3a2d542409232b4ffdb27ba78d757048', 4, 4, 1),
(31, '98745897', 'Jesús', 'Rosero', '7213852', 'Jesus@gmail.com', 'San Fernando', '98745897.pdf', '98745897.pdf', '31_98745897.png', 'c5f73b18bbdc46787a37fb75164f0f71', 4, 4, 1),
(32, '123654789', 'Aristoteles', 'Perruna', '7313125', 'Ari@hotmail.com', 'Centro', '123654789.pdf', '123654789.pdf', '32_123654789.png', 'df2ac1b08b98301953e6efe6fa49dd8b', 4, 1, 1),
(51, '1004854965', 'Maria', 'Botina', '3102564585', 'maria@gmail.com', 'laguna de la cocha', '1004854965.pdf', '1004854965.pdf', '51_1004854965.png', '51f2c95b5ef577e1b33fec6f9c12d53c', 4, 2, 1),
(58, '57897456', 'Juan', 'Chavez', '3114568789', 'Juancho@hotmail.com', 'Torres de Mariluz', '57897456.pdf', '57897456.pdf', '58_57897456.jpeg', '8bb7b0f3db770f0802d62be208e6d747', 4, 3, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `anio_escolar`
--
ALTER TABLE `anio_escolar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_institucion` (`id_institucion`);

--
-- Indices de la tabla `asignacion_docente`
--
ALTER TABLE `asignacion_docente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario_docente` (`id_usuario_docente`),
  ADD KEY `id_anio_escolar` (`id_anio_escolar`),
  ADD KEY `id_asignatura` (`id_asignatura`),
  ADD KEY `id_grupo` (`id_grupo`);

--
-- Indices de la tabla `asignatura`
--
ALTER TABLE `asignatura`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orden` (`orden`);

--
-- Indices de la tabla `gestion_cupos`
--
ALTER TABLE `gestion_cupos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `grado`
--
ALTER TABLE `grado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_institucion` (`id_institucion`);

--
-- Indices de la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_grado` (`id_grado`);

--
-- Indices de la tabla `grupo_estudiante`
--
ALTER TABLE `grupo_estudiante`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario_estudiante` (`id_usuario_estudiante`),
  ADD KEY `id_grupo` (`id_grupo`),
  ADD KEY `id_anio_escolar` (`id_anio_escolar`);

--
-- Indices de la tabla `historial_rotaciones`
--
ALTER TABLE `historial_rotaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estudiante_id` (`estudiante_id`),
  ADD KEY `especialidad_id` (`especialidad_id`);

--
-- Indices de la tabla `inasistencias`
--
ALTER TABLE `inasistencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_asignatura` (`id_asignatura`),
  ADD KEY `registrado_a_estudiante` (`registrado_a_estudiante`),
  ADD KEY `creado_por_docente` (`creado_por_docente`);

--
-- Indices de la tabla `institucion_educativa`
--
ALTER TABLE `institucion_educativa`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `es_hijo` (`es_hijo`);

--
-- Indices de la tabla `nota`
--
ALTER TABLE `nota`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario_estudiante` (`id_usuario_estudiante`),
  ADD KEY `id_asignatura` (`id_asignatura`),
  ADD KEY `id_periodo_academico` (`id_periodo_academico`),
  ADD KEY `id_tipo_actividad` (`id_tipo_actividad`);

--
-- Indices de la tabla `periodo_academico`
--
ALTER TABLE `periodo_academico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_anio_escolar` (`id_anio_escolar`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_rol` (`id_rol`,`id_menu`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indices de la tabla `recepcion_aulas`
--
ALTER TABLE `recepcion_aulas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `recepcion_biblioteca`
--
ALTER TABLE `recepcion_biblioteca`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_actividad`
--
ALTER TABLE `tipo_actividad`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identificacion` (`identificacion`),
  ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `anio_escolar`
--
ALTER TABLE `anio_escolar`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `asignacion_docente`
--
ALTER TABLE `asignacion_docente`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `asignatura`
--
ALTER TABLE `asignatura`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `gestion_cupos`
--
ALTER TABLE `gestion_cupos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `grado`
--
ALTER TABLE `grado`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `grupo`
--
ALTER TABLE `grupo`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `grupo_estudiante`
--
ALTER TABLE `grupo_estudiante`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `historial_rotaciones`
--
ALTER TABLE `historial_rotaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `inasistencias`
--
ALTER TABLE `inasistencias`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `institucion_educativa`
--
ALTER TABLE `institucion_educativa`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `nota`
--
ALTER TABLE `nota`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `periodo_academico`
--
ALTER TABLE `periodo_academico`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT de la tabla `recepcion_aulas`
--
ALTER TABLE `recepcion_aulas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `recepcion_biblioteca`
--
ALTER TABLE `recepcion_biblioteca`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tipo_actividad`
--
ALTER TABLE `tipo_actividad`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `anio_escolar`
--
ALTER TABLE `anio_escolar`
  ADD CONSTRAINT `anio_escolar_ibfk_1` FOREIGN KEY (`id_institucion`) REFERENCES `institucion_educativa` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `asignacion_docente`
--
ALTER TABLE `asignacion_docente`
  ADD CONSTRAINT `asignacion_docente_ibfk_1` FOREIGN KEY (`id_usuario_docente`) REFERENCES `usuario` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `asignacion_docente_ibfk_2` FOREIGN KEY (`id_anio_escolar`) REFERENCES `anio_escolar` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `asignacion_docente_ibfk_3` FOREIGN KEY (`id_asignatura`) REFERENCES `asignatura` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `asignacion_docente_ibfk_4` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `grado`
--
ALTER TABLE `grado`
  ADD CONSTRAINT `grado_ibfk_1` FOREIGN KEY (`id_institucion`) REFERENCES `institucion_educativa` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD CONSTRAINT `grupo_ibfk_1` FOREIGN KEY (`id_grado`) REFERENCES `grado` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `grupo_estudiante`
--
ALTER TABLE `grupo_estudiante`
  ADD CONSTRAINT `grupo_estudiante_ibfk_1` FOREIGN KEY (`id_usuario_estudiante`) REFERENCES `usuario` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `grupo_estudiante_ibfk_2` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `grupo_estudiante_ibfk_3` FOREIGN KEY (`id_anio_escolar`) REFERENCES `anio_escolar` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `historial_rotaciones`
--
ALTER TABLE `historial_rotaciones`
  ADD CONSTRAINT `historial_rotaciones_ibfk_1` FOREIGN KEY (`estudiante_id`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `historial_rotaciones_ibfk_2` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidades` (`id`);

--
-- Filtros para la tabla `inasistencias`
--
ALTER TABLE `inasistencias`
  ADD CONSTRAINT `inasistencias_ibfk_1` FOREIGN KEY (`id_asignatura`) REFERENCES `asignatura` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inasistencias_ibfk_2` FOREIGN KEY (`registrado_a_estudiante`) REFERENCES `usuario` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inasistencias_ibfk_3` FOREIGN KEY (`creado_por_docente`) REFERENCES `usuario` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`es_hijo`) REFERENCES `menu` (`id`);

--
-- Filtros para la tabla `nota`
--
ALTER TABLE `nota`
  ADD CONSTRAINT `nota_ibfk_1` FOREIGN KEY (`id_usuario_estudiante`) REFERENCES `usuario` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `nota_ibfk_2` FOREIGN KEY (`id_asignatura`) REFERENCES `asignatura` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `nota_ibfk_3` FOREIGN KEY (`id_periodo_academico`) REFERENCES `periodo_academico` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `nota_ibfk_4` FOREIGN KEY (`id_tipo_actividad`) REFERENCES `tipo_actividad` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `periodo_academico`
--
ALTER TABLE `periodo_academico`
  ADD CONSTRAINT `periodo_academico_ibfk_1` FOREIGN KEY (`id_anio_escolar`) REFERENCES `anio_escolar` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `permisos_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `permisos_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
