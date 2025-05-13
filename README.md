# 🐴 PRW_SuarezBritoNoa — Gestión de Hípica

📌 **Descripción**  
Este proyecto es una aplicación web orientada a la gestión de una **hípica**, desarrollada como trabajo final de Programación Web.  
Permite administrar alumnos/as y profesorado, clases de doma y salto, horarios, valoraciones, imágenes de perfil y más.

Incluye inicio de sesión, subida de imágenes, sistema de valoración con estrellas, roles diferenciados (alumnado y profesorado) y una interfaz estructurada para su uso desde cualquier dispositivo.

---

## 🚀 Tecnologías Utilizadas
- 🖥 **Frontend:** HTML5, CSS3, JavaScript (vanilla)
- ⚙️ **Backend:** PHP (programación funcional)
- 🗄 **Base de Datos:** MySQL (gestionada desde PhpMyAdmin)
- 🗂 **Gestión de tareas:** Trello
- ☁️ **Repositorio:** GitHub (para publicación del proyecto)

---

## 🛠 Instalación y Ejecución
Sigue estos pasos para instalar y ejecutar el proyecto en tu entorno local:

1️⃣ **Descargar y configurar el servidor**  
   - Instala [XAMPP](https://www.apachefriends.org/es/index.html) si aún no lo tienes.  
   - Activa los módulos **Apache** y **MySQL** desde el panel de control.

2️⃣ **Clonar o copiar el proyecto**  
   - Coloca todos los archivos en el directorio del servidor web:  
     ```
     C:\xampp\htdocs\PRW_SuarezBritoNoa
     ```

3️⃣ **Configurar la base de datos**  
   - Abre **phpMyAdmin** y crea la base de datos ejecutando:  
     ```sql
     CREATE DATABASE bdhipica;
     USE bdhipica;
     ```
   - Ejecuta los scripts `bdhipica.php` y `bdinsert.php` para crear las tablas y añadir los primeros datos.

4️⃣ **Editar la conexión a la base de datos**  
   - Modifica el archivo `conexion.php` en la carpeta `Pagina/` asegurándote de tener los siguientes datos:
     ```php
     $hostDB = '127.0.0.1';
     $nombreDB = 'bdhipica';
     $usuarioDB = 'root';
     $contraDB = '';
     ```

5️⃣ **Ejecutar el proyecto**  
   - Abre tu navegador y accede a:
     ```
     http://localhost/PRW_SuarezBritoNoa/PHP/Pagina/inicio.php
     ```

---

## 📌 Funcionalidades Principales

### 🧍‍♀️ Alumnado
✅ Registro y login con imagen de perfil  
✅ Consulta de horarios generales de clases  
✅ Visualización de valoraciones recibidas  
✅ Edición de sus datos personales  

### 👨‍🏫 Profesorado
✅ Acceso autorizado con login  
✅ Consulta del listado de alumnos/as  
✅ Valoración con estrellas y comentarios  
✅ Eliminación de registros  
✅ Consulta del mismo horario general  
✅ Modificación de datos propios  

---

## 🗃️ Organización del Proyecto

La planificación del proyecto se realizó en **6 semanas** usando la herramienta **Trello**, con tareas divididas por columnas:

- Semana 1: Instalación de entorno y análisis inicial  
- Semana 2: Diseño de base de datos y conexión  
- Semana 3: Funciones básicas y estructura  
- Semana 4: Funcionalidades para alumnado  
- Semana 5: Funcionalidades para profesorado  
- Semana 6: Documentación, pruebas y entrega final  

---

✔️ Los archivos PHP están divididos en vistas y lógica funcional  
✔️ Cada vista tiene su propio archivo `.css`  
✔️ Imágenes separadas en perfiles y recursos visuales  
✔️ Interacción dinámica con JavaScript para valoraciones

---

## 📨 Contacto

📧 Email: [noajananoa@gmail.com](mailto:noajananoa@gmail.com)

---

🚀 _Gracias por visitar este proyecto. ¡Cualquier duda o sugerencia será bienvenida!_  


