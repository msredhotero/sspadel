-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 12-07-2016 a las 09:30:12
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `sspadel`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbgrupos`
--

CREATE TABLE IF NOT EXISTS `dbgrupos` (
  `IdGrupo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  PRIMARY KEY (`IdGrupo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbjugadores`
--

CREATE TABLE IF NOT EXISTS `dbjugadores` (
  `idjugador` int(11) NOT NULL AUTO_INCREMENT,
  `apellido` varchar(60) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `idequipo` int(11) NOT NULL DEFAULT '0',
  `dni` int(11) NOT NULL,
  `invitado` bit(1) DEFAULT b'0',
  `expulsado` bit(1) DEFAULT b'0',
  `email` varchar(120) DEFAULT NULL,
  `facebook` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idjugador`),
  KEY `dni` (`dni`),
  KEY `idequipo` (`idequipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbplayoff`
--

CREATE TABLE IF NOT EXISTS `dbplayoff` (
  `idplayoff` int(11) NOT NULL AUTO_INCREMENT,
  `reftorneo` int(11) NOT NULL,
  `refzona` int(11) NOT NULL,
  `refequipo` int(11) NOT NULL,
  `fechacreacion` date NOT NULL,
  PRIMARY KEY (`idplayoff`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbtorneos`
--

CREATE TABLE IF NOT EXISTS `dbtorneos` (
  `IdTorneo` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(200) NOT NULL,
  `FechaCreacion` date NOT NULL,
  `Activo` bit(1) NOT NULL,
  `reftipotorneo` smallint(6) NOT NULL,
  PRIMARY KEY (`IdTorneo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbtorneossedes`
--

CREATE TABLE IF NOT EXISTS `dbtorneossedes` (
  `idtorneosede` int(11) NOT NULL AUTO_INCREMENT,
  `reftorneo` int(11) NOT NULL,
  `refsede` int(11) NOT NULL,
  PRIMARY KEY (`idtorneosede`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbusuarios`
--

CREATE TABLE IF NOT EXISTS `dbusuarios` (
  `idusuario` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(10) NOT NULL,
  `password` varchar(10) NOT NULL,
  `refroll` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nombrecompleto` varchar(70) NOT NULL,
  PRIMARY KEY (`idusuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `dbusuarios`
--

INSERT INTO `dbusuarios` (`idusuario`, `usuario`, `password`, `refroll`, `email`, `nombrecompleto`) VALUES
(1, 'msredhoter', 'marcos', 1, 'msredhotero@msn.com', 'Saupurein Safar Marcos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `predio_menu`
--

CREATE TABLE IF NOT EXISTS `predio_menu` (
  `idmenu` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `icono` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Orden` smallint(6) DEFAULT NULL,
  `hover` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `permiso` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`idmenu`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=22 ;

--
-- Volcado de datos para la tabla `predio_menu`
--

INSERT INTO `predio_menu` (`idmenu`, `url`, `icono`, `nombre`, `Orden`, `hover`, `permiso`) VALUES
(1, '../torneos/', 'icotorneos', 'Torneos', 6, NULL, 'Administrador'),
(3, '../zonas/', 'icozonas', 'Zonas', 8, NULL, 'Administrador'),
(4, '../zonasequipos/', 'icozonasequipos', 'Categorias-Equipos', 10, NULL, 'Administrador'),
(5, '../fixture/', 'icofixture', 'Fixture', 11, NULL, 'Administrador'),
(6, '../jugadores/', 'icojugadores', 'Jugadores', 9, NULL, 'Administrador'),
(7, '../estadisticas/', 'icochart', 'Estadisticas', 12, NULL, 'Marcos'),
(12, '../logout.php', 'icosalir', 'Salir', 30, NULL, 'Administrador, Empleado'),
(13, '../index.php', 'icodashboard', 'Dashboard', 1, NULL, 'Administrador, Empleado'),
(14, '../planillas/', 'icoreportes', 'Planillas', 17, NULL, 'Administrador'),
(16, '../sedes/', 'icosedes', 'Sedes', 2, NULL, 'Administrador'),
(17, '../horarios/', 'icoturnos', 'Horarios', 3, NULL, 'Administrador'),
(18, '../canchas/', 'icocanchas', 'Canchas', 4, NULL, 'Administrador'),
(20, '../playoff/', 'icoplayoff', 'PlayOff', 15, NULL, 'Administrador'),
(21, '../tipotorneo/', 'icotorneos', 'Tipo Torneo', 5, NULL, 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbetapas`
--

CREATE TABLE IF NOT EXISTS `tbetapas` (
  `idetapa` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `valor` int(11) NOT NULL,
  PRIMARY KEY (`idetapa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbfechas`
--

CREATE TABLE IF NOT EXISTS `tbfechas` (
  `idfecha` int(11) NOT NULL AUTO_INCREMENT,
  `tipofecha` varchar(10) DEFAULT NULL,
  `resumen` mediumtext,
  PRIMARY KEY (`idfecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbhorarios`
--

CREATE TABLE IF NOT EXISTS `tbhorarios` (
  `idhorario` int(11) NOT NULL AUTO_INCREMENT,
  `horario` time NOT NULL,
  `reftipotorneo` smallint(6) NOT NULL,
  PRIMARY KEY (`idhorario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbplayoff`
--

CREATE TABLE IF NOT EXISTS `tbplayoff` (
  `idplayoff` int(11) NOT NULL AUTO_INCREMENT,
  `refplayoffequipo_a` int(11) NOT NULL,
  `refplayoffresultado_a` tinyint(4) NOT NULL,
  `refplayoffequipo_b` int(11) NOT NULL,
  `refplayoffresultado_b` tinyint(4) NOT NULL,
  `fechajuego` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `refcancha` int(11) DEFAULT NULL,
  `chequeado` bit(1) DEFAULT NULL,
  `refetapa` tinyint(4) NOT NULL,
  `penalesa` smallint(6) DEFAULT NULL,
  `penalesb` smallint(6) DEFAULT NULL,
  `refzona` int(11) NOT NULL,
  PRIMARY KEY (`idplayoff`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbroles`
--

CREATE TABLE IF NOT EXISTS `tbroles` (
  `idrol` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) NOT NULL,
  `activo` bit(1) NOT NULL,
  PRIMARY KEY (`idrol`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `tbroles`
--

INSERT INTO `tbroles` (`idrol`, `descripcion`, `activo`) VALUES
(1, 'Administrador', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbsedes`
--

CREATE TABLE IF NOT EXISTS `tbsedes` (
  `idsede` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `direccion` varchar(120) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idsede`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbtipotorneo`
--

CREATE TABLE IF NOT EXISTS `tbtipotorneo` (
  `idtipotorneo` int(11) NOT NULL AUTO_INCREMENT,
  `descripciontorneo` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `activo` bit(1) NOT NULL,
  PRIMARY KEY (`idtipotorneo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `tbtipotorneo`
--

INSERT INTO `tbtipotorneo` (`idtipotorneo`, `descripciontorneo`, `activo`) VALUES
(1, 'Torneo de Verano', b'0');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
