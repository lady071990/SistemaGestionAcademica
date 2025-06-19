--
-- CREATE DATABASE academico;
-- USE academico;
--
-- CREAR TABLA instinstitucion_educativa
--
CREATE TABLE institucion_educativa (
    id INT(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    direccion VARCHAR(100) NULL,
    telefono VARCHAR(15) NULL,
    email VARCHAR(60) NOT NULL,
    nombre_directora VARCHAR(80) NULL,
    pagina_web VARCHAR(100) NULL,
    tipo VARCHAR(50) NULL DEFAULT 'Universidad',
    programas TEXT NULL,
    especialidades_medicas TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
--
-- CREAR TABLA roles
--
CREATE TABLE roles (
    id INT(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(20) NOT NULL,
    valor CHAR(3) NOT NULL
);
--
-- CREAR TABLA usuario
--
CREATE TABLE usuario (
    id INT(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    identificacion varchar(15) NOT NULL UNIQUE,
    nombres varchar(50) NOT NULL,
    apellidos varchar(50) NOT NULL,
    telefono varchar(10) NULL,
    email varchar(50) NOT NULL,
    direccion varchar(30) DEFAULT NULL,
    hoja_vida VARCHAR(100) DEFAULT NULL,
    documentos VARCHAR(100) DEFAULT NULL,
    foto VARCHAR(100) DEFAULT NULL,
    clave varchar(40) DEFAULT NULL,
    rol_id INT(4) NOT NULL,
    estado INT(1) NOT NULL,
    INDEX (rol_id),
    FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE RESTRICT ON UPDATE CASCADE
);
--
--
-- CREAR TABLA anio_escolar
--
CREATE TABLE anio_escolar (
    id int(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    inicio DATETIME NOT NULL,
    fin DATETIME NOT NULL,
    id_institucion INT(4) NOT NULL,
    estado INT(4) NOT NULL,
    FOREIGN KEY (id_institucion) REFERENCES institucion_educativa(id) ON DELETE RESTRICT ON UPDATE CASCADE
);
--
--
-- CREAR TABLA periodo_academico
--
CREATE TABLE periodo_academico (
    id int(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    inicio_periodo DATETIME NOT NULL,
    finalizacion_periodo DATETIME NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    id_anio_escolar INT(4) NOT NULL,
    FOREIGN KEY (id_anio_escolar) REFERENCES anio_escolar(id) ON DELETE RESTRICT ON UPDATE CASCADE
);
--
-- CREAR TABLA grado
--
CREATE TABLE grado (
    id int(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre_grado varchar(30) NOT NULL,
    id_institucion INT(4) NOT NULL,
    FOREIGN KEY (id_institucion) REFERENCES institucion_educativa(id) ON DELETE RESTRICT ON UPDATE CASCADE
);
--
-- CREAR TABLA grupo
--
CREATE TABLE grupo (
    id int(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre_grupo varchar(30) NOT NULL,
    id_grado INT(4) NOT NULL,
    FOREIGN KEY (id_grado) REFERENCES grado(id) ON DELETE RESTRICT ON UPDATE CASCADE
);
--
--
-- CREAR TABLA asignatura
--
CREATE TABLE asignatura(
    id int(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre_asignatura varchar(30) NOT NULL
);
--
--
-- CREAR TABLA grupo_estudiante
--
CREATE TABLE grupo_estudiante (
    id INT(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario_estudiante INT(4) NOT NULL,
    id_grupo INT(4) NOT NULL,
    id_anio_escolar INT(4) NOT NULL,
    FOREIGN KEY (id_usuario_estudiante) REFERENCES usuario(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_grupo) REFERENCES grupo(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_anio_escolar) REFERENCES anio_escolar(id) ON DELETE RESTRICT ON UPDATE CASCADE
);
--
--
-- CREAR TABLA asignacion_docente
--
CREATE TABLE asignacion_docente (
    id INT(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario_docente INT(4) NOT NULL,
    id_anio_escolar INT(4) NOT NULL,
    id_asignatura INT(4) NOT NULL,
    id_grupo INT(4) NOT NULL,
    link_clase_virtual TEXT NULL,
    intensidad_horaria DOUBLE NULL,
    FOREIGN KEY (id_usuario_docente) REFERENCES usuario(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_anio_escolar) REFERENCES anio_escolar(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_asignatura) REFERENCES asignatura(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_grupo) REFERENCES grupo(id) ON DELETE RESTRICT ON UPDATE CASCADE
);
--
--
-- CREAR TABLA inasistencias
--
CREATE TABLE inasistencias (
    id INT(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    cantidad INT(4) NOT NULL,
    justificacion TEXT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    id_asignatura INT(4) NOT NULL,
    registrado_a_estudiante INT(4) NOT NULL,
    creado_por_docente INT(4) NOT NULL,
    FOREIGN KEY (id_asignatura) REFERENCES asignatura(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (registrado_a_estudiante) REFERENCES usuario(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (creado_por_docente) REFERENCES usuario(id) ON DELETE RESTRICT ON UPDATE CASCADE
);
--
--
-- CREAR TABLA tipo_actividad
--
CREATE TABLE tipo_actividad (
    id INT(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre_actividad VARCHAR(200) NOT NULL
);
--
--
-- CREAR TABLA nota
--
CREATE TABLE nota (
    id INT(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario_estudiante INT(4) NOT NULL,
    id_periodo_academico INT(4) NOT NULL,
    id_asignatura INT(4) NOT NULL,
    id_tipo_actividad INT(4) NOT NULL,
    nota DOUBLE NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario_estudiante) REFERENCES usuario(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_asignatura) REFERENCES asignatura(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_periodo_academico) REFERENCES periodo_academico(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_tipo_actividad) REFERENCES tipo_actividad(id) ON DELETE RESTRICT ON UPDATE CASCADE
);
--
--
-- CREAR TABLA menu
--
CREATE TABLE menu (
    id int(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(60) NOT NULL,
    ruta VARCHAR(200) NULL,
    tipo INT(2) NOT NULL,
    es_hijo INT(4) NULL,
    posicion INT(4) NOT NULL,
    INDEX (es_hijo),
    FOREIGN KEY (es_hijo) REFERENCES menu(id)
);
--
--
-- CREAR TABLA permisos
--
CREATE TABLE permisos (
    id INT(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_rol INT(4) NOT NULL,
    id_menu INT(4) NOT NULL,
    estado INT(1) NOT NULL,
    INDEX (id_rol, id_menu),
    FOREIGN KEY (id_rol) REFERENCES roles(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_menu) REFERENCES menu(id) ON DELETE RESTRICT ON UPDATE CASCADE
);
--
--
--
--
--/*************************************************************************************************/
--
--
--
-- LLENADO DE DATOS PARA LAS TABLAS
--
--
--
-- TABLA institucion_educativa
INSERT INTO institucion_educativa (
    nombre, direccion, telefono, email, nombre_directora, pagina_web, tipo, programas, especialidades_medicas
) VALUES
(
    'Universidad Nacional de Colombia',
    'Carrera 45 #26-85, Bogotá',
    '6013165000',
    'contacto@unal.edu.co',
    'Dra. Claudia Rodríguez',
    'https://unal.edu.co',
    'Universidad',
    'Ingeniería de Sistemas, Medicina, Derecho, Psicología',
    'Pediatría, Ginecología, Cardiología'
),
(
    'Instituto Técnico de Educación Superior ITES',
    'Calle 12 # 8-45, Cali',
    '6024871234',
    'info@ites.edu.co',
    'Dra. Luz Marina Ramírez',
    'http://ites.edu.co',
    'Instituto Técnico',
    'Sistemas, Contabilidad, Enfermería',
    NULL
),
(
    'Escuela Normal Superior de Pasto',
    'Cra. 25 # 18-70, Pasto',
    '6027221122',
    'escuelanormal@educacion.gov.co',
    'Lic. Carmen Gómez',
    NULL,
    'Escuela Normal',
    'Pedagogía Infantil, Formación Docente',
    NULL
),
(
    'Corporación Universitaria CUN',
    'Av. Caracas # 54-71, Bogotá',
    '6017459999',
    'info@cun.edu.co',
    'Dra. Verónica Pérez',
    'https://www.cun.edu.co',
    'Universidad',
    'Ingeniería de Sistemas, Administración, Derecho',
    'Medicina General, Psicología Clínica'
);
--
--
-- TABLA roles
INSERT INTO roles (nombre, valor)
VALUES ('Secretaria', 'S'),
    ('Docente', 'D'),
    ('Acudiente', 'A'),
    ('Estudiante', 'E'),
    ('Desconocido', 'N'),
    ('Root', 'R');
--
--
--
-- TABLA usuario
INSERT INTO usuario ( 
    identificacion,
    nombres,
    apellidos,
    telefono,
    email,
    direccion,
    hoja_vida,
    documentos,
    foto,
    clave,
    rol_id,
    estado
)
VALUES 
(
    '100101',
    'Carlos', 
    'González', 
    '3123456789', 
    'carlos@correo.com',
    'Calle 15 # 5-10',
    'hv_carlos.pdf',
    'doc_carlos.pdf',  
    'foto_carlos.jpg',
    '202cb962ac59075b964b07152d234b70', 
    2, 
    1
),
(
    '100102',
    'Luisa',
    'Peralta',
    '12344321',
    'test2@gmail.com',
    'Cll. 19 # 3 - 1',
    'hv_tipo.pdf',
    'doc_luisa.pdf',
    'foto_tipo.jpg',
    '202cb962ac59075b964b07152d234b70',
    3,
    1
),
(
    '100103',
    'Julian',
    'Zambrano',
    '12344321',
    'test3@gmail.com',
    'Cll. 19 # 3 - 1',
    'hv_carlos.pdf',
    'doc_julian.pdf',
    'foto_carlos.jpg',
    '202cb962ac59075b964b07152d234b70',
    4,
    1
),
(
    '100104',
    'Desconocido',
    'Desconocido',
    '12344321',
    'test4@gmail.com',
    'Cll. 19 # 3 - 1',
    'hv_desconocido.pdf',
    'doc_datos.pdf',
    'foto_desconocido.jpg',
    '202cb962ac59075b964b07152d234b70',
    5,
    1
),
(
    '100105',
    'Super',
    'Admin',
    '12344321',
    'test5@gmail.com',
    'Cll. 19 # 3 - 1',
    'hv_admin.pdf',
    'doc_archivos.pdf',
    'foto_admin.jpg',
    '202cb962ac59075b964b07152d234b70',
    6,
    1
);

--
--
--
-- TABLA anio_escolar
INSERT INTO anio_escolar (inicio, fin, id_institucion, estado)
VALUES ('2023-01-01', '2023-12-01', 1, 1);
--
--
--
-- TABLA periodo_academico
INSERT INTO periodo_academico (
        nombre,
        inicio_periodo,
        finalizacion_periodo,
        id_anio_escolar
    )
VALUES (
        'Periodo 1',
        '2023-01-01 23:59:59',
        '2023-03-30 00:00:00',
        1
    ),
    (
        'Periodo 2',
        '2023-04-01 23:59:59',
        '2023-06-30 00:00:00',
        1
    ),
    (
        'Periodo 3',
        '2023-07-01 23:59:59',
        '2023-09-30 00:00:00',
        1
    ),
    (
        'Periodo 4',
        '2023-10-01 23:59:59',
        '2023-12-30 00:00:00',
        1
    );
--
--
--
-- TABLA grado
INSERT INTO grado (nombre_grado, id_institucion)
VALUES ('Párbulos', 1),
    ('Primero', 1),
    ('Segundo', 1),
    ('Tercero', 1),
    ('Cuarto', 1),
    ('Quinto', 1),
    ('Sexto', 1),
    ('Séptimo', 1),
    ('Octavo', 1),
    ('Noveno', 1),
    ('Décimo', 1),
    ('Onceavo', 1);
--
--
--
-- TABLA grupo
INSERT INTO grupo (nombre_grupo, id_grado)
VALUES ('A', 1),
    ('B', 1),
    ('A', 2),
    ('B', 2),
    ('A', 3),
    ('B', 3),
    ('A', 4),
    ('B', 4),
    ('A', 5),
    ('B', 5),
    ('A', 6),
    ('B', 6),
    ('A', 7),
    ('B', 7),
    ('A', 8),
    ('B', 8),
    ('A', 9),
    ('B', 9),
    ('A', 10),
    ('B', 10),
    ('A', 11),
    ('B', 11),
    ('A', 12),
    ('B', 12);
--
--
--
-- TABLA asignatura
INSERT INTO asignatura (nombre_asignatura)
VALUES ('Giecología'),
    ('Medicina Interna'),
    ('Urgencias'),
    ('Neonatos'),
    ('Cirugía'),
    ('Ortopedia');
--
--
--
-- TABLA menu
/*
 -- (tipo) = > 1: Padre, 2: Hijo,
 -- es_hijo: id del padre a quien pertenece el submenu
 --*/
INSERT INTO menu (nombre, ruta, tipo, es_hijo, posicion)
VALUES 
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
(22, 'Registro de Cupos', 'principal.php?CONTENIDO=layout/components/gestion-cupos/lista-gestion-cupos.php', 2, 21, 22);
(23 'Recepción Aulas', '#', 1, NULL, 23),
(24 'Registro de Aulas', 'principal.php?CONTENIDO=layout/components/recepcion-aulas/lista-recepcion-aulas.php', 2, 23, 24),
(25,'Registro Biblioteca', 'principal.php?CONTENIDO=layout/components/recepcion-biblioteca/lista-recepcion-biblioteca.php', 2, 23, 25),
(26 'Consultas', '#', 1, NULL, 26),
(27,'Consultas Notas', 'principal.php?CONTENIDO=layout/components/notas/lista-notas-consulta.php', 2, 26, 27);
--
--
--
-- TABLA permisos
-- rol = 6 Super Admin
INSERT INTO permisos (id_rol, id_menu, estado)
VALUES (6, 1, 1),
    (6, 2, 1),
    (6, 3, 1),
    (6, 4, 1),
    (6, 5, 1),
    (6, 6, 1),
    (6, 7, 1),
    (6, 8, 1),
    (6, 9, 1),
    (6, 10, 1),
    (6, 11, 1),
    (6, 12, 1),
    (6, 13, 1),
    (6, 14, 1),
    (6, 15, 1),
    (6, 16, 1),
    (6, 17, 1),
    (6, 18, 1),
    (6, 19, 1),
    (6, 20, 1),
    (6, 21, 1),
    (6, 22, 1),
    (6, 23, 1),
    (6, 24, 1),
    (6, 25, 1);

--rol=1 secretaria
INSERT INTO permisos (id_rol, id_menu, estado)
VALUES (1, 1, 1),
    (1, 2, 1),
    (1, 3, 1),
    (1, 4, 1),
    (1, 5, 1),
    (1, 6, 1),
    (1, 7, 1),
    (1, 8, 1),
    (1, 9, 1),
    (1, 10, 1),
    (1, 11, 1),
    (1, 12, 1),
    (1, 13, 1),
    (1, 14, 1),
    (1, 15, 1),
    (1, 16, 1),
    (1, 17, 1),
    (1, 18, 1),
    (1, 19, 1),
    (1, 20, 1),
    (1, 21, 1),
    (1, 22, 1),
    (1, 23, 1),
    (1, 24, 1),
    (1, 25, 1);

--
--
/**permisos para un docente**/
INSERT INTO permisos (id_rol, id_menu, estado)
VALUES (2, 7, 1),
    (2, 9, 1),
    (2, 10, 1),
    (2, 11, 1),
    (2, 12, 1),
    (2, 13, 1),
    (2, 14, 1),
    (2, 15, 1),
    (2, 16, 1),
    (2, 19, 1),
    (2, 20, 1),
    (2, 23, 1),
    (2, 24, 1),
    (2, 25, 1);
    
/**permisos para un estudiante**/
INSERT INTO permisos (id_rol, id_menu, estado)
VALUES (4, 7, 1),
(4, 8, 1),
(4, 10, 1),
(4, 13, 1),
(4, 15, 1),
(4, 17, 1),
(4, 20, 1);
--
/**permisos para un acudiente**/
INSERT INTO permisos (id_rol, id_menu, estado)
VALUES (3, 7, 1),
(3, 8, 1),
(3, 10, 1),
(3, 13, 1),
(3, 15, 1),
(3, 26, 1),
(3, 27, 1);
--
/**permisos para universidad**/
INSERT INTO permisos (id_rol, id_menu, estado)
VALUES (7, 1, 1),
(7, 2, 1),
(7, 16, 1),
(7, 19, 1),
(7, 26, 1),
(7, 24, 1);
--

--------------------------------------------------------------------------------------
--
--
SELECT grupo_estudiante.id,
    grupo_estudiante.id_usuario_estudiante,
    grupo_estudiante.id_grupo,
    grupo_estudiante.id_anio_escolar,
    usuario.identificacion,
    usuario.nombres,
    usuario.apellidos,
    grado.nombre_grado,
    grupo.nombre_grupo,
    grupo.id_grado
FROM grupo_estudiante
    JOIN usuario ON grupo_estudiante.id_usuario_estudiante = usuario.id
    JOIN grupo ON grupo_estudiante.id_grupo = grupo.id
    JOIN grado ON grupo.id_grado = grado.id;
---
SELECT a.id as id_a,
    gd.nombre_grado,
    gr.nombre_grupo,
    a.nombre_asignatura
FROM asignatura a
    JOIN asignacion_docente ad ON a.id = ad.id_asignatura
    JOIN grupo gr ON ad.id_grupo = gr.id
    JOIN grupo_estudiante ge ON gr.id = ge.id_grupo
    JOIN grado gd ON gr.id_grado = gd.id
ORDER BY gd.nombre_grado,
    gr.nombre_grupo,
    a.nombre_asignatura;
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--
--Consultar indices de la base de datos
select index_schema,
    index_name,
    group_concat(
        column_name
        order by seq_in_index
    ) as index_columns,
    index_type,
    case
        non_unique
        when 1 then 'not unique'
        else 'unique'
    end as is_unique,
    table_name
from information_schema.statistics
where table_schema not in (
        'information_schema',
        'mysql',
        'performance_schema',
        'sys'
    )
    and index_schema = 'academico'
group by index_schema,
    index_name,
    index_type,
    non_unique,
    table_name
order by index_schema,
    table_name,
    index_name;
--
--
---CREATE INDEX id_index ON usuario (id);
---ALTER TABLE name_table AUTO_INCREMENT = 0;
---ALTER TABLE asignacion_docente DROP FOREIGN KEY asignacion_docente_ibfk_4;
---ALTER TABLE asignacion_docente RENAME COLUMN id_grado TO id_grupo;
---ALTER TABLE asignacion_docente ADD FOREIGN KEY(id_grupo) REFERENCES grupo(id) ON DELETE RESTRICT ON UPDATE CASCADE;
---ALTER TABLE academico.inasistencias ADD creado_por_docente int(4) NULL;
--
--