
DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `consultarReporteDetalles`$$
CREATE PROCEDURE `consultarReporteDetalles` (IN `_id_reporte` INT)  BEGIN
    SELECT id_reporte_historial, fecha, descripcion, estatus FROM Reporte_historial WHERE Id_reporte=_id_reporte ORDER BY id_reporte_historial DESC;
END$$

DROP PROCEDURE IF EXISTS `consultarReportes`$$
CREATE PROCEDURE `consultarReportes` (IN `_id_usuario` INT)  BEGIN
    SELECT id_reporte, fecha, latitud, longitud, referencia FROM Reportes WHERE Id_usuario=_id_usuario ORDER BY id_reporte DESC;
END$$

DROP PROCEDURE IF EXISTS `eliminarReporte`$$
CREATE PROCEDURE `eliminarReporte` (IN `_id_reporte` INT, IN `_id_usuario` INT)  BEGIN
    DELETE FROM Reportes WHERE Id_reporte=_id_reporte AND Id_usuario=_id_usuario;
END$$

DROP PROCEDURE IF EXISTS `informacionUsuario`$$
CREATE PROCEDURE `informacionUsuario` (IN `_id_usuario` INT)  BEGIN
    SELECT Usuario_datos.Apellido_materno, Usuario_datos.Apellido_paterno, Usuario_datos.Nombres, Usuarios.Correo FROM Usuario_datos INNER JOIN Usuarios ON Usuarios.Id_usuario_datos=Usuario_datos.Id_usuario_datos WHERE Usuarios.Id_usuario=_Id_usuario;
END$$

DROP PROCEDURE IF EXISTS `login`$$
CREATE PROCEDURE `login` (IN `_usuario` VARCHAR(50), IN `_contrasena` VARCHAR(50))  BEGIN
    SELECT Id_usuario, Tipo_usuario FROM Usuarios WHERE Usuario = _usuario AND Contrasena = _contrasena;
END$$

DROP PROCEDURE IF EXISTS `modificarInformacionUsuario`$$
CREATE PROCEDURE `modificarInformacionUsuario` (IN `_apellido_paterno` VARCHAR(50), IN `_apellido_materno` VARCHAR(50), IN `_nombres` VARCHAR(50), IN `_id_usuario` INT)  BEGIN
    UPDATE Usuario_datos SET Apellido_paterno=_apellido_paterno, Apellido_materno=_apellido_materno, Nombres=_nombres WHERE Id_usuario=_id_usuario;
END$$

DROP PROCEDURE IF EXISTS `modificarUsuario`$$
CREATE PROCEDURE `modificarUsuario` (IN `_anterior_password` VARCHAR(255), IN `_nueva_password` VARCHAR(255), IN `_correo` VARCHAR(50), IN `_id_usuario` INT)  BEGIN
DECLARE _contrasena_iguales INTEGER;
		SELECT count(Contrasena) INTO _contrasena_iguales 
		FROM Usuarios 
		WHERE Id_usuario = _id_usuario AND Contrasena =_anterior_password;
	IF (_contrasena_iguales > 0) THEN
		UPDATE Usuarios 
		SET Contrasena =_nueva_contrasena
		WHERE Id_usuario = _id_usuario;
	END IF;
END$$

DROP PROCEDURE IF EXISTS `registroBache`$$
CREATE PROCEDURE `registroBache` (IN `_fechaHoraServidor` VARCHAR(50), IN `_latitud` VARCHAR(45), IN `_longitud` VARCHAR(45), IN `_referencia` VARCHAR(100), IN `_comentario` VARCHAR(45), IN `_imagen` VARCHAR(45), IN `_id_usuario` INT)  BEGIN
    INSERT INTO Reportes (Fecha, Latitud, Longitud, Referencia, Comentario, Imagen, Id_usuario) 
VALUES (_fechaHoraServidor, _latitud, _longitud, _referencia, _comentario, _imagen, _id_usuario);
END$$

DROP PROCEDURE IF EXISTS `registroBacheHistorial`$$
CREATE PROCEDURE `registroBacheHistorial` (IN `_fecha` VARCHAR(50), IN `_id_reporte` INT)  BEGIN
    INSERT INTO Reporte_historial (Fecha, Id_reporte) 
VALUES (_fecha, _id_reporte);
END$$

DROP PROCEDURE IF EXISTS `registroInformacionUsuario`$$
CREATE PROCEDURE `registroInformacionUsuario` (IN `_apellido_paterno` VARCHAR(50), IN `_apellido_materno` VARCHAR(50), IN `_nombres` VARCHAR(50))  BEGIN
    INSERT INTO Usuario_datos (Apellido_paterno, Apellido_materno, Nombres) 
	VALUES (_apellido_paterno, _apellido_materno, _nombres);
END$$

DROP PROCEDURE IF EXISTS `registroUsuario`$$
CREATE PROCEDURE `registroUsuario` (IN `_usuario` VARCHAR(50), IN `_password` VARCHAR(50), IN `_correo` VARCHAR(50), IN `_Id_usuario_datos` INT)  BEGIN
    INSERT INTO Usuarios (Usuario, Contrasena, Correo, Tipo_usuario, Id_usuario_datos) 
	VALUES (_usuario, _password, _correo, 2, _Id_usuario_datos);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `brigadas`
--

CREATE TABLE `brigadas` (
  `Id_brigada` int(11) NOT NULL,
  `Nombre_brigada` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE `reportes` (
  `Id_reporte` int(11) NOT NULL,
  `Fecha` date NOT NULL,
  `Latitud` varchar(45) NOT NULL,
  `Longitud` varchar(45) NOT NULL,
  `Referencia` varchar(100) DEFAULT NULL,
  `Comentario` varchar(255) DEFAULT NULL,
  `Imagen` varchar(100) DEFAULT NULL,
  `Id_usuario` int(11) NOT NULL,
  `Id_brigada` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `reportes`
--

INSERT INTO `reportes` (`Id_reporte`, `Fecha`, `Latitud`, `Longitud`, `Referencia`, `Comentario`, `Imagen`, `Id_usuario`, `Id_brigada`) VALUES
(3, '2017-07-10', '-88.3091676235199', '18.512427374623527', 'Av NÃ¡poles, 312', NULL, NULL, 2, NULL),
(10, '2017-07-13', '18.502584065457263', '-88.30089569091797', 'Calle JosÃ© MarÃ­a Morelos, #147', NULL, NULL, 1, NULL),
(11, '2017-07-13', '18.50421193859529', '-88.29870700836182', 'Av Independencia, #184', NULL, NULL, 1, NULL),
(12, '2017-07-13', '18.508891987640734', '-88.3000373840332', 'Calle JosÃ© MarÃ­a Morelos, #265', NULL, NULL, 1, NULL),
(13, '2017-07-13', '18.50706067934004', '-88.3066463470459', 'Florencia, #217', NULL, NULL, 1, NULL),
(14, '2017-07-13', '18.497293370871546', '-88.30527305603027', 'Av. Rafael E. Melgar, #68', NULL, NULL, 1, NULL),
(15, '2017-07-13', '18.509258246949564', '-88.30604553222656', 'San Salvador, #375', NULL, NULL, 1, NULL),
(16, '2017-07-13', '18.50282824741469', '-88.29535961151123', 'Av de los HÃ©roes, #169', NULL, NULL, 1, NULL),
(18, '2017-07-13', '18.51237141942718', '-88.30926418304443', NULL, NULL, NULL, 1, NULL),
(19, '2017-07-13', '18.51237141942718', '-88.30926418304443', 'Av NÃ¡poles, #304', NULL, NULL, 1, NULL),
(20, '2017-07-13', '18.522788944143432', '-88.31607967615128', '31, #81', NULL, NULL, 1, NULL),
(21, '2017-07-13', '18.508708857692394', '-88.31003665924072', 'Bolonia, #426', NULL, NULL, 1, NULL),
(23, '2017-07-15', '18.49763930589656', '-88.3049726486206', 'Av Ãlvaro ObregÃ³n, #321', NULL, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte_historial`
--

CREATE TABLE `reporte_historial` (
  `Id_reporte_historial` int(11) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `Fecha` date NOT NULL,
  `Estatus` set('En espera','Atendido','Finalizado','Cancelado') NOT NULL DEFAULT 'En espera',
  `Id_reporte` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `reporte_historial`
--

INSERT INTO `reporte_historial` (`Id_reporte_historial`, `Descripcion`, `Fecha`, `Estatus`, `Id_reporte`) VALUES
(3, NULL, '2017-07-10', 'En espera', 3),
(10, NULL, '2017-07-13', 'En espera', 10),
(11, NULL, '2017-07-13', 'En espera', 11),
(12, NULL, '2017-07-13', 'En espera', 12),
(13, NULL, '2017-07-13', 'En espera', 13),
(14, NULL, '2017-07-13', 'En espera', 14),
(15, NULL, '2017-07-13', 'En espera', 15),
(16, NULL, '2017-07-13', 'En espera', 16),
(18, NULL, '2017-07-13', 'En espera', 18),
(19, NULL, '2017-07-13', 'En espera', 19),
(20, NULL, '2017-07-13', 'En espera', 20),
(21, NULL, '2017-07-13', 'En espera', 21),
(23, NULL, '2017-07-15', 'En espera', 23);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `Id_usuario` int(11) NOT NULL,
  `Usuario` varchar(25) NOT NULL,
  `Contrasena` varchar(255) NOT NULL,
  `Correo` varchar(45) NOT NULL,
  `Tipo_usuario` tinyint(1) NOT NULL,
  `Encargado` tinyint(4) DEFAULT NULL,
  `Id_usuario_datos` int(11) NOT NULL,
  `Id_brigada` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`Id_usuario`, `Usuario`, `Contrasena`, `Correo`, `Tipo_usuario`, `Encargado`, `Id_usuario_datos`, `Id_brigada`) VALUES
(1, 'uvs', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'uvs@gmail.com', 2, NULL, 1, NULL),
(2, 'julio28', '52336104be246289fc8c4a76561d0b4fb825755a', 'julio@ut.com', 2, NULL, 2, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_datos`
--

CREATE TABLE `usuario_datos` (
  `Id_usuario_datos` int(11) NOT NULL,
  `Apellido_paterno` varchar(45) NOT NULL,
  `Apellido_materno` varchar(45) NOT NULL,
  `Nombres` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario_datos`
--

INSERT INTO `usuario_datos` (`Id_usuario_datos`, `Apellido_paterno`, `Apellido_materno`, `Nombres`) VALUES
(1, 'Rivera ', 'PÃ©rez', 'Joel Nahim'),
(2, 'Chan', 'GarcÃ­a', 'Julio RenÃ©');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `brigadas`
--
ALTER TABLE `brigadas`
  ADD PRIMARY KEY (`Id_brigada`);

--
-- Indices de la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`Id_reporte`,`Id_usuario`),
  ADD KEY `fk_Reportes_Usuarios1_idx` (`Id_usuario`),
  ADD KEY `fk_Reportes_Brigadas1_idx` (`Id_brigada`);

--
-- Indices de la tabla `reporte_historial`
--
ALTER TABLE `reporte_historial`
  ADD PRIMARY KEY (`Id_reporte_historial`,`Id_reporte`),
  ADD KEY `fk_Reporte_historial_Reportes1_idx` (`Id_reporte`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`Id_usuario`,`Id_usuario_datos`),
  ADD UNIQUE KEY `Usuario_UNIQUE` (`Usuario`),
  ADD UNIQUE KEY `Correo_UNIQUE` (`Correo`),
  ADD KEY `fk_Usuarios_Usuario_datos_idx` (`Id_usuario_datos`),
  ADD KEY `fk_Usuarios_Brigadas1_idx` (`Id_brigada`);

--
-- Indices de la tabla `usuario_datos`
--
ALTER TABLE `usuario_datos`
  ADD PRIMARY KEY (`Id_usuario_datos`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `reportes`
--
ALTER TABLE `reportes`
  MODIFY `Id_reporte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT de la tabla `reporte_historial`
--
ALTER TABLE `reporte_historial`
  MODIFY `Id_reporte_historial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `Id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `usuario_datos`
--
ALTER TABLE `usuario_datos`
  MODIFY `Id_usuario_datos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD CONSTRAINT `fk_Reportes_Brigadas1` FOREIGN KEY (`Id_brigada`) REFERENCES `brigadas` (`Id_brigada`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Reportes_Usuarios1` FOREIGN KEY (`Id_usuario`) REFERENCES `usuarios` (`Id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `reporte_historial`
--
ALTER TABLE `reporte_historial`
  ADD CONSTRAINT `fk_Reporte_historial_Reportes1` FOREIGN KEY (`Id_reporte`) REFERENCES `reportes` (`Id_reporte`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_Usuarios_Brigadas1` FOREIGN KEY (`Id_brigada`) REFERENCES `brigadas` (`Id_brigada`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Usuarios_Usuario_datos` FOREIGN KEY (`Id_usuario_datos`) REFERENCES `usuario_datos` (`Id_usuario_datos`) ON UPDATE NO ACTION;
COMMIT;

