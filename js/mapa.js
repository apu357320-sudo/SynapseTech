const mapa = L.mapa('visualizar').setView([-34.9011, -56.1645], 19);
const ingresar0 = document.getElementById('ingresar0');
const ubicar = document.getElementById('ubicar');
const departamento = document.getElementById('departamento');
const direccion = document.getElementById('dir');

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(mapa);

const ImagenMarcador = L.icon({
iconUrl: https://api.geoapify.com/v1/geocode/search?text=${encodeURIComponent(texto)}&filter=countrycode:uy&lang=es&limit=1&format=json&apiKey=4572a7318c164b3b873097a1f7b2c618
iconSize: [38, 45],    

const ImagenDestino = L.icon({
iconUrl:../img/destino.png    
iconSize: [38, 45],

const Marcador = L.marker([-34.9011, -56.1645], 
{
draggable: true,
icon: ImagenMarcador
}).addTo(mapa);

const insertlat = document.getElementById('lat');
const insertlng = document.getElementById('lng');

ingresar0.addEventListener('click', () => {
  let lugar0 = marcador.getLatLng();
  insertlat.value = lugar0.lat;
  insertlng.value = lugar0.lng;
const marcadorDestino = L.marker([-34.900362955713476, -56.163353436677056], {
{draggable: true, ImagenMarcador
}).addTo(mapa);

ubicar.addEventListener('click', async () => {

  const texto = `${direccion.value},${departamento.value}, Uruguay`;

  const url = `https://api.geoapify.com/v1/geocode/search?text=${encodeURIComponent(texto)}&filter=countrycode:uy&lang=es&limit=1&format=json&apiKey=4572a7318c164b3b873097a1f7b2c618`;

  const respuesta = await fetch(url);
  const dato = await respuesta.json();
  
  const resultado = dato.results[0];

  if (resultado) {
    const marcadorH = L.marker([resultado.lat, resultado.lon], {
      draggable: true,
      icon: ImagenMarcador
    }).addTo(mapa);
    alert(`${resultado.lat},${resultado.lon}`);
  }
});
