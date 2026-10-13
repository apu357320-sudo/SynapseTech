const formularioEncuesta = document.getElementById('formularioEncuesta');
const panelEncuestas = document.getElementById('panelEncuestas');
const mensajeEncuesta = document.getElementById('mensajeEncuesta');

if (formularioEncuesta) {
  formularioEncuesta.addEventListener('submit', async (evento) => {
    evento.preventDefault();
    mensajeEncuesta.className = 'mensaje';

    const datos = new FormData(formularioEncuesta);
    if (!datos.get('servicio') || !datos.get('calificacion') || !datos.get('claridad')) {
      mensajeEncuesta.textContent = 'Completa el servicio, la calificación y si la información fue clara.';
      return;
    }

    try {
      const respuesta = await fetch('../php/guardarEncuesta.php', { method: 'POST', body: datos });
      const resultado = await respuesta.json();
      mensajeEncuesta.textContent = resultado.mensaje;
      if (resultado.exito) {
        mensajeEncuesta.className = 'mensaje exito';
        formularioEncuesta.style.display = 'none';
      }
    } catch (error) {
      mensajeEncuesta.textContent = 'No se pudo contactar con el servidor.';
    }
  });
}

function agregarFilaResultado(tabla, nombre, cantidad, total) {
  const fila = document.createElement('tr');
  const celdaNombre = document.createElement('td');
  const celdaCantidad = document.createElement('td');
  const celdaBarra = document.createElement('td');
  const barra = document.createElement('div');

  celdaNombre.textContent = nombre;
  celdaCantidad.textContent = cantidad;
  barra.className = 'barra-resultado';
  barra.style.width = `${Math.round(cantidad * 100 / total)}%`;

  celdaBarra.appendChild(barra);
  fila.append(celdaNombre, celdaCantidad, celdaBarra);
  tabla.appendChild(fila);
}

async function cargarResultados() {
  const respuesta = await fetch('../php/reportes.php');
  if (respuesta.status === 401 || respuesta.status === 403) {
    window.location.href = '../index.html';
    return;
  }
  const datos = await respuesta.json();
  if (!datos.exito) throw new Error(datos.mensaje);

  document.getElementById('totalRespuestas').textContent = datos.total;
  document.getElementById('promedio').textContent = datos.promedio;
  document.getElementById('porcentajeSatisfechos').textContent = `${datos.porcentaje_satisfechos}%`;
  document.getElementById('porcentajeClaro').textContent = `${datos.porcentaje_claro}%`;

  if (datos.total === 0) {
    mensajeEncuesta.textContent = 'Todavía no hay respuestas.';
    return;
  }

  const tablaCalificaciones = document.getElementById('tablaCalificaciones');
  const tablaServicios = document.getElementById('tablaServicios');
  datos.calificaciones.forEach((una) => agregarFilaResultado(tablaCalificaciones, una.valor, una.cantidad, datos.total));
  datos.servicios.forEach((uno) => agregarFilaResultado(tablaServicios, uno.valor, uno.cantidad, datos.total));

  const listaComentarios = document.getElementById('listaComentarios');
  datos.comentarios.forEach((comentario) => {
    const elemento = document.createElement('li');
    elemento.textContent = `${comentario.fecha.slice(0, 10)} — ${comentario.texto}`;
    listaComentarios.appendChild(elemento);
  });
}

if (panelEncuestas) {
  cargarResultados().catch((error) => {
    mensajeEncuesta.textContent = error.message;
  });
}
