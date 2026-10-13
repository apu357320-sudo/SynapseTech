USE `synapsetech_db`;

ALTER TABLE `traslado`
  ADD COLUMN IF NOT EXISTS `origen_lat` decimal(9,6) DEFAULT NULL AFTER `origen`,
  ADD COLUMN IF NOT EXISTS `origen_lng` decimal(9,6) DEFAULT NULL AFTER `origen_lat`,
  ADD COLUMN IF NOT EXISTS `destino_lat` decimal(9,6) DEFAULT NULL AFTER `destino`,
  ADD COLUMN IF NOT EXISTS `destino_lng` decimal(9,6) DEFAULT NULL AFTER `destino_lat`;

INSERT IGNORE INTO `usuario` (`ci`, `nombre`, `apellido`, `email`, `contrasena`, `estado`, `id_rol`) VALUES
('41234567', 'Carlos', 'Rodríguez', 'carlos.rodriguez@hc.test', '$2y$10$KPQPRyr6/tiX2wyBF.Hq7.Cy8jEwoVJpzfi.xff5SOBviZKsj12mi', 1, 3),
('42345678', 'María', 'Gómez', 'maria.gomez@hc.test', '$2y$10$KPQPRyr6/tiX2wyBF.Hq7.Cy8jEwoVJpzfi.xff5SOBviZKsj12mi', 1, 3),
('43456789', 'Laura', 'Fernández', 'laura.fernandez@hc.test', '$2y$10$KPQPRyr6/tiX2wyBF.Hq7.Cy8jEwoVJpzfi.xff5SOBviZKsj12mi', 1, 4),
('44567890', 'José', 'Martínez', 'jose.martinez@hc.test', '$2y$10$KPQPRyr6/tiX2wyBF.Hq7.Cy8jEwoVJpzfi.xff5SOBviZKsj12mi', 1, 4);

INSERT IGNORE INTO `chofer` (`ci_chofer`, `id_usuario`)
SELECT `ci`, `id_usuario` FROM `usuario` WHERE `ci` IN ('41234567', '42345678');

INSERT IGNORE INTO `enfermero` (`ci_enfermero`, `id_usuario`)
SELECT `ci`, `id_usuario` FROM `usuario` WHERE `ci` IN ('43456789', '44567890');

INSERT IGNORE INTO `vehiculo` (`matricula`, `tipo_vehiculo`, `estado`, `disponibilidad`, `ultimo_mantenimiento`) VALUES
('SOF 1001', 'Ambulancia', 'Operativo', 1, '2026-08-15'),
('SOF 1002', 'Ambulancia', 'Operativo', 1, '2026-09-02'),
('SOF 2001', 'Auto', 'Operativo', 1, '2026-07-20');
