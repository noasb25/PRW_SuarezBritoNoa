<!-- 

CREATE DATABASE IF NOT EXISTS bdhipica;
USE bdhipica;

-- Tabla Alumno
CREATE TABLE Alumno (
    id_alumno INT AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(9) UNIQUE NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    telefono CHAR(9),
    fechaNacimiento DATE,
    seguro VARCHAR(12),
    foto_perfil VARCHAR(255) DEFAULT NULL
);

-- Tabla NivelEspecialidad
CREATE TABLE NivelEspecialidad (
    id_alumno INT NOT NULL,
    nivelEspecialidad ENUM('salto', 'doma') NOT NULL,
    PRIMARY KEY (id_alumno, nivelEspecialidad),
    FOREIGN KEY (id_alumno) REFERENCES Alumno(id_alumno)
);

-- Tabla Tipo_Cuota
CREATE TABLE Tipo_Cuota (
    id_tipo_cuota INT AUTO_INCREMENT PRIMARY KEY,
    nivel ENUM('básico', 'avanzado') NOT NULL,
    duracion TIME NOT NULL,
    precio DECIMAL(5,2) NOT NULL
);

-- Tabla Profesor
CREATE TABLE Profesor (
    id_profesor INT AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(9) UNIQUE NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    telefono CHAR(9),
    fechaContratacion DATE,
    salario DECIMAL(7,2)
);

-- Tabla ProfesorEspecialidad
CREATE TABLE ProfesorEspecialidad (
    id_profesor INT NOT NULL,
    especialidad ENUM('salto', 'doma', 'salto y doma') NOT NULL,
    PRIMARY KEY (id_profesor, especialidad),
    FOREIGN KEY (id_profesor) REFERENCES Profesor(id_profesor)
);

-- Tabla Pista
CREATE TABLE Pista (
    id_pista INT AUTO_INCREMENT PRIMARY KEY,
    horarioDisponibilidad TIME,
    fechaUltimoMantenimiento DATE,
    tipo ENUM('enseñar nivel I', 'enseñar nivel II', 'entrenar en competiciones', 'doma y salto', 'descanso') NOT NULL,
    estado VARCHAR(20),
    tamaño ENUM('redonda y pequeña', 'mediana y rectangular', 'grande y rectangular') NOT NULL
);

-- Tabla Tipo_Caballo
CREATE TABLE Tipo_Caballo (
    id_tipo_caballo INT AUTO_INCREMENT PRIMARY KEY,
    altura DECIMAL(4,2),
    peso DECIMAL(6,2),
    nivelEntrenamiento ENUM('nivel básico', 'nivel intermedio', 'nivel avanzado') NOT NULL,
    caracter VARCHAR(50),
    descripcion ENUM('salto', 'doma', 'no es de salto ni doma') NOT NULL
);

-- Tabla Caballo
CREATE TABLE Caballo (
    id_caballo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    fechaNacimiento DATE,
    raza VARCHAR(50),
    sexo ENUM('macho', 'hembra') NOT NULL,
    color VARCHAR(20),
    fechaIngreso DATE,
    id_tipo_caballo INT,
    FOREIGN KEY (id_tipo_caballo) REFERENCES Tipo_Caballo(id_tipo_caballo)
);

-- Tabla Incidencia
CREATE TABLE Incidencia (
    id_incidencia INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE,
    hora TIME,
    gravedad ENUM('leve', 'alta', 'muy alta') NOT NULL,
    observaciones VARCHAR(200),
    id_caballo INT,
    FOREIGN KEY (id_caballo) REFERENCES Caballo(id_caballo)
);

-- Tabla Clase
CREATE TABLE Clase (
    id_clase INT AUTO_INCREMENT PRIMARY KEY,
    fechaInicio DATE,
    fechaFin DATE,
    especialidad ENUM('salto', 'doma') NOT NULL,
    cupoMax INT,
    id_profesor INT,
    avisoIncidencia TINYINT(1) DEFAULT 0,
    FOREIGN KEY (id_profesor) REFERENCES Profesor(id_profesor)
);

-- Tabla Horario
CREATE TABLE Horario (
    id_clase INT NOT NULL,
    diaSemana ENUM('lunes', 'martes', 'miércoles', 'jueves', 'viernes') NOT NULL,
    horaInicio TIME NOT NULL,
    PRIMARY KEY (id_clase, diaSemana, horaInicio),
    FOREIGN KEY (id_clase) REFERENCES Clase(id_clase)
);

-- Tabla ClaseAlumno
CREATE TABLE ClaseAlumno (
    id_clase INT NOT NULL,
    id_alumno INT NOT NULL,
    PRIMARY KEY (id_clase, id_alumno),
    FOREIGN KEY (id_clase) REFERENCES Clase(id_clase),
    FOREIGN KEY (id_alumno) REFERENCES Alumno(id_alumno)
);

-- Tabla Clase_TipoCuota
CREATE TABLE Clase_TipoCuota (
    id_clase INT NOT NULL,
    id_tipo_cuota INT NOT NULL,
    PRIMARY KEY (id_clase, id_tipo_cuota),
    FOREIGN KEY (id_clase) REFERENCES Clase(id_clase),
    FOREIGN KEY (id_tipo_cuota) REFERENCES Tipo_Cuota(id_tipo_cuota)
);

-- Tabla ClasePista
CREATE TABLE ClasePista (
    id_clase INT NOT NULL,
    id_pista INT NOT NULL,
    PRIMARY KEY (id_clase, id_pista),
    FOREIGN KEY (id_clase) REFERENCES Clase(id_clase),
    FOREIGN KEY (id_pista) REFERENCES Pista(id_pista)
);

-- Tabla ClaseCaballo
CREATE TABLE ClaseCaballo (
    id_clase INT NOT NULL,
    id_caballo INT NOT NULL,
    PRIMARY KEY (id_clase, id_caballo),
    FOREIGN KEY (id_clase) REFERENCES Clase(id_clase),
    FOREIGN KEY (id_caballo) REFERENCES Caballo(id_caballo)
);

-- Tabla Valoracion
CREATE TABLE Valoracion (
    id_valoracion INT AUTO_INCREMENT PRIMARY KEY,
    id_profesor INT NOT NULL,
    id_alumno INT NOT NULL,
    puntuacion TINYINT CHECK (puntuacion BETWEEN 1 AND 5),
    comentario TEXT,
    fecha DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (id_profesor) REFERENCES Profesor(id_profesor),
    FOREIGN KEY (id_alumno) REFERENCES Alumno(id_alumno)
);


-->