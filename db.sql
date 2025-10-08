-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-09-2025 a las 01:10:37
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

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

-- ===== QPAY: ESQUEMA =====
-- Opción A: crear la BD si no existe
CREATE DATABASE IF NOT EXISTS `qpay`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `qpay`;

-- Tabla Usuarios (la que usa el login/registro)
CREATE TABLE IF NOT EXISTS `Usuarios` (
  `Id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Correo` VARCHAR(255) NOT NULL,
  `ContrasenaHash` VARCHAR(255) NOT NULL,
  `Nombre` VARCHAR(100) NOT NULL,
  `Apellido` VARCHAR(100) NOT NULL,
  `FechaNacimiento` DATE NOT NULL,
  `Dni` VARCHAR(20) NOT NULL,
  `CreadoEn` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `uniq_correo` (`Correo`),
  UNIQUE KEY `uniq_dni` (`Dni`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


SET NAMES utf8mb4;

-- Agregar columnas nuevas si no existen
ALTER TABLE `Usuarios`
  ADD COLUMN IF NOT EXISTS `tipo_documento`
    ENUM('DNI','LC','LE','CI','Pasaporte') NOT NULL DEFAULT 'DNI'
    AFTER `CreadoEn`,
  ADD COLUMN IF NOT EXISTS `numero_documento`
    VARCHAR(32) NOT NULL
    AFTER `tipo_documento`;

-- Backfill: si usabas `Dni`, copiamos su valor a `numero_documento` cuando esté vacío
UPDATE `Usuarios`
   SET `numero_documento` = COALESCE(NULLIF(TRIM(`numero_documento`),''), TRIM(`Dni`))
 WHERE (TRIM(`numero_documento`) = '' OR `numero_documento` IS NULL)
   AND `Dni` IS NOT NULL;

-- Índice único en documento (nuevo)
ALTER TABLE `Usuarios`
  ADD UNIQUE KEY `uniq_documento` (`tipo_documento`,`numero_documento`);

-- (Opcional) eliminar índice único viejo de Dni si existía
-- OJO: solo si ya no lo usás en la app.
-- SHOW INDEX FROM `Usuarios`;  -- para ver el nombre exacto
DROP INDEX `uniq_dni` ON `Usuarios`;

--  (Opcional) eliminar columna Dni si ya migraste todo y no la vas a usar
-- ALTER TABLE `Usuarios` DROP COLUMN `Dni`;

-- Asegurar índice único en correo (por si falta)
ALTER TABLE `Usuarios`
  ADD UNIQUE KEY IF NOT EXISTS `uniq_correo` (`Correo`);

-- Crear tabla de restablecimientos si no existe
CREATE TABLE IF NOT EXISTS `restablecimientos_contrasena` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id`   INT UNSIGNED NOT NULL,
  `codigo_hash`  CHAR(60)     NOT NULL,
  `vence_el`     DATETIME     NOT NULL,
  `intentos`     TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `usado`        TINYINT(1)   NOT NULL DEFAULT 0,
  `creado_el`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_usuario_id` (`usuario_id`),
  KEY `idx_vence_el` (`vence_el`),
  KEY `idx_usado` (`usado`),
  CONSTRAINT `fk_rc_usuario`
    FOREIGN KEY (`usuario_id`) REFERENCES `Usuarios`(`Id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;