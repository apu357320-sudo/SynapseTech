// Códigos QR. Usa la librería gratuita qrcodejs, que se carga en el HTML
// antes que este archivo y define el objeto QRCode.

// Dibuja dentro de "contenedor" un QR que lleva a "direccion".
function dibujarQr(contenedor, direccion) {
  contenedor.replaceChildren(); // borra el QR anterior, si había
  new QRCode(contenedor, { text: direccion, width: 170, height: 170 });
}

// Todo elemento con el atributo data-qr se convierte en un QR. El atributo
// tiene la ruta de la página, relativa a la página actual. Ejemplo:
//   <div class="contenedor-qr" data-qr="usuario/paciente.html"></div>
// new URL() arma la dirección completa (http://.../usuario/paciente.html).
document.querySelectorAll('[data-qr]').forEach((contenedor) => {
  const direccion = new URL(contenedor.dataset.qr, window.location.href).href;
  dibujarQr(contenedor, direccion);
});
