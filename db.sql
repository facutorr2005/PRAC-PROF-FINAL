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
