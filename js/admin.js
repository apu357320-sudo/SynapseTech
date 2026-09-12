const mensajeAdmin = document.getElementById('mensajeAdmin');
const tablaUsuarios = document.getElementById('tablaUsuarios');
const tablaDocumentos = document.getElementById('tablaDocumentos');
const formularioUsuario = document.getElementById('formularioUsuario');

async function enviar(url, datos) {
  const respuesta = await fetch(url, { method: 'POST', body: datos });
  return respuesta.json();
}

async function cargarUsuarios() {
  const datos = await (await fetch('../php/usuarios.php')).json();
  tablaUsuarios.innerHTML = '';
  datos.usuarios.forEach((usuario) => {
    const fila = document.createElement('tr');
    fila.innerHTML = `<td>${usuario.nombre_usuario}</td><td>${usuario.nombre_completo}</td><td>${usuario.email}</td><td>${usuario.rol}</td><td>${usuario.numeroId}</td><td><button data-editar="${usuario.id_usuario}">Editar</button> <button data-eliminar="${usuario.id_usuario}">Eliminar</button></td>`;
    tablaUsuarios.appendChild(fila);
  });
}

async function cargarDocumentosAdmin() {
  const datos = await (await fetch('../php/obtener_doc.php')).json();
  tablaDocumentos.innerHTML = '';
  datos.documentos.forEach((documento) => {
    const fila = document.createElement('tr');
    fila.innerHTML = `<td>${documento.titulo}</td><td>${documento.archivo_nombre}</td><td>${documento.fecha_subida}</td><td><button data-doc-editar="${documento.id}">Editar título</button> <button data-doc-eliminar="${documento.id}">Eliminar</button></td>`;
    tablaDocumentos.appendChild(fila);
  });
}

formularioUsuario.addEventListener('submit', async (evento) => {
  evento.preventDefault();
  const datos = await enviar('../php/registrarUsuario.php', new FormData(formularioUsuario));
  mensajeAdmin.textContent = datos.mensaje;
  mensajeAdmin.className = datos.exito ? 'mensaje exito' : 'mensaje';
  if (datos.exito) { formularioUsuario.reset(); cargarUsuarios(); }
});

tablaUsuarios.addEventListener('click', async (evento) => {
  const boton = evento.target;
  const id = boton.dataset.eliminar || boton.dataset.editar;
  if (!id) return;
  if (boton.dataset.eliminar) {
  if (!confirm('¿Querés eliminar este usuario?')) return;
    const datos = new FormData(); datos.append('id_usuario', id);
    const respuesta = await enviar('../php/eliminarUsuario.php', datos); mensajeAdmin.textContent = respuesta.mensaje;
  } else {
    const nombre = prompt('Nuevo nombre completo:'); const email = prompt('Nuevo email:'); const rol = prompt('Nuevo rol:', 'Personal Administrativo'); const password = prompt('Nueva contraseña (dejá vacío para conservarla):');
    if (!nombre || !email || !rol) return;
    const datos = new FormData(); datos.append('id_usuario', id); datos.append('nombre_completo', nombre); datos.append('email', email); datos.append('rol', rol);
    if (password) datos.append('password', password);
    const respuesta = await enviar('../php/actualizarUsuario.php', datos); mensajeAdmin.textContent = respuesta.mensaje;
  }
  cargarUsuarios();
});

tablaDocumentos.addEventListener('click', async (evento) => {
  const boton = evento.target; const id = boton.dataset.docEliminar || boton.dataset.docEditar;
  if (!id) return;
  const datos = new FormData(); datos.append('id', id);
  if (boton.dataset.docEliminar) { if (!confirm('¿Querés eliminar este documento y su archivo?')) return; const respuesta = await enviar('../php/eliminarDocumento.php', datos); mensajeAdmin.textContent = respuesta.mensaje; }
  else { const titulo = prompt('Nuevo título:'); if (!titulo) return; datos.append('titulo', titulo); const respuesta = await enviar('../php/actualizarDocumento.php', datos); mensajeAdmin.textContent = respuesta.mensaje; }
  cargarDocumentosAdmin();
});

Promise.all([cargarUsuarios(), cargarDocumentosAdmin()]).catch(() => { mensajeAdmin.textContent = 'No se pudieron cargar los datos.'; });
