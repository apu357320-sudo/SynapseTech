# Contexto del proyecto S.I.G.S.M. (para Claude Code)

> Resumen de una conversación previa en Claude (claude.ai). Fecha: 12/10/2026. Pegar o guardar como `CLAUDE.md` en la raíz del repo.

## 1. Quién es el usuario y qué necesita
- Estudiante de BT Tecnologías de la Información (UTU, Uruguay), trabajando con 2 compañeros. **De este proyecto depende pasar de año.**
- Responder siempre en español.
- **Textos de la interfaz en tuteo neutro, no en voseo** (pedido del 12/10/2026): "crea", "completa", "selecciona", "puedes". Los enlaces y botones van en infinitivo ("Volver", "Registrar"). Vale para HTML, mensajes de JS y mensajes de PHP.
- Pidió que Claude actúe como "un integrante más del grupo": ayuda con el código y su generación. Ellos se ocupan de estudiar.

## 2. El proyecto: S.I.G.S.M. (Sistema Informático de Gestión de Servicios Médicos)
Cliente ficticio: Hospital de Clínicas. Dos módulos integrados en un panel principal con login:
1. **Módulo Documentación:** un funcionario administrativo sube documentos por categoría (IVE, prostatectomía, imagenología, nefrología, warfarina, etc.). Los pacientes los ven en el celular escaneando un **QR** (vista mobile-first, sin login). Incluye **encuestas de satisfacción anónimas**, con almacenamiento, indicadores y visualización básica de resultados.
2. **Módulo Ambulancias (trazabilidad):** alta, edición y seguimiento de traslados con conductor, enfermero (único responsable del paciente), vehículo (ambulancias y autos), origen, destino, hora de salida y de llegada estimada/efectiva. Historial filtrable por paciente o vehículo. Logro máximo: datos del paciente por **API externa consultando la C.I.** (se exigen APIs gratuitas; mencionaron Leaflet para mapas).

### Requisitos obligatorios (resumen)
- **Stack:** PHP, MySQL/MariaDB, HTML5, CSS3, JavaScript básico, Bootstrap 5.3 o Flex/Grid, XAMPP, GitHub.
- **Funcionalidad:** login/logout, sesiones PHP, mínimo 2 roles (admin y usuario básico), rutas protegidas que **redirigen al login**, panel de administración con filtros, CRUD de documentos por categoría, vista pública por QR, diseño responsive.
- **Base de datos:** DER, esquema relacional en 3FN, DDL y DML, restricciones no estructurales documentadas, **usuarios de BD con distintos privilegios** (admin y aplicación restringida), **vistas SQL**.
- **Seguridad:** consultas preparadas (PDO/MySQLi), `password_hash()` / `password_verify()`, validación frontend (JS) y backend (PHP), control de acceso por roles.
- **Arquitectura:** **3 capas visibles en carpetas** (Presentación, Negocio, Datos) y **mínimo 2 clases PHP** con atributos y métodos. Código comentado.
- **Entrega final:** pruebas funcionales documentadas, instalable en otra máquina siguiendo el `README.md`, manual de usuario con capturas, documentación técnica (DER definitivo, esquema relacional, diagramas), USB con código, scripts SQL y credenciales de prueba.

## 3. Plazos
- **Hoy: 12/10/2026.** Deben tener el proyecto terminado para fines de octubre, por la **Muestra Anual** del instituto (quedan ~15-17 días).
- **Tercera y última entrega: mediados de noviembre**, con presentación del producto y **explicación oral del código**.

## 4. Reglas sobre IA (MUY IMPORTANTE)
- El instituto permite usar IA de forma moderada, pero **el código no debe estar generado con IA**.
- Los profesores sospechan de quien avanza mucho sin haberse visto trabajar en clase, y en la defensa preguntan por el código.
- **El usuario admitió que la gran mayoría de los archivos del proyecto fueron hechos con IA** (la base de datos la hicieron ellos).
- Acordado con Claude: **no ayudar a disimular el origen del código.** El rol de Claude es de **tutor y revisor**:
  - Explicar conceptos y el código existente línea por línea.
  - Revisar, encontrar errores y proponer estructura.
  - Dar ejemplos **chicos** para lo que no vieron (QR, Leaflet, clases PHP).
  - Los estudiantes reescriben las partes centrales con sus palabras, las comentan y hacen commits frecuentes de cada integrante.
  - Recomendado: indicar en el `README` qué partes usaron IA como apoyo.
- **Instrucción para Claude Code:** evitar entregar archivos enteros ya hechos; preferir explicar, señalar qué cambiar y dar fragmentos cortos que ellos puedan escribir y defender. Si el usuario insiste en código completo, recordar este acuerdo.

## 5. Nivel del equipo
Nivel básico, "con las justas". Mejor en **SQL, HTML y JS**. Más flojos en **PHP y parte del CSS**. Están terminando la introducción a Full Stack. No les enseñaron aún nada de QR ni de APIs.

> **Las secciones 6 a 10 son el estado del 12/10/2026 a la mañana, antes de trabajar con Claude Code.** Varias cosas ya cambiaron: el estado actual y la bitácora están en las secciones 12 a 15.

## 6. Estado actual del repositorio (SynapseTech)
```
admin/     contacto.html, encuestas_panel.html, index.php, panelcontrol.html,
           registrar_usuario.php, traslado_form.html/.php, traslado.html, traslados.php
estilo/    administracion.css, documentos.css, pacientes.css, panelcontrol.css, style.css, traslados.css
js/        admin.js, configurarAdmin.js, documentos.js, encuestas.js, inicioSesion.js,
           qr.js, registro.js, traslado-mapa.js, traslado.js
php/       actualizarDocumento/Traslado/Usuario, apiPacientes, cargar_doc, cerrarSesion,
           conexion, config, crearAdministrador, eliminarDocumento/Traslado/Usuario,
           guardarEncuesta, guardarTraslado, inicioSesion, obtener_doc, registrarUsuario,
           reportes, sesion, traslados, usuarios
usuario/   cargar_documento.php, contactos2.html, encuesta.html, index.php, paciente.html
(raíz)     .mcp.json, configurar_admin.html, index.html, index.php, logo.svg,
           skills-lock.json, synapsetech_db.sql
```

## 7. Lo que Claude ya revisó
Archivos recibidos: `conexion.php`, `sesion.php`, `inicioSesion.php`, `synapsetech_db.sql`. **No vistos todavía:** `config.php` (define `responder()`, `limpiar()` y las constantes DB_*), el CRUD de documentos, `guardarTraslado.php`, `guardarEncuesta.php`, `qr.js`, `apiPacientes.php`.

### Base de datos (`synapsetech_db`, MariaDB 10.4)
Tablas: `rol`, `usuario`, `categoria_documento`, `documento`, `encuesta`, `pregunta`, `respuesta_encuesta`, `detalle_respuesta`, `vehiculo`, `chofer`, `enfermero`, `paciente`, `elemento`, `objeto`, `persona_acompanante`, `traslado`, `traslado_elemento`.
- **Bien:** PK/FK con `ON DELETE/UPDATE` pensados, `ci` y `email` únicos, baja lógica con `usuario.estado`, herencia `elemento` → `objeto` / `persona_acompanante`, encuestas realmente anónimas (sin vínculo a usuario).
- **Pendiente / a mejorar:**
  1. **DML de prueba:** hoy solo hay roles (Administrador, Personal Administrativo, Chofer, Enfermero), 5 categorías y 1 encuesta con 4 preguntas. Faltan admin con contraseña hasheada, vehículos, choferes, enfermeros, pacientes y traslados.
  2. **Usuarios de BD con privilegios** (admin y aplicación) y **vistas SQL** (requisito explícito).
  3. `estado` como `varchar` libre en `traslado`, `vehiculo` y `encuesta`: usar `ENUM` o `CHECK` y documentarlo como restricción no estructural.
  4. **Redundancia** en `chofer` / `enfermero`: guardan `ci_chofer` / `ci_enfermero` y la CI ya está en `usuario.ci`. Justificarlo o corregirlo (cuestión de 3FN).
  5. No se ve cómo se modelan las **rutas del circuito nacional** que pide la letra.
  6. Revisar que exista `CREATE DATABASE` o instrucciones de importación en el README.

### Código PHP revisado
- `inicioSesion.php`: **bien** — usa consultas preparadas, `password_verify` y mismo mensaje de error para usuario inexistente o contraseña mala. Login por CI o email.
  - Agregar `session_regenerate_id(true)` tras validar el login.
- `conexion.php`: mysqli con `set_charset('utf8mb4')`, usa constantes de `config.php`. Para las 3 capas, convertirlo en una clase `Database`.
- `sesion.php`: `exigirSesion($soloAdministrador)` responde JSON 401/403. **Falta una versión para páginas que redirija al login** (la rúbrica lo exige).

## 8. Problemas detectados a resolver
1. **Archivos `.html` en `admin/`** (`panelcontrol.html`, `traslado.html`, `traslado_form.html`, `encuestas_panel.html`, `contacto.html`): PHP no puede protegerlos con sesión. Pasarlos a `.php` con guarda de sesión arriba.
2. **No hay clases PHP** (mínimo 2 obligatorias). Propuesta: `php/clases/` con `Database`, `Usuario`, `Documento`, `Traslado`; los archivos actuales quedan como controladores finos.
3. **Estructura por capas poco visible** en carpetas.
4. Revisar que `limpiar()` en `config.php` no escape HTML al **recibir** datos (se escapa al mostrar, no al guardar).
5. **`crearAdministrador.php`** debe borrarse o protegerse antes de entregar.
6. **Secretos:** `config.php`, `.mcp.json` y `skills-lock.json` no deben llevar claves ni subirse a GitHub; agregarlos al `.gitignore`.

## 9. Plan propuesto (~15 días hasta la Muestra Anual)
1. Revisar BD y login (hecho en parte) y completar DML de prueba.
2. Reorganizar en 3 capas y crear 2+ clases PHP.
3. Seguridad: consultas preparadas en todos los endpoints, `password_hash`, roles, redirecciones, `session_regenerate_id`.
4. Encuestas anónimas con resultados y **QR** (librería JS gratuita, sin Composer; ya existe `qr.js`).
5. Módulo ambulancias: Leaflet (OpenStreetMap, gratis) y API de pacientes por C.I. (o simulada).
6. Pruebas funcionales, `README`, manuales.
7. Ensayo de defensa oral: que cada integrante pueda explicar cada archivo.

## 10. Siguiente paso inmediato
Revisar `config.php` (sin contraseñas), `cargar_doc.php` + `documentos.js` y `guardarTraslado.php`. Por cada archivo: explicar línea por línea y que los estudiantes lo reescriban y comenten con sus palabras.

## 11. Otros datos de contexto
- Entorno del usuario: **Windows 10**. Acaba de instalar el CLI de Claude Code con `irm https://claude.ai/install.ps1 | iex` (pendiente confirmar con `claude --version`). Tiene un plan de pago de Claude para usar Claude Code en VS Code.
- Herramientas: VS Code, XAMPP 8.x, GitHub.
- En esta misma conversación también se hizo un juego idle de monedas en HTML (artifact aparte, sin relación con este proyecto).

---

## 12. Decisiones tomadas con Claude Code (12/10/2026)
- **Diseño:** estilo "ficha clínica" en verde sanitario. Fondo crema `#f4f1e8`, hojas `#fffdf8`, verde `#1f5c4a` (oscuro `#153f33`, claro `#e4eee9`), líneas `#c9c2ae`, texto `#26302b`, texto secundario `#5f6a63`, error `#a3271f`. Títulos en Source Serif 4, texto en IBM Plex Sans. Bordes rectos, sin sombras ni degradados; header y footer con línea doble verde.
- **CSS plano:** sin variables, sin anidado, sin frameworks. `style.css`, `pacientes.css` y `panelcontrol.css` repiten la misma base a propósito (cada archivo es independiente).
- **Sin Bootstrap:** se quitó de las 3 páginas que lo cargaban. La rúbrica acepta Flex/Grid.
- **Errores de base de datos:** `conexion.php` usa `mysqli_report(MYSQLI_REPORT_OFF)`, así `execute()` devuelve `false` y cada archivo muestra su mensaje. No usar `try/catch` de `mysqli_sql_exception`.
- **Páginas protegidas:** usan `exigirSesionPagina()` de `sesion.php` (redirige). Los PHP que responden JSON usan `exigirSesion()`.
- **Cédula:** 7 u 8 dígitos sin puntos ni guion, validada en JS y en PHP.
- **Pruebas:** se hacen contra una copia temporal (`synapsetech_prueba`) y un servidor de prueba, nunca contra `synapsetech_db`. Al terminar se borra la copia y se apagan los servidores.
- **Regla vigente hasta nuevo aviso:** no agregar archivos nuevos ni funcionalidades que los necesiten; solo pulir lo que hay.

## 13. Bitácora de cambios

### Módulo Ambulancias (formulario con Leaflet)
- `traslado_mapa.sql` (nuevo): columnas `origen_lat`, `origen_lng`, `destino_lat`, `destino_lng` en `traslado` y datos de prueba (2 choferes, 2 enfermeros, 3 vehículos). **Falta ejecutarlo en phpMyAdmin sobre `synapsetech_db`.** Contraseña de los usuarios de prueba: `Prueba123`.
- `synapsetech_db.sql`: las mismas 4 columnas en el `CREATE TABLE traslado`.
- `admin/traslado_form.html` se borró; su contenido pasó a `admin/traslado_form.php` (protegido). Nombre y apellido separados, select cargados desde la base, coordenadas en campos ocultos.
- `js/traslado-mapa.js` reescrito: el mapa ya no pisa el texto de origen/destino, marcadores O y D, línea y distancia, validación, envío.
- `php/opcionesTraslado.php` (nuevo): choferes, enfermeros y vehículos disponibles.
- `php/guardarTraslado.php` reescrito contra las tablas reales (`traslado`, `chofer`, `enfermero`, `paciente`), con transacción.
- `php/traslados.php`, `actualizarTraslado.php`, `eliminarTraslado.php`: nombres de tabla y columnas corregidos. Un traslado finalizado o cancelado ya no se puede modificar.
- `admin/traslados.php` + `js/traslado.js`: columnas Vehículo y Salida, **filtro por paciente o vehículo** (punto de la rúbrica), controles apagados en traslados cerrados.
- Se quitaron del formulario "Tipo de elemento" y "Observaciones" porque no se guardaban en ningún lado.

### Usuarios y sesión
- `php/registrarUsuario.php`: valida cédula y email, y al registrar un Chofer o Enfermero crea también su fila en `chofer` o `enfermero` (transacción).
- Formularios de alta (`admin/index.php`, `admin/registrar_usuario.php`): se quitó el campo "Usuario" (no se usaba) y "Número de empleado" pasó a llamarse "C.I.", que es lo que realmente guarda.
- `js/admin.js` reescrito: arma las tablas con `textContent` en vez de `innerHTML` (evita inyección de HTML), y al editar muestra los valores actuales.
- `php/sesion.php`: nueva `exigirSesionPagina()`; reemplaza las dos líneas copiadas en cada página.
- `php/inicioSesion.php`: `session_regenerate_id(true)` al entrar.
- `php/crearAdministrador.php`: cuenta administradores (`id_rol = 1`) en vez de usuarios.
- `php/eliminarUsuario.php` y `actualizarUsuario.php`: mensajes claros para "tiene traslados asociados" y "email repetido".
- `admin/index.php`: menú con enlaces a Traslados, Nuevo traslado y Cargar documento (antes no había forma de llegar).

### Diseño y textos
- Los 6 CSS reescritos con el estilo de la sección 12.
- HTML: sin Bootstrap, sin `style="..."`, botones con `boton-principal` / `boton-secundario`, encabezados con título y subtítulo dentro de un `<div>`, `usuario/paciente.html` con el `<div>` sin cerrar corregido.
- Textos pasados de voseo a tuteo neutro en HTML, JS y PHP. Títulos "HR Clínica" corregidos a "Hospital de Clínicas".

## 14. Bitácora de pruebas (12/10/2026, sobre la copia `synapsetech_prueba`)
Todas pasaron salvo donde se indica.

| Prueba | Resultado |
|---|---|
| Importar `synapsetech_db.sql` y ejecutar `traslado_mapa.sql` dos veces | Sin errores; no duplica datos |
| Abrir una página de `admin/` sin sesión | Redirige al login |
| Chofer logueado abre `admin/traslados.php` | Redirige a su panel |
| Cerrar sesión y volver a una página protegida | Redirige al login |
| Crear el primer administrador con usuarios de prueba ya cargados | Lo crea; un segundo intento se rechaza |
| Registrar chofer | Aparece en `usuario`, en `chofer` y en el select del formulario |
| Registrar con email repetido, cédula con puntos, email inválido, rol inexistente | Mensaje de error en cada caso |
| Nombre con `<b>` en la tabla de usuarios | Se muestra como texto |
| Alta de traslado completa desde el navegador (con clic en el mapa) | Se guarda y aparece en el listado |
| Alta sin apellido, sin destino en el mapa, llegada anterior a la salida, fecha inválida, chofer inexistente | Mensaje de error en cada caso; el paciente no queda guardado si falla el traslado |
| Finalizar un traslado | Guarda la hora de llegada efectiva |
| Reabrir o cancelar un traslado finalizado | Se rechaza |
| Eliminar un chofer con traslados | Se rechaza con mensaje |
| Filtro del listado por matrícula y por texto inexistente | Muestra 1 fila y 0 filas |
| Diseño en 1280 px y 390 px | Sin desborde horizontal |
| Destino en Buenos Aires | **No se rechaza:** el límite "dentro de Uruguay" es un rectángulo aproximado |

No probado todavía: carga, edición y borrado de documentos; edición de usuario desde el navegador (los `prompt`); `admin/contacto.html`, `usuario/contactos2.html` y las páginas de encuesta con el diseño nuevo.

## 15. Errores encontrados y pendientes

### Corregidos
- El módulo de traslados consultaba tablas que no existen (`traslados`, `choferes`, `enfermeros`).
- `php/actualizarUsuario.php` tenía una `z` y una `s` sueltas que daban error de sintaxis.
- En PHP 8.2 un error de base (email repetido, por ejemplo) devolvía un 500 en vez del mensaje.
- Un chofer o enfermero registrado desde el panel no aparecía al crear traslados.
- `admin.js` insertaba datos de usuarios con `innerHTML` sin escapar.
- Error propio del 12/10: un script de reemplazos de Claude dañó `usuario/cargar_documento.php` y `js/inicioSesion.js`; se restauraron desde git y se rehicieron los cambios a mano. Desde entonces los cambios se hacen archivo por archivo.

### Pendientes (necesitan archivos nuevos o una decisión del equipo)
Los puntos 3, 4, 5 y 7 de la primera lista se resolvieron en la segunda tanda (sección 16).
1. **Captura de phpMyAdmin:** `traslado_mapa.sql` ya se ejecutó sobre `synapsetech_db` el 12/10 (por consola), pero la entrega pide capturas: volver a ejecutarlo en phpMyAdmin (no rompe nada) y sacar la captura.
2. **Clases PHP (mínimo 2) y carpetas por capas.** Sigue sin haber ninguna clase. Es lo más grave que falta de la rúbrica.
6. **Páginas `.html` dentro de `admin/`** (`panelcontrol.html`, `contacto.html`, `encuestas_panel.html`, `traslado.html`): no se pueden proteger con sesión mientras no sean `.php`. `encuestas_panel.html` no muestra datos sin sesión de administrador (la protección está en `reportes.php`), pero la página en sí se puede abrir. `panelcontrol.html` no está enlazada desde ningún lado.
8. **`.gitignore`:** falta; `.mcp.json`, `skills-lock.json` y `.claude/` no deberían subirse.
9. **Base de datos:** usuarios de BD con privilegios, vistas SQL (la consulta de `php/traslados.php` es buena candidata), `estado` como `ENUM` (el `DEFAULT` de `traslado` dice `Pendiente` y el código usa `Solicitado`), y la redundancia de la cédula en `chofer` / `enfermero`.
10. **Traslados:** solo el Administrador puede entrar (la letra habla de un funcionario administrativo); el traslado de objetos y acompañantes (`elemento`, `objeto`, `traslado_elemento`) no tiene formulario; cambiar el rol de un usuario a Chofer o Enfermero desde "Editar" no crea su fila; el seguimiento no muestra el mapa.
11. **README, manual de usuario y casos de prueba** para la entrega (la tabla de la sección 14 sirve de base).

## 16. Segunda tanda (12/10/2026, noche): lo que faltaba sin crear archivos
Se completaron los archivos que ya existían vacíos. Único archivo fuera del repo: el respaldo de la base.

### Base de datos real
- Respaldo previo: `C:\Users\David\Documents\synapsetech_db_respaldo_2026-10-12.sql` (hecho con `mysqldump`).
- Se ejecutó `traslado_mapa.sql` sobre `synapsetech_db`: ahora `traslado` tiene las 4 columnas de coordenadas y hay 2 choferes, 2 enfermeros y 3 vehículos de prueba (contraseña `Prueba123`). La base solo tenía el usuario administrador del equipo, que no se tocó.
- Las pruebas siguieron haciéndose sobre la copia `synapsetech_prueba` (ya borrada): en la base real no quedó ningún traslado, documento ni respuesta de prueba.

### Cambios
- **Paciente por C.I.:** `php/apiPacientes.php` busca en la tabla `paciente` (simula la API externa; para usar una real se cambia solo la consulta de ese archivo). El botón "Buscar" del formulario de traslado completa nombre y apellido.
- **Documentos por categoría:** `usuario/cargar_documento.php` pide la categoría; `php/cargar_doc.php` la valida y la guarda (antes era siempre la 1). `php/obtener_doc.php` devuelve también la categoría y la lista de categorías, acepta `?categoria=` y **ya no pide sesión** (solo lectura: lo usa la vista pública). El panel de administración muestra la categoría y tiene un **filtro por categoría**.
- **Vista pública para pacientes:** `usuario/paciente.html` dejó de ser un login de maqueta (mandaba la contraseña por GET). Ahora lista los documentos por categoría, muestra el elegido y enlaza a la encuesta, sin sesión. `js/documentos.js` se reescribió para servir a esta página y a la de carga.
- **QR:** `js/qr.js` usa la librería gratuita qrcodejs (CDN de cdnjs). El login muestra el QR de la vista de pacientes y el panel de administración tiene "Ver QR" por documento (`paciente.html?id=N`).
- **Encuestas anónimas:** `usuario/encuesta.html` (formulario), `php/guardarEncuesta.php` (valida y guarda en `respuesta_encuesta` y `detalle_respuesta`, sin datos de quien responde), `php/reportes.php` (indicadores, solo administrador), `admin/encuestas_panel.html` (resultados con barras) y `js/encuestas.js` (sirve a las dos páginas).
- **CSS:** estilos de la encuesta al final de `pacientes.css`; filtro, QR e indicadores al final de `administracion.css`; tarjetas anchas de la vista de pacientes en `documentos.css`.

### Decisiones a conocer
- La encuesta usa las 4 preguntas que ya están en la tabla `pregunta` (ids 1 a 4). El formulario y `guardarEncuesta.php` las tienen fijas: si se agregan preguntas a la tabla, no aparecen solas. La tabla `pregunta` no tiene columna de tipo de respuesta.
- La lista de servicios de la encuesta está escrita en `encuesta.html` y repetida en `guardarEncuesta.php`; hay que cambiarla en los dos lados.
- Indicador de satisfacción = porcentaje de respuestas con calificación 4 o 5.
- **El QR lleva a la dirección con la que se abrió el sitio.** Si se abre con `localhost`, el celular no puede entrar. Para la demostración hay que abrir el sitio con la IP de la PC (por ejemplo `http://192.168.1.50/synapsetech/`) y que el celular esté en la misma red WiFi.

### Pruebas (sobre la copia de prueba)
| Prueba | Resultado |
|---|---|
| Buscar paciente existente, inexistente y con cédula inválida | Completa los campos / avisa / avisa |
| Cargar documento sin datos y con título, categoría y PDF | Mensaje de error / se guarda y aparece en la lista |
| Panel de administración: categoría en la tabla, filtro por otra categoría | Se ve la categoría; el filtro deja 0 filas |
| "Ver QR" de un documento | Dibuja el QR y muestra la dirección |
| Vista de pacientes sin sesión, con `?id=1` | Documento elegido, enlace que abre el archivo (200), sin desborde en 390 px |
| Encuesta vacía, completa, con calificación 9, servicio inventado, sin claridad | Error / se guarda / error / error / error |
| Panel de encuestas sin sesión | Vuelve al login |
| Panel de encuestas con 4 respuestas | 4 respuestas, promedio 4, 75 % satisfechos, 75 % información clara |
| Comentario con `<b>` | Se muestra como texto |
| Sitio real (Apache) sin sesión: 6 páginas protegidas y 4 PHP con datos | Redirigen al login (302) / responden 401 |

No probado: escanear el QR con un celular real, editar el título y eliminar un documento desde el navegador, documentos de imagen o Word, y `admin/contacto.html` / `usuario/contactos2.html` con el diseño nuevo.

### Nuevos pendientes
- Las categorías de documento son solo 5; la letra nombra muchas más (warfarina, ostomizados, trasplante, etc.). Se agregan con `INSERT` en `categoria_documento`.
- No hay pantalla para crear o editar categorías ni encuestas.
- `admin/contacto.html` y `usuario/contactos2.html` quedaron sin enlace desde la vista de pacientes.
