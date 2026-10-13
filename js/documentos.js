// Documentos. Lo usan dos páginas:
//  - usuario/cargar_documento.php: formulario de carga + vista previa.
//  - usuario/paciente.html: vista pública para pacientes (la del QR).
// Por eso antes de usar un elemento se pregunta si existe en la página.

const formularioCarga = document.getElementById('formularioCarga');
const selectCategoria = document.getElementById('categoria');           // categoría del documento nuevo
const filtroCategoria = document.getElementById('filtroCategoria');     // filtro de la lista
const listaDocumentos = document.getElementById('listaDocumentos');
const mensajeDocumentos = document.getElementById('mensajeDocumentos');
const vistaDocumento = document.getElementById('vistaDocumento');
const vistaImagen = document.getElementById('vistaImagen');
const enlaceDocumento = document.getElementById('enlaceDocumento');

// Si la dirección trae ?id=5 (así llega el QR de un documento), ese documento queda elegido.
const idPedido = new URLSearchParams(window.location.search).get('id');
let categoriasCargadas = false;

function agregarOpcion(select, valor, texto) {
  const opcion = document.createElement('option');
  opcion.value = valor;
  opcion.textContent = texto;
  select.appendChild(opcion);
  return opcion;
}

// Pide los documentos (de una categoría, o todos) y llena el select.
async function cargarDocumentos() {
  const categoria = filtroCategoria ? filtroCategoria.value : '';
  const respuesta = await fetch(`../php/obtener_doc.php?categoria=${categoria}`);
  const datos = await respuesta.json();
  if (!datos.exito) throw new Error(datos.mensaje);

  // Las categorías se cargan una sola vez, en los select que tenga la página.
  if (!categoriasCargadas) {
    datos.categorias.forEach((una) => {
      if (selectCategoria) agregarOpcion(selectCategoria, una.id, una.nombre);
      if (filtroCategoria) agregarOpcion(filtroCategoria, una.id, una.nombre);
    });
    categoriasCargadas = true;
  }

  listaDocumentos.replaceChildren();
  agregarOpcion(listaDocumentos, '', datos.documentos.length ? 'Selecciona un documento' : 'No hay documentos en esta categoría');
  datos.documentos.forEach((documento) => {
    const opcion = agregarOpcion(listaDocumentos, documento.id, `${documento.titulo} (${documento.categoria})`);
    opcion.dataset.ruta = `../${documento.archivo_ruta}`;
    opcion.dataset.nombre = documento.archivo_nombre;
    if (idPedido === String(documento.id)) opcion.selected = true;
  });
  mostrarDocumento();
}

// Muestra el documento elegido: las imágenes en un <img> y el resto en un <iframe>.
function mostrarDocumento() {
  const opcion = listaDocumentos.selectedOptions[0];
  const ruta = opcion ? opcion.dataset.ruta : '';

  vistaImagen.style.display = 'none';
  vistaDocumento.style.display = 'none';
  if (enlaceDocumento) enlaceDocumento.style.display = ruta ? '' : 'none';
  if (!ruta) return;

  // El enlace sirve en los celulares que no muestran PDF dentro de la página.
  if (enlaceDocumento) enlaceDocumento.href = ruta;

  if (/\.(jpg|jpeg|png|gif)$/.test(opcion.dataset.nombre.toLowerCase())) {
    vistaImagen.style.display = 'block';
    vistaImagen.src = ruta;
  } else {
    vistaDocumento.style.display = 'block';
    vistaDocumento.src = ruta;
  }
}

function avisarError(error) {
  mensajeDocumentos.textContent = error.message;
  mensajeDocumentos.className = 'mensaje';
}

listaDocumentos.addEventListener('change', mostrarDocumento);
filtroCategoria?.addEventListener('change', () => cargarDocumentos().catch(avisarError));

// Carga de un documento nuevo (solo en la página que tiene el formulario).
formularioCarga?.addEventListener('submit', async (evento) => {
  evento.preventDefault();
  const archivo = formularioCarga.archivo.files[0];
  if (!formularioCarga.titulo.value.trim() || !selectCategoria.value || !archivo) {
    avisarError(new Error('Completa el título, la categoría y selecciona un archivo.'));
    return;
  }
  const respuesta = await fetch('../php/cargar_doc.php', { method: 'POST', body: new FormData(formularioCarga) });
  const datos = await respuesta.json();
  mensajeDocumentos.textContent = datos.mensaje;
  mensajeDocumentos.className = datos.exito ? 'mensaje exito' : 'mensaje';
  if (datos.exito) {
    formularioCarga.reset();
    await cargarDocumentos();
  }
});

cargarDocumentos().catch(avisarError);
