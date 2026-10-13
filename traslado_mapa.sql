-- ------------------------------------------------------------
-- S.I.G.S.M. - Módulo Ambulancias
-- Script complementario de synapsetech_db.sql
-- 1) Agrega las coordenadas del mapa a la tabla `traslado`.
-- 2) Carga datos de prueba para poder usar el formulario de traslados.
-- Se puede ejecutar más de una vez sin romper nada (IF NOT EXISTS / IGNORE).
-- ------------------------------------------------------------

USE `synapsetech_db`;

-- 1) DDL: coordenadas elegidas en el mapa (Leaflet).
--    DECIMAL(9,6) guarda 6 decimales, que equivale a unos 10 cm de precisión.
--    Son NULL porque los traslados cargados antes del mapa no las tienen.
ALTER TABLE `traslado`
  ADD COLUMN IF NOT EXISTS `origen_lat` decimal(9,6) DEFAULT NULL AFTER `origen`,
  ADD COLUMN IF NOT EXISTS `origen_lng` decimal(9,6) DEFAULT NULL AFTER `origen_lat`,
  ADD COLUMN IF NOT EXISTS `destino_lat` decimal(9,6) DEFAULT NULL AFTER `destino`,
  ADD COLUMN IF NOT EXISTS `destino_lng` decimal(9,6) DEFAULT NULL AFTER `destino_lat`;

-- 2) DML: datos de prueba.
--    La contraseña de estos cuatro usuarios es: Prueba123
--    (guardada con password_hash, nunca en texto plano).
INSERT IGNORE INTO `usuario` (`ci`, `nombre`, `apellido`, `email`, `contrasena`, `estado`, `id_rol`) VALUES
('41234567', 'Carlos', 'Rodríguez', 'carlos.rodriguez@hc.test', '$2y$10$KPQPRyr6/tiX2wyBF.Hq7.Cy8jEwoVJpzfi.xff5SOBviZKsj12mi', 1, 3),
('42345678', 'María', 'Gómez', 'maria.gomez@hc.test', '$2y$10$KPQPRyr6/tiX2wyBF.Hq7.Cy8jEwoVJpzfi.xff5SOBviZKsj12mi', 1, 3),
('43456789', 'Laura', 'Fernández', 'laura.fernandez@hc.test', '$2y$10$KPQPRyr6/tiX2wyBF.Hq7.Cy8jEwoVJpzfi.xff5SOBviZKsj12mi', 1, 4),
('44567890', 'José', 'Martínez', 'jose.martinez@hc.test', '$2y$10$KPQPRyr6/tiX2wyBF.Hq7.Cy8jEwoVJpzfi.xff5SOBviZKsj12mi', 1, 4);

-- Cada chofer y cada enfermero es un usuario: se busca su id por la cédula.
INSERT IGNORE INTO `chofer` (`ci_chofer`, `id_usuario`)
SELECT `ci`, `id_usuario` FROM `usuario` WHERE `ci` IN ('41234567', '42345678');

INSERT IGNORE INTO `enfermero` (`ci_enfermero`, `id_usuario`)
SELECT `ci`, `id_usuario` FROM `usuario` WHERE `ci` IN ('43456789', '44567890');

INSERT IGNORE INTO `vehiculo` (`matricula`, `tipo_vehiculo`, `estado`, `disponibilidad`, `ultimo_mantenimiento`) VALUES
('SOF 1001', 'Ambulancia', 'Operativo', 1, '2026-08-15'),
('SOF 1002', 'Ambulancia', 'Operativo', 1, '2026-09-02'),
('SOF 2001', 'Auto', 'Operativo', 1, '2026-07-20');
