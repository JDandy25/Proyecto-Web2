-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-07-2025 a las 18:09:17
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
-- Base de datos: `proyecto-web2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(128) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `fechaCreado` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre`, `descripcion`, `estado`, `fechaCreado`) VALUES
(1, 'asas', 'asdasdadad', 1, '2025-06-29 03:13:15'),
(2, 'rererer', 'ccscscscsc', 1, '2025-07-01 01:10:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(128) NOT NULL,
  `apellidos` varchar(128) NOT NULL,
  `dni` char(8) NOT NULL,
  `correo` varchar(200) NOT NULL,
  `telefono` char(9) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `fechaCreado` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `nombre`, `apellidos`, `dni`, `correo`, `telefono`, `direccion`, `estado`, `fechaCreado`) VALUES
(1, 'aaaaaa', 'asd', 'asd', 'asd', 'asd', 'asd', 1, '2025-06-29 01:36:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `id_compra` int(11) NOT NULL,
  `tipoComprobante` varchar(30) NOT NULL,
  `serie_Comprobante` varchar(30) NOT NULL,
  `numComprobante` varchar(30) NOT NULL,
  `fechaHora` datetime NOT NULL,
  `impuesto` decimal(10,2) NOT NULL,
  `total_compra` decimal(10,2) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallecompra`
--

CREATE TABLE `detallecompra` (
  `id_detalleCompra` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalleventa`
--

CREATE TABLE `detalleventa` (
  `id_detalleVenta` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `precioVenta` decimal(10,2) NOT NULL,
  `descuento` decimal(10,2) NOT NULL,
  `id_producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `id_empleado` int(11) NOT NULL,
  `nombre` varchar(128) NOT NULL,
  `apePater` varchar(64) NOT NULL,
  `apeMater` varchar(64) NOT NULL,
  `dni` char(8) NOT NULL,
  `direccion` varchar(250) NOT NULL,
  `telefono` char(9) NOT NULL,
  `correo` varchar(250) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_turno` int(11) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `fechaCreado` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`id_empleado`, `nombre`, `apePater`, `apeMater`, `dni`, `direccion`, `telefono`, `correo`, `id_usuario`, `id_turno`, `estado`, `fechaCreado`) VALUES
(4, 'dasdsada', 'sdadsada', 'sdadadasdsd', '78541236', 'cascacacasca', '965231487', 'ccacascascascacs', 4, 4, 1, '2025-06-28 00:49:59'),
(5, 'xasxaxax', 'xaxaxaxxxas', 'axaaasxasxasx', '12312312', 'cacaccaa', '123123123', 'asccacsacs', 5, 4, 1, '2025-06-28 00:49:59'),
(11, 'asd', 'asd', 'asd', '123', 'dsad', '123', 'qwqdxas', 28, 4, 1, '2025-06-28 14:08:01'),
(12, '11233123', '123123', '1', '1', '1', '1', '1', 30, 4, 1, '2025-06-28 14:12:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `codigoProd` varchar(50) NOT NULL,
  `stock` decimal(11,2) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `fechaCreado` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_producto`, `nombre`, `descripcion`, `codigoProd`, `stock`, `imagen`, `id_categoria`, `estado`, `fechaCreado`) VALUES
(9, 'sdada', 'aadadadad', 'aaaaa', 121.00, '????\0JFIF\0\0`\0`\0\0??\0;CREATOR: gd-jpeg v1.0 (using IJG JPEG v80), quality = 85\n??\0C\0	\Z!\Z\"$\"$??\0C??\08?\"\0??\0\0\0\0\0\0', 1, 3, '2025-06-30 21:14:48'),
(16, 'sacasc', 'ascascca', 'SAC001', 2121.00, '/Proyecto-Web2/imgProductos/prod_6864e6c4b6ed23.09401937.jpeg', 1, 1, '2025-07-02 02:59:00'),
(17, 'dqdqwdqdw', 'dqwdqwdqd', 'DQD001', 211212.00, '/Proyecto-Web2/imgProductos/prod_6864e78edafaa7.67279135.webp', 2, 1, '2025-07-02 03:02:22'),
(18, 'xasxas', 'xaxsxa', 'XAS001', 2122.00, '/Proyecto-Web2/imgProductos/prod_6864e842eb5548.50041988.jpg', 1, 1, '2025-07-02 03:05:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id_proveedor` int(11) NOT NULL,
  `nombres` varchar(128) NOT NULL,
  `apellidos` varchar(128) NOT NULL,
  `RUC` char(11) NOT NULL,
  `telefono` char(9) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `fechaCreado` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id_proveedor`, `nombres`, `apellidos`, `RUC`, `telefono`, `correo`, `estado`, `fechaCreado`) VALUES
(1, 'qqqqqqq', 'asd', '123', '123', 'asd', 1, '2025-06-29 02:16:15'),
(2, 'aaaaa', 'aaaaa', '1111', '11111', '11111', 1, '2025-06-29 02:16:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(64) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `fechaCreado` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre`, `estado`, `fechaCreado`) VALUES
(3, 'Administrador', 1, '2025-06-28 00:47:29'),
(4, 'Despachador', 1, '2025-06-28 00:47:29'),
(5, 'Vendedor', 1, '2025-06-28 00:47:48'),
(6, 'Comprador', 1, '2025-06-28 00:47:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno`
--

CREATE TABLE `turno` (
  `id_turno` int(11) NOT NULL,
  `nombre` varchar(128) NOT NULL,
  `horaIngreso` time NOT NULL,
  `horaSalida` time NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `fechaCreado` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `turno`
--

INSERT INTO `turno` (`id_turno`, `nombre`, `horaIngreso`, `horaSalida`, `estado`, `fechaCreado`) VALUES
(1, 'Mañana', '07:30:00', '12:45:00', 1, '2025-06-26 17:32:54'),
(2, 'Tarde', '12:45:00', '18:45:00', 1, '2025-06-26 17:32:54'),
(3, 'Noche', '18:30:00', '23:59:59', 1, '2025-06-26 17:34:39'),
(4, 'Completo', '00:00:01', '23:59:59', 1, '2025-06-26 17:34:39'),
(5, 'mañana-tarde', '07:40:00', '18:45:00', 1, '2025-06-28 00:46:06'),
(6, 'tarde-noche', '12:45:00', '23:59:59', 1, '2025-06-28 00:46:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `usuario` varchar(25) NOT NULL,
  `clave` varchar(25) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `fechaCreado` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `usuario`, `clave`, `id_rol`, `estado`, `fechaCreado`) VALUES
(4, '1234', '1234', 3, 1, '2025-06-28 00:49:04'),
(5, 'abcd', 'abcd', 5, 0, '2025-06-28 00:49:04'),
(12, 'aaa', '$2y$10$77ZJZDm5JqEsLdrcR/', 5, 0, '2025-06-28 04:02:34'),
(13, 'asas', '$2y$10$.fJCxeG0IbV916.M2H', 3, 0, '2025-06-28 04:32:02'),
(14, 'azazazazazaa', '$2y$10$pGM2n.4olKfKYuo6Ws', 3, 0, '2025-06-28 12:08:55'),
(15, 'asas', '$2y$10$CPCq4DK3bh7p9iLWU6', 5, 0, '2025-06-28 12:11:21'),
(16, '1212', '$2y$10$i7LbKoce/aKeMcD4.p', 3, 1, '2025-06-28 12:16:25'),
(17, 'aaaa', '$2y$10$fT6rm3k8hN90CL673C', 3, 1, '2025-06-28 12:24:54'),
(18, '1234', '$2y$10$1c5aVb1mLpPh9955GN', 3, 0, '2025-06-28 12:32:55'),
(19, 'qqq', '$2y$10$MkxtKsXKTlKOAeKZsX', 3, 1, '2025-06-28 12:35:07'),
(20, '1212', '$2y$10$tCELUJhdhNdRZUtF41', 3, 1, '2025-06-28 12:51:13'),
(21, '123', '$2y$10$wzvEDF5Ey1abLh2Zvt', 3, 1, '2025-06-28 13:01:49'),
(22, 'qwqw', '$2y$10$4EAvaFrsM.N8BeZMqi', 3, 1, '2025-06-28 13:04:02'),
(23, '123123', '$2y$10$UpZc/QlpsDAg3eSZDG', 3, 1, '2025-06-28 13:10:22'),
(24, '12', '$2y$10$/MV2e/KzQZM9evxAVY', 3, 1, '2025-06-28 13:18:56'),
(25, '1', '$2y$10$u8eS4cSCxR5D0psjR.', 3, 1, '2025-06-28 13:52:37'),
(26, 'qwe', '$2y$10$3aXO09.YTVtURRSYeQ', 3, 1, '2025-06-28 14:03:05'),
(27, 'sad', '$2y$10$RZ4XtKKLLEHZYWFqsM', 3, 1, '2025-06-28 14:03:22'),
(28, '2', '$2y$10$PqwCFd92qIW4QwH5az', 3, 1, '2025-06-28 14:07:16'),
(29, '1', '$2y$10$zzYpBcfPFFqmPTsAAX', 3, 1, '2025-06-28 14:11:48'),
(30, '1', '$2y$10$736pIiPQU.GmOxACCU', 3, 1, '2025-06-28 14:12:35'),
(31, '12', '$2y$10$6k9mZ7X4uNP7XKZCWU', 5, 1, '2025-06-28 23:28:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `id_venta` int(11) NOT NULL,
  `tipoComprobante` varchar(25) NOT NULL,
  `serie_Comprobante` varchar(25) NOT NULL,
  `numComprobante` varchar(25) NOT NULL,
  `fechaHora` datetime NOT NULL,
  `impuesto` decimal(10,2) NOT NULL,
  `total_venta` decimal(10,2) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `id_empleado` (`id_empleado`,`id_proveedor`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `detallecompra`
--
ALTER TABLE `detallecompra`
  ADD PRIMARY KEY (`id_detalleCompra`),
  ADD KEY `id_producto` (`id_producto`,`id_compra`),
  ADD KEY `id_compra` (`id_compra`);

--
-- Indices de la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  ADD PRIMARY KEY (`id_detalleVenta`),
  ADD KEY `id_venta` (`id_venta`,`id_producto`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id_empleado`),
  ADD KEY `id_usuario` (`id_usuario`,`id_turno`),
  ADD KEY `id_turno` (`id_turno`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `turno`
--
ALTER TABLE `turno`
  ADD PRIMARY KEY (`id_turno`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_cliente` (`id_cliente`,`id_empleado`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detallecompra`
--
ALTER TABLE `detallecompra`
  MODIFY `id_detalleCompra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  MODIFY `id_detalleVenta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `turno`
--
ALTER TABLE `turno`
  MODIFY `id_turno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`) ON UPDATE CASCADE,
  ADD CONSTRAINT `compra_ibfk_2` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id_empleado`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `detallecompra`
--
ALTER TABLE `detallecompra`
  ADD CONSTRAINT `detallecompra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compra` (`id_compra`) ON UPDATE CASCADE,
  ADD CONSTRAINT `detallecompra_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  ADD CONSTRAINT `detalleventa_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`id_venta`) ON UPDATE CASCADE,
  ADD CONSTRAINT `detalleventa_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `empleado_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE,
  ADD CONSTRAINT `empleado_ibfk_2` FOREIGN KEY (`id_turno`) REFERENCES `turno` (`id_turno`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id_empleado`) ON UPDATE CASCADE,
  ADD CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
