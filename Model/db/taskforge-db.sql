-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-03-2025 a las 04:23:20
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `taskforge-db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `buy`
--

CREATE TABLE `buy` (
  `buy_id` int(11) NOT NULL,
  `stock_id` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `price_buy` decimal(10,2) DEFAULT NULL,
  `payment` varchar(100) NOT NULL,
  `fech` timestamp NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `buy`
--

INSERT INTO `buy` (`buy_id`, `stock_id`, `amount`, `price_buy`, `payment`, `fech`, `user_id`) VALUES
(10, 25, 3, 200.00, 'Efectivo', '2025-02-20 20:30:05', 22);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clients`
--

CREATE TABLE `clients` (
  `client_id` int(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `debt_total` int(100) NOT NULL,
  `debt_paid` int(100) NOT NULL DEFAULT 0,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clients`
--

INSERT INTO `clients` (`client_id`, `name`, `debt_total`, `debt_paid`, `user_id`) VALUES
(1, 'LOL YAYA', 154, 0, 24),
(2, 'Aeon', 3000, 0, 24),
(3, 'Ferchoch0-Respaldos', 3434, 0, 24);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoice`
--

CREATE TABLE `invoice` (
  `invoice_id` int(11) NOT NULL,
  `cuit` bigint(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `business_name` varchar(100) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `invoice_type` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `invoice`
--

INSERT INTO `invoice` (`invoice_id`, `cuit`, `address`, `business_name`, `contact`, `user_id`, `invoice_type`) VALUES
(1, 4535345, 'san vicente 1955', 'mcdonald', '1134345334', 24, 'A'),
(2, 4535345, 'san vicente 1953', 'mcdonald', '1134345334', 24, 'A'),
(3, 4535345, 'san vicente 1955', 'burguerking', '1134345334', 24, 'B'),
(11, 4535345, 'san vicente 1955', 'mcdonald', '1134345334', 22, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sell`
--

CREATE TABLE `sell` (
  `sell_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `price_sell` decimal(10,2) NOT NULL,
  `payment` varchar(100) NOT NULL,
  `fech` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sell`
--

INSERT INTO `sell` (`sell_id`, `stock_id`, `amount`, `price_sell`, `payment`, `fech`, `user_id`, `invoice_id`) VALUES
(6, 25, 3, 200.00, 'Tarjeta', '2025-02-20 19:43:46', 22, 1),
(7, 25, 2, 230.00, 'Efectivo', '2025-02-20 20:08:30', 22, NULL),
(8, 12, 23, 230.00, 'Transferencia', '2025-02-20 20:16:37', 22, NULL),
(9, 12, 2, 200.00, 'Tarjeta', '2025-02-20 20:22:24', 22, NULL),
(10, 28, 3, 233.00, 'Efectivo', '2025-02-26 19:51:11', 22, NULL),
(11, 28, 2, 233.00, 'Efectivo', '2025-02-26 19:51:11', 22, NULL),
(12, 12, 54, 233.00, 'Tarjeta', '2025-02-26 19:53:47', 22, NULL),
(13, 12, 3, 233.00, 'Tarjeta', '2025-02-26 19:53:48', 22, NULL),
(14, 25, 3, 233.00, 'Transferencia', '2025-02-26 19:54:17', 22, NULL),
(15, 28, 2, 233.00, 'Transferencia', '2025-02-26 19:54:17', 22, NULL),
(16, 12, 21, 233.00, 'Transferencia', '2025-02-26 19:54:17', 22, NULL),
(17, 28, 3, 233.00, 'Efectivo', '2025-02-26 19:59:57', 22, NULL),
(18, 28, 2, 233.00, 'Tarjeta', '2025-02-26 21:55:40', 22, NULL),
(19, 28, 2, 233.00, 'Tarjeta', '2025-02-26 21:55:40', 22, NULL),
(20, 25, 2, 233.00, 'payment', '2025-02-26 23:38:49', 22, NULL),
(21, 25, 2, 233.00, 'payment', '2025-02-26 23:40:03', 22, NULL),
(22, 28, 2, 233.00, 'Efectivo', '2025-02-26 23:40:13', 22, NULL),
(23, 28, 3, 233.00, 'Transferencia', '2025-02-26 23:40:28', 22, NULL),
(27, 27, 2, 300.00, 'Efectivo', '2025-03-10 21:21:41', 24, NULL),
(28, 27, 3, 300.00, 'Efectivo', '2025-03-10 23:26:12', 24, NULL),
(29, 13, 3, 230.00, 'Efectivo', '2025-03-11 04:14:50', 24, NULL),
(30, 27, 3, 300.00, 'Efectivo', '2025-03-11 04:16:33', 24, NULL),
(31, 27, 2, 300.00, 'Efectivo', '2025-03-11 04:16:33', 24, NULL),
(32, 27, 3, 300.00, 'Efectivo', '2025-03-11 04:16:36', 24, NULL),
(33, 27, 2, 300.00, 'Efectivo', '2025-03-11 04:16:36', 24, NULL),
(34, 16, 1, 670.00, 'Tarjeta', '2025-03-11 04:17:44', 24, NULL),
(35, 27, 3, 300.00, 'Efectivo', '2025-03-11 04:19:49', 24, 3),
(36, 27, 2, 300.00, 'Efectivo', '2025-03-11 04:20:14', 24, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE `stock` (
  `stock_id` int(11) NOT NULL,
  `products` varchar(100) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `stock_min` int(11) DEFAULT NULL,
  `type_amount` varchar(100) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `stock`
--

INSERT INTO `stock` (`stock_id`, `products`, `stock`, `stock_min`, `type_amount`, `price`, `user_id`) VALUES
(12, 'Papel', -31, 15, 'u.', 110, 22),
(13, 'Papel', 76, 43, 'u.', 230, 24),
(16, 'Caja 50x60', 32, 5, 'u.', 670, 24),
(25, 'Lapiz', 1, 15, 'u.', 150, 22),
(26, 'Fibron', 0, 3, 'u.', 300, 24),
(27, 'Caja 10x20', 10, 50, 'u.', 300, 24),
(28, 'agua', -13, 3, 'kg.', 233, 22);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT 'default-profile.png',
  `verification_code` varchar(6) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `profile_image`, `verification_code`, `verified`) VALUES
(14, 'marc', 'marcoaurelio@gmail.com', '$2y$10$4NmKrX16b6GUVl53ViXl8.vBY3HgDnI2dTHjBRQs4zBCEcZsNFrn.', 'default-profile.png', '381531', 0),
(22, 'fern', 'delvalle.fernando.daniel@gmail.com', '$2y$10$50VFr84CqKOenFEpQVBi3Ok.BMmaDMeVF6fc0/CKHqftBSe3B5UAe', '1739747366_28a0cbb2f9461e0ab8eaf48c562b02fb.jpg', '948522', 1),
(24, 'Papelera', 'delvalle.fernando.d@gmail.com', '$2y$10$htFhk.z0qfujAzRLX34TOuNqOvQNdkD92KbV7T2EixiHnFhRvpE7y', 'default-profile.png', '734250', 1),
(32, 'fdelvalle', 'fdelvalle@frba.utn.edu.ar', '$2y$10$cs0ky1..vQLvxJVlFDAT9.hiPqhqbed9pp.hq/IRiu4UmltdKqIxC', 'default-profile.png', '659457', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `buy`
--
ALTER TABLE `buy`
  ADD PRIMARY KEY (`buy_id`),
  ADD KEY `stock_id` (`stock_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`),
  ADD KEY `fk_user_client` (`user_id`);

--
-- Indices de la tabla `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `sell`
--
ALTER TABLE `sell`
  ADD PRIMARY KEY (`sell_id`),
  ADD KEY `stock_id` (`stock_id`),
  ADD KEY `fk_user_id` (`user_id`),
  ADD KEY `fk_invoice` (`invoice_id`);

--
-- Indices de la tabla `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `fk_user_products` (`user_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `buy`
--
ALTER TABLE `buy`
  MODIFY `buy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `sell`
--
ALTER TABLE `sell`
  MODIFY `sell_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `buy`
--
ALTER TABLE `buy`
  ADD CONSTRAINT `buy_ibfk_1` FOREIGN KEY (`stock_id`) REFERENCES `stock` (`stock_id`),
  ADD CONSTRAINT `buy_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Filtros para la tabla `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `fk_user_client` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Filtros para la tabla `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `sell`
--
ALTER TABLE `sell`
  ADD CONSTRAINT `fk_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`invoice_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `sell_ibfk_1` FOREIGN KEY (`stock_id`) REFERENCES `stock` (`stock_id`);

--
-- Filtros para la tabla `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `fk_user_products` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
