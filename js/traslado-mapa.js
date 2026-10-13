const formulario = document.getElementById('trasladoForm');
const mensajeFormulario = document.getElementById('mensajeTrasladoForm');
const estadoMapa = document.getElementById('estadoMapa');
const botonRegistrar = document.getElementById('registrar');
const botonCancelar = document.getElementById('cancelar');
const botonesUbicacion = document.querySelectorAll('[data-ubicacion]');

const HOSPITAL = [-34.8914, -56.1519];
const LIMITES_URUGUAY = L.latLngBounds([-35.1, -58.5], [-30.0, -53.0]);

const mapa = L.map('mapaTraslado', {
  maxBounds: LIMITES_URUGUAY.pad(0.2),
  minZoom: 6
}).setView(HOSPITAL, 14);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 19,
  attribution: '&copy; OpenStreetMap contributors'
}).addTo(mapa);

const marcadores = { origen: null, destino: null };
const lineaRecorrido = L.polyline([], { color: '#1f5c4a', dashArray: '6 8' }).addTo(mapa);
let ubicacionActiva = 'destino';

function guardarCoordenadas(ubicacion, coordenadas) {
  formulario.elements[`${ubicacion}_lat`].value = coordenadas.lat.toFixed(6);
  formulario.elements[`${ubicacion}_lng`].value = coordenadas.lng.toFixed(6);
  actualizarRecorrido();
}

function colocarMarcador(ubicacion, coordenadas) {
  if (marcadores[ubicacion]) {
    marcadores[ubicacion].setLatLng(coordenadas);
  } else {
    const icono = L.divIcon({
      className: `marcador marcador-${ubicacion}`,
      html: ubicacion === 'origen' ? 'O' : 'D',
      iconSize: [30, 30]
    });
    const marcador = L.marker(coordenadas, { draggable: true, icon: icono })
      .addTo(mapa)
      .bindPopup(ubicacion === 'origen' ? 'Origen del traslado' : 'Destino del traslado');

    marcador.on('dragend', () => guardarCoordenadas(ubicacion, marcador.getLatLng()));
    marcadores[ubicacion] = marcador;
  }
  guardarCoordenadas(ubicacion, coordenadas);
}

function actualizarRecorrido() {
  if (!marcadores.origen || !marcadores.destino) return;
  const puntos = [marcadores.origen.getLatLng(), marcadores.destino.getLatLng()];
  lineaRecorrido.setLatLngs(puntos);
  const kilometros = (mapa.distance(puntos[0], puntos[1]) / 1000).toFixed(1);
  estadoMapa.textContent = `Distancia en línea recta: ${kilometros} km.`;
}

botonesUbicacion.forEach((boton) => {
  boton.addEventListener('click', () => {
    ubicacionActiva = boton.dataset.ubicacion;
    botonesUbicacion.forEach((otro) => otro.setAttribute('aria-pressed', String(otro === boton)));
    estadoMapa.textContent = `Haz clic en el mapa para marcar el ${ubicacionActiva}.`;
  });
});

mapa.on('click', (evento) => {
  if (!LIMITES_URUGUAY.contains(evento.latlng)) {
    estadoMapa.textContent = 'Ese punto está fuera de Uruguay.';
    return;
  }
  colocarMarcador(ubicacionActiva, evento.latlng);
});

colocarMarcador('origen', L.latLng(HOSPITAL));

function llenarSelect(select, opciones) {
  opciones.forEach((opcion) => {
    const elemento = document.createElement('option');
    elemento.value = opcion.valor;
    elemento.textContent = opcion.texto;
    select.appendChild(elemento);
  });
}

async function cargarOpciones() {
  const respuesta = await fetch('../php/opcionesTraslado.php');
  const datos = await respuesta.json();
  if (!respuesta.ok || !datos.exito) throw new Error(datos.mensaje || 'No se pudieron cargar las opciones.');
  llenarSelect(formulario.elements.ci_chofer, datos.choferes);
  llenarSelect(formulario.elements.ci_enfermero, datos.enfermeros);
  llenarSelect(formulario.elements.matricula, datos.vehiculos);
}

cargarOpciones().catch((error) => {
  mensajeFormulario.textContent = error.message;
});

document.getElementById('buscarCi').addEventListener('click', async () => {
  const ci = formulario.elements.paciente_ci.value.trim();
  const respuesta = await fetch(`../php/apiPacientes.php?ci=${encodeURIComponent(ci)}`);
  const datos = await respuesta.json();
  if (!datos.exito) {
    mostrarError('ciError', datos.mensaje);
    return;
  }
  mostrarError('ciError', '');
  formulario.elements.paciente_nombre.value = datos.paciente.nombre;
  formulario.elements.paciente_apellido.value = datos.paciente.apellido;
});

function mostrarError(idError, texto) {
  document.getElementById(idError).textContent = texto;
  return texto === '';
}

function validarFormulario() {
  const campos = formulario.elements;
  const salida = campos.hora_salida.value;
  const llegada = campos.hora_llegada_estimada.value;
  const marcadoresEnUruguay = marcadores.destino
    && LIMITES_URUGUAY.contains(marcadores.origen.getLatLng())
    && LIMITES_URUGUAY.contains(marcadores.destino.getLatLng());

  const resultados = [
    mostrarError('ciError', /^\d{7,8}$/.test(campos.paciente_ci.value.trim()) ? '' : 'La cédula debe tener 7 u 8 dígitos, sin puntos ni guion.'),
    mostrarError('nombreError', campos.paciente_nombre.value.trim() ? '' : 'Ingresa el nombre.'),
    mostrarError('apellidoError', campos.paciente_apellido.value.trim() ? '' : 'Ingresa el apellido.'),
    mostrarError('conductorError', campos.ci_chofer.value ? '' : 'Elige un conductor.'),
    mostrarError('enfermeroError', campos.ci_enfermero.value ? '' : 'Elige un enfermero/a.'),
    mostrarError('vehiculoError', campos.matricula.value ? '' : 'Elige un vehículo.'),
    mostrarError('origenError', campos.origen.value.trim() ? '' : 'Ingresa el origen.'),
    mostrarError('destinoError', campos.destino.value.trim() ? '' : 'Ingresa el destino.'),
    mostrarError('salidaError', salida ? '' : 'Ingresa la hora de salida.'),
    mostrarError('llegadaError', llegada && llegada <= salida ? 'La llegada estimada debe ser posterior a la salida.' : ''),
    mostrarError('mapaError', marcadoresEnUruguay ? '' : 'Marca el destino en el mapa, dentro de Uruguay.')
  ];
  return resultados.every((correcto) => correcto);
}

formulario.addEventListener('submit', async (evento) => {
  evento.preventDefault();
  mensajeFormulario.className = 'mensaje';
  if (!validarFormulario()) {
    mensajeFormulario.textContent = 'Revisa los campos marcados.';
    return;
  }

  botonRegistrar.disabled = true;
  mensajeFormulario.textContent = 'Guardando el traslado...';

  try {
    const respuesta = await fetch('../php/guardarTraslado.php', { method: 'POST', body: new FormData(formulario) });
    const resultado = await respuesta.json();
    if (!respuesta.ok || !resultado.exito) throw new Error(resultado.mensaje || 'No se pudo guardar el traslado.');
    mensajeFormulario.textContent = resultado.mensaje;
    mensajeFormulario.className = 'mensaje exito';
    setTimeout(() => { window.location.href = 'traslados.php'; }, 1200);
  } catch (error) {
    mensajeFormulario.textContent = error.message;
    botonRegistrar.disabled = false;
  }
});

botonCancelar.addEventListener('click', () => {
  window.location.href = 'traslados.php';
});
