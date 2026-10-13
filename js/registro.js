const formularioUsuario = document.getElementById('formularioUsuario');
const mensajeRegistro = document.getElementById('mensajeRegistro');
formularioUsuario.addEventListener('submit', async (evento) => {
  evento.preventDefault();
  const campos = [...formularioUsuario.querySelectorAll('input, select')];
  if (campos.some((campo) => !campo.value.trim())) { 
    mensajeRegistro.textContent = 'Completa todos los campos.'; 
    return; }
  const respuesta = await fetch('../php/registrarUsuario.php', { method: 'POST', body: new FormData(formularioUsuario) });
  const datos = await respuesta.json(); mensajeRegistro.textContent = datos.mensaje;
  if (datos.exito) formularioUsuario.reset();
});
