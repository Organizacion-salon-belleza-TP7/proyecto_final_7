-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 04-08-2025 a las 22:17:12
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
  `id_servicio_combos_select` int(11) NOT NULL,
  `fecha_cita` datetime DEFAULT NULL,
  `activo` tinyint(1) NOT NULL,
  `hash_identificacion` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Estructura de tabla para la tabla `historial_logeos`
--

CREATE TABLE `historial_logeos` (
  `id_historial_logueos` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_logueo` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'tinta para cabello morada', 50, '2026-10-26', 600, 700, 'tinta-morada.jpg', 1);

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
  `imagen_lugar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `decremento` int(11) DEFAULT NULL
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
-- Estructura de tabla para la tabla `productos_seleccionado`
--

CREATE TABLE `productos_seleccionado` (
  `id_producto_seleccionado` int(11) NOT NULL,
  `id_inventario` int(11) DEFAULT NULL,
  `cantidad_seleccionada` int(11) DEFAULT NULL,
  `hash_identificacion` varchar(10000) DEFAULT NULL
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
(15, 19, 1, 1);

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
  `id_tipo_servicio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id_servicios`, `nombre`, `descripcion`, `duracion`, `id_tiempo_servicio`, `precio`, `activo`, `id_tipo_servicio`) VALUES
(1, 'tintado', 'se le cambia el color de pelo', 34, 1, 700, 0, 1),
(14, 'ttetet', 'rerere', 33, 1, 33, 1, 1),
(16, 'deded', 'fefe', 2, 2, 2, 1, 1),
(18, 'fufi', 'fufito', 1, 1, 1, 1, 2),
(19, 'fefe', 'fefesito', 12, 2, 1500, 0, 3),
(20, 'bacalao', 'bacalaito', 1, 2, 222, 0, 4),
(21, 'fefe', 'fefesin', 1, 2, 11, 1, 5),
(22, 'gggge', 'gege', 5, 2, 22, 1, 2),
(23, 'feg', 'gegr', 12, 2, 222, 1, 4);

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
(11, 1, 25);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(45) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `dni` int(255) DEFAULT NULL,
  `id_tipo_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `contrasena`, `dni`, `id_tipo_usuario`) VALUES
(1, 'julio', '787878', 46610283, 1),
(2, 'pepe', '1234', NULL, 2);

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
-- Índices para tablas volcadas
--

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
-- Indices de la tabla `productos_seleccionado`
--
ALTER TABLE `productos_seleccionado`
  ADD PRIMARY KEY (`id_producto_seleccionado`);

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
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT de la tabla `historial_logeos`
--
ALTER TABLE `historial_logeos`
  MODIFY `id_historial_logueos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id_inventario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `lista_espera`
--
ALTER TABLE `lista_espera`
  MODIFY `id_lista_espera` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lugares`
--
ALTER TABLE `lugares`
  MODIFY `id_lugar` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lugares_trabajo`
--
ALTER TABLE `lugares_trabajo`
  MODIFY `id_lugares_trabajo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `metodos_pagos`
--
ALTER TABLE `metodos_pagos`
  MODIFY `id_metodo_pago` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT de la tabla `productos_seleccionado`
--
ALTER TABLE `productos_seleccionado`
  MODIFY `id_producto_seleccionado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `product_usados`
--
ALTER TABLE `product_usados`
  MODIFY `id_products_usados` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id_servicios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

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
  MODIFY `id_trabajadores_servicios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios_personas`
--
ALTER TABLE `usuarios_personas`
  MODIFY `id_usuarios_personas` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
