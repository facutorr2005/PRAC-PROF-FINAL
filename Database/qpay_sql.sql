-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 05-11-2025 a las 17:42:56
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
-- Base de datos: `qpay`
--
CREATE DATABASE IF NOT EXISTS `qpay` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `qpay`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `restablecimientos_contrasena`
--

DROP TABLE IF EXISTS `restablecimientos_contrasena`;
CREATE TABLE `restablecimientos_contrasena` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `codigo_hash` char(60) NOT NULL,
  `vence_el` datetime NOT NULL,
  `intentos` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `usado` tinyint(1) NOT NULL DEFAULT 0,
  `creado_el` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncar tablas antes de insertar `restablecimientos_contrasena`
--

TRUNCATE TABLE `restablecimientos_contrasena`;
--
-- Volcado de datos para la tabla `restablecimientos_contrasena`
--

INSERT INTO `restablecimientos_contrasena` (`id`, `usuario_id`, `codigo_hash`, `vence_el`, `intentos`, `usado`, `creado_el`) VALUES
(9, 2, '$2y$10$b/hI7qderhaHp5t54JGoRuhoiC6DQSQAMPp/nzqFS.KomHJpWP6P.', '2025-10-03 02:30:42', 0, 0, '2025-10-02 21:15:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

DROP TABLE IF EXISTS `Usuarios`;
CREATE TABLE `Usuarios` (
  `Id` int(11) NOT NULL,
  `Correo` varchar(255) NOT NULL,
  `ContrasenaHash` varchar(255) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Apellido` varchar(100) NOT NULL,
  `FechaNacimiento` date NOT NULL,
  `Dni` varchar(20) NOT NULL,
  `CreadoEn` datetime NOT NULL DEFAULT current_timestamp(),
  `tipo_documento` enum('DNI','LC','LE','CI','Pasaporte') NOT NULL DEFAULT 'DNI',
  `numero_documento` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncar tablas antes de insertar `Usuarios`
--

TRUNCATE TABLE `Usuarios`;
--
-- Volcado de datos para la tabla `Usuarios`
--

INSERT INTO `Usuarios` (`Id`, `Correo`, `ContrasenaHash`, `Nombre`, `Apellido`, `FechaNacimiento`, `Dni`, `CreadoEn`, `tipo_documento`, `numero_documento`) VALUES
(1, 'demo@qpay.test', '$2y$10$ZV0dxhOozb8OC62EAVu84.jAxKxkV9PYGTM1ZTrlAfAc2RycbMc6W', 'Demo', 'Usuario', '2000-01-01', '12345678', '2025-09-21 00:02:55', 'DNI', ''),
(2, 'ivangonzalolap@gmail.com', '$2y$10$/LpBVrFXN0yx2wGie8Mt2OVaGJ7Hjwlf5q.WYVICyfbW0Ut.1vHyi', 'Ivan Gonzalo', 'Lapresa', '1995-11-04', '39253505', '2025-09-21 00:48:37', 'DNI', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `restablecimientos_contrasena`
--
ALTER TABLE `restablecimientos_contrasena`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usuario_id` (`usuario_id`),
  ADD KEY `idx_vence_el` (`vence_el`),
  ADD KEY `idx_usado` (`usado`);

--
-- Indices de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `uniq_correo` (`Correo`),
  ADD UNIQUE KEY `uniq_dni` (`Dni`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `restablecimientos_contrasena`
--
ALTER TABLE `restablecimientos_contrasena`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `restablecimientos_contrasena`
--
ALTER TABLE `restablecimientos_contrasena`
  ADD CONSTRAINT `fk_rc_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `Usuarios` (`Id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
