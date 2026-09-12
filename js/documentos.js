const formularioCarga = document.getElementById('formularioCarga');
const listaDocumentos = document.getElementById('listaDocumentos');
const mensajeDocumentos = document.getElementById('mensajeDocumentos');
const vistaDocumento = document.getElementById('vistaDocumento');
const vistaImagen = document.getElementById('vistaImagen');

async function cargarDocumentos() {
  if (!listaDocumentos) return;
  const respuesta = await fetch('../php/obtener_doc.php');
  const datos = await respuesta.json();
  if (!datos.exito) throw new Error(datos.mensaje);
  listaDocumentos.innerHTML = '<option value="">Seleccioná un documento</option>';
  datos.documentos.forEach((documento) => {
    const opcion = document.createElement('option');
    opcion.value = documento.id;
    opcion.textContent = documento.titulo;
    opcion.dataset.ruta = `../${documento.archivo_ruta}`;
    opcion.dataset.nombre = documento.archivo_nombre;
    if (new URLSearchParams(window.location.search).get('id') === String(documento.id)) opcion.selected = true;
    listaDocumentos.appendChild(opcion);
  });
}

formularioCarga?.addEventListener('submit', async (evento) => {
  evento.preventDefault();
  const archivo = formularioCarga.archivo.files[0];
  if (!formularioCarga.titulo.value.trim() || !archivo) {
    mensajeDocumentos.textContent = 'Completá el título y seleccioná un archivo.';
    return;
  }
  const respuesta = await fetch('../php/cargar_doc.php', { method: 'POST', body: new FormData(formularioCarga) });
  const datos = await respuesta.json();
  mensajeDocumentos.textContent = datos.mensaje;
  mensajeDocumentos.className = datos.exito ? 'mensaje exito' : 'mensaje';
  if (datos.exito) { formularioCarga.reset(); await cargarDocumentos(); }
});

listaDocumentos?.addEventListener('change', () => {
  const opcion = listaDocumentos.selectedOptions[0];
  if (!opcion?.dataset.ruta) return;
  const ruta = opcion.dataset.ruta;
  const nombre = opcion.dataset.nombre.toLowerCase();
  if (/\.(jpg|jpeg|png|gif)$/.test(nombre)) {
    vistaDocumento.style.display = 'none'; vistaImagen.style.display = 'block'; vistaImagen.src = ruta;
  } else {
    vistaImagen.style.display = 'none'; vistaDocumento.style.display = 'block'; vistaDocumento.src = ruta;
  }
});

cargarDocumentos().then(() => {
  if (listaDocumentos?.value) listaDocumentos.dispatchEvent(new Event('change'));
}).catch((error) => { if (mensajeDocumentos) mensajeDocumentos.textContent = error.message; });
