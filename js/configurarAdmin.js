const formularioAdministrador = document.getElementById('formularioAdministrador');
const mensajeAdministrador = document.getElementById('mensajeAdministrador');

formularioAdministrador.addEventListener('submit', async (evento) => {
  evento.preventDefault();
  mensajeAdministrador.textContent = 'Creando el administrador...';

  try {
    const respuesta = await fetch('php/crearAdministrador.php', {
      method: 'POST',
      body: new FormData(formularioAdministrador),
    });
    const datos = await respuesta.json();
    mensajeAdministrador.textContent = datos.mensaje;
    mensajeAdministrador.className = datos.exito ? 'mensaje exito' : 'mensaje';

    if (datos.exito) {
      formularioAdministrador.reset();
      setTimeout(() => { window.location.href = 'index.html'; }, 1200);
    }
  } catch (error) {
    mensajeAdministrador.textContent = 'No se pudo contactar con el servidor.';
    mensajeAdministrador.className = 'mensaje';
  }
});
