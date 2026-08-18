/* JS externo para traslado_form.html: búsqueda mock y validaciones */
(function(){
  const buscarBtn = document.getElementById('buscarCi');
  const ciInput = document.getElementById('ci');
  const nombreInput = document.getElementById('nombre');
  const apellidoInput = document.getElementById('apellido');
  const registrarBtn = document.getElementById('registrar');
  const cancelarBtn = document.getElementById('cancelar');
  let ciBuscado = null;

  function showError(id, message){
    const el = document.getElementById(id);
    if(!el) return;
    el.textContent = message;
    el.style.display = message ? 'block' : 'none';
  }

  if(buscarBtn){
    buscarBtn.addEventListener('click', function(){
      const ci = ciInput.value.trim();
      if(!ci){
        showError('ciError', 'La Cédula (CI) es obligatoria para buscar.');
        nombreInput.value = '';
        apellidoInput.value = '';
        ciBuscado = null;
        return;
      }

      // Simulación de llamada a API: rellenar con datos de ejemplo
      nombreInput.value = 'Juan';
      apellidoInput.value = 'Pérez';
      ciBuscado = ci;
      showError('ciError', '');
      showError('nombreError', '');
      showError('apellidoError', '');
    });
  }

  if(cancelarBtn){
    cancelarBtn.addEventListener('click', function(){
      alert('Volviendo al panel de traslados');
    });
  }

  if(registrarBtn){
    registrarBtn.addEventListener('click', function(){
      // limpiar errores
      ['ciError','nombreError','apellidoError','conductorError','enfermeroError','vehiculoError','origenError','destinoError','salidaError','llegadaError'].forEach(id=>showError(id,''));

      const ci = ciInput.value.trim();
      const nombre = nombreInput.value.trim();
      const apellido = apellidoInput.value.trim();
      const conductor = document.getElementById('conductor').value;
      const enfermero = document.getElementById('enfermero').value;
      const vehiculo = document.getElementById('vehiculo').value;
      const origen = document.getElementById('origen').value.trim();
      const destino = document.getElementById('destino').value.trim();
      const salida = document.getElementById('salida').value;
      const llegada = document.getElementById('llegada').value;

      let ok = true;

      if(!ci){ showError('ciError','La Cédula (CI) es obligatoria.'); ok = false; }
      if(!ciBuscado || ciBuscado !== ci){ showError('ciError','Debe buscar la CI antes de registrar.'); ok = false; }
      if(!nombre){ showError('nombreError','Nombre no encontrado.'); ok = false; }
      if(!apellido){ showError('apellidoError','Apellido no encontrado.'); ok = false; }
      if(!conductor){ showError('conductorError','Seleccione un conductor.'); ok = false; }
      if(!enfermero){ showError('enfermeroError','Seleccione un enfermero/a.'); ok = false; }
      if(!vehiculo){ showError('vehiculoError','Seleccione un vehículo.'); ok = false; }
      if(!origen){ showError('origenError','Origen es requerido.'); ok = false; }
      if(!destino){ showError('destinoError','Destino es requerido.'); ok = false; }
      if(!salida){ showError('salidaError','Hora de salida es requerida.'); ok = false; }
      if(!llegada){ showError('llegadaError','Hora estimada de llegada es requerida.'); ok = false; }

      if(!ok) return;

      // Simular registro y número de seguimiento
      const rnd = Math.floor(Math.random()*9)+1; // 1-9
      const seguimiento = `TR-2026-00${rnd}`;
      alert('Traslado registrado con éxito. N° de seguimiento: ' + seguimiento + ' (simulado)');
    });
  }
})();
