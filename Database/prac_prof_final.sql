-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 06-11-2025 a las 00:37:01
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `prac_prof_final`
--
CREATE DATABASE IF NOT EXISTS `prac_prof_final` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `prac_prof_final`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `CodigoEAN` varchar(13) DEFAULT NULL,
  `Nombre` varchar(255) NOT NULL,
  `PrecioVenta` decimal(12,2) NOT NULL,
  `Imagen` varchar(255) NOT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`ID`),
  UNIQUE KEY `uniq_productos_ean` (`CodigoEAN`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncar tablas antes de insertar `productos`
--

TRUNCATE TABLE `productos`;
--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`ID`, `CodigoEAN`, `Nombre`, `PrecioVenta`, `Imagen`, `CreatedAt`, `UpdatedAt`) VALUES
(1, '7791234567890', 'Manzana Roja 1kg', 2499.00, 'img/productos/manzana.jpg', '2025-10-19 00:15:29', NULL),
(2, '7790987654321', 'Banana Ecuador 1kg', 2999.00, 'img/productos/banana.jpg', '2025-10-19 00:15:29', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recuperacion_contrasena`
--

DROP TABLE IF EXISTS `recuperacion_contrasena`;
CREATE TABLE IF NOT EXISTS `recuperacion_contrasena` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `correo` varchar(150) NOT NULL,
  `codigo_hash` varchar(255) NOT NULL,
  `vence_el` datetime NOT NULL,
  `intentos` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `enviado_el` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_correo` (`correo`),
  KEY `fk_recuperacion_usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncar tablas antes de insertar `recuperacion_contrasena`
--

TRUNCATE TABLE `recuperacion_contrasena`;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transacciones`
--

DROP TABLE IF EXISTS `transacciones`;
CREATE TABLE IF NOT EXISTS `transacciones` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Momento` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncar tablas antes de insertar `transacciones`
--

TRUNCATE TABLE `transacciones`;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transacciones_detalles`
--

DROP TABLE IF EXISTS `transacciones_detalles`;
CREATE TABLE IF NOT EXISTS `transacciones_detalles` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `IDtransaccion` int(10) UNSIGNED NOT NULL,
  `CodigoEAN` varchar(13) NOT NULL,
  `Cantidad` int(11) NOT NULL,
  `PrecioVenta` decimal(12,2) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `idx_td_tx` (`IDtransaccion`),
  KEY `idx_td_ean` (`CodigoEAN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncar tablas antes de insertar `transacciones_detalles`
--

TRUNCATE TABLE `transacciones_detalles`;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) DEFAULT NULL,
  `Apellido` varchar(100) DEFAULT NULL,
  `Email` varchar(150) NOT NULL,
  `DNI` varchar(50) DEFAULT NULL,
  `FechaNacimiento` date DEFAULT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`ID`),
  UNIQUE KEY `uniq_usuarios_correo` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncar tablas antes de insertar `usuarios`
--

TRUNCATE TABLE `usuarios`;
--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`ID`, `Nombre`, `Apellido`, `Email`, `DNI`, `FechaNacimiento`, `PasswordHash`, `CreatedAt`, `UpdatedAt`) VALUES
(1, 'Demo', 'Usuario', 'demo@qpay.test', '12345678', '1990-01-01', '<PEGA_AQUÍ_EL_HASH_GENERADO_CON_password_hash>', '2025-10-19 00:15:29', NULL),
(3, 'dario alejandro', 'alarcon', 'darioalarcon635@gmail.com', '38904923', '1995-03-29', '$2y$10$rtaXazBgK4CJWdYPZf4u8udF1.7wO70.L.oAIcE95geK6lYVTtDxK', '2025-10-31 23:44:40', '2025-11-01 00:49:07'),
(5, 'Ivan Gonzalo', 'Lapresa', 'ivangonzalolap@gmail.com', '39253505', '1995-11-04', '$2y$10$U8aCU7EMpeA6savSjPSTEObonCe8PEMJ5P/iZj3bBcHahAVV2wwFq', '2025-11-05 20:18:10', NULL);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `recuperacion_contrasena`
--
ALTER TABLE `recuperacion_contrasena`
  ADD CONSTRAINT `fk_recuperacion_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`ID`) ON DELETE CASCADE;

--
-- Filtros para la tabla `transacciones_detalles`
--
ALTER TABLE `transacciones_detalles`
  ADD CONSTRAINT `fk_td_prod` FOREIGN KEY (`CodigoEAN`) REFERENCES `productos` (`CodigoEAN`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_td_tx` FOREIGN KEY (`IDtransaccion`) REFERENCES `transacciones` (`ID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
