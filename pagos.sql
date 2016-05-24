-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 24-05-2016 a las 07:06:29
-- Versión del servidor: 5.5.49-0ubuntu0.14.04.1
-- Versión de PHP: 5.5.9-1ubuntu4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `pagos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adeudos`
--

CREATE TABLE IF NOT EXISTS `adeudos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sub_concepto_id` int(10) unsigned NOT NULL,
  `id_persona` int(10) unsigned DEFAULT NULL,
  `importe` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fecha_limite` date DEFAULT NULL,
  `periodo` int(10) unsigned DEFAULT NULL,
  `grado` tinyint(2) unsigned DEFAULT NULL,
  `paquete_id` int(10) unsigned DEFAULT NULL,
  `status_adeudo` tinyint(1) NOT NULL DEFAULT '0',
  `recargo` float(12,0) NOT NULL DEFAULT '0',
  `tipo_recargo` int(10) unsigned DEFAULT NULL,
  `subconcepto_paquete_id` int(11) DEFAULT NULL,
  `digito_referencia` tinyint(1) NOT NULL,
  `descripcion_sc` varchar(30) DEFAULT NULL,
  `recargo_acumulado` tinyint(1) NOT NULL DEFAULT '1',
  `aplica_beca` tinyint(1) NOT NULL DEFAULT '1',
  `aplica_recargo` tinyint(1) NOT NULL DEFAULT '1',
  `es_inscripcion` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Este campo sirve para bloquear la beca si se tiene un retraso en el pago del mismo.',
  `es_recursamiento` tinyint(4) NOT NULL,
  `materia_recursamiento` varchar(300) DEFAULT NULL,
  `tipo_adeudo` int(10) NOT NULL,
  `mes` varchar(20) NOT NULL,
  `matricula` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `adeudos_FKIndex1` (`sub_concepto_id`),
  KEY `id_plandepago` (`paquete_id`),
  KEY `tipo_recargo` (`tipo_recargo`),
  KEY `adeudos_ibfk_1_idx` (`sub_concepto_id`,`tipo_adeudo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=91 ;

--
-- Volcado de datos para la tabla `adeudos`
--

INSERT INTO `adeudos` (`id`, `sub_concepto_id`, `id_persona`, `importe`, `created_at`, `updated_at`, `fecha_limite`, `periodo`, `grado`, `paquete_id`, `status_adeudo`, `recargo`, `tipo_recargo`, `subconcepto_paquete_id`, `digito_referencia`, `descripcion_sc`, `recargo_acumulado`, `aplica_beca`, `aplica_recargo`, `es_inscripcion`, `es_recursamiento`, `materia_recursamiento`, `tipo_adeudo`, `mes`, `matricula`) VALUES
(76, 245, 22, 500, '2016-05-15 22:24:07', '2016-05-15 22:24:07', '2016-05-05', 162, 9, 3, 1, 100, 2, 83, 0, 'Mayo', 0, 0, 1, 1, 0, NULL, 1, 'Mayo', 'UP110153'),
(77, 246, 22, 880, '2016-05-15 22:24:07', '2016-05-15 22:24:07', '2016-05-10', 162, 9, 3, 0, 65, 2, 84, 1, 'Mayo', 1, 1, 1, 0, 0, NULL, 1, 'Mayo', 'UP110153'),
(78, 246, 22, 880, '2016-05-15 22:24:08', '2016-05-15 22:24:08', '2016-06-10', 162, 9, 3, 0, 65, 2, 85, 2, 'Junio', 1, 1, 1, 0, 0, NULL, 1, 'Junio', 'UP110153'),
(79, 246, 22, 880, '2016-05-15 22:24:08', '2016-05-15 22:24:08', '2016-07-10', 162, 9, 3, 0, 65, 2, 86, 3, 'Julio', 1, 1, 1, 0, 0, NULL, 1, 'Julio', 'UP110153'),
(80, 246, 22, 880, '2016-05-15 22:24:08', '2016-05-15 22:24:08', '2016-08-10', 162, 9, 3, 0, 65, 2, 87, 4, 'Agosto', 1, 1, 1, 0, 0, NULL, 1, 'Agosto', 'UP110153'),
(81, 245, 268, 500, '2016-05-15 22:24:09', '2016-05-15 22:24:09', '2016-05-05', 162, 2, 3, 0, 100, 2, 83, 0, 'Mayo', 0, 0, 1, 1, 0, NULL, 1, 'Mayo', 'AN050187'),
(82, 246, 268, 880, '2016-05-15 22:24:09', '2016-05-15 22:24:09', '2016-05-10', 162, 2, 3, 0, 65, 2, 84, 1, 'Mayo', 1, 1, 1, 0, 0, NULL, 1, 'Mayo', 'AN050187'),
(83, 246, 268, 880, '2016-05-15 22:24:09', '2016-05-15 22:24:09', '2016-06-10', 162, 2, 3, 0, 65, 2, 85, 2, 'Junio', 1, 1, 1, 0, 0, NULL, 1, 'Junio', 'AN050187'),
(84, 246, 268, 880, '2016-05-15 22:24:09', '2016-05-15 22:24:09', '2016-07-10', 162, 2, 3, 0, 65, 2, 86, 3, 'Julio', 1, 1, 1, 0, 0, NULL, 1, 'Julio', 'AN050187'),
(85, 246, 268, 880, '2016-05-15 22:24:10', '2016-05-15 22:24:10', '2016-08-10', 162, 2, 3, 0, 65, 2, 87, 4, 'Agosto', 1, 1, 1, 0, 0, NULL, 1, 'Agosto', 'AN050187'),
(86, 245, 528, 500, '2016-05-15 22:24:11', '2016-05-15 22:24:11', '2016-05-05', 162, 10, 3, 0, 100, 2, 83, 0, 'Mayo', 0, 0, 1, 1, 0, NULL, 1, 'Mayo', 'SE060551'),
(87, 246, 528, 880, '2016-05-15 22:24:11', '2016-05-15 22:24:11', '2016-05-10', 162, 10, 3, 0, 65, 2, 84, 1, 'Mayo', 1, 1, 1, 0, 0, NULL, 1, 'Mayo', 'SE060551'),
(88, 246, 528, 880, '2016-05-15 22:24:11', '2016-05-15 22:24:11', '2016-06-10', 162, 10, 3, 0, 65, 2, 85, 2, 'Junio', 1, 1, 1, 0, 0, NULL, 1, 'Junio', 'SE060551'),
(89, 246, 528, 880, '2016-05-15 22:24:12', '2016-05-15 22:24:12', '2016-07-10', 162, 10, 3, 0, 65, 2, 86, 3, 'Julio', 1, 1, 1, 0, 0, NULL, 1, 'Julio', 'SE060551'),
(90, 246, 528, 880, '2016-05-15 22:24:12', '2016-05-15 22:24:12', '2016-08-10', 162, 10, 3, 0, 65, 2, 87, 4, 'Agosto', 1, 1, 1, 0, 0, NULL, 1, 'Agosto', 'SE060551');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adeudos_paqueteplandepago`
--

CREATE TABLE IF NOT EXISTS `adeudos_paqueteplandepago` (
  `paquete_id` int(10) unsigned NOT NULL,
  `adeudos_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`paquete_id`,`adeudos_id`),
  KEY `adeudos_id` (`adeudos_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adeudos_tipopago`
--

CREATE TABLE IF NOT EXISTS `adeudos_tipopago` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_pago_id` int(10) unsigned NOT NULL,
  `adeudos_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `adeudos_tipopago_FKIndex1` (`adeudos_id`),
  KEY `adeudos_tipopago_FKIndex2` (`tipo_pago_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=181 ;

--
-- Volcado de datos para la tabla `adeudos_tipopago`
--

INSERT INTO `adeudos_tipopago` (`id`, `tipo_pago_id`, `adeudos_id`, `created_at`, `updated_at`) VALUES
(151, 1, 76, '2016-05-15 22:24:07', '2016-05-15 22:24:07'),
(152, 2, 76, '2016-05-15 22:24:07', '2016-05-15 22:24:07'),
(153, 1, 77, '2016-05-15 22:24:07', '2016-05-15 22:24:07'),
(154, 2, 77, '2016-05-15 22:24:08', '2016-05-15 22:24:08'),
(155, 1, 78, '2016-05-15 22:24:08', '2016-05-15 22:24:08'),
(156, 2, 78, '2016-05-15 22:24:08', '2016-05-15 22:24:08'),
(157, 1, 79, '2016-05-15 22:24:08', '2016-05-15 22:24:08'),
(158, 2, 79, '2016-05-15 22:24:08', '2016-05-15 22:24:08'),
(159, 1, 80, '2016-05-15 22:24:08', '2016-05-15 22:24:08'),
(160, 2, 80, '2016-05-15 22:24:08', '2016-05-15 22:24:08'),
(161, 1, 81, '2016-05-15 22:24:09', '2016-05-15 22:24:09'),
(162, 2, 81, '2016-05-15 22:24:09', '2016-05-15 22:24:09'),
(163, 1, 82, '2016-05-15 22:24:09', '2016-05-15 22:24:09'),
(164, 2, 82, '2016-05-15 22:24:09', '2016-05-15 22:24:09'),
(165, 1, 83, '2016-05-15 22:24:09', '2016-05-15 22:24:09'),
(166, 2, 83, '2016-05-15 22:24:09', '2016-05-15 22:24:09'),
(167, 1, 84, '2016-05-15 22:24:09', '2016-05-15 22:24:09'),
(168, 2, 84, '2016-05-15 22:24:10', '2016-05-15 22:24:10'),
(169, 1, 85, '2016-05-15 22:24:10', '2016-05-15 22:24:10'),
(170, 2, 85, '2016-05-15 22:24:10', '2016-05-15 22:24:10'),
(171, 1, 86, '2016-05-15 22:24:11', '2016-05-15 22:24:11'),
(172, 2, 86, '2016-05-15 22:24:11', '2016-05-15 22:24:11'),
(173, 1, 87, '2016-05-15 22:24:11', '2016-05-15 22:24:11'),
(174, 2, 87, '2016-05-15 22:24:11', '2016-05-15 22:24:11'),
(175, 1, 88, '2016-05-15 22:24:11', '2016-05-15 22:24:11'),
(176, 2, 88, '2016-05-15 22:24:11', '2016-05-15 22:24:11'),
(177, 1, 89, '2016-05-15 22:24:12', '2016-05-15 22:24:12'),
(178, 2, 89, '2016-05-15 22:24:12', '2016-05-15 22:24:12'),
(179, 1, 90, '2016-05-15 22:24:12', '2016-05-15 22:24:12'),
(180, 2, 90, '2016-05-15 22:24:12', '2016-05-15 22:24:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agrupaciones`
--

CREATE TABLE IF NOT EXISTS `agrupaciones` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `agrupaciones`
--

INSERT INTO `agrupaciones` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'PLAN DE PAGOS', 'ESTA AGRUPACIÓN CONTIENE LOS PLANES DE PAGO EXISTENTES', '2014-11-23 00:20:07', '2014-11-23 00:20:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bancos`
--

CREATE TABLE IF NOT EXISTS `bancos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `banco` varchar(50) DEFAULT NULL,
  `descripcion` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `bancos`
--

INSERT INTO `bancos` (`id`, `banco`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'BANCOMER', 'CUENTAS DE BANCOMER', '2015-01-06 13:35:12', '2015-01-08 06:32:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `becas`
--

CREATE TABLE IF NOT EXISTS `becas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_importe_id` int(10) unsigned NOT NULL,
  `subcidios_id` int(10) unsigned NOT NULL,
  `importe` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tipobeca` int(10) unsigned DEFAULT NULL,
  `abreviatura` varchar(10) DEFAULT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `becas_FKIndex1` (`subcidios_id`),
  KEY `becas_FKIndex2` (`tipo_importe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `becas_alumno`
--

CREATE TABLE IF NOT EXISTS `becas_alumno` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `periodo` int(10) NOT NULL,
  `idnivel` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `idbeca` int(10) unsigned NOT NULL,
  `id_persona` int(10) unsigned NOT NULL,
  `asignada_por` varchar(200) NOT NULL,
  `cancelada_por` varchar(200) DEFAULT NULL,
  `cancelada_motivo` text,
  `cancelada_fecha` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idbeca` (`idbeca`,`id_persona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conceptos`
--

CREATE TABLE IF NOT EXISTS `conceptos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `concepto` varchar(30) NOT NULL,
  `descripcion` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `banco_id` int(10) NOT NULL,
  `cuenta_id` int(10) NOT NULL,
  `descripcion_officio` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `conceptos`
--

INSERT INTO `conceptos` (`id`, `concepto`, `descripcion`, `created_at`, `updated_at`, `banco_id`, `cuenta_id`, `descripcion_officio`) VALUES
(6, '41 DERECHO P SER', 'DERECHO P SER', '2015-12-06 12:00:54', '2015-12-06 12:01:21', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas`
--

CREATE TABLE IF NOT EXISTS `cuentas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bancos_id` int(10) unsigned NOT NULL,
  `cuenta` varchar(50) DEFAULT NULL,
  `activo_cobros` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cuentas_FKIndex1` (`bancos_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Volcado de datos para la tabla `cuentas`
--

INSERT INTO `cuentas` (`id`, `bancos_id`, `cuenta`, `activo_cobros`, `created_at`, `updated_at`) VALUES
(19, 1, '0785318', 1, '2015-01-26 15:00:39', '2015-12-08 06:40:07'),
(20, 1, '0785319', 0, '2015-12-08 06:39:28', '2015-12-08 06:40:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `descuentos`
--

CREATE TABLE IF NOT EXISTS `descuentos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_importe_id` int(10) unsigned NOT NULL,
  `adeudos_id` int(10) unsigned NOT NULL,
  `importe` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `no_officio` longtext NOT NULL,
  `importe_recargo` float NOT NULL DEFAULT '0',
  `descripcion_officio` longtext,
  PRIMARY KEY (`id`),
  KEY `descuentos_FKIndex1` (`adeudos_id`),
  KEY `descuentos_FKIndex2` (`tipo_importe_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `descuentos`
--

INSERT INTO `descuentos` (`id`, `tipo_importe_id`, `adeudos_id`, `importe`, `created_at`, `updated_at`, `no_officio`, `importe_recargo`, `descripcion_officio`) VALUES
(1, 2, 77, 500, NULL, NULL, '1222', 30, 'La descripcion del descuento.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devoluciones`
--

CREATE TABLE IF NOT EXISTS `devoluciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `periodo` int(11) NOT NULL,
  `status_devolucion` int(11) DEFAULT '0',
  `importe` double NOT NULL,
  `id_persona` int(11) NOT NULL,
  `fecha_devolucion` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `forma_pago`
--

CREATE TABLE IF NOT EXISTS `forma_pago` (
  `id` int(11) NOT NULL,
  `forma` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `groups_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos_monetarios`
--

CREATE TABLE IF NOT EXISTS `ingresos_monetarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_pago` datetime NOT NULL,
  `importe` double NOT NULL,
  `tipo_pago` tinyint(4) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2012_12_06_225921_migration_cartalyst_sentry_install_users', 1),
('2012_12_06_225929_migration_cartalyst_sentry_install_groups', 1),
('2012_12_06_225945_migration_cartalyst_sentry_install_users_groups_pivot', 1),
('2012_12_06_225988_migration_cartalyst_sentry_install_throttle', 1),
('2015_01_21_052037_add_api_token_users_table', 2),
('2015_01_21_052620_add_api_token_users_table', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE IF NOT EXISTS `pagos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `importe` float NOT NULL,
  `fecha_pago` datetime NOT NULL,
  `tipo_pago` int(10) NOT NULL,
  `forma_pago` int(10) NOT NULL,
  `importe_recargo` float NOT NULL DEFAULT '0',
  `adeudos_id` int(10) NOT NULL COMMENT '	',
  `referencia_id` int(11) DEFAULT NULL,
  `beca` float NOT NULL,
  `cueta_id` int(10) NOT NULL,
  `descuento` float NOT NULL DEFAULT '0',
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `lugar` varchar(45) DEFAULT NULL,
  `descuento_recargo` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `importe`, `fecha_pago`, `tipo_pago`, `forma_pago`, `importe_recargo`, `adeudos_id`, `referencia_id`, `beca`, `cueta_id`, `descuento`, `create_at`, `update_at`, `lugar`, `descuento_recargo`) VALUES
(1, 500, '2016-05-05 00:00:00', 1, 1, 0, 76, 2, 0, 19, 0, NULL, NULL, 'Aguascalientes', 0),
(2, 100, '2016-05-05 00:00:00', 2, 1, 5, 77, NULL, 0, 19, 0, NULL, NULL, 'Aguascalientes', 0),
(3, 100, '2016-05-05 00:00:00', 2, 1, 5, 77, NULL, 0, 19, 0, NULL, NULL, 'Aguascalientes', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paqueteplandepago`
--

CREATE TABLE IF NOT EXISTS `paqueteplandepago` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `periodo` int(10) unsigned DEFAULT NULL,
  `idnivel` int(10) unsigned DEFAULT NULL,
  `nivel` varchar(20) DEFAULT NULL,
  `id_plandepago` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `es_propedeutico` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_plandepago` (`id_plandepago`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `paqueteplandepago`
--

INSERT INTO `paqueteplandepago` (`id`, `periodo`, `idnivel`, `nivel`, `id_plandepago`, `created_at`, `updated_at`, `es_propedeutico`) VALUES
(3, 162, 1, 'LICENCIATURA', 1, '2016-05-15 22:14:33', '2016-05-15 22:14:33', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan_de_pago`
--

CREATE TABLE IF NOT EXISTS `plan_de_pago` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` text NOT NULL,
  `clave_plan` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `id_agrupaciones` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agrupacionesF` (`id_agrupaciones`),
  KEY `id_agrupaciones` (`id_agrupaciones`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `plan_de_pago`
--

INSERT INTO `plan_de_pago` (`id`, `descripcion`, `clave_plan`, `created_at`, `updated_at`, `id_agrupaciones`) VALUES
(1, 'PLAN INSCRIPCION TEST 1', '1', '2016-05-09 01:35:52', '2016-05-09 01:35:52', 1),
(2, 'PLAN DE PAGOS  COLEGIATURA  TEST 2', '2', '2016-05-09 01:52:00', '2016-05-09 01:52:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prorrogas`
--

CREATE TABLE IF NOT EXISTS `prorrogas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adeudos_id` int(11) NOT NULL,
  `no_oficio` int(11) NOT NULL,
  `nombre_responsable` varchar(100) DEFAULT NULL,
  `rason_prorroga` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `referencias`
--

CREATE TABLE IF NOT EXISTS `referencias` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `adeudos_id` int(10) unsigned NOT NULL,
  `cuentas_id` int(10) unsigned NOT NULL,
  `referencia` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `importe` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`adeudos_id`),
  KEY `referencias_FKIndex1` (`adeudos_id`),
  KEY `referencias_FKIndex2` (`cuentas_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `referencias`
--

INSERT INTO `referencias` (`id`, `adeudos_id`, `cuentas_id`, `referencia`, `created_at`, `updated_at`, `importe`) VALUES
(2, 76, 19, '13203209230230230', NULL, NULL, 500);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `referencias_pagadas`
--

CREATE TABLE IF NOT EXISTS `referencias_pagadas` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_referencia` int(10) unsigned DEFAULT NULL,
  `fecha_de_pago` date NOT NULL,
  `importe` float NOT NULL,
  `estado` varchar(10) CHARACTER SET latin1 NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `id_referenciaFKIndex1` (`id_referencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_pago`
--

CREATE TABLE IF NOT EXISTS `registro_pago` (
  `id` int(10) NOT NULL,
  `asignada_por` varchar(250) NOT NULL,
  `razon` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `email_login_asignacion` varchar(250) NOT NULL,
  `adeudo_id` int(10) NOT NULL,
  `pago_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuesta_bancaria`
--

CREATE TABLE IF NOT EXISTS `respuesta_bancaria` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `monto` float(22,0) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `no_transacciones` int(10) NOT NULL,
  `cobro_inmediato` float(22,0) NOT NULL,
  `comisiones_creadas` float(22,0) NOT NULL,
  `remesas` float(22,0) NOT NULL,
  `comisiones_remesas` float(22,0) NOT NULL,
  `abonado` float(22,0) NOT NULL,
  `nombre_archivo` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subcidios`
--

CREATE TABLE IF NOT EXISTS `subcidios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(15) DEFAULT NULL,
  `descripcion` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `subcidios`
--

INSERT INTO `subcidios` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'INTITUCIÓN', 'SUBCIDIO OTORGADO POR LA INSTITUCIÓN', NULL, NULL),
(2, 'SEP', 'SUBCIDIO OTORGADO POR LA SEP', NULL, NULL),
(3, 'MUNICIPAL', 'SUBCIDIO OTORGADO POR LA ENTIDAD MUNICIPAL', NULL, NULL),
(4, 'ESTATAL', 'SUBCIDIO OTORGADO POR LA ENTIDAD ESTATAL', NULL, NULL),
(5, 'FEDERAL', 'SUBCIDIO OTORGADO POR LA ENTIDAD FEDERAL', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subconcepto_paqueteplandepago`
--

CREATE TABLE IF NOT EXISTS `subconcepto_paqueteplandepago` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sub_concepto_id` int(10) unsigned NOT NULL,
  `paquete_id` int(10) unsigned NOT NULL,
  `fecha_de_vencimiento` date NOT NULL,
  `recargo` float NOT NULL,
  `tipo_recargo` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tipos_pago` longtext NOT NULL,
  `digito_referencia` tinyint(1) NOT NULL,
  `descripcion_sc` varchar(30) NOT NULL,
  `recargo_acumulado` tinyint(1) NOT NULL DEFAULT '0',
  `mes` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `subconcepto_plandepago_FKIndex2` (`sub_concepto_id`),
  KEY `subconcepto_plandepago_FKIndex1` (`paquete_id`) USING BTREE,
  KEY `tipo_recargo` (`tipo_recargo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=88 ;

--
-- Volcado de datos para la tabla `subconcepto_paqueteplandepago`
--

INSERT INTO `subconcepto_paqueteplandepago` (`id`, `sub_concepto_id`, `paquete_id`, `fecha_de_vencimiento`, `recargo`, `tipo_recargo`, `created_at`, `updated_at`, `tipos_pago`, `digito_referencia`, `descripcion_sc`, `recargo_acumulado`, `mes`) VALUES
(83, 245, 3, '2016-05-05', 100, 2, NULL, NULL, '[1,2]', 0, 'Mayo', 0, 'Mayo'),
(84, 246, 3, '2016-05-10', 65, 2, NULL, NULL, '[1,2]', 1, 'Mayo', 1, 'Mayo'),
(85, 246, 3, '2016-06-10', 65, 2, NULL, NULL, '[1,2]', 2, 'Junio', 1, 'Junio'),
(86, 246, 3, '2016-07-10', 65, 2, NULL, NULL, '[1,2]', 3, 'Julio', 1, 'Julio'),
(87, 246, 3, '2016-08-10', 65, 2, NULL, NULL, '[1,2]', 4, 'Agosto', 1, 'Agosto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sub_conceptos`
--

CREATE TABLE IF NOT EXISTS `sub_conceptos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `conceptos_id` int(10) unsigned NOT NULL,
  `tipo_adeudo` int(10) unsigned NOT NULL,
  `sub_concepto` varchar(50) NOT NULL,
  `descripcion` text,
  `importe` float NOT NULL,
  `periodo` int(10) NOT NULL,
  `nivel_id` int(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `aplica_beca` tinyint(1) NOT NULL DEFAULT '1',
  `es_inscripcion` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sub_concepto_FKIndex1` (`conceptos_id`),
  KEY `tipo_adeudo` (`tipo_adeudo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=247 ;

--
-- Volcado de datos para la tabla `sub_conceptos`
--

INSERT INTO `sub_conceptos` (`id`, `conceptos_id`, `tipo_adeudo`, `sub_concepto`, `descripcion`, `importe`, `periodo`, `nivel_id`, `created_at`, `updated_at`, `aplica_beca`, `es_inscripcion`) VALUES
(196, 6, 1, '1 INSCRIPCION Y REINSCRIPCION DE LICENCIATURA', 'INSCRIPCION Y REINSCRIPCION DE LICENCIATURA', 500, 153, 1, '2015-12-06 12:04:27', '2015-12-06 12:04:27', 0, 1),
(197, 6, 1, '3 REGISTRO A PROPEDÉUTICO', 'REGISTRO A PROPEDÉUTICO', 500, 153, 1, '2015-12-06 12:06:43', '2015-12-06 12:06:43', 0, 1),
(198, 6, 1, '2 INSCRIPCIÓN Y REINSCRIPCIÓN DE MAESTRÍA', 'INSCRIPCIÓN Y REINSCRIPCIÓN DE MAESTRÍA', 1100, 153, 3, '2015-12-06 12:09:37', '2015-12-06 12:09:37', 0, 1),
(200, 6, 1, '7 COLEGIATURA MENSUAL DE MAESTRÍA', 'COLEGIATURA MENSUAL DE MAESTRÍA', 1500, 153, 3, '2015-12-06 12:13:57', '2015-12-06 12:13:57', 1, 0),
(201, 6, 1, '6 COLEGIATURA MENSUAL DE LICENCIATURA', 'COLEGIATURA MENSUAL DE LICENCIATURA', 880, 153, 1, '2015-12-06 12:17:07', '2015-12-06 12:17:07', 1, 0),
(202, 6, 1, '8 COLEGIATURA MENSUAL DE PROPEDÉUTICO', 'COLEGIATURA MENSUAL DE PROPEDÉUTICO', 880, 153, 1, '2015-12-06 12:27:45', '2015-12-06 12:27:45', 1, 0),
(204, 6, 1, '9 PAGO MENSUAL POR MATERIA RECURSADA', 'PAGO MENSUAL POR MATERIA RECURSADA', 150, 153, 1, '2015-12-06 13:05:23', '2015-12-06 13:05:23', 1, 0),
(205, 6, 1, '9 PAGO MENSUAL POR MATERIA RECURSADA', 'PAGO MENSUAL POR MATERIA RECURSADA', 150, 153, 3, '2015-12-06 13:06:20', '2015-12-06 13:06:20', 0, 0),
(206, 6, 1, '91 PAGO MENSUAL POR 2 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 2 MATERIAS RECURSADAS', 300, 153, 1, '2015-12-07 14:53:25', '2015-12-07 14:53:25', 1, 0),
(207, 6, 1, '92 PAGO MENSUAL POR 3 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 3 MATERIAS RECURSADAS', 450, 153, 1, '2015-12-07 14:54:03', '2015-12-07 14:54:03', 1, 0),
(208, 6, 1, '93 PAGO MENSUAL POR 4 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 4 MATERIAS RECURSADAS', 600, 153, 1, '2015-12-07 14:54:36', '2015-12-07 14:54:36', 1, 0),
(209, 6, 1, '94 PAGO MENSUAL POR 5 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 5 MATERIAS RECURSADAS', 750, 153, 1, '2015-12-07 14:56:50', '2015-12-07 14:56:50', 1, 0),
(210, 6, 1, '95 PAGO MENSUAL POR 6 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 6 MATERIAS RECURSADAS', 900, 153, 1, '2015-12-07 14:57:19', '2015-12-07 14:57:19', 1, 0),
(211, 6, 1, '96 PAGO MENSUAL POR 7 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 7 MATERIAS RECURSADAS', 1050, 153, 1, '2015-12-07 14:57:48', '2015-12-07 14:57:48', 1, 0),
(212, 6, 1, '97 PAGO MENSUAL POR 8 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 8 MATERIAS RECURSADAS', 1200, 153, 1, '2015-12-07 14:58:20', '2015-12-07 14:58:20', 1, 0),
(213, 6, 1, '1 INSCRIPCION Y REINSCRIPCION DE LICENCIATURA', 'INSCRIPCIÓN Y REINSCRIPCIÓN DE LICENCIATURA', 500, 151, 1, '2016-03-18 14:41:29', '2016-03-18 14:41:29', 0, 1),
(214, 6, 1, '1 INSCRIPCIÓN Y REINSCRIPCIÓN DE LICENCIATURA', 'INSCRIPCIÓN Y REINSCRIPCIÓN DE LICENCIATURA', 500, 152, 1, '2016-03-18 14:42:44', '2016-03-18 14:42:44', 0, 1),
(215, 6, 1, '1 INSCRIPCIÓN Y REINSCRIPCIÓN DE LICENCIATURA', 'INSCRIPCIÓN Y REINSCRIPCIÓN DE LICENCIATURA', 500, 161, 1, '2016-03-18 14:42:58', '2016-03-18 14:42:58', 0, 1),
(216, 6, 1, '6 COLEGIATURA MENSUAL DE LICENCIATURA', 'COLEGIATURA MENSUAL DE LICENCIATURA', 880, 151, 1, '2016-03-18 14:47:52', '2016-03-18 14:47:52', 1, 0),
(217, 6, 1, '6 COLEGIATURA MENSUAL DE LICENCIATURA', 'COLEGIATURA MENSUAL DE LICENCIATURA', 880, 152, 1, '2016-03-18 14:48:12', '2016-03-18 14:48:12', 1, 0),
(218, 6, 1, '6 COLEGIATURA MENSUAL DE LICENCIATURA', 'COLEGIATURA MENSUAL DE LICENCIATURA', 880, 161, 1, '2016-03-18 14:48:38', '2016-03-18 14:48:38', 1, 0),
(220, 6, 1, '9 PAGO MENSUAL POR MATERIA RECURSADA', 'PAGO MENSUAL POR MATERIA RECURSADA', 150, 151, 1, '2016-03-18 14:56:42', '2016-03-18 14:56:42', 1, 0),
(221, 6, 1, '9 PAGO MENSUAL POR MATERIA RECURSADA', 'PAGO MENSUAL POR MATERIA RECURSADA', 150, 152, 1, '2016-03-18 14:56:49', '2016-03-18 14:56:49', 1, 0),
(222, 6, 1, '9 PAGO MENSUAL POR MATERIA RECURSADA', 'PAGO MENSUAL POR MATERIA RECURSADA', 150, 161, 1, '2016-03-18 14:56:55', '2016-03-18 14:56:55', 1, 0),
(224, 6, 1, '91 PAGO MENSUAL POR 2 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 2 MATERIAS RECURSADAS', 300, 151, 1, '2016-03-18 15:00:29', '2016-03-18 15:00:29', 1, 0),
(225, 6, 1, '91 PAGO MENSUAL POR 2 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 2 MATERIAS RECURSADAS', 300, 152, 1, '2016-03-18 15:00:40', '2016-03-18 15:00:40', 1, 0),
(226, 6, 1, '91 PAGO MENSUAL POR 2 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 2 MATERIAS RECURSADAS', 300, 161, 1, '2016-03-18 15:00:53', '2016-03-18 15:00:53', 1, 0),
(227, 6, 1, '92 PAGO MENSUAL POR 3 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 3 MATERIAS RECURSADAS', 450, 151, 1, '2016-03-18 15:02:27', '2016-03-18 15:02:27', 1, 0),
(228, 6, 1, '92 PAGO MENSUAL POR 3 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 3 MATERIAS RECURSADAS', 450, 152, 1, '2016-03-18 15:02:34', '2016-03-18 15:02:34', 1, 0),
(229, 6, 1, '92 PAGO MENSUAL POR 3 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 3 MATERIAS RECURSADAS', 450, 161, 1, '2016-03-18 15:02:41', '2016-03-18 15:02:41', 1, 0),
(230, 6, 1, '93 PAGO MENSUAL POR 4 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 4 MATERIAS RECURSADAS', 600, 151, 1, '2016-03-18 15:08:33', '2016-03-18 15:08:33', 1, 0),
(231, 6, 1, '93 PAGO MENSUAL POR 4 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 4 MATERIAS RECURSADAS', 600, 152, 1, '2016-03-18 15:08:49', '2016-03-18 15:08:49', 1, 0),
(232, 6, 1, '93 PAGO MENSUAL POR 4 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 4 MATERIAS RECURSADAS', 600, 161, 1, '2016-03-18 15:08:55', '2016-03-18 15:08:55', 1, 0),
(233, 6, 1, '94 PAGO MENSUAL POR 5 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 5 MATERIAS RECURSADAS', 750, 151, 1, '2016-03-18 15:09:40', '2016-03-18 15:09:40', 1, 0),
(234, 6, 1, '94 PAGO MENSUAL POR 5 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 5 MATERIAS RECURSADAS', 750, 152, 1, '2016-03-18 15:10:09', '2016-03-18 15:10:09', 1, 0),
(235, 6, 1, '94 PAGO MENSUAL POR 5 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 5 MATERIAS RECURSADAS', 750, 161, 1, '2016-03-18 15:10:26', '2016-03-18 15:10:26', 1, 0),
(236, 6, 1, '95 PAGO MENSUAL POR 6 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 6 MATERIAS RECURSADAS', 900, 151, 1, '2016-03-18 15:11:21', '2016-03-18 15:11:21', 1, 1),
(237, 6, 1, '95 PAGO MENSUAL POR 6 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 6 MATERIAS RECURSADAS', 900, 152, 1, '2016-03-18 15:11:31', '2016-03-18 15:11:31', 1, 0),
(238, 6, 1, '95 PAGO MENSUAL POR 6 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 6 MATERIAS RECURSADAS', 900, 161, 1, '2016-03-18 15:12:12', '2016-03-18 15:12:12', 1, 0),
(239, 6, 1, '96 PAGO MENSUAL POR 7 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 7 MATERIAS RECURSADAS', 1050, 151, 1, '2016-03-18 15:12:56', '2016-03-18 15:12:56', 1, 0),
(240, 6, 1, '96 PAGO MENSUAL POR 7 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 7 MATERIAS RECURSADAS', 1050, 152, 1, '2016-03-18 15:13:09', '2016-03-18 15:13:09', 1, 0),
(241, 6, 1, '96 PAGO MENSUAL POR 7 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 7 MATERIAS RECURSADAS', 1050, 161, 1, '2016-03-18 15:13:21', '2016-03-18 15:13:21', 1, 0),
(242, 6, 1, '97 PAGO MENSUAL POR 8 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 8 MATERIAS RECURSADAS', 1200, 151, 1, '2016-03-18 15:14:53', '2016-03-18 15:14:53', 1, 0),
(243, 6, 1, '97 PAGO MENSUAL POR 8 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 8 MATERIAS RECURSADAS', 1200, 152, 1, '2016-03-18 15:15:07', '2016-03-18 15:15:07', 1, 0),
(244, 6, 1, '97 PAGO MENSUAL POR 8 MATERIAS RECURSADAS', 'PAGO MENSUAL POR 8 MATERIAS RECURSADAS', 1200, 161, 1, '2016-03-18 15:15:22', '2016-03-18 15:15:22', 1, 0),
(245, 6, 1, '1 INSCRIPCIÓN Y REINSCRIPCIÓN DE LICENCIATURA', 'INSCRIPCIÓN Y REINSCRIPCIÓN DE LICENCIATURA', 500, 162, 1, '2016-05-09 02:04:28', '2016-05-09 02:04:28', 0, 1),
(246, 6, 1, '6 COLEGIATURA MENSUAL DE LICENCIATURA', '6 COLEGIATURA MENSUAL DE LICENCIATURA', 880, 162, 1, '2016-05-09 02:05:11', '2016-05-09 02:05:11', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `throttle`
--

CREATE TABLE IF NOT EXISTS `throttle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `attempts` int(11) NOT NULL DEFAULT '0',
  `suspended` tinyint(1) NOT NULL DEFAULT '0',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `last_attempt_at` timestamp NULL DEFAULT NULL,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `banned_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `throttle_user_id_index` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `throttle`
--

INSERT INTO `throttle` (`id`, `user_id`, `ip_address`, `attempts`, `suspended`, `banned`, `last_attempt_at`, `suspended_at`, `banned_at`) VALUES
(1, 1, '127.0.0.1', 0, 0, 0, NULL, NULL, NULL),
(2, 3, '127.0.0.1', 0, 0, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_adeudo`
--

CREATE TABLE IF NOT EXISTS `tipo_adeudo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `descripcion` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `tipo_adeudo`
--

INSERT INTO `tipo_adeudo` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'MONETARIO', 'ADEUDO QUE SE PAGARA EN EFECTIVO ', '2014-11-29 04:56:02', '2014-11-29 04:56:02'),
(2, 'NO MONETARIO', 'ADEUDO QUE SE PAGARA EN PRODUCTO', '2014-11-29 04:56:37', '2014-11-29 04:56:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_importe`
--

CREATE TABLE IF NOT EXISTS `tipo_importe` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) DEFAULT NULL,
  `descripcion` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `tipo_importe`
--

INSERT INTO `tipo_importe` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'PORCENTAJE', 'TIPO DE IMPORTE GENERADO MEDIANTE PORCENTAJE', '2015-09-21 21:19:24', NULL),
(2, 'IMPORTE', 'TIPO DE IMPORTE GENERADO MEDIANTE CANTIDAD DE IMPORTE', '2015-09-21 21:19:33', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_pago`
--

CREATE TABLE IF NOT EXISTS `tipo_pago` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `tipo_pago`
--

INSERT INTO `tipo_pago` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Banco', 'Pago a realzar mediante referemcia bancaria', NULL, NULL),
(2, 'Caja', 'Pago a realizar en caja', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `activation_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activated_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `persist_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reset_password_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `api_token` varchar(96) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_activation_code_index` (`activation_code`),
  KEY `users_reset_password_code_index` (`reset_password_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `permissions`, `activated`, `activation_code`, `activated_at`, `last_login`, `persist_code`, `reset_password_code`, `first_name`, `last_name`, `created_at`, `updated_at`, `api_token`) VALUES
(3, 'api.pagos@pagos.com', '$2y$10$f2Rj17m2Qcij1esv9b6cUOWibPZmGpKYSUeuSg9GQ0PjEN3NZpynS', NULL, 1, NULL, NULL, '2015-01-21 11:45:33', '$2y$10$qeiiW2.GH0u6EGq.kwOLMOCcrjv3rphp85sStQUH0lQLyda5gyW1.', NULL, NULL, NULL, '2015-01-21 11:44:31', '2015-01-21 11:45:33', '40b18490330b673bca074c62b655e2b177123605f1d62488039a52860bba3603');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `adeudos`
--
ALTER TABLE `adeudos`
  ADD CONSTRAINT `adeudos_ibfk_4` FOREIGN KEY (`tipo_recargo`) REFERENCES `tipo_importe` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `adeudos_paqueteplandepago`
--
ALTER TABLE `adeudos_paqueteplandepago`
  ADD CONSTRAINT `adeudos_paqueteplandepago_ibfk_1` FOREIGN KEY (`paquete_id`) REFERENCES `paqueteplandepago` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `adeudos_paqueteplandepago_ibfk_2` FOREIGN KEY (`adeudos_id`) REFERENCES `adeudos` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `adeudos_tipopago`
--
ALTER TABLE `adeudos_tipopago`
  ADD CONSTRAINT `adeudos_tipopago_ibfk_1` FOREIGN KEY (`adeudos_id`) REFERENCES `adeudos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `adeudos_tipopago_ibfk_2` FOREIGN KEY (`tipo_pago_id`) REFERENCES `tipo_pago` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `becas`
--
ALTER TABLE `becas`
  ADD CONSTRAINT `becas_ibfk_1` FOREIGN KEY (`tipo_importe_id`) REFERENCES `tipo_importe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `becas_ibfk_2` FOREIGN KEY (`subcidios_id`) REFERENCES `subcidios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `becas_alumno`
--
ALTER TABLE `becas_alumno`
  ADD CONSTRAINT `becas_alumno_ibfk_1` FOREIGN KEY (`idbeca`) REFERENCES `becas` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD CONSTRAINT `cuentas_ibfk_1` FOREIGN KEY (`bancos_id`) REFERENCES `bancos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `descuentos`
--
ALTER TABLE `descuentos`
  ADD CONSTRAINT `descuentos_ibfk_1` FOREIGN KEY (`adeudos_id`) REFERENCES `adeudos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `descuentos_ibfk_2` FOREIGN KEY (`tipo_importe_id`) REFERENCES `tipo_importe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `paqueteplandepago`
--
ALTER TABLE `paqueteplandepago`
  ADD CONSTRAINT `paqueteplandepago_ibfk_1` FOREIGN KEY (`id_plandepago`) REFERENCES `plan_de_pago` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `plan_de_pago`
--
ALTER TABLE `plan_de_pago`
  ADD CONSTRAINT `plan_de_pago_ibfk_1` FOREIGN KEY (`id_agrupaciones`) REFERENCES `agrupaciones` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `referencias`
--
ALTER TABLE `referencias`
  ADD CONSTRAINT `referencias_ibfk_1` FOREIGN KEY (`cuentas_id`) REFERENCES `cuentas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `referencias_ibfk_2` FOREIGN KEY (`adeudos_id`) REFERENCES `adeudos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `referencias_pagadas`
--
ALTER TABLE `referencias_pagadas`
  ADD CONSTRAINT `referencias_pagadas_ibfk_1` FOREIGN KEY (`id_referencia`) REFERENCES `referencias` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `sub_conceptos`
--
ALTER TABLE `sub_conceptos`
  ADD CONSTRAINT `sub_conceptos_ibfk_1` FOREIGN KEY (`conceptos_id`) REFERENCES `conceptos` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `sub_conceptos_ibfk_2` FOREIGN KEY (`tipo_adeudo`) REFERENCES `tipo_adeudo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
