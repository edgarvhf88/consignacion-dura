-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 19-09-2016 a las 05:07:01
-- Versión del servidor: 5.6.12-log
-- Versión de PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `big_data`
--
CREATE DATABASE IF NOT EXISTS `big_data` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `big_data`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `importar`
--

CREATE TABLE IF NOT EXISTS `importar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  `ape_pat` varchar(30) NOT NULL,
  `ape_mat` varchar(30) NOT NULL,
  `edad` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `importar`
--

INSERT INTO `importar` (`id`, `nombre`, `ape_pat`, `ape_mat`, `edad`) VALUES
(1, 'lennin', 'vargas', 'montoya', 17),
(2, 'elizabeth', 'padilla', 'apon', 20),
(3, 'arnold', 'quispe', 'campos', 57),
(4, 'silver', 'zamata', 'ford', 50),
(5, 'adrian', 'renteria', 'torpoco', 38),
(6, 'maxiel', 'carillo', 'zapata', 24);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
