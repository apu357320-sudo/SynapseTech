function dibujarQr(contenedor, direccion) {
  contenedor.replaceChildren();
  new QRCode(contenedor, { text: direccion, width: 170, height: 170 });
}

document.querySelectorAll('[data-qr]').forEach((contenedor) => {
  const direccion = new URL(contenedor.dataset.qr, window.location.href).href;
  dibujarQr(contenedor, direccion);
});
