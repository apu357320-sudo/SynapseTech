(() => {
  const mapElement = document.getElementById('mapaTraslado');
  const originInput = document.getElementById('origen');
  const destinationInput = document.getElementById('destino');
  const status = document.getElementById('estadoMapa');
  const form = document.getElementById('trasladoForm');
  const formMessage = document.getElementById('mensajeTrasladoForm');
  const submitButton = document.getElementById('registrar');
  const cancelButton = document.getElementById('cancelar');
  const targetButtons = document.querySelectorAll('[data-ubicacion]');

  if (!mapElement || !originInput || !destinationInput || !window.L) return;

  const origin = [-34.8872, -56.1585];
  const map = L.map(mapElement).setView(origin, 14);
  let target = 'origen';
  let originMarker;
  let destinationMarker;

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  function saveCoordinates(input, coordinates) {
    input.value = `Coordenadas: ${coordinates.lat.toFixed(6)}, ${coordinates.lng.toFixed(6)}`;
  }

  function setMarker(location, coordinates, updateField = true) {
    const input = location === 'origen' ? originInput : destinationInput;
    const marker = location === 'origen' ? originMarker : destinationMarker;

    if (marker) {
      marker.setLatLng(coordinates);
    } else {
      const newMarker = L.marker(coordinates, { draggable: true })
        .addTo(map)
        .bindPopup(location === 'origen' ? 'Origen del traslado' : 'Destino del traslado');

      newMarker.on('dragend', (event) => {
        saveCoordinates(input, event.target.getLatLng());
      });

      if (location === 'origen') originMarker = newMarker;
      else destinationMarker = newMarker;
    }

    if (updateField) saveCoordinates(input, coordinates);
  }

  targetButtons.forEach((button) => {
    button.addEventListener('click', () => {
      target = button.dataset.ubicacion;
      targetButtons.forEach((option) => {
        option.setAttribute('aria-pressed', String(option === button));
      });
      status.textContent = `Seleccionando el ${target}. Hacé clic en el mapa o arrastrá su marcador.`;
    });
  });

  map.on('click', (event) => setMarker(target, event.latlng));
  setMarker('origen', L.latLng(origin[0], origin[1]), false);

  form?.addEventListener('submit', async (event) => {
    event.preventDefault();
    submitButton.disabled = true;
    formMessage.textContent = 'Guardando el traslado...';
    formMessage.className = 'mensaje';

    try {
      const response = await fetch('../php/guardarTraslado.php', {
        method: 'POST',
        body: new FormData(form)
      });
      const result = await response.json();
      if (!response.ok || !result.exito) throw new Error(result.mensaje || 'No se pudo guardar el traslado.');
      formMessage.textContent = result.mensaje;
      formMessage.className = 'mensaje exito';
    } catch (error) {
      formMessage.textContent = error.message;
      formMessage.className = 'mensaje';
    } finally {
      submitButton.disabled = false;
    }
  });

  cancelButton?.addEventListener('click', () => {
    window.location.href = 'traslados.php';
  });
})();