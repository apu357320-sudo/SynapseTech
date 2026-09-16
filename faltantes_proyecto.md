# SIGSM: pendientes para finalizar el proyecto

Este documento resume lo que falta implementar, corregir y validar para que el SIGSM pueda considerarse terminado según `contexto.md`.

## Estado general

El proyecto tiene una base funcional inicial de autenticación, usuarios, documentos y traslados. También existen el esquema SQL, páginas HTML/PHP, JavaScript y estilos. Sin embargo, varios contratos entre la base de datos y el backend no coinciden, y los módulos de encuestas, QR y reportes están incompletos.

La prioridad principal es hacer que el modelo de datos y el código utilicen los mismos nombres, claves y reglas. Después se deben completar los flujos de negocio, la seguridad, las pruebas y la documentación de despliegue.

## Prioridad 1: bloqueos funcionales

### 1. Unificar el esquema SQL y el backend

- El SQL define `traslado`, pero el backend utiliza `traslados`.
- El SQL define `chofer` y `enfermero`, pero el backend consulta `choferes` y `enfermeros`.
- El SQL utiliza `id_traslado`; el JavaScript y PHP utilizan `id`.
- El SQL relaciona choferes y enfermeros mediante cédulas (`ci_chofer` y `ci_enfermero`); el formulario envía identificadores numéricos.
- El SQL utiliza `matricula` para vehículos; el formulario y backend utilizan `vehiculo` como texto libre.
- El SQL registra `hora_llegada_efectiva`; el backend intenta escribir `hora_llegada_real`.
- El SQL no tiene una columna `observaciones` en `traslado`; esa información pertenece a `traslado_elemento`.
- El listado ordena por `fecha`, pero la tabla SQL no define esa columna.
- Debe decidirse si se adapta el SQL al código o, preferiblemente, si se adapta el código al modelo normalizado existente.

### 2. Completar el módulo de trazabilidad

- Corregir alta, listado, actualización y cancelación para usar el esquema real.
- Crear consultas para obtener vehículos, choferes y enfermeros disponibles.
- Validar en backend que los recursos estén habilitados y disponibles.
- Relacionar el vehículo mediante su matrícula y no mediante texto libre.
- Relacionar chofer y enfermero mediante sus claves reales.
- Implementar los tipos de elemento definidos por el negocio: paciente, material biológico, equipamiento e insumos.
- Guardar correctamente la relación entre traslado y elemento en `traslado_elemento`.
- Completar fecha/hora efectiva de llegada.
- Agregar fecha/hora efectiva de retorno a la base.
- Validar las transiciones: `Solicitado -> En tránsito -> Llegada a destino -> En retorno -> Finalizado en base`.
- Impedir saltos de estado no autorizados.
- Registrar quién cambió cada estado y cuándo.
- Agregar filtros por estado, fecha, vehículo, chofer, paciente o elemento.
- Agregar búsqueda, ordenación y paginación del historial.
- Añadir una vista operativa para monitoreo y otra para consulta histórica.
- Implementar manejo de errores visible para el usuario y registro técnico de errores.

### 3. Completar la integración de pacientes

- Implementar `php/apiPacientes.php`, que actualmente no contiene la integración necesaria.
- Definir el contrato de la API externa: URL, autenticación, timeout, formato y errores.
- Buscar pacientes por C.I.
- Permitir carga manual como mecanismo alternativo.
- Validar formato de C.I., nombre y apellido en frontend y backend.
- Evitar almacenar información clínica innecesaria o sin protección.
- Definir qué ocurre si la API externa no está disponible.

## Prioridad 2: módulos incompletos

### 4. Gestión documental

- Completar las categorías institucionales solicitadas en `contexto.md`.
- Añadir selección de categoría al formulario de carga.
- Implementar búsqueda, filtrado y ordenación por título, categoría y fecha.
- Completar edición de metadatos y contenido.
- Definir si la baja será lógica o física.
- Validar extensión, MIME, tamaño y contenido de cada archivo.
- Generar nombres de archivo seguros y no predecibles.
- Evitar ejecución de archivos subidos.
- Definir almacenamiento seguro y permisos del directorio de documentos.
- Proteger las rutas de descarga cuando el documento no sea público.
- Completar la vista pública para pacientes sin autenticación.
- Mostrar título, categoría, descripción y documento de forma optimizada para móvil.

### 5. Códigos QR

- Implementar `js/qr.js`, que actualmente está vacío.
- Generar un QR por documento o por URL pública.
- Guardar la URL o identificador del QR en la base.
- Crear una ruta pública estable para resolver el documento.
- Validar que el QR no exponga rutas internas ni datos sensibles.
- Permitir regenerar o invalidar un QR.
- Probar el acceso desde un teléfono sin sesión iniciada.

### 6. Encuestas

- Implementar `js/encuestas.js`, que actualmente está vacío.
- Completar `php/guardarEncuesta.php`.
- Completar `php/reportes.php`.
- Crear el CRUD de encuestas y preguntas.
- Permitir activar, desactivar y versionar encuestas.
- Segmentar encuestas por servicio o programa médico.
- Crear el formulario público anónimo.
- Guardar respuestas y detalles en `respuesta_encuesta` y `detalle_respuesta`.
- Validar respuestas obligatorias y tipos de pregunta.
- Evitar almacenar datos que permitan identificar al paciente.
- Crear KPIs: respuestas recibidas, promedio, distribución y evolución temporal.
- Añadir filtros por encuesta, servicio y período.
- Mostrar reportes en el panel administrativo.

## Prioridad 3: seguridad y reglas de negocio

### 7. Autenticación y autorización

- Revisar endpoint por endpoint y aplicar el rol mínimo necesario.
- Separar claramente permisos de administrador, personal operativo, chofer, enfermero y paciente.
- Evitar que cualquier usuario autenticado pueda modificar estados o eliminar información.
- Regenerar el ID de sesión después del inicio de sesión.
- Configurar cookies con `HttpOnly`, `Secure` cuando corresponda y `SameSite` adecuado.
- Configurar expiración e inactividad de sesión.
- Mantener `password_hash()` y `password_verify()` para contraseñas.
- Evitar respuestas de error que revelen estructura interna o consultas SQL.

### 8. Protección de formularios y salidas

- Implementar tokens CSRF en formularios y endpoints que modifican datos.
- Aplicar validación estricta en backend, además de la validación JavaScript.
- Escapar datos antes de insertarlos en HTML.
- Revisar el uso de `innerHTML` en tablas y reemplazarlo por construcción segura cuando los datos provengan de usuarios o de la base.
- Validar fechas, estados, identificadores, roles y relaciones existentes.
- Añadir límites de tamaño y frecuencia para cargas y formularios.
- Centralizar respuestas JSON y códigos HTTP.
- Añadir logging de accesos y operaciones sensibles.

## Prioridad 4: frontend y experiencia de uso

### 9. Pantallas y navegación

- Completar las páginas que actualmente son estructuras mínimas, especialmente encuestas.
- Unificar encabezados, navegación, mensajes y pie de página.
- Eliminar estilos duplicados entre `estilo/style.css`, `estilo/estilos.css`, `panelcontrol.css`, `pacientes.css` y `traslado.css`.
- Revisar enlaces antiguos y páginas que quedaron de prototipos anteriores.
- Mostrar estados de carga, errores de red y confirmaciones de éxito.
- Añadir confirmaciones específicas para eliminaciones y cambios irreversibles.
- Revisar navegación móvil y tamaños de tablas.
- Mantener el logo local y verificar sus rutas desde cada subcarpeta.
- Añadir estados vacíos cuando no existan documentos, traslados o respuestas.

### 10. Accesibilidad

- Revisar contraste de colores.
- Mantener etiquetas asociadas a todos los controles.
- Añadir mensajes accesibles mediante `aria-live`.
- Garantizar navegación completa por teclado.
- Revisar foco visible y orden de tabulación.
- Usar encabezados en orden jerárquico.
- Proporcionar textos alternativos adecuados para imágenes y QR.

## Prioridad 5: base de datos y calidad

### 11. Modelo de datos

- Confirmar que todas las tablas estén en 3FN.
- Revisar nombres, tipos y longitudes de columnas.
- Añadir índices para búsquedas frecuentes.
- Añadir restricciones de unicidad donde corresponda.
- Definir políticas de eliminación y actualización de claves foráneas.
- Crear vistas para historial de traslados, documentos públicos y reportes de encuestas.
- Añadir datos iniciales realistas para vehículos, roles, choferes y enfermeros.
- Separar datos de demostración de datos de producción.
- Definir migraciones versionadas en lugar de depender únicamente de un dump completo.

### 12. Pruebas

- Probar instalación limpia de la base de datos.
- Probar inicio y cierre de sesión.
- Probar permisos por cada rol.
- Probar alta, edición, listado y baja de usuarios.
- Probar carga y descarga de documentos.
- Probar acceso público mediante QR.
- Probar creación y transición completa de un traslado.
- Probar indisponibilidad de vehículo, chofer o enfermero.
- Probar consulta de paciente existente y carga manual.
- Probar envío anónimo de encuestas.
- Probar reportes con cero respuestas y con muchos registros.
- Probar entradas inválidas, sesiones vencidas y solicitudes sin autorización.
- Probar visualización en móvil y escritorio.
- Automatizar pruebas de endpoints y, cuando sea posible, pruebas de navegador.

## Prioridad 6: despliegue y documentación

- Documentar instalación en XAMPP y Apache.
- Documentar versión de PHP, MariaDB y extensiones requeridas.
- Documentar creación de la base y configuración de credenciales.
- Sacar credenciales del código y usar configuración local no versionada.
- Documentar permisos de carpetas de documentos.
- Documentar integración con el panel institucional.
- Documentar configuración de la API externa de pacientes.
- Documentar copias de seguridad y restauración.
- Documentar procedimiento de actualización y migración.
- Crear una guía de usuario para administración y operación.
- Crear una guía breve para el acceso del paciente mediante QR.
- Definir criterios de aceptación para declarar terminada cada funcionalidad.

## Orden recomendado de trabajo

1. Congelar el modelo de datos y corregir la incompatibilidad SQL/PHP.
2. Completar autenticación, roles y protección CSRF.
3. Terminar el flujo de trazabilidad con datos reales de vehículos, choferes, enfermeros y pacientes.
4. Terminar documentos, categorías y acceso público por QR.
5. Implementar encuestas y reportes.
6. Unificar frontend y accesibilidad.
7. Ejecutar pruebas de integración y seguridad.
8. Documentar instalación, operación y despliegue.
9. Realizar una prueba de aceptación con los flujos completos del hospital.

## Criterio de finalización

El proyecto podrá considerarse terminado cuando:

- La base de datos y el backend utilicen exactamente las mismas tablas y columnas.
- Cada rol solo pueda realizar las operaciones autorizadas.
- Un traslado pueda recorrer todo su ciclo y dejar trazabilidad completa.
- Un paciente pueda acceder a un documento mediante QR sin iniciar sesión.
- Las encuestas puedan responderse de forma anónima y producir indicadores.
- Los archivos subidos estén validados y protegidos.
- Existan pruebas reproducibles de los flujos principales.
- La instalación en un servidor limpio esté documentada y pueda repetirse.
- No queden endpoints vacíos, páginas de demostración ni consultas incompatibles con el esquema final.
