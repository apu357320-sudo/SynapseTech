<?php
require_once __DIR__ . '/../php/sesion.php';
exigirSesionPagina(true); // solo administradores
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../estilo/style.css">
    <link rel="stylesheet" href="../estilo/traslados.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <title>Nuevo Traslado - Hospital de Clínicas</title>
  </head>
  <body class="traslado-form-page">
    <header>
        <a href="index.php" class="enlace-logo" aria-label="Ir al panel de administración">
          <img src="../logo.svg" alt="Logo del Hospital de Clínicas" class="logo">
        </a>
      <div>
        <h1>Solicitud de traslado</h1>
        <p>Hospital de Clínicas</p>
      </div>
    </header>

    <main>
      <section class="tarjeta">
        <h2>Nuevo traslado de ambulancia</h2>

        <form id="trasladoForm" novalidate>
          <fieldset>
            <legend>Datos del Paciente</legend>

            <div class="form-grid">
              <div class="full">
                <label for="ci">Cédula (CI)</label>
                <div class="ci-row">
                  <input type="text" id="ci" name="paciente_ci" inputmode="numeric" maxlength="8" placeholder="Ej: 12345678" required />
                  <button type="button" id="buscarCi" class="boton-secundario">Buscar</button>
                </div>
                <span id="ciError" class="error"></span>
              </div>

              <div>
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="paciente_nombre" maxlength="50" required />
                <span id="nombreError" class="error"></span>
              </div>

              <div>
                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="paciente_apellido" maxlength="50" required />
                <span id="apellidoError" class="error"></span>
              </div>
            </div>
          </fieldset>

          <fieldset class="traslado-datos">
            <legend>Datos del Traslado</legend>

            <!-- Las opciones de los tres select las carga traslado-mapa.js desde la base de datos -->
            <div class="form-grid">
              <div>
                <label for="conductor">Conductor</label>
                <select id="conductor" name="ci_chofer" required>
                  <option value="">Seleccionar</option>
                </select>
                <span id="conductorError" class="error"></span>
              </div>

              <div>
                <label for="enfermero">Enfermero/a acompañante</label>
                <select id="enfermero" name="ci_enfermero" required>
                  <option value="">Seleccionar</option>
                </select>
                <span id="enfermeroError" class="error"></span>
              </div>

              <div class="full">
                <label for="vehiculo">Vehículo</label>
                <select id="vehiculo" name="matricula" required>
                  <option value="">Seleccionar</option>
                </select>
                <span id="vehiculoError" class="error"></span>
              </div>

              <div>
                <label for="salida">Hora de Salida</label>
                <input type="datetime-local" id="salida" name="hora_salida" required />
                <span id="salidaError" class="error"></span>
              </div>

              <div>
                <label for="llegada">Hora Estimada de Llegada</label>
                <input type="datetime-local" id="llegada" name="hora_llegada_estimada" />
                <span id="llegadaError" class="error"></span>
              </div>
            </div>
          </fieldset>

          <fieldset class="traslado-datos">
            <legend>Recorrido</legend>

            <div class="form-grid">
              <div>
                <label for="origen">Origen</label>
                <input type="text" id="origen" name="origen" maxlength="150" value="Hospital de Clínicas - Piso 6" required />
                <span id="origenError" class="error"></span>
              </div>

              <div>
                <label for="destino">Destino</label>
                <input type="text" id="destino" name="destino" maxlength="150" placeholder="Ej: Centro de Nefrología" required />
                <span id="destinoError" class="error"></span>
              </div>
            </div>

            <!-- Coordenadas elegidas en el mapa. Las completa traslado-mapa.js. -->
            <input type="hidden" name="origen_lat" />
            <input type="hidden" name="origen_lng" />
            <input type="hidden" name="destino_lat" />
            <input type="hidden" name="destino_lng" />

            <p class="mapa-instruccion">Escribe el nombre del lugar arriba y marca el punto en el mapa. Puedes arrastrar los marcadores para ajustarlos.</p>
            <div class="mapa-controles" role="group" aria-label="Ubicación a marcar">
              <button type="button" class="boton-secundario" data-ubicacion="origen" aria-pressed="false">Marcar origen</button>
              <button type="button" class="boton-secundario" data-ubicacion="destino" aria-pressed="true">Marcar destino</button>
            </div>
            <div id="mapaTraslado" class="mapa-traslado" role="application" aria-label="Mapa para seleccionar el origen y destino del traslado"></div>
            <p id="estadoMapa" class="estado-mapa" aria-live="polite">Haz clic en el mapa para marcar el destino.</p>
            <span id="mapaError" class="error"></span>
          </fieldset>

          <div class="botones-contenedor">
            <button type="button" id="cancelar" class="boton-secundario">Cancelar</button>
            <button type="submit" id="registrar" class="boton-principal">Registrar el traslado</button>
          </div>
          <p id="mensajeTrasladoForm" class="mensaje" role="alert" aria-live="polite"></p>
        </form>
      </section>
    </main>

    <footer>
      <p>Hospital de Clínicas</p>
      <p>Instituto Tecnológico Superior de Paysandú - ITSP</p>
    </footer>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="../js/traslado-mapa.js"></script>
  </body>
</html>
