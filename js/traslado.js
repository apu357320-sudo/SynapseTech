const listaTraslados = document.getElementById('listaTraslados');
const mensajeTraslado = document.getElementById('mensajeTraslado');
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

function crearFila(traslado) {
  const fila = document.createElement('tr');
  agregarCelda(fila, traslado.paciente_nombre || traslado.paciente_ci);
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
  botonCancelar.disabled = traslado.estado === 'Finalizado' || traslado.estado === 'Cancelado';

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
    celda.colSpan = 7;
    celda.textContent = 'No hay traslados para mostrar.';
    fila.appendChild(celda);
    listaTraslados.appendChild(fila);
    return;
  }

  resultado.traslados.forEach((traslado) => listaTraslados.appendChild(crearFila(traslado)));
}

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
    } else if (boton.dataset.cancelar && window.confirm('¿Querés cancelar este traslado?')) {
      const resultado = await enviarTraslado('../php/eliminarTraslado.php', datos);
      mensajeTraslado.textContent = resultado.mensaje;
    } else {
      return;
    }

    await cargarTraslados();
  } catch (error) {
    mensajeTraslado.textContent = error.message;
  }
});

cargarTraslados().catch((error) => {
  mensajeTraslado.textContent = error.message;
});