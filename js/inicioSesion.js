const formularioLogin = document.getElementById('formularioLogin');
const mensajeLogin = document.getElementById('mensajeLogin');

formularioLogin.addEventListener('submit', async (evento) => {
  evento.preventDefault();
  const usuario = formularioLogin.usuario.value.trim();
  const password = formularioLogin.password.value;
  if (!usuario || !password) {
    mensajeLogin.textContent = 'Completa el usuario y la contraseña.';
    return;
  }

  try {
    const respuesta = await fetch('php/inicioSesion.php', { method: 'POST', body: new FormData(formularioLogin) });
    const tipo = respuesta.headers.get('content-type') || '';
    if (!tipo.includes('application/json')) {
      throw new Error('La página no fue abierta desde Apache.');
    }
    const datos = await respuesta.json();
    mensajeLogin.textContent = datos.mensaje;
    if (datos.exito) window.location.href = datos.redireccion;
  } catch (error) {
    mensajeLogin.textContent = 'No se pudo contactar con el servidor. Abre el sitio desde http://localhost/synapsetech/ con Apache de XAMPP encendido (no con Live Server).';
  }
});
