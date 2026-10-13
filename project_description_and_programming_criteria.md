# S.I.G.S.M. - Sistema Informático de Gestión de Servicios Médicos
**Proyecto de Egreso - BT Tecnologías de la Información - 2026**
Instituciones: Instituto Superior Brazo Oriental (I.S.B.O.), Polo Educativo Tecnológico Vista Linda, Instituto Tecnológico Superior Paysandú, Escuela Superior de Comercio Villa Muñoz (DGETP-UTU).

---

## 1. Generalidades del Proyecto y Letra

Nuestro cliente, el **Hospital de Clínicas**, ha solicitado el desarrollo de dos servicios que deberán ser implementados y alojados en los servidores del Departamento Técnico de Informática (DTI), ubicados en las instalaciones del hospital (piso 6).

### Primer Servicio: Módulo de Gestión de Documentación para Pacientes
* **Objetivo:** Desarrollar un servicio que permita a un funcionario administrativo cargar y gestionar documentación en un servidor, de modo que los pacientes del Hospital de Clínicas puedan acceder a dicha información de forma digital mediante el escaneo de un **código QR**, visualizándola directamente en sus dispositivos móviles sin necesidad de copias impresas.
* **Problemática que resuelve:** Actualmente se destina presupuesto a la impresión de documentos informativos. Esto optimiza recursos, reduce costos y promueve la digitalización.
* **Documentos incluidos (divididos por categoría):**
  * Indicaciones de interrupción voluntaria del embarazo.
  * Prostatectomía radical (indicaciones e información para el paciente).
  * Preparación para estudios imagenológicos.
  * Estudios diagnósticos con pertecneciato.
  * Centellograma de perfusión miocárdica.
  * Indicaciones ecocardiograma con dobutamina.
  * Indicaciones para pacientes en tratamiento con warfarina.
  * Indicaciones ecocardiograma transesofágico.
  * Indicaciones para ingreso a centro de nefrología y trasplante.
  * Plan de alta enfermería, Nefrología.
  * Indicaciones de enfermería para usuarios trasplantados.
  * Prevención de infecciones.
  * Encuesta de satisfacción del usuario trasplantado (**anónimas**).
  * Encuesta estándar de satisfacción (**anónima**).
  * Otras encuestas sobre la evolución de los programas (del Fondo Nacional de Recursos), segmentadas.
  * Pauta para pacientes ostomizados, entre otros.
* **Módulo de Encuestas:** Permite a los pacientes completar formularios de satisfacción de forma digital. Las respuestas se almacenan en el servidor para realizar análisis y calcular indicadores de satisfacción que contribuyan a la mejora continua.

### Segundo Servicio: Módulo de Trazabilidad de Ambulancias
* **Objetivo:** Destinado a la gestión y seguimiento del transporte realizado mediante ambulancias y otros vehículos del hospital, permitiendo registrar y administrar solicitudes de traslado y controles operativos.
* **Datos mínimos por solicitud:**
  * El conductor responsable del vehículo (pueden manejar toda la flota del hospital: ambulancias y autos).
  * El enfermero que viaja con el chofer (es el único responsable del paciente).
  * El paciente o elemento a trasladar (dependiendo de qué se traslade se define si se usa una ambulancia u otro vehículo; los datos del paciente se toman directamente desde una **API externa** consumiendo sus datos por la C.I.).
  * Solicitud de origen (ver workflow de ruta base definida).
  * El destino, la hora de salida y la hora estimada o efectiva de llegada.
  * Gestión de rutas dentro del circuito nacional (no todos los traslados se pueden hacer en cualquier vehículo).
* **Alcance del traslado:** El elemento trasladado puede ser un paciente biológico, equipamiento médico u otros insumos especializados. Las ambulancias parten desde el Hospital de Clínicas y un funcionario administrativo realiza el seguimiento del estado desde la salida hasta la llegada al destino y posterior retorno.

### Infraestructura e Integración
* Las aplicaciones se alojarán en servidores propios del Hospital de Clínicas.
* Se integrarán al sistema centralizado existente mediante un panel principal de acceso. Los usuarios iniciarán sesión con sus credenciales habituales y accederán tanto a los módulos anteriores como a los dos nuevos.
* **Entorno Tecnológico / Servidor:** Servidor de bases de datos en GNU/Linux (distribución a criterio del equipo).
* **Herramientas recomendadas:** Visual Studio Code, XAMPP 8.x (PHP y MySQL en local), VirtualBox 7.x / VMware Workstation 17, y GitHub para control de versiones.

---

## 2. Criterios de Logro: Programación Full Stack

### Primera Entrega

1. **Justificación Tecnológica:**
   * Documento técnico justificando PHP como lenguaje backend (ventajas en sistemas institucionales).
   * Justificación de MySQL/MariaDB (persistencia de datos clínicos/operativos, integridad y consistencia).
   * Evaluación de Bootstrap como framework frontend para interfaces administrativas y móviles (pacientes).
   * Selección de Git/GitHub y entorno de desarrollo (VS Code + extensiones).
   * Relación de tecnologías con acceso móvil (QR), gestión administrativa y persistencia de traslados.

2. **Prototipo de Interfaz de Usuario:**
   * Prototipo visual completo (sin lógica) contemplando ambos módulos de forma individual.

3. **Implementación de Diseño Responsivo:**
   * Uso de FlexBox, Grid o Bootstrap 5.3.
   * Módulos diferenciados: Módulo Documentación (panel de administración, vista accesible por QR *mobile-first*, formulario de encuesta) y Módulo Ambulancias (panel de gestión, alta de traslado, seguimiento de estado). Adaptación correcta a pantallas y experiencia uniforme.

4. **Modelado de Datos:**
   * **DER Inicial:** Entidades principales, atributos y relaciones.
   * **Restricciones No Estructurales:** Documentadas y especificadas para su implementación.
   * **Esquema Relacional Normalizado:** Transformación del DER a tablas normalizadas hasta la **Tercera Forma Normal (3FN)**, identificando claves primarias y foráneas.

5. **Configuración del Entorno de Desarrollo:**
   * Documentación paso a paso (servidor Apache, versión de PHP, MySQL/MariaDB, configuración de IDE y extensiones).

6. **Control de Versiones y Repositorio:**
   * Repositorio en GitHub estructurado, convenciones de commits, código fuente del prototipo y archivo `README.md` con instrucciones claras.

---

### Segunda Entrega (Primera versión funcional - Módulo Documentación)

1. **Base de Datos:**
   * Corrección del DER y esquema relacional según devoluciones.
   * Scripts DDL completos (`CREATE TABLE` con PK, FK y restricciones) ejecutados en phpMyAdmin con evidencia de capturas.
   * Datos de prueba mediante sentencias DML (`INSERT`).
   * Documentación de restricciones no estructurales implementadas.

2. **Autenticación y Gestión de Sesiones:**
   * Login con formulario HTML y validación en PHP.
   * Gestión de sesiones (`session_start()` y `$_SESSION`) y cierre (`logout()`).
   * Diferenciación de al menos dos roles: **administrador** y **usuario básico/paciente**.
   * Rutas protegidas que redirigen al login si no hay sesión activa.

3. **Módulo Documentación (CRUD Funcional):**
   * **Alta:** Formulario con validación en PHP y almacenamiento en BD.
   * **Listado:** Consulta `SELECT` en tabla HTML.
   * **Edición:** Formulario prellenado y `UPDATE` en BD.
   * **Eliminación:** Baja lógica o física con confirmación.
   * Validación en backend (PHP) con mensajes claros. Vista accesible por QR mostrando datos correctos.

4. **Arquitectura y Código:**
   * Organización en tres capas visibles en carpetas: **Presentación** (HTML, CSS, JS), **Negocio** (PHP) y **Datos** (acceso a BD).
   * Al menos **dos clases PHP** con atributos y métodos del dominio.
   * Código comentado en secciones principales.

5. **Control de Versiones:**
   * GitHub actualizado, commits frecuentes y descriptivos, `README.md` al día.

6. **Módulo Ambulancias (Avance):**
   * Alta de traslado (conductor, enfermero, origen, destino), listado con estado actual, edición y seguimiento. Ingreso manual de datos del paciente.

---

### Third Entrega (Sistema Completo e Integrado)

1. **Base de Datos (Versión Final):**
   * Scripts DDL + DML finales listos para producción.
   * Creación de usuarios de base de datos con distintos privilegios (administrador y aplicación con acceso restringido).
   * Vistas SQL para consultas frecuentes.

2. **Seguridad:**
   * **Protección contra inyección SQL:** Consultas preparadas obligatorias (PDO o MySQLi con *prepared statements*).
   * **Hashing de contraseñas:** Uso de `password_hash()` y `password_verify()` (prohibido texto plano).
   * Validación dual (Frontend en JavaScript y Backend en PHP).
   * Control de acceso basado en roles.

3. **Módulo Documentación (Funcionalidad Completa):**
   * CRUD completo, sistema de encuestas de satisfacción anónimas con almacenamiento y visualización básica de resultados.
   * Generación/simulación de código QR para acceso móvil de pacientes.
   * Vista *mobile-first* optimizada y panel de administración con filtros.

4. **Arquitectura, Código y Documentación:**
   * Estructura de tres capas limpia, clases documentadas, nombres descriptivos.
   * Manual de usuario (con capturas) y documentación técnica final (DER definitivo, esquema relacional, diagramas de clases/secuencia coordinados con Ingeniería de Software).

5. **Pruebas e Instalación:**
   * Pruebas funcionales documentadas (casos de prueba básicos).
   * Instalabilidad en otra máquina siguiendo el `README`.
   * Entrega en USB con código fuente ejecutable, scripts SQL, credenciales de prueba y documentación.

6. **Módulo Ambulancias (Final):**
   * Gestión completa (alta, edición, seguimiento, conductor, enfermero, vehículo, origen, destino, horarios).
   * Datos del paciente: ingreso manual mínimo, e **integración con API externa por C.I.** como logro máximo.
   * Historial filtrable por paciente o vehículo.

---

### Requerimientos Mínimos Obligatorios (Resumen Core)
* **Tecnologías:** PHP, MySQL/MariaDB, HTML5, CSS3, JavaScript básico, GitHub, XAMPP.
* **Funcionalidades:** Login, Logout, sesiones PHP, 2 roles (admin / usuario básico), panel de administración, CRUD de documentos por categoría, vista pública por QR y diseño responsive.
* **Base de datos:** DER, relacional, DDL/DML, PK, FK, normalización básica.
* **Seguridad:** `password_hash()`, validaciones backend/frontend, consultas preparadas (PDO/MySQLi), protección por sesiones.
* **Organización:** Capas (Presentación, Lógica, Datos), mínimo 2 clases PHP, nombres descriptivos, comentarios, repositorio GitHub con `README.md`, manuales de instalación y uso, scripts SQL y casos de prueba.