const formularioTraslado = document.getElementById('trasladoForm');
const listaTraslados = document.getElementById('listaTraslados');
const mensajeTraslado = document.getElementById('mensajeTraslado');

async function cargarTraslados() {
  if (!listaTraslados) return;
  const respuesta = await fetch('../php/traslados.php');
  const datos = await respuesta.json();
  listaTraslados.innerHTML = '';
  datos.traslados.forEach((traslado) => {
    const fila = document.createElement('tr');
    const siguiente = { 'Solicitado': 'En camino', 'En camino': 'En destino', 'En destino': 'Retornando', 'Retornando': 'Finalizado' }[traslado.estado];
    fila.innerHTML = `<td>${traslado.paciente_nombre}</td><td>${traslado.origen}</td><td>${traslado.destino}</td><td>${traslado.chofer}</td><td>${traslado.enfermero}</td><td>${traslado.estado}</td><td>${siguiente ? `<button data-id="${traslado.id}" data-estado="${siguiente}">Pasar a ${siguiente}</button>` : ''} <button data-cancelar="${traslado.id}">Cancelar</button></td>`;
    listaTraslados.appendChild(fila);
  });
}

formularioTraslado?.addEventListener('submit', async (evento) => {
  evento.preventDefault();
  const respuesta = await fetch('../php/guardarTraslado.php', { method: 'POST', body: new FormData(formularioTraslado) });
  const datos = await respuesta.json();
  mensajeTraslado.textContent = datos.mensaje;
  mensajeTraslado.className = datos.exito ? 'mensaje exito' : 'mensaje';
  if (datos.exito) { formularioTraslado.reset(); cargarTraslados(); }
});

listaTraslados?.addEventListener('click', async (evento) => {
  const boton = evento.target;
  if (!boton.dataset.id && !boton.dataset.cancelar) return;
  if (!confirm('¿Confirmás esta acción?')) return;
  const datos = new FormData();
  datos.append('id', boton.dataset.id || boton.dataset.cancelar);
  let url = '../php/actualizarTraslado.php';
  if (boton.dataset.id) datos.append('estado', boton.dataset.estado);
  else url = '../php/eliminarTraslado.php';
  const respuesta = await fetch(url, { method: 'POST', body: datos });
  const resultado = await respuesta.json();
  mensajeTraslado.textContent = resultado.mensaje;
  cargarTraslados();
});

cargarTraslados().catch(() => { if (mensajeTraslado) mensajeTraslado.textContent = 'No se pudieron cargar los traslados.'; });
