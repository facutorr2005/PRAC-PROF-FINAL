-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 22-11-2025 a las 03:54:43
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
-- Estructura de tabla para la tabla `carrito`
--

DROP TABLE IF EXISTS `carrito`;
CREATE TABLE `carrito` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `producto_id` bigint(20) UNSIGNED NOT NULL,
  `cantidad` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_transaccion`
--

DROP TABLE IF EXISTS `detalle_transaccion`;
CREATE TABLE `detalle_transaccion` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaccion_id` bigint(20) UNSIGNED NOT NULL,
  `producto_id` bigint(20) UNSIGNED NOT NULL,
  `ean` varchar(32) NOT NULL,
  `nombre_producto` varchar(255) NOT NULL,
  `precio_unitario` decimal(12,2) NOT NULL,
  `cantidad` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

DROP TABLE IF EXISTS `producto`;
CREATE TABLE `producto` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ean` varchar(32) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `precio` decimal(12,2) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE `productos` (
  `ID` int(10) UNSIGNED NOT NULL,
  `CodigoEAN` varchar(13) DEFAULT NULL,
  `Nombre` varchar(255) NOT NULL,
  `PrecioVenta` decimal(12,2) NOT NULL,
  `Imagen` varchar(255) NOT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`ID`, `CodigoEAN`, `Nombre`, `PrecioVenta`, `Imagen`, `CreatedAt`, `UpdatedAt`) VALUES
(1, '7791234567890', 'Manzana Roja 1kg', 2499.00, 'img/productos/manzana.jpg', '2025-10-19 00:15:29', NULL),
(2, '7790987654321', 'Banana Ecuador 1kg', 2999.00, 'img/productos/banana.jpg', '2025-10-19 00:15:29', NULL),
(3, '7790895000430', 'Coca-Cola 1.5L', 3500.00, 'imagenes/coca15.png', '2025-11-19 20:31:50', NULL),
(4, '2035017000000', 'Bife de chorizo', 21500.00, 'bife-de-chorizo.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(5, '2000000127910', 'Pollo entero', 13996.00, 'pollo-entero.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(6, '2000000481241', 'Carne picada especial', 12299.00, 'carne-picada-especial.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(7, '2006480000002', 'Chorizo parrillero', 1900.00, 'chorizo-parrillero.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(8, '2035008000002', 'Costillas de cerdo', 10699.00, 'costillas-de-cerdo.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(9, '2003507200001', 'Tomate redondo', 2200.00, 'tomate-redondo.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(10, '2012705000009', 'Papa blanca', 1250.00, 'papa-blanca.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(11, '2000649000001', 'Lechuga criolla', 2299.00, 'lechuga-criolla.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(12, '2000708000003', 'Zanahoria', 1799.00, 'zanahoria.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(13, '2000615000004', 'Cebolla', 1049.00, 'cebolla.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(14, '7790787002528', 'Leche entera', 2049.00, 'leche-entera.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(15, '2000000135076', 'Queso cremoso', 7000.00, 'queso-cremoso.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(16, '7790787960514', 'Yogur bebible', 2859.00, 'yogur-bebible.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(17, '7790079019159', 'Jamón cocido', 5729.00, 'jamon-cocido.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(18, '7793940054006', 'Manteca', 4019.00, 'manteca.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(19, '7990000046609', 'Pan francés', 1312.00, 'pan-frances.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(20, '2000944000003', 'Medialunas', 485.00, 'medialuna.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(21, '2006911000007', 'Facturas surtidas', 562.00, 'facturas-surtidas.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(22, '7791473001665', 'Bizcochuelo vainilla', 6989.00, 'bizcochuelo-de-vainilla.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(23, '7790199602927', 'Pan rallado', 2700.00, 'pan-rallado.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(24, '7790070431493', 'Arroz largo fino', 1014.00, 'arroz-largo-fino.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(25, '7790070337108', 'Fideos spaghetti', 2100.00, 'fideos-spaghetti.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(26, '7790070562203', 'Harina 000', 1080.00, 'harina-000.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(27, '7791100000283', 'Azúcar', 1200.00, 'azucar.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(28, '7793704000928', 'Yerba mate', 4500.00, 'yerba-mate.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(29, '7792931000039', 'Agua mineral sin gas', 1657.00, 'agua-mineral-sin-gas.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(30, '7622201703110', 'Jugo en polvo', 235.00, 'jugo-en-polvo.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(31, '7792798007387', 'Cerveza rubia', 3850.00, 'cerveza-rubia.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(32, '7790314063824', 'Vino tinto', 2649.00, 'vino-tinto.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(33, '7790132098459', 'Lavandina', 1400.00, 'lavandina.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(34, '7791290794115', 'Detergente', 3300.00, 'detergente.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(35, '7791290792104', 'Jabón en polvo', 9000.00, 'jabon-en-polvo.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(36, '7791130683814', 'Desodorante de pisos', 2200.00, 'desodorante-de-pisos.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(37, '7793253000370', 'Esponja', 937.00, 'esponja.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(38, '2800004601672', 'Plato llano de cerámica', 3800.00, 'plato-llano-de-ceramica.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(39, '7891711735809', 'Vaso de vidrio', 2900.00, 'vaso-de-vidrio.png', '2025-11-20 20:31:53', '2025-11-20 21:36:31'),
(40, '2800004773027', 'Cuchillo de cocina', 3500.00, 'cuchillo-de-cocina.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(41, '2800004678612', 'Sartén antiadherente', 36000.00, 'sarten-antiadherente.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51'),
(42, '2800001053382', 'Olla de acero inoxidable', 39999.00, 'olla-de-acero-inoxidable.png', '2025-11-20 20:31:53', '2025-11-20 20:59:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recuperacion_contrasena`
--

DROP TABLE IF EXISTS `recuperacion_contrasena`;
CREATE TABLE `recuperacion_contrasena` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `correo` varchar(150) NOT NULL,
  `codigo_hash` varchar(255) NOT NULL,
  `vence_el` datetime NOT NULL,
  `intentos` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `enviado_el` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transaccion`
--

DROP TABLE IF EXISTS `transaccion`;
CREATE TABLE `transaccion` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `estado` enum('confirmada','cancelada') NOT NULL DEFAULT 'confirmada',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transacciones`
--

DROP TABLE IF EXISTS `transacciones`;
CREATE TABLE `transacciones` (
  `ID` int(10) UNSIGNED NOT NULL,
  `Momento` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `transacciones`
--

INSERT INTO `transacciones` (`ID`, `Momento`) VALUES
(1, '2025-11-19 20:33:11'),
(2, '2025-11-19 20:33:20'),
(3, '2025-11-20 22:06:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transacciones_detalles`
--

DROP TABLE IF EXISTS `transacciones_detalles`;
CREATE TABLE `transacciones_detalles` (
  `ID` int(10) UNSIGNED NOT NULL,
  `IDtransaccion` int(10) UNSIGNED NOT NULL,
  `CodigoEAN` varchar(13) NOT NULL,
  `Cantidad` int(11) NOT NULL,
  `PrecioVenta` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `transacciones_detalles`
--

INSERT INTO `transacciones_detalles` (`ID`, `IDtransaccion`, `CodigoEAN`, `Cantidad`, `PrecioVenta`) VALUES
(1, 1, '7790895000430', 2, 3500.00),
(2, 2, '7790895000430', 2, 3500.00),
(3, 3, '2000000127910', 1, 13996.00),
(4, 3, '2003507200001', 1, 2200.00),
(5, 3, '2012705000009', 1, 1250.00),
(6, 3, '2035017000000', 1, 21500.00),
(7, 3, '2000944000003', 1, 485.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `ID` int(10) UNSIGNED NOT NULL,
  `Nombre` varchar(100) DEFAULT NULL,
  `Apellido` varchar(100) DEFAULT NULL,
  `Email` varchar(150) NOT NULL,
  `DNI` varchar(50) DEFAULT NULL,
  `FechaNacimiento` date DEFAULT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`ID`, `Nombre`, `Apellido`, `Email`, `DNI`, `FechaNacimiento`, `PasswordHash`, `CreatedAt`, `UpdatedAt`) VALUES
(1, 'Demo', 'Usuario', 'demo@qpay.test', '12345678', '1990-01-01', '<PEGA_AQUÍ_EL_HASH_GENERADO_CON_password_hash>', '2025-10-19 00:15:29', NULL),
(3, 'dario alejandro', 'alarcon', 'darioalarcon635@gmail.com', '38904923', '1995-03-29', '$2y$10$rtaXazBgK4CJWdYPZf4u8udF1.7wO70.L.oAIcE95geK6lYVTtDxK', '2025-10-31 23:44:40', '2025-11-01 00:49:07'),
(6, 'ricardo', 'cedaro', 'rcedaro@gmail.com', '16269772', '1963-01-23', '$2y$10$bhnK.Rv0nS4ZEWgQwOw7aeVmA/H752FaAO9glME0on.UsKmkBYeKS', '2025-11-12 19:36:57', '2025-11-12 19:40:02'),
(7, 'FACUNDO', 'ARRIBAS', 'arribasinformatica@gmail.com', '12312312', '1979-10-20', '$2y$10$HKVV5RDq/.lhxDQXkWU1wOaNQrmLBWUK5QyC.WSaOryQshadvVkIq', '2025-11-12 19:42:13', '2025-11-12 19:44:42'),
(8, 'Ivan Gonzalo', 'Lapresa', 'ivangonzalolap@gmail.com', '39253505', '1995-11-04', '$2y$10$WgHAlGRKrosOcK4FNo8hRuuu6lylPhRzFndlnbum0vV4czstJ/ZGe', '2025-11-12 21:11:02', '2025-11-20 19:05:51'),
(9, 'Manuel Elian', 'Alvarez', 'a.manuelelian@gmail.com', '43844841', '2002-02-09', '$2y$10$M/Cno/NleVDPZ6igqU9aV.wRGVpgbx56boqw5/i9rS5sRKBNA8nli', '2025-11-19 19:08:48', NULL),
(10, 'Facundo', 'Torres', 'facutorresisei@gmail.com', '46537676', '2005-06-14', '$2y$10$eAma8tuxI5SDf76M5gBAMO06g0xXJN8NteN1LPRbfBTcQF3NwnrQ.', '2025-11-19 20:25:36', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_carrito_usuario_producto` (`usuario_id`,`producto_id`),
  ADD KEY `idx_carrito_usuario` (`usuario_id`),
  ADD KEY `idx_carrito_producto` (`producto_id`);

--
-- Indices de la tabla `detalle_transaccion`
--
ALTER TABLE `detalle_transaccion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_detalle_tx` (`transaccion_id`),
  ADD KEY `idx_detalle_producto` (`producto_id`),
  ADD KEY `idx_detalle_ean` (`ean`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_producto_ean` (`ean`),
  ADD KEY `idx_producto_nombre` (`nombre`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `uniq_productos_ean` (`CodigoEAN`);

--
-- Indices de la tabla `recuperacion_contrasena`
--
ALTER TABLE `recuperacion_contrasena`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_correo` (`correo`),
  ADD KEY `fk_recuperacion_usuario` (`id_usuario`);

--
-- Indices de la tabla `transaccion`
--
ALTER TABLE `transaccion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tx_usuario` (`usuario_id`),
  ADD KEY `idx_tx_estado` (`estado`);

--
-- Indices de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `transacciones_detalles`
--
ALTER TABLE `transacciones_detalles`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `idx_td_tx` (`IDtransaccion`),
  ADD KEY `idx_td_ean` (`CodigoEAN`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `uniq_usuarios_correo` (`Email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_transaccion`
--
ALTER TABLE `detalle_transaccion`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `recuperacion_contrasena`
--
ALTER TABLE `recuperacion_contrasena`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `transaccion`
--
ALTER TABLE `transaccion`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `transacciones_detalles`
--
ALTER TABLE `transacciones_detalles`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `fk_carrito_producto` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_transaccion`
--
ALTER TABLE `detalle_transaccion`
  ADD CONSTRAINT `fk_detalle_producto` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detalle_tx` FOREIGN KEY (`transaccion_id`) REFERENCES `transaccion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
