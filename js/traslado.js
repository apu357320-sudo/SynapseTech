// Seguimiento de traslados (admin/traslados.php): listado, filtro y cambio de estado.

const listaTraslados = document.getElementById('listaTraslados');
const mensajeTraslado = document.getElementById('mensajeTraslado');
const filtroTraslados = document.getElementById('filtroTraslados');
const estadosTraslado = ['Solicitado', 'En camino', 'En destino', 'Retornando', 'Finalizado'];

async function enviarTraslado(url, datos) {
  const respuesta = await fetch(url, { method: 'POST', body: datos });
  const resultado = await respuesta.json();
  if (!respuesta.ok || !resultado.exito) throw new Error(resultado.mensaje || 'No se pudo completar la operación.');
  return resultado;
}

function agregarCelda(fila, valor) {
  const celda = document.createElement('td');
  celda.textContent = valor || '—';
  fila.appendChild(celda);
}

// Pasa "2026-10-20 08:30:00" (formato de MySQL) a "20/10/2026 08:30".
function formatearFecha(fecha) {
  if (!fecha) return '';
  const [dia, hora] = fecha.split(' ');
  const [anio, mes, numeroDia] = dia.split('-');
  return `${numeroDia}/${mes}/${anio} ${hora.slice(0, 5)}`;
}

// Muestra solo las filas cuyo paciente o vehículo contiene el texto buscado.
function filtrarTraslados() {
  const texto = filtroTraslados.value.trim().toLowerCase();
  listaTraslados.querySelectorAll('tr').forEach((fila) => {
    const coincide = !fila.dataset.busqueda || fila.dataset.busqueda.includes(texto);
    fila.style.display = coincide ? '' : 'none';
  });
}

function crearFila(traslado) {
  const fila = document.createElement('tr');
  // Texto en minúsculas por el que se puede buscar esta fila desde el filtro.
  fila.dataset.busqueda = `${traslado.paciente_nombre} ${traslado.paciente_ci} ${traslado.matricula}`.toLowerCase();
  agregarCelda(fila, traslado.paciente_nombre || traslado.paciente_ci);
  agregarCelda(fila, traslado.matricula);
  agregarCelda(fila, formatearFecha(traslado.hora_salida));
  agregarCelda(fila, traslado.origen);
  agregarCelda(fila, traslado.destino);
  agregarCelda(fila, traslado.chofer);
  agregarCelda(fila, traslado.enfermero);
  agregarCelda(fila, traslado.estado || 'Solicitado');

  const celdaAcciones = document.createElement('td');
  const acciones = document.createElement('div');
  acciones.className = 'acciones-traslado';
  const selectorEstado = document.createElement('select');
  selectorEstado.dataset.estado = '';
  selectorEstado.setAttribute('aria-label', `Nuevo estado para ${traslado.paciente_nombre || traslado.paciente_ci}`);

  estadosTraslado.forEach((estado) => {
    const opcion = document.createElement('option');
    opcion.value = estado;
    opcion.textContent = estado;
    opcion.selected = estado === traslado.estado;
    selectorEstado.appendChild(opcion);
  });

  const botonActualizar = document.createElement('button');
  botonActualizar.type = 'button';
  botonActualizar.dataset.actualizar = traslado.id;
  botonActualizar.textContent = 'Actualizar';

  const botonCancelar = document.createElement('button');
  botonCancelar.type = 'button';
  botonCancelar.dataset.cancelar = traslado.id;
  botonCancelar.textContent = 'Cancelar';

  // Un traslado finalizado o cancelado ya está cerrado: no se puede cambiar.
  const cerrado = traslado.estado === 'Finalizado' || traslado.estado === 'Cancelado';
  selectorEstado.disabled = cerrado;
  botonActualizar.disabled = cerrado;
  botonCancelar.disabled = cerrado;

  acciones.append(selectorEstado, botonActualizar, botonCancelar);
  celdaAcciones.appendChild(acciones);
  fila.appendChild(celdaAcciones);
  return fila;
}

async function cargarTraslados() {
  const respuesta = await fetch('../php/traslados.php');
  const resultado = await respuesta.json();
  if (!respuesta.ok || !resultado.exito) throw new Error(resultado.mensaje || 'No se pudieron cargar los traslados.');

  listaTraslados.replaceChildren();
  if (!resultado.traslados.length) {
    const fila = document.createElement('tr');
    const celda = document.createElement('td');
    celda.colSpan = 9;
    celda.textContent = 'No hay traslados para mostrar.';
    fila.appendChild(celda);
    listaTraslados.appendChild(fila);
    return;
  }

  resultado.traslados.forEach((traslado) => listaTraslados.appendChild(crearFila(traslado)));
  filtrarTraslados(); // mantiene el filtro después de recargar la tabla
}

filtroTraslados.addEventListener('input', filtrarTraslados);

listaTraslados?.addEventListener('click', async (evento) => {
  const boton = evento.target.closest('button');
  if (!boton) return;

  const datos = new FormData();
  datos.append('id', boton.dataset.actualizar || boton.dataset.cancelar);

  try {
    if (boton.dataset.actualizar) {
      const fila = boton.closest('tr');
      datos.append('estado', fila.querySelector('[data-estado]').value);
      const resultado = await enviarTraslado('../php/actualizarTraslado.php', datos);
      mensajeTraslado.textContent = resultado.mensaje;
      mensajeTraslado.className = 'mensaje exito';
    } else if (boton.dataset.cancelar && window.confirm('¿Quieres cancelar este traslado?')) {
      const resultado = await enviarTraslado('../php/eliminarTraslado.php', datos);
      mensajeTraslado.textContent = resultado.mensaje;
      mensajeTraslado.className = 'mensaje exito';
    } else {
      return;
    }

    await cargarTraslados();
  } catch (error) {
    mensajeTraslado.textContent = error.message;
    mensajeTraslado.className = 'mensaje';
  }
});

cargarTraslados().catch((error) => {
  mensajeTraslado.textContent = error.message;
});