-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-02-2024 a las 22:58:24
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
-- Base de datos: `spotify-clone`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `songs`
--

CREATE TABLE `songs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `cover` varchar(255) NOT NULL,
  `audio` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `songs`
--

INSERT INTO `songs` (`id`, `title`, `author`, `cover`, `audio`) VALUES
(1, 'LA ÚLTIMA', 'Quevedo', '65d4f7b1376d0.jpg', '65d4f7b139079.mp3'),
(2, 'CONTRA TODOS', 'FUNZO & BABY LOUD', '65d4f38fbd05d.jpg', '65d4f38fbe873.mp3'),
(3, 'PELO RIZAO', 'FUNZO & BABY LOUD', '65d4f4cb7da8a.jpg', '65d4f4cb7f150.mp3'),
(4, 'ALICANTINA', 'FUNZO & BABY LOUD', '65d4f4f3ea2f9.jpg', '65d4f4f3eb8d6.mp3'),
(5, 'HURACÁN', 'FUNZO & BABY LOUD', '65d4f605beacc.jpg', '65d4f605c019a.mp3'),
(6, 'INTERESTELAR', 'FUNZO & BABY LOUD', '65d4f6416bab4.jpg', '65d4f6416d5e8.mp3'),
(7, 'GRAFFITI', 'FUNZO & BABY LOUD', '65d4f67558d08.jpg', '65d4f6755a44b.mp3');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `songs`
--
ALTER TABLE `songs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `songs`
--
ALTER TABLE `songs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
