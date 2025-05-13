<!-- 

-- Insertar datos en la tabla Alumno
INSERT INTO Alumno (dni, nombre, apellido, telefono, fechaNacimiento, seguro)
VALUES
('12345678A', 'Noa', 'Suárez', '123456789', '2000-01-15', 'S123456789'),
('87654321B', 'María', 'Gómez', '987654321', '1998-03-22', 'S987654321'),
('23456789C', 'Antonio', 'Ramos', '234567890', '2002-05-10', 'S234567890');

-- Insertar datos en la tabla NivelEspecialidad
INSERT INTO NivelEspecialidad (id_alumno, nivelEspecialidad)
VALUES
(1, 'salto'),
(2, 'doma'),
(3, 'doma');

-- Insertar datos en la tabla Tipo_Cuota
INSERT INTO Tipo_Cuota (nivel, duracion, precio)
VALUES
('básico', '00:30:00', 15.00),
('avanzado', '00:45:00', 20.00);

-- Insertar datos en la tabla Profesor
INSERT INTO Profesor (dni, nombre, apellido, telefono, fechaContratacion, salario)
VALUES
('11223344C', 'Enrique', 'López', '112233445', '2015-06-01', 1800.00),
('44332211D', 'Ana', 'Ramírez', '554433221', '2018-09-15', 1900.00);

-- Insertar datos en la tabla ProfesorEspecialidad
INSERT INTO ProfesorEspecialidad (id_profesor, especialidad)
VALUES
(1, 'salto'),
(2, 'doma');

-- Insertar datos en la tabla Pista
INSERT INTO Pista (horarioDisponibilidad, fechaUltimoMantenimiento, tipo, estado, tamaño)
VALUES
('08:00:00', '2024-01-10', 'enseñar nivel I', 'libre', 'redonda y pequeña'),
('10:00:00', '2024-02-15', 'doma y salto', 'ocupada', 'grande y rectangular');

-- Insertar datos en la tabla Tipo_Caballo
INSERT INTO Tipo_Caballo (altura, peso, nivelEntrenamiento, caracter, descripcion)
VALUES
(1.50, 450.00, 'nivel intermedio', 'tranquilo', 'salto'),
(1.60, 500.00, 'nivel avanzado', 'energico', 'doma');

-- Insertar datos en la tabla Caballo
INSERT INTO Caballo (nombre, fechaNacimiento, raza, sexo, color, fechaIngreso, id_tipo_caballo)
VALUES
('Oliver', '2010-04-05', 'Arabe', 'macho', 'negro', '2015-06-20', 1),
('Candela', '2012-08-18', 'Pura Raza Española', 'hembra', 'blanco', '2017-07-10', 2);

-- Insertar datos en la tabla Clase
INSERT INTO Clase (fechaInicio, fechaFin, especialidad, cupoMax, id_profesor, avisoIncidencia)
VALUES
('2024-12-02', '2024-12-08', 'salto', 10, 1, 0),
('2024-12-02', '2024-12-02', 'doma', 8, 2, 0);

-- Insertar datos en la tabla Horario
INSERT INTO Horario (id_clase, diaSemana, horaInicio)
VALUES
(1, 'lunes', '10:00:00'), (1, 'lunes', '11:00:00'), (1, 'lunes', '12:00:00'), 
(2, 'lunes', '16:00:00'), (2, 'lunes', '17:00:00'), (2, 'lunes', '18:00:00'),
(2, 'martes', '10:00:00'), (2, 'martes', '11:00:00'), (2, 'martes', '12:00:00'),
(1, 'martes', '16:00:00'), (1, 'martes', '17:00:00'), (1, 'martes', '18:00:00'),
(1, 'miércoles', '10:00:00'), (1, 'miércoles', '11:00:00'), (2, 'miércoles', '12:00:00'),
(2, 'miércoles', '16:00:00'), (2, 'miércoles', '17:00:00'), (2, 'miércoles', '18:00:00'),
(2, 'jueves', '10:00:00'), (2, 'jueves', '11:00:00'), (1, 'jueves', '12:00:00'),
(1, 'jueves', '16:00:00'), (1, 'jueves', '17:00:00'), (1, 'jueves', '18:00:00'),
(1, 'viernes', '10:00:00'), (1, 'viernes', '11:00:00'), (1, 'viernes', '12:00:00'),
(2, 'viernes', '16:00:00'), (2, 'viernes', '17:00:00'), (2, 'viernes', '18:00:00');

-- Insertar datos en la tabla ClaseAlumno
INSERT INTO ClaseAlumno (id_clase, id_alumno)
VALUES
(1, 1),
(1, 2),
(2, 2),
(2, 3);

-- Insertar datos en la tabla Clase_TipoCuota
INSERT INTO Clase_TipoCuota (id_clase, id_tipo_cuota)
VALUES
(1, 1),
(2, 2);

-- Insertar datos en la tabla ClasePista
INSERT INTO ClasePista (id_clase, id_pista)
VALUES
(1, 1),
(2, 2);

-- Insertar datos en la tabla ClaseCaballo
INSERT INTO ClaseCaballo (id_clase, id_caballo)
VALUES
(1, 1),
(2, 2);



-->