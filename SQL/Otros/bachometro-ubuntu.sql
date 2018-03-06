-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 08, 2017 at 01:01 
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bachometro`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultarReporteDetalles` (IN `_id_reporte` INT)  BEGIN
    SELECT h.id_reporte_historial, h.fecha, h.descripcion, h.estatus, r.imagen, r.comentario FROM reporte_historial h INNER JOIN reportes r ON (h.id_reporte=r.id_reporte)  WHERE h.Id_reporte=_id_reporte ORDER BY h.id_reporte_historial DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `consultarReportes` (IN `_id_usuario` INT)  BEGIN
    SELECT r.id_reporte, r.fecha, r.latitud, r.longitud, r.referencia, r.comentario, r.imagen, h.estatus, h.fecha, h.descripcion FROM reportes r INNER JOIN reporte_historial h ON r.Id_reporte=h.Id_reporte WHERE r.Id_usuario=_id_usuario group by h.id_reporte ORDER BY r.id_reporte DESC ;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `eliminarReporte` (IN `_id_reporte` INT, IN `_id_usuario` INT)  BEGIN
    DELETE FROM reportes WHERE Id_reporte=_id_reporte AND Id_usuario=_id_usuario;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `informacionUsuario` (IN `_id_usuario` INT)  BEGIN
    SELECT usuario_datos.Apellido_materno, usuario_datos.Apellido_paterno, usuario_datos.Nombres, usuarios.Correo FROM usuario_datos INNER JOIN usuarios ON usuarios.Id_usuario_datos=usuario_datos.Id_usuario_datos WHERE usuarios.Id_usuario=_Id_usuario;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `login` (IN `_usuario` VARCHAR(50), IN `_contrasena` VARCHAR(50))  BEGIN
    SELECT Id_usuario, Tipo_usuario FROM usuarios WHERE Usuario = _usuario AND Contrasena = _contrasena;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `modificarInformacionUsuario` (IN `_apellido_paterno` VARCHAR(50), IN `_apellido_materno` VARCHAR(50), IN `_nombres` VARCHAR(50), IN `_id_usuario` INT)  BEGIN
    UPDATE Usuario_datos SET Apellido_paterno=_apellido_paterno, Apellido_materno=_apellido_materno, Nombres=_nombres WHERE Id_usuario=_id_usuario;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `modificarUsuario` (IN `_anterior_password` VARCHAR(255), IN `_nueva_password` VARCHAR(255), IN `_correo` VARCHAR(50), IN `_id_usuario` INT)  BEGIN
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `registroBache` (IN `_fechaHoraServidor` VARCHAR(50), IN `_latitud` VARCHAR(45), IN `_longitud` VARCHAR(45), IN `_referencia` VARCHAR(100), IN `_comentario` VARCHAR(45), IN `_imagen` VARCHAR(45), IN `_id_usuario` INT)  BEGIN
    INSERT INTO reportes (Fecha, Latitud, Longitud, Referencia, Comentario, Imagen, Id_usuario) 
VALUES (_fechaHoraServidor, _latitud, _longitud, _referencia, _comentario, _imagen, _id_usuario);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `registroBacheHistorial` (IN `_fecha` VARCHAR(50), IN `_id_reporte` INT)  BEGIN
    INSERT INTO reporte_historial (Fecha, Id_reporte) 
VALUES (_fecha, _id_reporte);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `registroInformacionUsuario` (IN `_apellido_paterno` VARCHAR(50), IN `_apellido_materno` VARCHAR(50), IN `_nombres` VARCHAR(50))  BEGIN
    INSERT INTO usuario_datos (Apellido_paterno, Apellido_materno, Nombres) 
	VALUES (_apellido_paterno, _apellido_materno, _nombres);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `registroUsuario` (IN `_usuario` VARCHAR(50), IN `_password` VARCHAR(50), IN `_correo` VARCHAR(50), IN `_Id_usuario_datos` INT)  BEGIN
    INSERT INTO usuarios (Usuario, Contrasena, Correo, Tipo_usuario, Id_usuario_datos) 
	VALUES (_usuario, _password, _correo, 2, _Id_usuario_datos);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `brigadas`
--

CREATE TABLE `brigadas` (
  `Id_brigada` int(11) NOT NULL,
  `Nombre_brigada` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reportes`
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
-- Dumping data for table `reportes`
--

INSERT INTO `reportes` (`Id_reporte`, `Fecha`, `Latitud`, `Longitud`, `Referencia`, `Comentario`, `Imagen`, `Id_usuario`, `Id_brigada`) VALUES
(95, '2017-07-27', '18.51786511519624', '-88.30772370100044', 'Calle Juan José Siordia, #410A', 'asdklasjk', NULL, 5, NULL),
(97, '2017-07-30', '18.50948207185211', '-88.31038445234321', 'San Salvador, #434-436', 'Sad', '../reportes/97.jpg', 1, NULL),
(98, '2017-07-31', '18.512127251083566', '-88.29402923583984', 'Av de los Héroes, #321', 'esta culero', NULL, 6, NULL),
(99, '2017-07-31', '18.512127251083566', '-88.29402923583984', 'Av de los Héroes, #321', 'esta culero', NULL, 6, NULL),
(102, '2017-07-31', '18.514446836280598', '-88.29402923583984', 'Calle Isla Cancun, #159E', 'khb', NULL, 6, NULL),
(103, '2017-07-31', '18.514446836280598', '-88.29402923583984', 'Calle Isla Cancun, #159E', 'khb', NULL, 6, NULL),
(104, '2017-08-01', '18.516807083830848', '-88.31454724073433', 'Av. 4 de Marzo, #361', 'URGE ', '../reportes/104.PNG', 1, NULL),
(105, '2017-08-01', '18.51981838667812', '-88.32222908735298', 'Av Insurgentes, #11B', 'DASDSD', '../reportes/105.PNG', 1, NULL),
(108, '2017-08-01', '18.506307802621688', '-88.2975956797602', 'Av. Juárez, #222', 'SAD', '../reportes/108.jpg', 1, NULL),
(109, '2017-08-01', '18.510540148357904', '-88.29311728477478', 'Calle Chicozapote, #168', 'Un bache muy grande y peligroso', NULL, 7, NULL),
(110, '2017-08-01', '18.51043841050168', '-88.31859827041626', 'Calle Manuel Acuña, #21', 'Reparar la calle por favor', '../reportes/110.png', 7, NULL),
(111, '2017-08-01', '18.513103922367574', '-88.30488681793213', 'Av Andrés Quintana Roo, #322', '', NULL, 8, NULL),
(113, '2017-08-01', '18.514202670898566', '-88.31836223602295', 'Javier Rojo Gomez, #339', 'yolo', '../reportes/113.png', 7, NULL),
(114, '2017-08-01', '18.514202670898566', '-88.31836223602295', 'Javier Rojo Gomez, #339', 'yolo', '../reportes/114.png', 7, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reporte_historial`
--

CREATE TABLE `reporte_historial` (
  `Id_reporte_historial` int(11) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `Fecha` date NOT NULL,
  `Estatus` set('En espera','Atendido','Finalizado','Cancelado') NOT NULL DEFAULT 'En espera',
  `Id_reporte` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reporte_historial`
--

INSERT INTO `reporte_historial` (`Id_reporte_historial`, `Descripcion`, `Fecha`, `Estatus`, `Id_reporte`) VALUES
(53, NULL, '2017-07-27', 'En espera', 95),
(55, NULL, '2017-07-30', 'En espera', 97),
(56, NULL, '2017-07-31', 'En espera', 98),
(57, NULL, '2017-07-31', 'En espera', 99),
(60, NULL, '2017-07-31', 'En espera', 102),
(61, NULL, '2017-07-31', 'En espera', 103),
(62, NULL, '2017-08-01', 'En espera', 104),
(63, NULL, '2017-08-01', 'En espera', 105),
(66, NULL, '2017-08-01', 'En espera', 108),
(67, NULL, '2017-08-01', 'En espera', 109),
(68, NULL, '2017-08-01', 'En espera', 110),
(69, NULL, '2017-08-01', 'En espera', 111),
(70, 'YA VAMOS WEON', '2017-08-01', 'Atendido', 109),
(71, NULL, '2017-08-01', 'En espera', 113),
(72, NULL, '2017-08-01', 'En espera', 114);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
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
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`Id_usuario`, `Usuario`, `Contrasena`, `Correo`, `Tipo_usuario`, `Encargado`, `Id_usuario_datos`, `Id_brigada`) VALUES
(1, 'uvs', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'uvs@gmail.com', 2, NULL, 1, NULL),
(2, 'julio28', '52336104be246289fc8c4a76561d0b4fb825755a', 'julio@ut.com', 2, NULL, 2, NULL),
(3, 'pruebaa', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'pruebaaq@hotmail.com', 2, NULL, 3, NULL),
(4, 'aselem', '9a7848637c8d32b3ce3292b2696b8758da00c6df', 'aselem123@gmail.com', 2, NULL, 4, NULL),
(5, 'juan78', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', 'juan78@gmail.com', 2, NULL, 5, NULL),
(6, 'martinez.v', '8cb2237d0679ca88db6464eac60da96345513964', 'victor.martinez@hotmail.com', 2, NULL, 6, NULL),
(7, 'juan80', 'adc16fa41a38b174232f206e0b2bd006baaace68', 'juuan80@gmail.com', 2, NULL, 7, NULL),
(8, 'martinez2', '8cb2237d0679ca88db6464eac60da96345513964', 'martinez2@hotmail.com', 2, NULL, 8, NULL),
(9, 'manuel', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'martinez.victor@hotmail.com', 2, NULL, 9, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usuario_datos`
--

CREATE TABLE `usuario_datos` (
  `Id_usuario_datos` int(11) NOT NULL,
  `Apellido_paterno` varchar(45) NOT NULL,
  `Apellido_materno` varchar(45) NOT NULL,
  `Nombres` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usuario_datos`
--

INSERT INTO `usuario_datos` (`Id_usuario_datos`, `Apellido_paterno`, `Apellido_materno`, `Nombres`) VALUES
(1, 'Rivera ', 'PÃ©rez', 'Joel Nahim'),
(2, 'Chan', 'GarcÃ­a', 'Julio RenÃ©'),
(3, 'admin', 'asd', 'dmom'),
(4, 'Selem', 'Salinas', 'Abraham Elias'),
(5, 'Martinez', 'Perez', 'Juan'),
(6, 'Martinez', 'Montesinos', 'victor'),
(7, 'Rivera', 'Perez', 'Juan Manuel'),
(8, 'martinez', 'montesinos', 'victor manuel'),
(9, 'martinez', 'montesinos', 'manuel');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brigadas`
--
ALTER TABLE `brigadas`
  ADD PRIMARY KEY (`Id_brigada`);

--
-- Indexes for table `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`Id_reporte`,`Id_usuario`),
  ADD KEY `fk_Reportes_Usuarios1_idx` (`Id_usuario`),
  ADD KEY `fk_Reportes_Brigadas1_idx` (`Id_brigada`);

--
-- Indexes for table `reporte_historial`
--
ALTER TABLE `reporte_historial`
  ADD PRIMARY KEY (`Id_reporte_historial`,`Id_reporte`),
  ADD KEY `fk_Reporte_historial_Reportes1_idx` (`Id_reporte`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`Id_usuario`,`Id_usuario_datos`),
  ADD UNIQUE KEY `Usuario_UNIQUE` (`Usuario`),
  ADD UNIQUE KEY `Correo_UNIQUE` (`Correo`),
  ADD KEY `fk_Usuarios_Usuario_datos_idx` (`Id_usuario_datos`),
  ADD KEY `fk_Usuarios_Brigadas1_idx` (`Id_brigada`);

--
-- Indexes for table `usuario_datos`
--
ALTER TABLE `usuario_datos`
  ADD PRIMARY KEY (`Id_usuario_datos`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reportes`
--
ALTER TABLE `reportes`
  MODIFY `Id_reporte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;
--
-- AUTO_INCREMENT for table `reporte_historial`
--
ALTER TABLE `reporte_historial`
  MODIFY `Id_reporte_historial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;
--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `Id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `usuario_datos`
--
ALTER TABLE `usuario_datos`
  MODIFY `Id_usuario_datos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `reportes`
--
ALTER TABLE `reportes`
  ADD CONSTRAINT `fk_Reportes_Brigadas1` FOREIGN KEY (`Id_brigada`) REFERENCES `brigadas` (`Id_brigada`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Reportes_Usuarios1` FOREIGN KEY (`Id_usuario`) REFERENCES `usuarios` (`Id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `reporte_historial`
--
ALTER TABLE `reporte_historial`
  ADD CONSTRAINT `fk_Reporte_historial_Reportes1` FOREIGN KEY (`Id_reporte`) REFERENCES `reportes` (`Id_reporte`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_Usuarios_Brigadas1` FOREIGN KEY (`Id_brigada`) REFERENCES `brigadas` (`Id_brigada`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Usuarios_Usuario_datos` FOREIGN KEY (`Id_usuario_datos`) REFERENCES `usuario_datos` (`Id_usuario_datos`) ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
