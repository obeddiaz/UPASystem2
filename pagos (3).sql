-- phpMyAdmin SQL Dump
-- version 4.2.2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 05-12-2014 a las 09:59:15
-- Versión del servidor: 5.6.18-enterprise-commercial-advanced
-- Versión de PHP: 5.4.31

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
`id` int(10) unsigned NOT NULL,
  `sub_concepto_id` int(10) unsigned NOT NULL,
  `id_persona` int(10) unsigned DEFAULT NULL,
  `importe` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fecha_limite` date DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL,
  `periodo` int(10) unsigned DEFAULT NULL,
  `grado` tinyint(2) unsigned DEFAULT NULL,
  `paquete_id` int(10) unsigned DEFAULT NULL,
  `status_adeudo` tinyint(1) DEFAULT NULL,
  `recargo` float(12,0) NOT NULL DEFAULT '0',
  `tipo_recargo` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- Volcado de datos para la tabla `adeudos`
--

INSERT INTO `adeudos` (`id`, `sub_concepto_id`, `id_persona`, `importe`, `created_at`, `updated_at`, `fecha_limite`, `fecha_pago`, `periodo`, `grado`, `paquete_id`, `status_adeudo`, `recargo`, `tipo_recargo`) VALUES
(1, 3, 22, 500, '2014-11-29 07:42:17', '2014-11-29 07:42:17', '2014-10-10', NULL, 143, 6, 1, 0, 50, 2),
(2, 4, 22, 500, '2014-11-29 07:42:17', '2014-11-29 07:42:17', '2014-11-10', NULL, 143, 6, 1, 0, 100, 2),
(3, 4, 22, 500, '2014-11-29 07:42:17', '2014-11-29 07:42:17', '2014-12-10', NULL, 143, 6, 1, 0, 100, 2),
(4, 3, 699, 350, '2014-11-29 07:42:18', '2014-12-03 09:13:45', '2014-11-10', '2014-12-03', 143, 6, 1, 1, 50, 2),
(5, 4, 699, 450, '2014-11-29 07:42:18', '2014-12-03 09:13:46', '2014-12-10', '2014-12-03', 143, 6, 1, 0, 0, 2),
(6, 4, 699, 500, '2014-11-29 07:42:18', '2014-12-03 09:13:46', '2015-01-10', '2014-12-03', 143, 6, 1, 0, 0, 2),
(7, 3, 838, 500, '2014-11-29 07:42:18', '2014-11-29 07:42:18', '2014-09-10', NULL, 143, 6, 1, 0, 50, 2),
(8, 4, 838, 500, '2014-11-29 07:42:18', '2014-11-29 07:42:18', '2014-10-10', NULL, 143, 6, 1, 0, 100, 2),
(9, 4, 838, 500, '2014-11-29 07:42:18', '2014-11-29 07:42:18', '2014-11-10', NULL, 143, 6, 1, 0, 100, 2),
(10, 3, 1012, 500, '2014-11-29 07:42:22', '2014-11-29 07:42:22', '2014-09-10', NULL, 143, 6, 1, 0, 50, 2),
(11, 4, 1012, 500, '2014-11-29 07:42:22', '2014-11-29 07:42:22', '2014-10-10', NULL, 143, 6, 1, 0, 100, 2),
(12, 4, 1012, 500, '2014-11-29 07:42:22', '2014-11-29 07:42:22', '2014-11-10', NULL, 143, 6, 1, 0, 100, 2),
(13, 3, 1125, 500, '2014-11-29 07:42:22', '2014-11-29 07:42:22', '2014-09-10', NULL, 143, 6, 1, 0, 50, 2),
(14, 4, 1125, 500, '2014-11-29 07:42:22', '2014-11-29 07:42:22', '2014-10-10', NULL, 143, 6, 1, 0, 100, 2),
(15, 4, 1125, 500, '2014-11-29 07:42:22', '2014-11-29 07:42:22', '2014-11-10', NULL, 143, 6, 1, 0, 100, 2),
(16, 3, 5519, 500, '2014-12-05 11:41:12', '2014-12-05 11:41:12', '2014-09-10', NULL, 142, 3, 1, 0, 50, 2),
(17, 4, 5519, 500, '2014-12-05 11:41:12', '2014-12-05 11:41:12', '2014-10-10', NULL, 142, 3, 1, 0, 100, 2),
(18, 4, 5519, 500, '2014-12-05 11:41:12', '2014-12-05 11:41:12', '2014-11-10', NULL, 142, 3, 1, 0, 100, 2),
(19, 3, 6869, 500, '2014-12-05 11:41:12', '2014-12-05 11:41:12', '2014-09-10', NULL, 142, 0, 1, 0, 50, 2),
(20, 4, 6869, 500, '2014-12-05 11:41:12', '2014-12-05 11:41:12', '2014-10-10', NULL, 142, 0, 1, 0, 100, 2),
(21, 4, 6869, 500, '2014-12-05 11:41:12', '2014-12-05 11:41:12', '2014-11-10', NULL, 142, 0, 1, 0, 100, 2),
(22, 3, 6521, 500, '2014-12-05 11:41:12', '2014-12-05 11:41:12', '2014-09-10', NULL, 142, 0, 1, 0, 50, 2),
(23, 4, 6521, 500, '2014-12-05 11:41:12', '2014-12-05 11:41:12', '2014-10-10', NULL, 142, 0, 1, 0, 100, 2),
(24, 4, 6521, 500, '2014-12-05 11:41:12', '2014-12-05 11:41:12', '2014-11-10', NULL, 142, 0, 1, 0, 100, 2),
(25, 3, 1669, 500, '2014-12-05 11:41:12', '2014-12-05 11:41:12', '2014-09-10', NULL, 142, 9, 1, 0, 50, 2),
(26, 4, 1669, 500, '2014-12-05 11:41:12', '2014-12-05 11:41:12', '2014-10-10', NULL, 142, 9, 1, 0, 100, 2),
(27, 4, 1669, 500, '2014-12-05 11:41:12', '2014-12-05 11:41:12', '2014-11-10', NULL, 142, 9, 1, 0, 100, 2),
(28, 3, 838, 500, '2014-12-05 12:02:22', '2014-12-05 12:02:22', '2014-09-10', NULL, 142, NULL, 1, 0, 50, 2),
(29, 4, 838, 500, '2014-12-05 12:02:23', '2014-12-05 12:02:23', '2014-10-10', NULL, 142, NULL, 1, 0, 100, 2),
(30, 4, 838, 500, '2014-12-05 12:02:23', '2014-12-05 12:02:23', '2014-11-10', NULL, 142, NULL, 1, 0, 100, 2),
(31, 3, 5175, 500, '2014-12-05 12:02:58', '2014-12-05 12:02:58', '2014-09-10', NULL, 142, NULL, 1, 0, 50, 2),
(32, 4, 5175, 500, '2014-12-05 12:02:58', '2014-12-05 12:02:58', '2014-10-10', NULL, 142, NULL, 1, 0, 100, 2),
(33, 4, 5175, 500, '2014-12-05 12:02:58', '2014-12-05 12:02:58', '2014-11-10', NULL, 142, NULL, 1, 0, 100, 2),
(34, 3, 7074, 500, '2014-12-05 12:02:58', '2014-12-05 12:02:58', '2014-09-10', NULL, 142, NULL, 1, 0, 50, 2),
(35, 4, 7074, 500, '2014-12-05 12:02:58', '2014-12-05 12:02:58', '2014-10-10', NULL, 142, NULL, 1, 0, 100, 2),
(36, 4, 7074, 500, '2014-12-05 12:02:58', '2014-12-05 12:02:58', '2014-11-10', NULL, 142, NULL, 1, 0, 100, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adeudos_paqueteplandepago`
--

CREATE TABLE IF NOT EXISTS `adeudos_paqueteplandepago` (
  `paquete_id` int(10) unsigned NOT NULL,
  `adeudos_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adeudos_tipopago`
--

CREATE TABLE IF NOT EXISTS `adeudos_tipopago` (
`id` int(10) unsigned NOT NULL,
  `tipo_pago_id` int(10) unsigned NOT NULL,
  `adeudos_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agrupaciones`
--

CREATE TABLE IF NOT EXISTS `agrupaciones` (
`id` int(10) NOT NULL,
  `nombre` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
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
`id` int(10) unsigned NOT NULL,
  `banco` varchar(50) DEFAULT NULL,
  `descripcion` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `bancos`
--

INSERT INTO `bancos` (`id`, `banco`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'BANCOMER', 'ENTIDAD BANCARIA DONDE SE REALIZAN LOS PROCESOSS DE PAGO,', '2014-12-02 13:38:25', '2014-12-02 13:38:25'),
(3, 'BANAMEX', 'ENTIDAD BANCARIA DONDE SE REALIZAN LOS PROCESOS DE PAGO AUXILIARES,', '2014-12-02 13:55:11', '2014-12-02 13:55:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `becas`
--

CREATE TABLE IF NOT EXISTS `becas` (
`id` int(10) unsigned NOT NULL,
  `periodicidades_id` int(10) unsigned NOT NULL,
  `tipo_importe_id` int(10) unsigned NOT NULL,
  `subcidios_id` int(10) unsigned NOT NULL,
  `importe` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tipobeca` int(10) unsigned DEFAULT NULL,
  `abreviatura` varchar(10) DEFAULT NULL,
  `descripcion` text NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `becas`
--

INSERT INTO `becas` (`id`, `periodicidades_id`, `tipo_importe_id`, `subcidios_id`, `importe`, `created_at`, `updated_at`, `tipobeca`, `abreviatura`, `descripcion`) VALUES
(1, 8, 1, 1, 100, '2014-11-28 03:03:19', '2014-11-28 03:03:19', NULL, 'BEA100', 'BECA EXCELENCIA ACADEMICA 100%'),
(2, 8, 1, 1, 50, '2014-11-28 03:06:27', '2014-11-28 03:06:27', NULL, 'BEA50', 'BECA EXCELENCIA ACADEMICA 50%'),
(3, 8, 1, 1, 25, '2014-11-28 03:08:44', '2014-11-28 03:56:58', NULL, 'BECA25', 'BECA EXCELENCIA ACADEMICA 25%'),
(4, 8, 1, 1, 100, '2014-11-28 03:10:23', '2014-11-28 03:10:23', NULL, 'BECA100', 'BECA DEPORTIVA 100%'),
(5, 8, 1, 1, 80, '2014-11-28 03:11:44', '2014-11-28 03:11:44', NULL, 'BECA80', 'BECA DEPORTIVA 80%'),
(6, 8, 1, 1, 70, '2014-11-28 03:12:54', '2014-11-28 03:12:54', NULL, 'BECA70', 'BECA DEPORTIVA 70%'),
(7, 8, 1, 1, 90, '2014-11-28 03:13:16', '2014-11-28 03:13:16', NULL, 'BECA90', 'BECA DEPORTIVA 90%');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `becas_alumno`
--

CREATE TABLE IF NOT EXISTS `becas_alumno` (
`id` int(10) unsigned NOT NULL,
  `periodo` int(10) NOT NULL,
  `idnivel` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `idbeca` int(10) unsigned NOT NULL,
  `id_persona` int(10) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Volcado de datos para la tabla `becas_alumno`
--

INSERT INTO `becas_alumno` (`id`, `periodo`, `idnivel`, `status`, `idbeca`, `id_persona`) VALUES
(1, 143, 1, 1, 1, 2154),
(11, 143, 1, 0, 1, 699);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conceptos`
--

CREATE TABLE IF NOT EXISTS `conceptos` (
`id` int(10) unsigned NOT NULL,
  `concepto` varchar(30) NOT NULL,
  `descripcion` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `conceptos`
--

INSERT INTO `conceptos` (`id`, `concepto`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'PREUBA', 'CONCEPTO DE PRUEBA, MUESTRA UN SUB CONCEPTO DE PRUEBA', '2014-11-23 00:17:26', '2014-11-23 00:17:26'),
(2, 'INSCRIPCIÓN ', 'CONCEPTO DE INSCRIPCIÓN, CONTIENE TODOS LOS SUB-CONCEPTOS REFERENTES A LA INSCRIPCIÓN DE ALUMNOS', '2014-11-23 00:18:00', '2014-11-23 00:18:00'),
(3, 'COLEGIATURA ', 'CONCEPTO DE COLEGIATURA, MUESTRA TODOS LOS SUB CONCEPTOS REFERENTES A LA COLEGIATURAS DE UN ALUMNO', '2014-11-23 00:18:09', '2014-11-23 00:18:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas`
--

CREATE TABLE IF NOT EXISTS `cuentas` (
`id` int(10) unsigned NOT NULL,
  `bancos_id` int(10) unsigned NOT NULL,
  `cuenta` varchar(50) DEFAULT NULL,
  `activo_cobros` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `cuentas`
--

INSERT INTO `cuentas` (`id`, `bancos_id`, `cuenta`, `activo_cobros`, `created_at`, `updated_at`) VALUES
(1, 1, '0785318', 1, '2014-12-02 13:41:54', '2014-12-02 13:41:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `descuentos`
--

CREATE TABLE IF NOT EXISTS `descuentos` (
`id` int(10) unsigned NOT NULL,
  `tipo_importe_id` int(10) unsigned NOT NULL,
  `adeudos_id` int(10) unsigned NOT NULL,
  `importe` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `descuentos`
--

INSERT INTO `descuentos` (`id`, `tipo_importe_id`, `adeudos_id`, `importe`, `created_at`, `updated_at`) VALUES
(1, 2, 4, 100, NULL, NULL),
(2, 2, 5, 50, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paqueteplandepago`
--

CREATE TABLE IF NOT EXISTS `paqueteplandepago` (
`id` int(10) unsigned NOT NULL,
  `periodo` int(10) unsigned DEFAULT NULL,
  `idnivel` int(10) unsigned DEFAULT NULL,
  `nivel` varchar(20) DEFAULT NULL,
  `recargo` float DEFAULT NULL,
  `recargo_inscripcion` float DEFAULT NULL,
  `id_plandepago` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `paqueteplandepago`
--

INSERT INTO `paqueteplandepago` (`id`, `periodo`, `idnivel`, `nivel`, `recargo`, `recargo_inscripcion`, `id_plandepago`, `created_at`, `updated_at`) VALUES
(1, 142, 23, 'LICENCIATURA', 50, 100, 1, '2014-11-29 04:09:53', '2014-11-29 04:09:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodicidades`
--

CREATE TABLE IF NOT EXISTS `periodicidades` (
`id` int(10) unsigned NOT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `descripcion` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Volcado de datos para la tabla `periodicidades`
--

INSERT INTO `periodicidades` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(7, 'UNA VES', 'SOLO SE HARA UNA VES ESTE COBRO  O DESCUENTO', NULL, NULL),
(8, 'CADA MES', 'SE HARA ESTE COBRO O DESCUENTO CADA MES', NULL, NULL),
(9, 'CADA PERIODO', 'SE HARA ESTE COBRO  O DESCUENTO CADA PERIODO', NULL, NULL),
(10, 'CADA CICLO', 'SE HARA ESTE COBRO O DESCUENTO CADA CICLO', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan_de_pago`
--

CREATE TABLE IF NOT EXISTS `plan_de_pago` (
`id` int(10) unsigned NOT NULL,
  `descripcion` text NOT NULL,
  `clave_plan` varchar(6) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `id_agrupaciones` int(10) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `plan_de_pago`
--

INSERT INTO `plan_de_pago` (`id`, `descripcion`, `clave_plan`, `created_at`, `updated_at`, `id_agrupaciones`) VALUES
(1, 'PLAN DE PAGO MENSUAL LICENCIATURA', 'PPML', '2014-11-27 05:36:00', '2014-11-27 05:36:00', 1),
(2, 'PLAN DE PAGO MENSUAL MAESTRIA', 'PPMM', '2014-11-27 05:36:47', '2014-11-27 05:36:47', 1),
(3, 'PLAN DE PAGO MENSUAL PROPEDEUTICO', 'PPMP', '2014-11-27 05:38:13', '2014-11-27 05:38:13', 1),
(4, 'PLAN DE PAGO UNICO LICENCIATURA', 'PPUL', '2014-11-27 05:56:56', '2014-11-27 05:56:56', 1),
(5, 'PLAN PAGO PRUEBA TEST 1', NULL, '2014-11-27 05:57:49', '2014-11-27 06:11:13', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `referencias`
--

CREATE TABLE IF NOT EXISTS `referencias` (
`id` int(10) unsigned NOT NULL,
  `adeudos_id` int(10) unsigned NOT NULL,
  `cuentas_id` int(10) unsigned NOT NULL,
  `referencia` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `importe` float NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Volcado de datos para la tabla `referencias`
--

INSERT INTO `referencias` (`id`, `adeudos_id`, `cuentas_id`, `referencia`, `created_at`, `updated_at`, `importe`) VALUES
(2, 4, 1, '00699143003003504260', '2014-12-03 07:25:17', '2014-12-03 07:25:17', 0),
(3, 5, 1, '00699143004003507219', '2014-12-03 07:25:17', '2014-12-03 07:25:17', 0),
(4, 6, 1, '00699143004103505206', '2014-12-03 07:25:17', '2014-12-03 07:25:17', 0),
(8, 7, 1, '00838143003003503209', '2014-12-03 11:57:09', '2014-12-03 11:57:09', 0),
(9, 8, 1, '00838143004003501297', '2014-12-03 11:57:09', '2014-12-03 11:57:09', 0),
(10, 9, 1, '00838143004103508207', '2014-12-03 11:57:10', '2014-12-03 11:57:10', 0),
(11, 5, 1, '00699143004003502251', '2014-12-04 07:40:52', '2014-12-04 07:40:52', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `referencias_pagadas`
--

CREATE TABLE IF NOT EXISTS `referencias_pagadas` (
`id` int(10) NOT NULL,
  `id_referencia` int(10) unsigned DEFAULT NULL,
  `fecha_de_pago` date NOT NULL,
  `importe` float NOT NULL,
  `estado` varchar(10) CHARACTER SET latin1 NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuesta_bancaria`
--

CREATE TABLE IF NOT EXISTS `respuesta_bancaria` (
`id` int(10) unsigned NOT NULL,
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
  `nombre_archivo` varchar(25) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `respuesta_bancaria`
--

INSERT INTO `respuesta_bancaria` (`id`, `fecha`, `monto`, `created_at`, `updated_at`, `no_transacciones`, `cobro_inmediato`, `comisiones_creadas`, `remesas`, `comisiones_remesas`, `abonado`, `nombre_archivo`) VALUES
(1, '2014-12-03', NULL, '2014-12-03 09:13:46', '2014-12-03 09:13:46', 3, 0, 0, 0, 0, 1300, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subcidios`
--

CREATE TABLE IF NOT EXISTS `subcidios` (
`id` int(10) unsigned NOT NULL,
  `nombre` varchar(15) DEFAULT NULL,
  `descripcion` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
`id` int(10) unsigned NOT NULL,
  `sub_concepto_id` int(10) unsigned NOT NULL,
  `paquete_id` int(10) unsigned NOT NULL,
  `fecha_de_vencimiento` date NOT NULL,
  `recargo` float NOT NULL,
  `tipo_recargo` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `subconcepto_paqueteplandepago`
--

INSERT INTO `subconcepto_paqueteplandepago` (`id`, `sub_concepto_id`, `paquete_id`, `fecha_de_vencimiento`, `recargo`, `tipo_recargo`, `created_at`, `updated_at`) VALUES
(4, 3, 1, '2014-09-10', 50, 2, NULL, NULL),
(5, 4, 1, '2014-10-10', 100, 2, NULL, NULL),
(6, 4, 1, '2014-11-10', 100, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subconcepto_tipopago`
--

CREATE TABLE IF NOT EXISTS `subconcepto_tipopago` (
`id` int(10) unsigned NOT NULL,
  `tipo_pago_id` int(10) unsigned NOT NULL,
  `sub_concepto_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sub_conceptos`
--

CREATE TABLE IF NOT EXISTS `sub_conceptos` (
`id` int(10) unsigned NOT NULL,
  `conceptos_id` int(10) unsigned NOT NULL,
  `tipo_adeudo` int(10) unsigned NOT NULL,
  `sub_concepto` varchar(50) NOT NULL,
  `descripcion` text,
  `importe` float NOT NULL,
  `periodo` int(10) NOT NULL,
  `nivel_id` int(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `aplica_beca` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `sub_conceptos`
--

INSERT INTO `sub_conceptos` (`id`, `conceptos_id`, `tipo_adeudo`, `sub_concepto`, `descripcion`, `importe`, `periodo`, `nivel_id`, `created_at`, `updated_at`, `aplica_beca`) VALUES
(1, 1, 1, 'INSCRIPCIÓN LICENCIATURA', 'SUB CONCEPTO INSCRIPCIÓN LICENCIATURA, CORRESPONDE AL COBRO DE LA INSCRIPCIÓN A UN CUATRIMESTRE A ALUMNOS DE LICENCIATURA (NO PROPEDEUTICO, NO MAESTRÍA).', 500, 134, 1, '2014-11-22 12:00:00', '2014-11-22 12:00:00', 1),
(2, 2, 1, 'PRUEBS INSCRIPCIÓN', 'SUB CONCEPTO PRUEBA INSCRIPCIÓN,', 1, 150, 1, '2014-11-23 09:28:05', '2014-11-23 09:28:05', 1),
(3, 2, 1, 'INSCRIPCIÓN PROPEDEUTICO', 'SUB CONCEPTO INSCRIPCIÓN PROPEDEUTICO, CORRESPONDE AL COBRO DE LA INSCRIPCIÓN A UN CUATRIMESTRE A ALUMNOS DE LICENCIATURA PROPEDEUTICO.', 500, 143, 1, '2014-11-23 10:11:47', '2014-11-23 10:11:47', 0),
(4, 2, 1, 'pruebaa', 'CONCEPTO INSCRIPCIÓN PROPEDEUTICO, CORRESPONDE AL COBRO DE LA INSCRIPCIÓN A UN CUATRIMESTRE A ALUMNOS DE LICENCIATURA PROPEDEUTICO.', 500, 143, 1, '2014-11-23 10:15:43', '2014-11-23 10:15:43', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_adeudo`
--

CREATE TABLE IF NOT EXISTS `tipo_adeudo` (
`id` int(10) unsigned NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `descripcion` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
`id` int(10) unsigned NOT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `descripcion` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `tipo_importe`
--

INSERT INTO `tipo_importe` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'PORCENTAJE', 'TIPO DE IMPORTE GENERADO MEDIANTE PORCENTAJE', NULL, NULL),
(2, 'IMPORTE', 'TIPO DE IMPORTE GENERADO MEDIANTE CANTIDAD DE IMPORTE', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_pago`
--

CREATE TABLE IF NOT EXISTS `tipo_pago` (
`id` int(10) unsigned NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `adeudos`
--
ALTER TABLE `adeudos`
 ADD PRIMARY KEY (`id`), ADD KEY `adeudos_FKIndex1` (`sub_concepto_id`), ADD KEY `id_plandepago` (`paquete_id`), ADD KEY `tipo_recargo` (`tipo_recargo`);

--
-- Indices de la tabla `adeudos_paqueteplandepago`
--
ALTER TABLE `adeudos_paqueteplandepago`
 ADD PRIMARY KEY (`paquete_id`,`adeudos_id`), ADD KEY `adeudos_id` (`adeudos_id`);

--
-- Indices de la tabla `adeudos_tipopago`
--
ALTER TABLE `adeudos_tipopago`
 ADD PRIMARY KEY (`id`), ADD KEY `adeudos_tipopago_FKIndex1` (`adeudos_id`), ADD KEY `adeudos_tipopago_FKIndex2` (`tipo_pago_id`);

--
-- Indices de la tabla `agrupaciones`
--
ALTER TABLE `agrupaciones`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bancos`
--
ALTER TABLE `bancos`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `becas`
--
ALTER TABLE `becas`
 ADD PRIMARY KEY (`id`), ADD KEY `becas_FKIndex1` (`subcidios_id`), ADD KEY `becas_FKIndex2` (`tipo_importe_id`), ADD KEY `becas_FKIndex3` (`periodicidades_id`);

--
-- Indices de la tabla `becas_alumno`
--
ALTER TABLE `becas_alumno`
 ADD PRIMARY KEY (`id`), ADD KEY `idbeca` (`idbeca`,`id_persona`);

--
-- Indices de la tabla `conceptos`
--
ALTER TABLE `conceptos`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuentas`
--
ALTER TABLE `cuentas`
 ADD PRIMARY KEY (`id`), ADD KEY `cuentas_FKIndex1` (`bancos_id`);

--
-- Indices de la tabla `descuentos`
--
ALTER TABLE `descuentos`
 ADD PRIMARY KEY (`id`), ADD KEY `descuentos_FKIndex1` (`adeudos_id`), ADD KEY `descuentos_FKIndex2` (`tipo_importe_id`);

--
-- Indices de la tabla `paqueteplandepago`
--
ALTER TABLE `paqueteplandepago`
 ADD PRIMARY KEY (`id`), ADD KEY `id_plandepago` (`id_plandepago`);

--
-- Indices de la tabla `periodicidades`
--
ALTER TABLE `periodicidades`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `plan_de_pago`
--
ALTER TABLE `plan_de_pago`
 ADD PRIMARY KEY (`id`), ADD KEY `agrupacionesF` (`id_agrupaciones`), ADD KEY `id_agrupaciones` (`id_agrupaciones`);

--
-- Indices de la tabla `referencias`
--
ALTER TABLE `referencias`
 ADD PRIMARY KEY (`id`,`adeudos_id`), ADD KEY `referencias_FKIndex1` (`adeudos_id`), ADD KEY `referencias_FKIndex2` (`cuentas_id`);

--
-- Indices de la tabla `referencias_pagadas`
--
ALTER TABLE `referencias_pagadas`
 ADD PRIMARY KEY (`id`), ADD KEY `id_referenciaFKIndex1` (`id_referencia`);

--
-- Indices de la tabla `respuesta_bancaria`
--
ALTER TABLE `respuesta_bancaria`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `subcidios`
--
ALTER TABLE `subcidios`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `subconcepto_paqueteplandepago`
--
ALTER TABLE `subconcepto_paqueteplandepago`
 ADD PRIMARY KEY (`id`), ADD KEY `subconcepto_plandepago_FKIndex2` (`sub_concepto_id`), ADD KEY `subconcepto_plandepago_FKIndex1` (`paquete_id`) USING BTREE, ADD KEY `tipo_recargo` (`tipo_recargo`);

--
-- Indices de la tabla `subconcepto_tipopago`
--
ALTER TABLE `subconcepto_tipopago`
 ADD PRIMARY KEY (`id`), ADD KEY `subconcepto_tipopago_FKIndex1` (`sub_concepto_id`), ADD KEY `subconcepto_tipopago_FKIndex2` (`tipo_pago_id`);

--
-- Indices de la tabla `sub_conceptos`
--
ALTER TABLE `sub_conceptos`
 ADD PRIMARY KEY (`id`), ADD KEY `sub_concepto_FKIndex1` (`conceptos_id`), ADD KEY `tipo_adeudo` (`tipo_adeudo`);

--
-- Indices de la tabla `tipo_adeudo`
--
ALTER TABLE `tipo_adeudo`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_importe`
--
ALTER TABLE `tipo_importe`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_pago`
--
ALTER TABLE `tipo_pago`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `adeudos`
--
ALTER TABLE `adeudos`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT de la tabla `adeudos_tipopago`
--
ALTER TABLE `adeudos_tipopago`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `agrupaciones`
--
ALTER TABLE `agrupaciones`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `bancos`
--
ALTER TABLE `bancos`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `becas`
--
ALTER TABLE `becas`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `becas_alumno`
--
ALTER TABLE `becas_alumno`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT de la tabla `conceptos`
--
ALTER TABLE `conceptos`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `cuentas`
--
ALTER TABLE `cuentas`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `descuentos`
--
ALTER TABLE `descuentos`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `paqueteplandepago`
--
ALTER TABLE `paqueteplandepago`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `periodicidades`
--
ALTER TABLE `periodicidades`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT de la tabla `plan_de_pago`
--
ALTER TABLE `plan_de_pago`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `referencias`
--
ALTER TABLE `referencias`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT de la tabla `referencias_pagadas`
--
ALTER TABLE `referencias_pagadas`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `respuesta_bancaria`
--
ALTER TABLE `respuesta_bancaria`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `subcidios`
--
ALTER TABLE `subcidios`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `subconcepto_paqueteplandepago`
--
ALTER TABLE `subconcepto_paqueteplandepago`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `subconcepto_tipopago`
--
ALTER TABLE `subconcepto_tipopago`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `sub_conceptos`
--
ALTER TABLE `sub_conceptos`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `tipo_adeudo`
--
ALTER TABLE `tipo_adeudo`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `tipo_importe`
--
ALTER TABLE `tipo_importe`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `tipo_pago`
--
ALTER TABLE `tipo_pago`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `adeudos`
--
ALTER TABLE `adeudos`
ADD CONSTRAINT `adeudos_ibfk_1` FOREIGN KEY (`sub_concepto_id`) REFERENCES `sub_conceptos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
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
ADD CONSTRAINT `becas_ibfk_2` FOREIGN KEY (`subcidios_id`) REFERENCES `subcidios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `becas_ibfk_3` FOREIGN KEY (`periodicidades_id`) REFERENCES `periodicidades` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

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
-- Filtros para la tabla `subconcepto_paqueteplandepago`
--
ALTER TABLE `subconcepto_paqueteplandepago`
ADD CONSTRAINT `subconcepto_paqueteplandepago_ibfk_1` FOREIGN KEY (`sub_concepto_id`) REFERENCES `sub_conceptos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `subconcepto_paqueteplandepago_ibfk_2` FOREIGN KEY (`paquete_id`) REFERENCES `paqueteplandepago` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
ADD CONSTRAINT `subconcepto_paqueteplandepago_ibfk_3` FOREIGN KEY (`tipo_recargo`) REFERENCES `tipo_importe` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `subconcepto_tipopago`
--
ALTER TABLE `subconcepto_tipopago`
ADD CONSTRAINT `subconcepto_tipopago_ibfk_1` FOREIGN KEY (`sub_concepto_id`) REFERENCES `sub_conceptos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `subconcepto_tipopago_ibfk_2` FOREIGN KEY (`tipo_pago_id`) REFERENCES `tipo_pago` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `sub_conceptos`
--
ALTER TABLE `sub_conceptos`
ADD CONSTRAINT `sub_conceptos_ibfk_1` FOREIGN KEY (`conceptos_id`) REFERENCES `conceptos` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
ADD CONSTRAINT `sub_conceptos_ibfk_2` FOREIGN KEY (`tipo_adeudo`) REFERENCES `tipo_adeudo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
