// Panel de administración (admin/index.php): usuarios y documentos.

const mensajeAdmin = document.getElementById('mensajeAdmin');
const tablaUsuarios = document.getElementById('tablaUsuarios');
const tablaDocumentos = document.getElementById('tablaDocumentos');
const formularioUsuario = document.getElementById('formularioUsuario');

// Usuarios de la última carga, para tener sus datos a mano al editar.
let usuariosCargados = [];

async function enviar(url, datos) {
  const respuesta = await fetch(url, { method: 'POST', body: datos });
  return respuesta.json();
}

function mostrarMensaje(respuesta) {
  mensajeAdmin.textContent = respuesta.mensaje;
  mensajeAdmin.className = respuesta.exito ? 'mensaje exito' : 'mensaje';
}

// Las celdas se cargan con textContent y no con innerHTML: así, si un dato
// trae etiquetas HTML, se muestra como texto y no se ejecuta.
function agregarCelda(fila, texto) {
  const celda = document.createElement('td');
  celda.textContent = texto;
  fila.appendChild(celda);
}

// Crea un botón que guarda el id en un atributo data-*, por ejemplo data-editar="5".
function crearBoton(texto, nombreDato, id) {
  const boton = document.createElement('button');
  boton.type = 'button';
  boton.textContent = texto;
  boton.dataset[nombreDato] = id;
  return boton;
}

// ---------- Usuarios ----------

async function cargarUsuarios() {
  const datos = await (await fetch('../php/usuarios.php')).json();
  usuariosCargados = datos.usuarios;
  tablaUsuarios.replaceChildren();
  datos.usuarios.forEach((usuario) => {
    const fila = document.createElement('tr');
    agregarCelda(fila, usuario.numeroId);
    agregarCelda(fila, usuario.nombre_completo);
    agregarCelda(fila, usuario.email);
    agregarCelda(fila, usuario.rol);

    const acciones = document.createElement('td');
    acciones.append(crearBoton('Editar', 'editar', usuario.id_usuario), ' ', crearBoton('Eliminar', 'eliminar', usuario.id_usuario));
    fila.appendChild(acciones);
    tablaUsuarios.appendChild(fila);
  });
}

formularioUsuario.addEventListener('submit', async (evento) => {
  evento.preventDefault();
  if (!/^\d{7,8}$/.test(formularioUsuario.numeroId.value.trim())) {
    mostrarMensaje({ exito: false, mensaje: 'La cédula debe tener 7 u 8 dígitos, sin puntos ni guion.' });
    return;
  }
  const respuesta = await enviar('../php/registrarUsuario.php', new FormData(formularioUsuario));
  mostrarMensaje(respuesta);
  if (respuesta.exito) {
    formularioUsuario.reset();
    cargarUsuarios();
  }
});

tablaUsuarios.addEventListener('click', async (evento) => {
  const boton = evento.target;
  const id = boton.dataset.eliminar || boton.dataset.editar;
  if (!id) return;

  const datos = new FormData();
  datos.append('id_usuario', id);

  if (boton.dataset.eliminar) {
    if (!confirm('¿Quieres eliminar este usuario?')) return;
    mostrarMensaje(await enviar('../php/eliminarUsuario.php', datos));
  } else {
    // Los cuadros de diálogo ya vienen con el valor actual para no escribirlo de nuevo.
    const usuario = usuariosCargados.find((uno) => String(uno.id_usuario) === id);
    const nombre = prompt('Nuevo nombre completo:', usuario.nombre_completo);
    const email = prompt('Nuevo email:', usuario.email);
    const rol = prompt('Nuevo rol:', usuario.rol);
    const password = prompt('Nueva contraseña (deja vacío para conservarla):');
    if (!nombre || !email || !rol) return;

    datos.append('nombre_completo', nombre);
    datos.append('email', email);
    datos.append('rol', rol);
    if (password) datos.append('password', password);
    mostrarMensaje(await enviar('../php/actualizarUsuario.php', datos));
  }
  cargarUsuarios();
});

// ---------- Documentos ----------

const filtroCategoria = document.getElementById('filtroCategoria');
const qrDocumento = document.getElementById('qrDocumento');
const textoQr = document.getElementById('textoQr');
let categoriasCargadas = false;

// Pide los documentos de la categoría elegida en el filtro (o todos).
async function cargarDocumentosAdmin() {
  const datos = await (await fetch(`../php/obtener_doc.php?categoria=${filtroCategoria.value}`)).json();

  // Las opciones del filtro se cargan una sola vez.
  if (!categoriasCargadas) {
    datos.categorias.forEach((categoria) => {
      const opcion = document.createElement('option');
      opcion.value = categoria.id;
      opcion.textContent = categoria.nombre;
      filtroCategoria.appendChild(opcion);
    });
    categoriasCargadas = true;
  }

  tablaDocumentos.replaceChildren();
  datos.documentos.forEach((documento) => {
    const fila = document.createElement('tr');
    agregarCelda(fila, documento.titulo);
    agregarCelda(fila, documento.categoria);
    agregarCelda(fila, documento.archivo_nombre);
    agregarCelda(fila, documento.fecha_subida);

    const acciones = document.createElement('td');
    acciones.append(crearBoton('Ver QR', 'docQr', documento.id), ' ', crearBoton('Editar título', 'docEditar', documento.id), ' ', crearBoton('Eliminar', 'docEliminar', documento.id));
    fila.appendChild(acciones);
    tablaDocumentos.appendChild(fila);
  });
}

filtroCategoria.addEventListener('change', cargarDocumentosAdmin);

tablaDocumentos.addEventListener('click', async (evento) => {
  const boton = evento.target;

  // QR del documento: lleva a la vista de pacientes con ese documento ya elegido.
  if (boton.dataset.docQr) {
    const direccion = new URL(`../usuario/paciente.html?id=${boton.dataset.docQr}`, window.location.href).href;
    dibujarQr(qrDocumento, direccion); // función de qr.js
    textoQr.textContent = `Código QR de "${boton.closest('tr').cells[0].textContent}": ${direccion}`;
    return;
  }

  const id = boton.dataset.docEliminar || boton.dataset.docEditar;
  if (!id) return;

  const datos = new FormData();
  datos.append('id', id);

  if (boton.dataset.docEliminar) {
    if (!confirm('¿Quieres eliminar este documento y su archivo?')) return;
    mostrarMensaje(await enviar('../php/eliminarDocumento.php', datos));
  } else {
    const titulo = prompt('Nuevo título:');
    if (!titulo) return;
    datos.append('titulo', titulo);
    mostrarMensaje(await enviar('../php/actualizarDocumento.php', datos));
  }
  cargarDocumentosAdmin();
});

Promise.all([cargarUsuarios(), cargarDocumentosAdmin()]).catch(() => {
  mensajeAdmin.textContent = 'No se pudieron cargar los datos.';
});
