-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 02-09-2025 a las 13:25:53
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
-- Base de datos: `trabajo_final_7`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades_sistema`
--

CREATE TABLE `actividades_sistema` (
  `id_actividades_sistema` int(11) NOT NULL,
  `actividad` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE `caja` (
  `id_caja` int(11) NOT NULL,
  `fecha_venta` datetime DEFAULT NULL,
  `id_cita` int(11) DEFAULT NULL,
  `monto_total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja_product`
--

CREATE TABLE `caja_product` (
  `id_caja_product` int(11) NOT NULL,
  `fecha_venta` datetime DEFAULT NULL,
  `monto_total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id_cita` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `fecha_cita` datetime DEFAULT NULL,
  `activo` tinyint(1) NOT NULL,
  `hash_identificacion` varchar(255) NOT NULL,
  `id_lugar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id_cita`, `id_cliente`, `fecha_cita`, `activo`, `hash_identificacion`, `id_lugar`) VALUES
(1, 1, '2025-08-28 20:03:00', 1, 'aec160228c52d5d2305400fda8441613', 1),
(2, 1, '2005-09-23 12:00:00', 1, '38ca0b621e3a053fce55aa1da71faaeb', 1),
(3, 1, '2025-09-21 20:30:00', 1, '73b3c3335c07a001696c11c8f1124790', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(35) DEFAULT NULL,
  `apellido` varchar(45) NOT NULL,
  `alergias` varchar(35) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `dni` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre`, `apellido`, `alergias`, `fecha_nacimiento`, `dni`) VALUES
(1, 'pepe', 'alvarez', 'tengo alergias al limon', '2025-08-01', 545454545);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `combos`
--

CREATE TABLE `combos` (
  `id_combos` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `descripcion_combo` varchar(45) NOT NULL,
  `precio` float NOT NULL,
  `imagen` varchar(45) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `fecha_creacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `combos`
--

INSERT INTO `combos` (`id_combos`, `nombre`, `descripcion_combo`, `precio`, `imagen`, `activo`, `fecha_creacion`) VALUES
(1, 'prueba', 'pruebuna', 444, 'WhatsApp Image 2025-07-28 at 11.42.06.jpeg', 1, '2025-04-22 00:00:00'),
(2, 'jujua', 'jujuia', 78, 'wallhaven-po8yvj.jpg', 0, '2025-07-29 18:20:13'),
(3, 'guagua', 'guigui', 45, 'Screenshot_20250725_182130.png', 0, '2025-07-29 18:21:14'),
(4, 'hahaha', 'hahihi', 5000, 'Screenshot_20250722_154727.png', 0, '2025-07-29 19:24:30'),
(5, 'activo', 'activo', 4500, 'Screenshot_20250722_161634.png', 0, '2025-07-30 08:41:56'),
(6, 'prueba', 'pruebuna', 5678, 'Screenshot_20250722_161715.png', 1, '2025-07-30 09:15:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `combo_servicios`
--

CREATE TABLE `combo_servicios` (
  `id_combo_servicio` int(11) NOT NULL,
  `id_combos` int(11) DEFAULT NULL,
  `id_servicios` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `combo_servicios`
--

INSERT INTO `combo_servicios` (`id_combo_servicio`, `id_combos`, `id_servicios`) VALUES
(1, 1, 1),
(2, 2, 14),
(3, 3, 18),
(4, 3, 21),
(5, 4, 14),
(6, 4, 23),
(7, 5, 14),
(8, 6, 16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactos`
--

CREATE TABLE `contactos` (
  `id_contacto` int(11) NOT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `id_trabajador` int(11) DEFAULT NULL,
  `codigo_area` int(11) DEFAULT NULL,
  `numero_telefonico` int(11) DEFAULT NULL,
  `correo_electronico` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_horarios`
--

CREATE TABLE `control_horarios` (
  `id_control_horarios` int(11) NOT NULL,
  `id_trabajador_servicio` int(11) DEFAULT NULL,
  `id_cita` int(11) DEFAULT NULL,
  `comisiones` int(11) NOT NULL,
  `activo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_caja`
--

CREATE TABLE `detalle_caja` (
  `id_detalle_caja` int(11) NOT NULL,
  `id_multiple_pago` int(11) DEFAULT NULL,
  `id_puntos_descuento` int(11) DEFAULT NULL,
  `precio_unitario` int(11) DEFAULT NULL,
  `subtotal` int(11) DEFAULT NULL,
  `id_caja` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_caja_product`
--

CREATE TABLE `detalle_caja_product` (
  `id_detalle_caja_product` int(11) NOT NULL,
  `id_caja_product` int(11) DEFAULT NULL,
  `id_multiple_pago` int(11) DEFAULT NULL,
  `id_puntos_descuentos` int(11) DEFAULT NULL,
  `hash_identificacion` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_cita`
--

CREATE TABLE `detalle_cita` (
  `id_detalle` int(11) NOT NULL,
  `id_cita` int(11) DEFAULT NULL,
  `id_servicios` int(11) DEFAULT NULL,
  `id_combos` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_cita`
--

INSERT INTO `detalle_cita` (`id_detalle`, `id_cita`, `id_servicios`, `id_combos`) VALUES
(1, 1, 14, NULL),
(2, 1, NULL, 1),
(3, 2, 14, NULL),
(4, 2, NULL, 1),
(5, 3, 14, NULL),
(6, 3, NULL, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_logeos`
--

CREATE TABLE `historial_logeos` (
  `id_historial_logueos` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_logueo` datetime DEFAULT NULL,
  `fecha_logout` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_logeos`
--

INSERT INTO `historial_logeos` (`id_historial_logueos`, `id_usuario`, `fecha_logueo`, `fecha_logout`) VALUES
(1, 1, '2025-08-09 00:00:00', '2025-08-22 10:31:06'),
(2, 1, '2025-08-08 10:03:11', '2025-08-22 10:31:06'),
(3, 1, '2025-08-10 18:39:15', '2025-08-22 10:31:06'),
(4, 3, '2025-08-18 17:23:20', NULL),
(5, 1, '2025-08-18 17:23:42', '2025-08-22 10:31:06'),
(6, 1, '2025-08-18 17:25:15', '2025-08-22 10:31:06'),
(7, 1, '2025-08-20 09:56:31', '2025-08-22 10:31:06'),
(8, 1, '2025-08-20 09:57:09', '2025-08-22 10:31:06'),
(9, 1, '2025-08-22 10:29:14', '2025-08-22 10:31:06'),
(10, 1, '2025-08-22 10:31:23', NULL),
(11, 1, '2025-08-22 10:36:45', NULL),
(12, 1, '2025-08-29 08:56:07', NULL),
(13, 1, '2025-08-31 18:20:48', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id_inventario` int(11) NOT NULL,
  `nombre_producto` varchar(35) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `vencimiento` date DEFAULT NULL,
  `precio_producto` float DEFAULT NULL,
  `precio_venta` float NOT NULL,
  `imagen_producto` varchar(45) NOT NULL,
  `id_proveedor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id_inventario`, `nombre_producto`, `stock`, `vencimiento`, `precio_producto`, `precio_venta`, `imagen_producto`, `id_proveedor`) VALUES
(1, 'tinta para cabello morada', 50, '2026-10-26', 600, 700, 'tinta-morada.jpg', 1),
(2, 'jeje', 45, '2025-08-28', 345, 567, 'uploads/pink-rose-8397370_1280.jpg', 1),
(3, 'pepe_inventario', 34, '2025-08-26', 34, 23, 'uploads/rosa.jpg', 1),
(4, 'prueba_actual', 22222, '2025-08-13', 4566, 565656, 'wallhaven-po8yvj.jpg', 1),
(5, 'socialista', 456, '2025-08-04', 456, 789, '268777-800-auto.webp', 1),
(6, 'pepe_inventario', 4565, '2025-08-04', 5454, 767676, 'Captura desde 2025-08-12 16-47-42.png', 1),
(7, 'solo leveling', 34545, '2025-08-13', 54545, 3332, 'wallhaven-po8yvj.jpg', 1),
(8, 'socio', 345, '2025-08-20', 2345, 4556, 'Captura desde 2025-08-13 06-48-14.png', 1),
(9, 'pepesio', 565, '2025-08-05', 4546, 6778, 'Captura desde 2025-08-20 16-00-45.png', 1),
(10, 'cuak', 45, '2025-07-01', 5467, 7890, 'Captura desde 2025-08-20 15-59-39.png', 1),
(11, 'pruebita', 34, '2025-08-04', 2324, 4356, 'Captura desde 2025-08-12 16-47-42.png', 1),
(12, 'pruebitaaaaa', 3456, '2025-07-14', 4356, 6790, 'Captura desde 2025-08-20 15-59-39.png', 1),
(13, 'fefefe', 66532, '2025-06-09', 13456, 43566, 'Captura desde 2025-08-20 16-00-45.png', 1),
(14, 'epeppe', 34, '2025-05-05', 34355, 43432, 'Captura desde 2025-08-07 00-42-34.png', 1),
(15, 'jejejje', 646464, '2025-06-02', 43432, 42424, 'Captura desde 2025-08-20 16-02-44.png', 1),
(16, 'grgrgrgr', 43434, '2025-06-11', 54546, 42422, 'Captura desde 2025-08-20 16-02-44.png', 1),
(17, 'fefefef', 4324, '2025-04-14', 4242, 4132, 'Captura desde 2025-08-20 16-02-44.png', 1),
(18, 'muajaja', 31312, '2025-03-10', 3232, 5466, 'Captura desde 2025-08-20 16-02-44.png', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lista_espera`
--

CREATE TABLE `lista_espera` (
  `id_lista_espera` int(11) NOT NULL,
  `id_caja` int(11) DEFAULT NULL,
  `id_usuario_persona` int(11) DEFAULT NULL,
  `tiempo_estimado` time DEFAULT NULL,
  `confirmacion` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lugares`
--

CREATE TABLE `lugares` (
  `id_lugar` int(11) NOT NULL,
  `nombre_lugar` varchar(45) DEFAULT NULL,
  `cooordenadas` varchar(10000) DEFAULT NULL,
  `imagen_lugar` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lugares`
--

INSERT INTO `lugares` (`id_lugar`, `nombre_lugar`, `cooordenadas`, `imagen_lugar`, `activo`) VALUES
(1, 'prueba', '41.40338, 2.17403', 'prueba.jpg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lugares_trabajo`
--

CREATE TABLE `lugares_trabajo` (
  `id_lugares_trabajo` int(11) NOT NULL,
  `id_cita` int(11) DEFAULT NULL,
  `id_lugar` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pagos`
--

CREATE TABLE `metodos_pagos` (
  `id_metodo_pago` int(11) NOT NULL,
  `metodo_pago` varchar(45) DEFAULT NULL,
  `incremento` int(11) DEFAULT NULL,
  `decremento` int(11) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `metodos_pagos`
--

INSERT INTO `metodos_pagos` (`id_metodo_pago`, `metodo_pago`, `incremento`, `decremento`, `activo`) VALUES
(1, 'prueba', NULL, 10, 1),
(2, 'tarjetaaaa', 34, NULL, 0),
(3, 'debito', 45, NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_sistema`
--

CREATE TABLE `movimientos_sistema` (
  `id_movimientos_sistema` int(11) NOT NULL,
  `tiempo` datetime DEFAULT NULL,
  ` id_actividades_sistema` int(11) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `multiple_pago`
--

CREATE TABLE `multiple_pago` (
  `id_multiple_pago` int(11) NOT NULL,
  `id_metodo_pago` int(11) DEFAULT NULL,
  `cantidad_pagar` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nivel_profesionalismo`
--

CREATE TABLE `nivel_profesionalismo` (
  `id_nivel_profesionalismo` int(11) NOT NULL,
  `nivel_profesionalismo` varchar(45) DEFAULT NULL,
  `intereses` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `nivel_profesionalismo`
--

INSERT INTO `nivel_profesionalismo` (`id_nivel_profesionalismo`, `nivel_profesionalismo`, `intereses`) VALUES
(1, 'Profesional', 78);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes_compra`
--

CREATE TABLE `ordenes_compra` (
  `id_ordenes_compra` int(11) NOT NULL,
  `producto` varchar(45) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` int(11) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_comprados`
--

CREATE TABLE `productos_comprados` (
  `id_productos_comprados` int(11) NOT NULL,
  `id_orden_compra` int(11) DEFAULT NULL,
  `producto_confirmado` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_nuevos`
--

CREATE TABLE `productos_nuevos` (
  `id_productos_nuevos` int(11) NOT NULL,
  `id_productos_comprados` int(11) DEFAULT NULL,
  `id_inventario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_usados`
--

CREATE TABLE `product_usados` (
  `id_products_usados` int(11) NOT NULL,
  `id_servicios` int(11) DEFAULT NULL,
  `id_inventario` int(11) DEFAULT NULL,
  `cantidad_usada` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `product_usados`
--

INSERT INTO `product_usados` (`id_products_usados`, `id_servicios`, `id_inventario`, `cantidad_usada`) VALUES
(1, 1, 1, 1),
(2, 13, 1, 4),
(3, 14, 1, 1),
(4, 15, 1, 5),
(5, 16, 1, 2),
(6, 0, 1, 1),
(7, 0, 1, 2),
(8, NULL, 1, 2),
(9, NULL, 1, 2),
(10, 24, 1, 2),
(11, 25, 1, 2),
(12, 23, 1, 1),
(13, 22, 1, 1),
(14, 21, 1, 2),
(15, 19, 1, 1),
(16, 28, 1, 4),
(17, 26, 1, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id_proveedor` int(11) NOT NULL,
  `nombre_proveedor` varchar(35) DEFAULT NULL,
  `apellido_proveedor` varchar(45) NOT NULL,
  `dni` int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id_proveedor`, `nombre_proveedor`, `apellido_proveedor`, `dni`) VALUES
(1, 'tralero', 'tralala', 567891);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puntos_descuentos`
--

CREATE TABLE `puntos_descuentos` (
  `id_puntos_descuento` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `puntos_acumulados` int(11) DEFAULT NULL,
  `descuento` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reembolsos`
--

CREATE TABLE `reembolsos` (
  `id_reembolso` int(11) NOT NULL,
  `id_caja` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_caja_product` int(11) DEFAULT NULL,
  `cantidad_reembolsada` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id_servicios` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `duracion` int(11) DEFAULT NULL,
  `id_tiempo_servicio` int(11) NOT NULL,
  `precio` float DEFAULT NULL,
  `activo` tinyint(1) NOT NULL,
  `id_tipo_servicio` int(11) NOT NULL,
  `imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id_servicios`, `nombre`, `descripcion`, `duracion`, `id_tiempo_servicio`, `precio`, `activo`, `id_tipo_servicio`, `imagen`) VALUES
(1, 'tintado', 'se le cambia el color de pelo', 34, 1, 700, 0, 1, ''),
(14, 'ttetet', 'rerere', 33, 1, 33, 1, 1, ''),
(16, 'deded', 'fefe', 2, 2, 2, 1, 1, ''),
(18, 'fufi', 'fufito', 1, 1, 1, 1, 2, ''),
(19, 'fefe', 'fefesito', 12, 2, 1500, 0, 3, ''),
(20, 'bacalao', 'bacalaito', 1, 2, 222, 0, 4, ''),
(21, 'fefe', 'fefesin', 1, 2, 11, 1, 5, ''),
(22, 'gggge', 'gege', 5, 2, 22, 1, 2, ''),
(23, 'feg', 'gegr', 12, 2, 222, 1, 4, ''),
(26, 'prueba_actual', 'hehhehehe', 2, 2, 344, 1, 1, ''),
(27, 'prueba_actual', '456', 4, 1, 356, 0, 2, ''),
(28, 'prueba_actual', '456', 4, 1, 356, 0, 2, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios_combos_select`
--

CREATE TABLE `servicios_combos_select` (
  `id_servicio_combos_select` int(11) NOT NULL,
  `id_servicio` int(11) DEFAULT NULL,
  `id_combo_servicio` int(11) DEFAULT NULL,
  `hash_identificacion` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiempo_servicio`
--

CREATE TABLE `tiempo_servicio` (
  `id_tiempo_servicio` int(11) NOT NULL,
  `tiempo_servicio` varchar(35) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tiempo_servicio`
--

INSERT INTO `tiempo_servicio` (`id_tiempo_servicio`, `tiempo_servicio`) VALUES
(1, 'horas'),
(2, 'minutos'),
(3, 'segundos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_servicio`
--

CREATE TABLE `tipo_servicio` (
  `id_tipo_servicio` int(11) NOT NULL,
  `tipo_servicio` varchar(45) DEFAULT NULL,
  `intereses` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_servicio`
--

INSERT INTO `tipo_servicio` (`id_tipo_servicio`, `tipo_servicio`, `intereses`) VALUES
(1, 'basico', 5),
(2, 'Media', 10),
(3, 'Alta', 40),
(4, 'Vip', 70),
(5, 'Economico', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_trabajador`
--

CREATE TABLE `tipo_trabajador` (
  `id_tipo_trabajador` int(11) NOT NULL,
  `tipo_trabajador` varchar(35) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_trabajador`
--

INSERT INTO `tipo_trabajador` (`id_tipo_trabajador`, `tipo_trabajador`) VALUES
(1, 'Jefe');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_usuario`
--

CREATE TABLE `tipo_usuario` (
  `id_tipo_usuario` int(11) NOT NULL,
  `tipo_usuario` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_usuario`
--

INSERT INTO `tipo_usuario` (`id_tipo_usuario`, `tipo_usuario`) VALUES
(1, 'administrador'),
(2, 'cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajadores`
--

CREATE TABLE `trabajadores` (
  `id_trabajador` int(11) NOT NULL,
  `nombre_trabajador` varchar(35) DEFAULT NULL,
  `apellido_trabajador` varchar(45) NOT NULL,
  `dni` int(100) DEFAULT NULL,
  `id_tipo_trabajador` int(11) DEFAULT NULL,
  `id_nivel_profesionalismo` int(11) NOT NULL,
  `activo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `trabajadores`
--

INSERT INTO `trabajadores` (`id_trabajador`, `nombre_trabajador`, `apellido_trabajador`, `dni`, `id_tipo_trabajador`, `id_nivel_profesionalismo`, `activo`) VALUES
(1, 'pepesio', 'pepesito', 46610283, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajadores_servicios`
--

CREATE TABLE `trabajadores_servicios` (
  `id_trabajadores_servicios` int(11) NOT NULL,
  `id_trabajador` int(11) DEFAULT NULL,
  `id_servicio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `trabajadores_servicios`
--

INSERT INTO `trabajadores_servicios` (`id_trabajadores_servicios`, `id_trabajador`, `id_servicio`) VALUES
(1, 1, 1),
(2, 1, 14),
(3, 1, 16),
(4, 1, 18),
(5, 1, 19),
(6, 1, 20),
(7, 1, 21),
(8, 1, 22),
(9, 1, 23),
(10, 1, 24),
(11, 1, 25),
(12, 1, 26),
(13, 1, 27),
(14, 1, 28);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(45) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `id_tipo_usuario` int(11) DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `contrasena`, `id_tipo_usuario`) VALUES
(1, 'julio', '787878', 1),
(2, 'pepe', '1234', 2),
(3, 'julio', 'julio1234', 2),
(4, 'pepe', 'pepe1234', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_personas`
--

CREATE TABLE `usuarios_personas` (
  `id_usuarios_personas` int(11) NOT NULL,
  `id_trabajador` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_personas`
--

INSERT INTO `usuarios_personas` (`id_usuarios_personas`, `id_trabajador`, `id_cliente`, `id_usuario`) VALUES
(1, NULL, 1, 4);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades_sistema`
--
ALTER TABLE `actividades_sistema`
  ADD PRIMARY KEY (`id_actividades_sistema`);

--
-- Indices de la tabla `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`id_caja`);

--
-- Indices de la tabla `caja_product`
--
ALTER TABLE `caja_product`
  ADD PRIMARY KEY (`id_caja_product`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id_cita`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `combos`
--
ALTER TABLE `combos`
  ADD PRIMARY KEY (`id_combos`);

--
-- Indices de la tabla `combo_servicios`
--
ALTER TABLE `combo_servicios`
  ADD PRIMARY KEY (`id_combo_servicio`);

--
-- Indices de la tabla `contactos`
--
ALTER TABLE `contactos`
  ADD PRIMARY KEY (`id_contacto`);

--
-- Indices de la tabla `control_horarios`
--
ALTER TABLE `control_horarios`
  ADD PRIMARY KEY (`id_control_horarios`);

--
-- Indices de la tabla `detalle_caja`
--
ALTER TABLE `detalle_caja`
  ADD PRIMARY KEY (`id_detalle_caja`);

--
-- Indices de la tabla `detalle_caja_product`
--
ALTER TABLE `detalle_caja_product`
  ADD PRIMARY KEY (`id_detalle_caja_product`);

--
-- Indices de la tabla `detalle_cita`
--
ALTER TABLE `detalle_cita`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_cita` (`id_cita`);

--
-- Indices de la tabla `historial_logeos`
--
ALTER TABLE `historial_logeos`
  ADD PRIMARY KEY (`id_historial_logueos`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id_inventario`);

--
-- Indices de la tabla `lista_espera`
--
ALTER TABLE `lista_espera`
  ADD PRIMARY KEY (`id_lista_espera`);

--
-- Indices de la tabla `lugares`
--
ALTER TABLE `lugares`
  ADD PRIMARY KEY (`id_lugar`);

--
-- Indices de la tabla `lugares_trabajo`
--
ALTER TABLE `lugares_trabajo`
  ADD PRIMARY KEY (`id_lugares_trabajo`);

--
-- Indices de la tabla `metodos_pagos`
--
ALTER TABLE `metodos_pagos`
  ADD PRIMARY KEY (`id_metodo_pago`);

--
-- Indices de la tabla `movimientos_sistema`
--
ALTER TABLE `movimientos_sistema`
  ADD PRIMARY KEY (`id_movimientos_sistema`);

--
-- Indices de la tabla `multiple_pago`
--
ALTER TABLE `multiple_pago`
  ADD PRIMARY KEY (`id_multiple_pago`);

--
-- Indices de la tabla `nivel_profesionalismo`
--
ALTER TABLE `nivel_profesionalismo`
  ADD PRIMARY KEY (`id_nivel_profesionalismo`);

--
-- Indices de la tabla `ordenes_compra`
--
ALTER TABLE `ordenes_compra`
  ADD PRIMARY KEY (`id_ordenes_compra`);

--
-- Indices de la tabla `productos_comprados`
--
ALTER TABLE `productos_comprados`
  ADD PRIMARY KEY (`id_productos_comprados`);

--
-- Indices de la tabla `productos_nuevos`
--
ALTER TABLE `productos_nuevos`
  ADD PRIMARY KEY (`id_productos_nuevos`);

--
-- Indices de la tabla `product_usados`
--
ALTER TABLE `product_usados`
  ADD PRIMARY KEY (`id_products_usados`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `puntos_descuentos`
--
ALTER TABLE `puntos_descuentos`
  ADD PRIMARY KEY (`id_puntos_descuento`);

--
-- Indices de la tabla `reembolsos`
--
ALTER TABLE `reembolsos`
  ADD PRIMARY KEY (`id_reembolso`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id_servicios`);

--
-- Indices de la tabla `servicios_combos_select`
--
ALTER TABLE `servicios_combos_select`
  ADD PRIMARY KEY (`id_servicio_combos_select`);

--
-- Indices de la tabla `tiempo_servicio`
--
ALTER TABLE `tiempo_servicio`
  ADD PRIMARY KEY (`id_tiempo_servicio`);

--
-- Indices de la tabla `tipo_servicio`
--
ALTER TABLE `tipo_servicio`
  ADD PRIMARY KEY (`id_tipo_servicio`);

--
-- Indices de la tabla `tipo_trabajador`
--
ALTER TABLE `tipo_trabajador`
  ADD PRIMARY KEY (`id_tipo_trabajador`);

--
-- Indices de la tabla `tipo_usuario`
--
ALTER TABLE `tipo_usuario`
  ADD PRIMARY KEY (`id_tipo_usuario`);

--
-- Indices de la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  ADD PRIMARY KEY (`id_trabajador`);

--
-- Indices de la tabla `trabajadores_servicios`
--
ALTER TABLE `trabajadores_servicios`
  ADD PRIMARY KEY (`id_trabajadores_servicios`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `usuarios_personas`
--
ALTER TABLE `usuarios_personas`
  ADD PRIMARY KEY (`id_usuarios_personas`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades_sistema`
--
ALTER TABLE `actividades_sistema`
  MODIFY `id_actividades_sistema` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `caja`
--
ALTER TABLE `caja`
  MODIFY `id_caja` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `caja_product`
--
ALTER TABLE `caja_product`
  MODIFY `id_caja_product` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `combos`
--
ALTER TABLE `combos`
  MODIFY `id_combos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `combo_servicios`
--
ALTER TABLE `combo_servicios`
  MODIFY `id_combo_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `contactos`
--
ALTER TABLE `contactos`
  MODIFY `id_contacto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `control_horarios`
--
ALTER TABLE `control_horarios`
  MODIFY `id_control_horarios` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_caja`
--
ALTER TABLE `detalle_caja`
  MODIFY `id_detalle_caja` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_caja_product`
--
ALTER TABLE `detalle_caja_product`
  MODIFY `id_detalle_caja_product` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_cita`
--
ALTER TABLE `detalle_cita`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `historial_logeos`
--
ALTER TABLE `historial_logeos`
  MODIFY `id_historial_logueos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id_inventario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `lista_espera`
--
ALTER TABLE `lista_espera`
  MODIFY `id_lista_espera` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lugares`
--
ALTER TABLE `lugares`
  MODIFY `id_lugar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `lugares_trabajo`
--
ALTER TABLE `lugares_trabajo`
  MODIFY `id_lugares_trabajo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `metodos_pagos`
--
ALTER TABLE `metodos_pagos`
  MODIFY `id_metodo_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `movimientos_sistema`
--
ALTER TABLE `movimientos_sistema`
  MODIFY `id_movimientos_sistema` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `multiple_pago`
--
ALTER TABLE `multiple_pago`
  MODIFY `id_multiple_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `nivel_profesionalismo`
--
ALTER TABLE `nivel_profesionalismo`
  MODIFY `id_nivel_profesionalismo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ordenes_compra`
--
ALTER TABLE `ordenes_compra`
  MODIFY `id_ordenes_compra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos_comprados`
--
ALTER TABLE `productos_comprados`
  MODIFY `id_productos_comprados` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos_nuevos`
--
ALTER TABLE `productos_nuevos`
  MODIFY `id_productos_nuevos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `product_usados`
--
ALTER TABLE `product_usados`
  MODIFY `id_products_usados` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `puntos_descuentos`
--
ALTER TABLE `puntos_descuentos`
  MODIFY `id_puntos_descuento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reembolsos`
--
ALTER TABLE `reembolsos`
  MODIFY `id_reembolso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id_servicios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `servicios_combos_select`
--
ALTER TABLE `servicios_combos_select`
  MODIFY `id_servicio_combos_select` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tiempo_servicio`
--
ALTER TABLE `tiempo_servicio`
  MODIFY `id_tiempo_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipo_servicio`
--
ALTER TABLE `tipo_servicio`
  MODIFY `id_tipo_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipo_trabajador`
--
ALTER TABLE `tipo_trabajador`
  MODIFY `id_tipo_trabajador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipo_usuario`
--
ALTER TABLE `tipo_usuario`
  MODIFY `id_tipo_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  MODIFY `id_trabajador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `trabajadores_servicios`
--
ALTER TABLE `trabajadores_servicios`
  MODIFY `id_trabajadores_servicios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios_personas`
--
ALTER TABLE `usuarios_personas`
  MODIFY `id_usuarios_personas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
