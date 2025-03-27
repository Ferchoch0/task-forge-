-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-03-2025 a las 04:35:37
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
  `user_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `buy`
--

INSERT INTO `buy` (`buy_id`, `stock_id`, `amount`, `price_buy`, `payment`, `fech`, `user_id`, `supplier_id`) VALUES
(11, 27, 2, 300.00, 'Efectivo', '2025-03-24 02:00:55', 24, 1),
(12, 16, 2, 300.00, 'Transferencia', '2025-03-24 02:03:32', 24, 1),
(13, 16, 3, 670.00, 'Transferencia', '2025-03-25 02:03:32', 24, 1),
(14, 27, 1, 300.00, 'Efectivo', '2025-03-26 03:46:27', 24, 1),
(15, 27, 11, 300.00, 'Efectivo', '2025-03-26 03:46:27', 24, 1),
(16, 27, 1, 300.00, 'Tarjeta', '2025-03-26 16:01:50', 24, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cash`
--

CREATE TABLE `cash` (
  `cash_id` int(11) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `mov_type` tinyint(1) NOT NULL,
  `cash_amount` int(100) NOT NULL,
  `payment` varchar(100) NOT NULL,
  `fech` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cash`
--

INSERT INTO `cash` (`cash_id`, `description`, `mov_type`, `cash_amount`, `payment`, `fech`, `user_id`) VALUES
(NULL, 'comida', 1, 200, 'Efectivo', '2025-03-24 18:21:48', 24),
(NULL, 'almohada', 0, 200, 'Efectivo', '2025-03-24 18:22:07', 24),
(NULL, 'Merca', 1, 30000, 'Tarjeta', '2025-03-25 16:53:54', 24);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clients`
--

CREATE TABLE `clients` (
  `client_id` int(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `cuit` int(11) DEFAULT NULL,
  `contact` int(11) DEFAULT NULL,
  `invoice_type` enum('A','B','C') DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clients`
--

INSERT INTO `clients` (`client_id`, `name`, `cuit`, `contact`, `invoice_type`, `address`, `quantity`, `user_id`) VALUES
(4, 'Ferchoch0-Respaldos', 4535345, 1134345334, 'A', 'san vicente 1955', 1, 24),
(5, 'Aeon', 4535345, 1153423454, 'C', 'Maipu 185', 3, 24);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `debts`
--

CREATE TABLE `debts` (
  `debt_id` int(11) NOT NULL,
  `debt_type` tinyint(1) NOT NULL,
  `amount` int(100) NOT NULL,
  `fech` timestamp NOT NULL DEFAULT current_timestamp(),
  `client_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `debts`
--

INSERT INTO `debts` (`debt_id`, `debt_type`, `amount`, `fech`, `client_id`) VALUES
(1, 1, 2108, '2025-03-22 22:25:04', 4),
(2, 1, 472, '2025-03-23 00:10:42', 5),
(3, 0, 2000, '2025-03-23 22:06:51', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoice`
--

CREATE TABLE `invoice` (
  `invoice_id` int(11) NOT NULL,
  `fech` timestamp NOT NULL DEFAULT current_timestamp(),
  `client_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `invoice`
--

INSERT INTO `invoice` (`invoice_id`, `fech`, `client_id`, `user_id`) VALUES
(12, '2025-03-22 22:25:04', 4, 24),
(13, '2025-03-23 00:10:42', 5, 24),
(14, '2025-03-26 03:41:17', 5, 24),
(15, '2025-03-26 03:48:03', 5, 24),
(16, '2025-03-26 03:49:18', 4, 24),
(17, '2025-03-26 03:49:33', 5, 24),
(18, '2025-03-26 03:50:07', 4, 24);

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
  `client_id` int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sell`
--

INSERT INTO `sell` (`sell_id`, `stock_id`, `amount`, `price_sell`, `payment`, `fech`, `user_id`, `client_id`) VALUES
(37, 16, 2, 1054.00, 'deuda', '2025-03-22 22:25:04', 24, 4),
(38, 27, 1, 472.00, 'deuda', '2025-02-23 00:10:42', 24, 5),
(39, 26, 1, 472.00, 'Efectivo', '2025-02-25 20:54:50', 24, NULL),
(40, 29, 3, 787.00, 'Tarjeta', '2025-03-25 21:39:40', 24, NULL),
(41, 27, 3, 472.00, 'Tarjeta', '2025-03-25 21:56:28', 24, NULL),
(42, 16, 2, 1054.00, 'Efectivo', '2025-03-25 21:57:47', 24, NULL),
(43, 16, 3, 1054.00, 'Transferencia', '2025-03-24 21:57:56', 24, NULL),
(44, 13, 1, 362.00, 'Efectivo', '2025-03-26 00:54:34', 24, NULL),
(45, 27, 1, 472.00, 'Efectivo', '2025-03-26 03:23:26', 24, NULL),
(46, 27, 1, 472.00, 'Efectivo', '2025-03-26 03:45:40', 24, NULL),
(47, 27, 3, 472.00, 'Tarjeta', '2025-03-26 03:50:07', 24, 4),
(48, 13, 1, 362.00, 'Tarjeta', '2025-03-26 04:39:12', 24, NULL),
(49, 26, 1, 472.00, 'Tarjeta', '2025-03-26 16:02:30', 24, NULL),
(50, 16, 1, 1054.00, 'Efectivo', '2025-03-26 23:21:40', 24, NULL);

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
  `quantity` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `stock`
--

INSERT INTO `stock` (`stock_id`, `products`, `stock`, `stock_min`, `type_amount`, `price`, `quantity`, `user_id`) VALUES
(12, 'Papel', -31, 15, 'u.', 110, 0, 22),
(13, 'Papel', 74, 43, 'u.', 230, 6, 24),
(16, 'Caja 50x60', 29, 5, 'u.', 670, 1, 24),
(25, 'Lapiz', 1, 15, 'u.', 150, 0, 22),
(26, 'Fibron', -2, 3, 'u.', 300, 1, 24),
(27, 'Caja 10x20', 16, 50, 'u.', 300, 2, 24),
(28, 'agua', -13, 3, 'kg.', 233, 0, 22),
(29, 'Manteca', 14, 13, 'kg.', 500, 1, 24),
(33, 'Papiros 20x30', 80, 23, 'u.', 3000, 0, 24),
(34, 'Fibron', 23, 2, 'u.', 300, 0, 24),
(35, 'Cinta', 10, 3, 'u.', 500, 0, 24),
(36, 'Lapiz', 32, 10, 'u.', 200, 0, 24);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact` int(100) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `name`, `contact`, `user_id`) VALUES
(1, 'Macaco Plus', 1153424345, 24),
(2, 'Lucas', 1153423454, 24);

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
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_buy_supplier` (`supplier_id`);

--
-- Indices de la tabla `cash`
--
ALTER TABLE `cash`
  ADD KEY `fk_cash_user` (`user_id`);

--
-- Indices de la tabla `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`),
  ADD UNIQUE KEY `client_id` (`client_id`),
  ADD KEY `fk_user_client` (`user_id`);

--
-- Indices de la tabla `debts`
--
ALTER TABLE `debts`
  ADD PRIMARY KEY (`debt_id`),
  ADD KEY `fk_debts_client` (`client_id`);

--
-- Indices de la tabla `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_invoice_client` (`client_id`);

--
-- Indices de la tabla `sell`
--
ALTER TABLE `sell`
  ADD PRIMARY KEY (`sell_id`),
  ADD KEY `stock_id` (`stock_id`),
  ADD KEY `fk_user_id` (`user_id`),
  ADD KEY `fk_sell_client` (`client_id`);

--
-- Indices de la tabla `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `fk_user_products` (`user_id`);

--
-- Indices de la tabla `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`),
  ADD KEY `fk_supplier_user` (`user_id`);

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
  MODIFY `buy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `debts`
--
ALTER TABLE `debts`
  MODIFY `debt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `sell`
--
ALTER TABLE `sell`
  MODIFY `sell_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  ADD CONSTRAINT `buy_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_buy_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cash`
--
ALTER TABLE `cash`
  ADD CONSTRAINT `fk_cash_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Filtros para la tabla `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `fk_user_client` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Filtros para la tabla `debts`
--
ALTER TABLE `debts`
  ADD CONSTRAINT `fk_debts_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `fk_invoice_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `sell`
--
ALTER TABLE `sell`
  ADD CONSTRAINT `fk_sell_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`),
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `sell_ibfk_1` FOREIGN KEY (`stock_id`) REFERENCES `stock` (`stock_id`);

--
-- Filtros para la tabla `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `fk_user_products` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `supplier`
--
ALTER TABLE `supplier`
  ADD CONSTRAINT `fk_supplier_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
