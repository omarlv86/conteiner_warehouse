-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para logistica
CREATE DATABASE IF NOT EXISTS `logistica` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `logistica`;

-- Volcando estructura para tabla logistica.camion
CREATE TABLE IF NOT EXISTS `camion` (
  `idcamion` int NOT NULL AUTO_INCREMENT,
  `numero_placas` varchar(45) DEFAULT NULL,
  `nombre_conductor` varchar(45) DEFAULT NULL,
  `numero_economico` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idcamion`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla logistica.camion: ~9 rows (aproximadamente)
INSERT INTO `camion` (`idcamion`, `numero_placas`, `nombre_conductor`, `numero_economico`) VALUES
	(6, 'ZLC747562', 'OMAR', '134678'),
	(7, 'LZC4856772', 'JOSE', '128485'),
	(9, 'LZC56432', 'OMAR LUGO', '134678'),
	(10, 'LZC56432', 'OMAR LUGO', '134678'),
	(19, 'MICH38457', 'Alejandro', '485758'),
	(20, 'MOR285738', 'Luis', '45869483'),
	(21, 'MOR4563', 'Luis Antonio', '48938'),
	(24, 'MOR4563', 'Luis Antonio', '48938'),
	(25, 'MOR58382', 'JOSE LUIS LOPEZ', '8586383');

-- Volcando estructura para tabla logistica.contenedor
CREATE TABLE IF NOT EXISTS `contenedor` (
  `idcontenedor` int NOT NULL AUTO_INCREMENT,
  `numero` varchar(45) DEFAULT NULL,
  `tamano` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`idcontenedor`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla logistica.contenedor: ~7 rows (aproximadamente)
INSERT INTO `contenedor` (`idcontenedor`, `numero`, `tamano`) VALUES
	(5, '12345', '20HC'),
	(6, '65432', '40HC'),
	(7, '654324', '20HC'),
	(20, '6543243', '20HC'),
	(21, '3575738', '20HC'),
	(22, '8475728', '20HC'),
	(23, '86748300', '40HC');

-- Volcando estructura para tabla logistica.movimiento
CREATE TABLE IF NOT EXISTS `movimiento` (
  `id_movimiento` int NOT NULL AUTO_INCREMENT,
  `id_camion` int DEFAULT NULL,
  `id_contenedor` int DEFAULT NULL,
  `tipo_movimiento` enum('entrada','salida') DEFAULT NULL,
  `fecha_movimiento` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_movimiento`),
  KEY `id_camion` (`id_camion`),
  KEY `id_contenedor` (`id_contenedor`),
  CONSTRAINT `movimiento_ibfk_1` FOREIGN KEY (`id_camion`) REFERENCES `camion` (`idcamion`),
  CONSTRAINT `movimiento_ibfk_2` FOREIGN KEY (`id_contenedor`) REFERENCES `contenedor` (`idcontenedor`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla logistica.movimiento: ~10 rows (aproximadamente)
INSERT INTO `movimiento` (`id_movimiento`, `id_camion`, `id_contenedor`, `tipo_movimiento`, `fecha_movimiento`) VALUES
	(5, 6, 5, 'entrada', '2024-01-07 23:38:16'),
	(6, 7, 5, 'salida', '2024-11-10 07:10:20'),
	(7, 9, 5, 'entrada', '2024-12-07 23:53:44'),
	(8, 10, 6, 'entrada', '2024-12-07 23:57:43'),
	(23, 19, 20, 'entrada', '2024-12-08 00:17:11'),
	(24, 19, 21, 'entrada', '2024-12-08 00:17:11'),
	(25, 20, 22, 'entrada', '2024-12-08 00:22:03'),
	(26, 21, 23, 'entrada', '2024-12-08 00:37:40'),
	(27, 24, 23, 'salida', '2024-12-08 00:53:43'),
	(28, 25, 22, 'salida', '2024-12-08 00:58:35');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
