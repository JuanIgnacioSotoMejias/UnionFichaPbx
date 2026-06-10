-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: db:3306
-- Tiempo de generación: 10-06-2026 a las 14:43:53
-- Versión del servidor: 12.2.2-MariaDB-ubu2404
-- Versión de PHP: 8.3.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pbx_middleware`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alertas_productividad`
--

CREATE TABLE `alertas_productividad` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `operador_config_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tipo_alerta` varchar(255) NOT NULL,
  `nivel` enum('INFO','WARNING','CRITICAL') NOT NULL DEFAULT 'INFO',
  `descripcion` text NOT NULL,
  `metadatos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadatos`)),
  `leida` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `api_error_logs`
--

CREATE TABLE `api_error_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `codigo_http` smallint(6) DEFAULT NULL,
  `endpoint` varchar(500) DEFAULT NULL,
  `mensaje_error` text NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `resuelto` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora_ami`
--

CREATE TABLE `bitacora_ami` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `comando_enviado` varchar(255) NOT NULL,
  `extension` varchar(10) NOT NULL,
  `respuesta_asterisk` text NOT NULL,
  `status` enum('SUCCESS','ERROR','DRY_RUN') NOT NULL,
  `hora_inicio_esperada` time DEFAULT NULL,
  `hora_fin_esperada` time DEFAULT NULL,
  `estado_actual` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `bitacora_ami`
--

INSERT INTO `bitacora_ami` (`id`, `comando_enviado`, `extension`, `respuesta_asterisk`, `status`, `hora_inicio_esperada`, `hora_fin_esperada`, `estado_actual`, `created_at`, `updated_at`) VALUES
(1, 'QueueAdd', '8001', '[DRY_RUN] Simulado', 'DRY_RUN', NULL, NULL, NULL, '2026-05-20 18:43:28', '2026-05-20 18:43:28'),
(2, 'QueueAdd', '8001', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-05-22 15:00:41', '2026-05-22 15:00:41'),
(3, 'QueueAdd', '8004', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-05-25 17:45:18', '2026-05-25 17:45:18'),
(4, 'QueueRemove', '8004', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-05-25 17:47:54', '2026-05-25 17:47:54'),
(5, 'QueueAdd', '8004', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-05-25 17:52:53', '2026-05-25 17:52:53'),
(6, 'QueueRemove', '8004', 'Error connecting to ami: Se produjo un error durante el intento de conexión ya que la parte conectada no respondió adecuadamente tras un periodo de tiempo, o bien se produjo un error en la conexión establecida ya que el host conectado no ha podido responder', 'ERROR', NULL, NULL, NULL, '2026-05-25 18:01:03', '2026-05-25 18:01:03'),
(7, 'QueueAdd', '8010', 'Error connecting to ami: Se produjo un error durante el intento de conexión ya que la parte conectada no respondió adecuadamente tras un periodo de tiempo, o bien se produjo un error en la conexión establecida ya que el host conectado no ha podido responder', 'ERROR', NULL, NULL, NULL, '2026-05-28 16:22:01', '2026-05-28 16:22:01'),
(8, 'QueueRemove', '8010', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-05-28 16:37:59', '2026-05-28 16:37:59'),
(9, 'QueueAdd', '8010', '[DRY_RUN] Simulado', 'DRY_RUN', NULL, NULL, NULL, '2026-05-28 19:44:36', '2026-05-28 19:44:36'),
(10, 'QueueAdd', '8010', '[DRY_RUN] Simulado', 'DRY_RUN', NULL, NULL, NULL, '2026-05-28 19:45:11', '2026-05-28 19:45:11'),
(11, 'QueueAdd', '8010', '[DRY_RUN] Simulado', 'DRY_RUN', NULL, NULL, NULL, '2026-05-28 19:55:13', '2026-05-28 19:55:13'),
(12, 'QueueAdd', '8010', '[DRY_RUN] Simulado', 'DRY_RUN', NULL, NULL, NULL, '2026-05-28 19:56:26', '2026-05-28 19:56:26'),
(13, 'QueueAdd', '8010', '[DRY_RUN] Simulado', 'DRY_RUN', NULL, NULL, NULL, '2026-05-28 19:58:38', '2026-05-28 19:58:38'),
(14, 'QueueAdd', '8010', '[DRY_RUN] Simulado', 'DRY_RUN', NULL, NULL, NULL, '2026-05-29 13:23:41', '2026-05-29 13:23:41'),
(15, 'QueueAdd', '8010', '[DRY_RUN] Simulado', 'DRY_RUN', NULL, NULL, NULL, '2026-05-29 14:05:21', '2026-05-29 14:05:21'),
(16, 'QueueAdd', '8010', '[DRY_RUN] Simulado', 'DRY_RUN', NULL, NULL, NULL, '2026-05-29 14:24:52', '2026-05-29 14:24:52'),
(17, 'QueueAdd', '8010', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-05-29 14:33:15', '2026-05-29 14:33:15'),
(18, 'QueueRemove', '8010', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-05-29 14:34:09', '2026-05-29 14:34:09'),
(19, 'QueueAdd', '8010', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-05-29 14:35:40', '2026-05-29 14:35:40'),
(20, 'QueueAdd', '8003', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-05-29 14:35:57', '2026-05-29 14:35:57'),
(21, 'QueueAdd', '8003', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-05-29 15:00:29', '2026-05-29 15:00:29'),
(22, 'QueueAdd', '8010', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-05-29 15:00:45', '2026-05-29 15:00:45'),
(23, 'QueueAdd', '8010', '[DRY_RUN] Simulado', 'DRY_RUN', NULL, NULL, NULL, '2026-05-29 15:10:12', '2026-05-29 15:10:12'),
(24, 'QueueRemove', '8010', '[DRY_RUN] Simulado', 'DRY_RUN', NULL, NULL, NULL, '2026-05-29 15:11:15', '2026-05-29 15:11:15'),
(25, 'QueueRemove', '8010', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-05-29 15:43:07', '2026-05-29 15:43:07'),
(26, 'QueueRemove', '8010', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-05-29 15:44:39', '2026-05-29 15:44:39'),
(27, 'QueueAdd', '8010', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-05-29 15:45:57', '2026-05-29 15:45:57'),
(28, 'QueueAdd', '8010', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-05-29 15:54:04', '2026-05-29 15:54:04'),
(29, 'QueueAdd', '8010', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-05-29 17:18:48', '2026-05-29 17:18:48'),
(30, 'QueueAdd', '8009', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-05-29 17:19:52', '2026-05-29 17:19:52'),
(31, 'QueueRemove', '8009', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-05-29 18:40:45', '2026-05-29 18:40:45'),
(32, 'QueueRemove', '8010', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-05-29 18:43:35', '2026-05-29 18:43:35'),
(33, 'QueueAdd', '8010', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-05-29 18:52:17', '2026-05-29 18:52:17'),
(34, 'QueueRemove', '8010', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-05-29 18:58:21', '2026-05-29 18:58:21'),
(35, 'QueueAdd', '8010', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-05-29 18:58:44', '2026-05-29 18:58:44'),
(36, 'QueueRemove', '8010', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-06-01 19:51:14', '2026-06-01 19:51:14'),
(37, 'QueueAdd', '8010', 'Error reading', 'ERROR', NULL, NULL, NULL, '2026-06-01 19:51:48', '2026-06-01 19:51:48'),
(38, 'QueueAdd', '8010', 'Error connecting to ami: Se produjo un error durante el intento de conexión ya que la parte conectada no respondió adecuadamente tras un periodo de tiempo, o bien se produjo un error en la conexión establecida ya que el host conectado no ha podido responder', 'ERROR', NULL, NULL, NULL, '2026-06-04 13:51:11', '2026-06-04 13:51:11'),
(39, 'QueueAdd', '8010', 'implode(): Argument #2 ($array) must be of type ?array, string given', 'ERROR', NULL, NULL, NULL, '2026-06-04 14:04:57', '2026-06-04 14:04:57'),
(40, 'QueueRemove', '8010', 'implode(): Argument #2 ($array) must be of type ?array, string given', 'ERROR', NULL, NULL, NULL, '2026-06-04 14:05:48', '2026-06-04 14:05:48'),
(41, 'QueueAdd', '8010', 'implode(): Argument #2 ($array) must be of type ?array, string given', 'ERROR', NULL, NULL, NULL, '2026-06-04 14:07:06', '2026-06-04 14:07:06'),
(42, 'QueueRemove', '8010', 'implode(): Argument #2 ($array) must be of type ?array, string given', 'ERROR', NULL, NULL, NULL, '2026-06-04 14:07:51', '2026-06-04 14:07:51'),
(43, 'QueueAdd', '8009', 'implode(): Argument #2 ($array) must be of type ?array, string given', 'ERROR', NULL, NULL, NULL, '2026-06-04 14:27:48', '2026-06-04 14:27:48'),
(44, 'QueueRemove', '8009', 'implode(): Argument #2 ($array) must be of type ?array, string given', 'ERROR', NULL, NULL, NULL, '2026-06-04 14:28:27', '2026-06-04 14:28:27'),
(45, 'QueueRemove', '8009', 'implode(): Argument #2 ($array) must be of type ?array, string given', 'ERROR', NULL, NULL, NULL, '2026-06-04 14:29:03', '2026-06-04 14:29:03'),
(46, 'QueueAdd', '8009', 'implode(): Argument #2 ($array) must be of type ?array, string given', 'ERROR', NULL, NULL, NULL, '2026-06-04 14:47:39', '2026-06-04 14:47:39'),
(47, 'QueueAdd', '8009', 'implode(): Argument #2 ($array) must be of type ?array, string given', 'ERROR', NULL, NULL, NULL, '2026-06-04 14:48:28', '2026-06-04 14:48:28'),
(48, 'QueueRemove', '8009', 'implode(): Argument #2 ($array) must be of type ?array, string given', 'ERROR', NULL, NULL, NULL, '2026-06-04 14:49:07', '2026-06-04 14:49:07'),
(49, 'QueueRemove', '8009', 'implode(): Argument #2 ($array) must be of type ?array, string given', 'ERROR', NULL, NULL, NULL, '2026-06-04 14:50:12', '2026-06-04 14:50:12'),
(50, 'QueueRemove', '8009', 'implode(): Argument #2 ($array) must be of type ?array, string given', 'ERROR', NULL, NULL, NULL, '2026-06-04 14:50:52', '2026-06-04 14:50:52'),
(51, 'QueueRemove', '8009', 'implode(): Argument #2 ($array) must be of type ?array, string given', 'ERROR', NULL, NULL, NULL, '2026-06-04 14:51:29', '2026-06-04 14:51:29'),
(52, 'QueueRemove', '8009', 'implode(): Argument #2 ($array) must be of type ?array, string given', 'ERROR', NULL, NULL, NULL, '2026-06-04 14:52:35', '2026-06-04 14:52:35'),
(53, 'QueueAdd', '8009', 'implode(): Argument #2 ($array) must be of type ?array, string given', 'ERROR', NULL, NULL, NULL, '2026-06-04 14:53:33', '2026-06-04 14:53:33'),
(54, 'QueueRemove', '8009', 'implode(): Argument #2 ($array) must be of type ?array, string given', 'ERROR', NULL, NULL, NULL, '2026-06-04 14:54:48', '2026-06-04 14:54:48'),
(55, 'QueueAdd', '8010', 'implode(): Argument #2 ($array) must be of type ?array, string given', 'ERROR', NULL, NULL, NULL, '2026-06-04 16:43:17', '2026-06-04 16:43:17'),
(56, 'QueueRemove', '8010', 'implode(): Argument #2 ($array) must be of type ?array, string given', 'ERROR', NULL, NULL, NULL, '2026-06-04 16:44:37', '2026-06-04 16:44:37'),
(57, 'QueueRemove', '8010', 'implode(): Argument #2 ($array) must be of type ?array, string given', 'ERROR', NULL, NULL, NULL, '2026-06-04 16:45:16', '2026-06-04 16:45:16'),
(58, 'QueueRemove', '8010', 'Unable to remove interface: Not there', 'ERROR', NULL, NULL, NULL, '2026-06-04 19:14:16', '2026-06-04 19:14:16'),
(59, 'QueueRemove', '8010', 'Unable to remove interface: Not there', 'ERROR', NULL, NULL, NULL, '2026-06-04 19:14:59', '2026-06-04 19:14:59'),
(60, 'QueueRemove', '8010', 'Unable to remove interface: Not there', 'ERROR', NULL, NULL, NULL, '2026-06-04 19:15:34', '2026-06-04 19:15:34'),
(61, 'QueueAdd', '8010', 'Added interface to queue', 'SUCCESS', NULL, NULL, NULL, '2026-06-04 19:15:49', '2026-06-04 19:15:49'),
(62, 'QueueRemove', '8010', 'Removed interface from queue', 'SUCCESS', NULL, NULL, NULL, '2026-06-04 19:16:19', '2026-06-04 19:16:19'),
(63, 'QueueAdd', '8009', 'Added interface to queue', 'SUCCESS', NULL, NULL, NULL, '2026-06-05 15:14:25', '2026-06-05 15:14:25'),
(64, 'QueueRemove', '8009', 'Removed interface from queue', 'SUCCESS', NULL, NULL, NULL, '2026-06-05 15:15:06', '2026-06-05 15:15:06'),
(65, 'QueueAdd', '8010', 'Added interface to queue', 'SUCCESS', NULL, NULL, NULL, '2026-06-05 15:16:08', '2026-06-05 15:16:08'),
(66, 'QueueRemove', '8010', 'Removed interface from queue', 'SUCCESS', NULL, NULL, NULL, '2026-06-05 15:16:34', '2026-06-05 15:16:34'),
(67, 'QueueRemove', '8010', 'Unable to remove interface: Not there', 'ERROR', NULL, NULL, NULL, '2026-06-05 17:17:58', '2026-06-05 17:17:58'),
(68, 'QueueAdd', '8010', 'Added interface to queue', 'SUCCESS', NULL, NULL, NULL, '2026-06-05 17:31:49', '2026-06-05 17:31:49'),
(69, 'QueueRemove', '8010', 'Removed interface from queue', 'SUCCESS', NULL, NULL, NULL, '2026-06-05 17:32:09', '2026-06-05 17:32:09'),
(70, 'QueueRemove', '8010', 'Unable to remove interface: Not there', 'ERROR', NULL, NULL, NULL, '2026-06-05 17:33:10', '2026-06-05 17:33:10'),
(71, 'QueueAdd', '8010', 'Added interface to queue', 'SUCCESS', NULL, NULL, NULL, '2026-06-05 17:33:36', '2026-06-05 17:33:36'),
(72, 'QueueRemove', '8010', 'Removed interface from queue', 'SUCCESS', NULL, NULL, NULL, '2026-06-05 17:35:17', '2026-06-05 17:35:17'),
(73, 'QueueAdd', '8010', 'Added interface to queue', 'SUCCESS', NULL, NULL, NULL, '2026-06-05 17:38:37', '2026-06-05 17:38:37'),
(74, 'QueueRemove', '8010', 'Removed interface from queue', 'SUCCESS', NULL, NULL, NULL, '2026-06-05 17:40:20', '2026-06-05 17:40:20'),
(75, 'QueueAdd', '8010', 'Added interface to queue', 'SUCCESS', NULL, NULL, NULL, '2026-06-05 17:40:34', '2026-06-05 17:40:34'),
(76, 'QueueRemove', '8010', 'Removed interface from queue', 'SUCCESS', NULL, NULL, NULL, '2026-06-05 17:43:06', '2026-06-05 17:43:06'),
(77, 'QueueAdd', '8010', 'Added interface to queue', 'SUCCESS', NULL, NULL, NULL, '2026-06-05 17:57:20', '2026-06-05 17:57:20'),
(78, 'QueueRemove', '8010', 'Removed interface from queue', 'SUCCESS', NULL, NULL, NULL, '2026-06-05 17:58:31', '2026-06-05 17:58:31'),
(79, 'QueueAdd', '8010', 'Added interface to queue', 'SUCCESS', NULL, NULL, NULL, '2026-06-05 19:18:25', '2026-06-05 19:18:25'),
(80, 'QueueRemove', '8010', 'Removed interface from queue', 'SUCCESS', NULL, NULL, NULL, '2026-06-05 19:19:33', '2026-06-05 19:19:33'),
(81, 'QueueAdd', '8010', 'Added interface to queue', 'SUCCESS', NULL, NULL, NULL, '2026-06-05 19:20:06', '2026-06-05 19:20:06'),
(82, 'QueueRemove', '8010', 'Removed interface from queue', 'SUCCESS', NULL, NULL, NULL, '2026-06-05 19:23:30', '2026-06-05 19:23:30'),
(83, 'QueueRemove', '8010', 'Unable to remove interface: Not there', 'ERROR', NULL, NULL, NULL, '2026-06-05 19:40:39', '2026-06-05 19:40:39'),
(84, 'QueueAdd', '8010', 'Added interface to queue', 'SUCCESS', NULL, NULL, NULL, '2026-06-05 19:40:50', '2026-06-05 19:40:50'),
(85, 'QueueRemove', '8010', 'Removed interface from queue', 'SUCCESS', NULL, NULL, NULL, '2026-06-05 19:42:15', '2026-06-05 19:42:15'),
(86, 'QueueRemove', '8010', 'Unable to remove interface: Not there', 'ERROR', NULL, NULL, NULL, '2026-06-05 19:52:00', '2026-06-05 19:52:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('pbx-receptor-cache-dashboard:metricas_2026-06-09', 'a:4:{s:15:\"totalOperadores\";i:2;s:17:\"operadoresActivos\";i:0;s:15:\"totalEventosHoy\";i:0;s:10:\"erroresHoy\";i:0;}', 1781025058),
('pbx-receptor-cache-dashboard:metricas_2026-06-10', 'a:4:{s:15:\"totalOperadores\";i:2;s:17:\"operadoresActivos\";i:0;s:15:\"totalEventosHoy\";i:0;s:10:\"erroresHoy\";i:0;}', 1781102646),
('pbx-receptor-cache-dashboard:productividad_dia', 'a:2:{i:0;a:6:{s:2:\"id\";i:1;s:6:\"nombre\";s:10:\"Juan Perez\";s:9:\"extension\";s:4:\"0000\";s:3:\"aht\";i:0;s:9:\"ocupacion\";i:0;s:13:\"alertas_count\";i:0;}i:1;a:6:{s:2:\"id\";i:2;s:6:\"nombre\";s:14:\"Rosme Zabaleta\";s:9:\"extension\";s:4:\"8010\";s:3:\"aht\";i:0;s:9:\"ocupacion\";i:0;s:13:\"alertas_count\";i:0;}}', 1781102692),
('pbx-receptor-cache-extensions:ami_statuses:10fa2775bc66bba82eaf40d3fbff3762', 'a:10:{i:8006;s:6:\"ONLINE\";i:8007;s:6:\"ONLINE\";i:8008;s:6:\"ONLINE\";i:9001;s:6:\"ONLINE\";i:9002;s:6:\"ONLINE\";i:9003;s:6:\"ONLINE\";i:9004;s:6:\"ONLINE\";i:9005;s:7:\"OFFLINE\";i:8009;s:7:\"OFFLINE\";i:8010;s:6:\"ONLINE\";}', 1781023154),
('pbx-receptor-cache-extensions:ami_statuses:ba0ddf868b7bca5af02231a0d2013b28', 'a:14:{i:8001;s:6:\"ONLINE\";i:8002;s:6:\"ONLINE\";i:8003;s:6:\"ONLINE\";i:8004;s:6:\"ONLINE\";i:8005;s:6:\"ONLINE\";i:8007;s:6:\"ONLINE\";i:8008;s:6:\"ONLINE\";i:9001;s:6:\"ONLINE\";i:9002;s:6:\"ONLINE\";i:9003;s:6:\"ONLINE\";i:9004;s:6:\"ONLINE\";i:9005;s:7:\"OFFLINE\";i:8009;s:7:\"OFFLINE\";i:8010;s:6:\"ONLINE\";}', 1781023161);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `extensions`
--

CREATE TABLE `extensions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `numero` varchar(255) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `nombre_freepbx` varchar(255) DEFAULT NULL,
  `tipo_tecnologia` varchar(255) NOT NULL DEFAULT 'pjsip',
  `estado` enum('libre','en_uso','inactiva') NOT NULL DEFAULT 'libre',
  `grupo_horario` int(11) DEFAULT NULL COMMENT 'Grupo al que pertenece la extensión (ej. 1 o 2)',
  `sincronizado_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `categoria` enum('operador','despachador','interno') NOT NULL DEFAULT 'operador',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `motivo_inactividad` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `extensions`
--

INSERT INTO `extensions` (`id`, `numero`, `descripcion`, `nombre_freepbx`, `tipo_tecnologia`, `estado`, `grupo_horario`, `sincronizado_at`, `created_at`, `updated_at`, `categoria`, `is_active`, `motivo_inactividad`) VALUES
(3, '8001', 'Operador 1', 'Operador 1', 'pjsip', 'en_uso', NULL, '2026-05-22 17:13:41', '2026-05-22 17:13:41', '2026-05-27 15:54:45', 'operador', 1, NULL),
(4, '8002', 'Operador 2', 'Operador 2', 'pjsip', 'libre', NULL, '2026-05-22 17:13:41', '2026-05-22 17:13:41', '2026-05-22 17:13:41', 'operador', 1, NULL),
(5, '8003', 'Operador 3', 'Operador 3', 'pjsip', 'en_uso', NULL, '2026-05-22 17:13:41', '2026-05-22 17:13:41', '2026-05-29 14:35:56', 'operador', 1, NULL),
(6, '8004', 'Operador 4', 'Operador 4', 'pjsip', 'en_uso', NULL, '2026-05-22 17:13:41', '2026-05-22 17:13:41', '2026-05-27 15:54:53', 'operador', 1, NULL),
(7, '8005', 'Operador 5', 'Operador 5', 'pjsip', 'libre', NULL, '2026-05-22 17:13:41', '2026-05-22 17:13:41', '2026-05-22 17:13:41', 'operador', 1, NULL),
(8, '8006', 'Operador 6', 'Operador 6', 'pjsip', 'libre', NULL, '2026-05-22 17:13:41', '2026-05-22 17:13:41', '2026-05-22 17:13:41', 'operador', 1, NULL),
(9, '8007', 'Operador 7', 'Operador 7', 'pjsip', 'libre', NULL, '2026-05-22 17:13:41', '2026-05-22 17:13:41', '2026-05-22 17:13:41', 'despachador', 1, NULL),
(10, '8008', 'Operador 8', 'Operador 8', 'pjsip', 'libre', NULL, '2026-05-22 17:13:41', '2026-05-22 17:13:41', '2026-05-22 17:13:41', 'despachador', 1, NULL),
(11, '8009', 'Inactivo por sistema', 'Ext 8009', 'pjsip', 'libre', NULL, '2026-05-22 17:13:41', '2026-05-22 17:13:41', '2026-06-08 17:24:25', 'operador', 0, 'Falta de personal'),
(12, '8010', 'Inactivo por sistema', 'Ext 8010', 'pjsip', 'libre', NULL, '2026-05-22 17:13:41', '2026-05-22 17:13:41', '2026-06-08 17:24:25', 'operador', 0, 'Falta de personal'),
(13, '9001', 'Servicios Publicos', 'Servicios Publicos', 'pjsip', 'libre', NULL, '2026-05-22 17:13:41', '2026-05-22 17:13:41', '2026-05-22 17:13:41', 'operador', 1, NULL),
(14, '9002', 'Cuadrantes de Paz', 'Cuadrantes de Paz', 'pjsip', 'libre', NULL, '2026-05-22 17:13:41', '2026-05-22 17:13:41', '2026-05-22 17:13:41', 'operador', 1, NULL),
(15, '9003', 'Recepción', 'Recepción', 'pjsip', 'libre', NULL, '2026-05-22 17:13:41', '2026-05-22 17:13:41', '2026-05-22 17:13:41', 'operador', 1, NULL),
(16, '9004', 'vigilancia', 'vigilancia', 'pjsip', 'libre', NULL, '2026-05-22 17:13:41', '2026-05-22 17:13:41', '2026-05-22 17:13:41', 'operador', 1, NULL),
(17, '9005', 'Gestión comunicacional', 'Gestión comunicacional', 'pjsip', 'libre', NULL, '2026-05-22 17:13:41', '2026-05-22 17:13:41', '2026-05-22 17:13:41', 'operador', 1, NULL),
(18, '9006', 'Bienes', 'Bienes', 'pjsip', 'libre', NULL, '2026-05-22 17:13:41', '2026-05-22 17:13:41', '2026-05-22 17:13:41', 'operador', 1, NULL),
(19, '9007', 'TECNOLOGIA', 'TECNOLOGIA', 'pjsip', 'libre', NULL, '2026-05-22 17:13:42', '2026-05-22 17:13:42', '2026-05-22 17:13:42', 'operador', 1, NULL),
(20, '9008', 'RRHH', 'RRHH', 'pjsip', 'libre', NULL, '2026-05-22 17:13:42', '2026-05-22 17:13:42', '2026-05-22 17:13:42', 'operador', 1, NULL),
(21, '9009', 'Capacitacion', 'Capacitacion', 'pjsip', 'libre', NULL, '2026-05-22 17:13:42', '2026-05-22 17:13:42', '2026-05-22 17:13:42', 'operador', 1, NULL),
(22, '9010', 'Trasporte', 'Trasporte', 'pjsip', 'libre', NULL, '2026-05-22 17:13:42', '2026-05-22 17:13:42', '2026-05-22 17:13:42', 'operador', 1, NULL),
(23, '9011', 'Servicio Medico', 'Servicio Medico', 'pjsip', 'libre', NULL, '2026-05-22 17:13:42', '2026-05-22 17:13:42', '2026-05-22 17:13:42', 'operador', 1, NULL),
(24, '9012', 'Recursos Humanos', 'Recursos Humanos', 'pjsip', 'libre', NULL, '2026-05-22 17:13:42', '2026-05-22 17:13:42', '2026-05-22 17:13:42', 'operador', 1, NULL),
(25, '9013', 'Operaciones', 'Operaciones', 'pjsip', 'libre', NULL, '2026-05-22 17:13:42', '2026-05-22 17:13:42', '2026-05-22 17:13:42', 'operador', 1, NULL),
(26, '9014', 'DIRECCION', 'DIRECCION', 'pjsip', 'libre', NULL, '2026-05-22 17:13:42', '2026-05-22 17:13:42', '2026-05-22 17:13:42', 'operador', 1, NULL),
(27, '9015', 'VSS', 'VSS', 'pjsip', 'libre', NULL, '2026-05-22 17:13:42', '2026-05-22 17:13:42', '2026-05-22 17:13:42', 'operador', 1, NULL),
(28, '9016', 'despacho', 'despacho', 'pjsip', 'libre', NULL, '2026-05-22 17:13:42', '2026-05-22 17:13:42', '2026-05-22 17:13:42', 'operador', 1, NULL),
(29, '9017', 'CUPAZ', 'CUPAZ', 'pjsip', 'libre', NULL, '2026-05-22 17:13:42', '2026-05-22 17:13:42', '2026-05-22 17:13:42', 'operador', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ext_operador`
--

CREATE TABLE `ext_operador` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `extension_id` bigint(20) UNSIGNED NOT NULL,
  `operador_config_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ext_operador`
--

INSERT INTO `ext_operador` (`id`, `extension_id`, `operador_config_id`, `created_at`, `updated_at`) VALUES
(1, 3, 1, '2026-06-08 18:58:49', '2026-06-08 18:58:49'),
(2, 5, 2, '2026-06-08 18:58:49', '2026-06-08 18:58:49'),
(3, 6, 2, '2026-06-08 18:58:49', '2026-06-08 18:58:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ext_operador_pivote`
--

CREATE TABLE `ext_operador_pivote` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `extension_id` bigint(20) UNSIGNED NOT NULL,
  `operador_config_id` bigint(20) UNSIGNED NOT NULL,
  `asignado_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ext_operador_pivote`
--

INSERT INTO `ext_operador_pivote` (`id`, `extension_id`, `operador_config_id`, `asignado_at`) VALUES
(1, 3, 1, '2026-06-08 15:37:26'),
(2, 5, 2, '2026-06-08 15:37:26'),
(3, 6, 2, '2026-06-08 15:37:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_accesos`
--

CREATE TABLE `historial_accesos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `operador_config_id` bigint(20) UNSIGNED NOT NULL,
  `evento` enum('LOGIN','LOGOUT','FORCE_DISCONNECT') NOT NULL,
  `origen_ip` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `historial_accesos`
--

INSERT INTO `historial_accesos` (`id`, `operador_config_id`, `evento`, `origen_ip`, `created_at`) VALUES
(1, 1, 'LOGIN', '127.0.0.1', '2026-05-20 18:43:28'),
(2, 1, 'LOGIN', '127.0.0.1', '2026-05-22 15:00:44'),
(3, 2, 'LOGIN', '127.0.0.1', '2026-05-25 17:45:24'),
(4, 2, 'LOGOUT', '127.0.0.1', '2026-05-25 17:47:57'),
(5, 2, 'LOGIN', '127.0.0.1', '2026-05-25 17:52:56'),
(6, 2, 'LOGOUT', '127.0.0.1', '2026-05-25 18:01:06'),
(7, 2, 'LOGOUT', '127.0.0.1', '2026-05-28 16:38:00'),
(8, 2, 'LOGOUT', '127.0.0.1', '2026-05-29 14:34:08'),
(9, 2, 'LOGIN', '127.0.0.1', '2026-05-29 15:10:12'),
(10, 2, 'LOGOUT', '127.0.0.1', '2026-05-29 15:11:16'),
(11, 2, 'LOGOUT', '127.0.0.1', '2026-05-29 15:43:09'),
(12, 2, 'LOGOUT', '127.0.0.1', '2026-05-29 15:44:40'),
(13, 2, 'LOGIN', '127.0.0.1', '2026-05-29 15:54:06'),
(14, 2, 'LOGIN', '127.0.0.1', '2026-05-29 17:18:50'),
(15, 1, 'LOGIN', '127.0.0.1', '2026-05-29 17:19:54'),
(16, 1, 'LOGOUT', '127.0.0.1', '2026-05-29 18:40:46'),
(17, 2, 'LOGOUT', '127.0.0.1', '2026-05-29 18:43:35'),
(18, 2, 'LOGIN', '127.0.0.1', '2026-05-29 18:52:18'),
(19, 2, 'LOGOUT', '127.0.0.1', '2026-05-29 18:58:21'),
(20, 2, 'LOGIN', '127.0.0.1', '2026-05-29 18:58:45'),
(21, 2, 'LOGOUT', '127.0.0.1', '2026-06-01 19:51:14'),
(22, 2, 'LOGOUT', '127.0.0.1', '2026-06-04 14:05:49'),
(23, 2, 'LOGOUT', '127.0.0.1', '2026-06-04 14:07:54'),
(24, 1, 'LOGOUT', '127.0.0.1', '2026-06-04 14:28:29'),
(25, 1, 'LOGOUT', '127.0.0.1', '2026-06-04 14:29:04'),
(26, 1, 'LOGOUT', '127.0.0.1', '2026-06-04 14:49:08'),
(27, 1, 'LOGOUT', '127.0.0.1', '2026-06-04 14:50:14'),
(28, 1, 'LOGOUT', '127.0.0.1', '2026-06-04 14:50:53'),
(29, 1, 'LOGOUT', '127.0.0.1', '2026-06-04 14:51:29'),
(30, 1, 'LOGOUT', '127.0.0.1', '2026-06-04 14:52:35'),
(31, 1, 'LOGOUT', '127.0.0.1', '2026-06-04 14:54:48'),
(32, 2, 'LOGOUT', '127.0.0.1', '2026-06-04 16:44:39'),
(33, 2, 'LOGOUT', '127.0.0.1', '2026-06-04 16:45:17'),
(34, 2, 'LOGOUT', '127.0.0.1', '2026-06-04 19:14:16'),
(35, 2, 'LOGOUT', '127.0.0.1', '2026-06-04 19:15:02'),
(36, 2, 'LOGOUT', '127.0.0.1', '2026-06-04 19:15:36'),
(37, 2, 'LOGIN', '127.0.0.1', '2026-06-04 19:15:50'),
(38, 2, 'LOGOUT', '127.0.0.1', '2026-06-04 19:16:20'),
(39, 2, 'LOGIN', '127.0.0.1', '2026-06-05 15:14:27'),
(40, 2, 'LOGOUT', '127.0.0.1', '2026-06-05 15:15:10'),
(41, 1, 'LOGIN', '127.0.0.1', '2026-06-05 15:16:09'),
(42, 1, 'LOGOUT', '127.0.0.1', '2026-06-05 15:16:36'),
(43, 1, 'LOGOUT', '127.0.0.1', '2026-06-05 17:18:02'),
(44, 1, 'LOGIN', '127.0.0.1', '2026-06-05 17:31:50'),
(45, 1, 'LOGOUT', '127.0.0.1', '2026-06-05 17:32:11'),
(46, 1, 'LOGOUT', '127.0.0.1', '2026-06-05 17:33:12'),
(47, 1, 'LOGIN', '127.0.0.1', '2026-06-05 17:33:39'),
(48, 1, 'LOGOUT', '127.0.0.1', '2026-06-05 17:35:20'),
(49, 1, 'LOGIN', '127.0.0.1', '2026-06-05 17:38:39'),
(50, 1, 'LOGOUT', '127.0.0.1', '2026-06-05 17:40:22'),
(51, 1, 'LOGIN', '127.0.0.1', '2026-06-05 17:40:38'),
(52, 1, 'LOGOUT', '127.0.0.1', '2026-06-05 17:43:08'),
(53, 1, 'LOGIN', '127.0.0.1', '2026-06-05 17:57:21'),
(54, 1, 'LOGOUT', '127.0.0.1', '2026-06-05 17:58:32'),
(55, 1, 'LOGIN', '127.0.0.1', '2026-06-05 19:18:27'),
(56, 1, 'LOGOUT', '127.0.0.1', '2026-06-05 19:19:35'),
(57, 2, 'LOGIN', '127.0.0.1', '2026-06-05 19:20:08'),
(58, 2, 'LOGOUT', '127.0.0.1', '2026-06-05 19:23:32'),
(59, 2, 'LOGOUT', '127.0.0.1', '2026-06-05 19:40:42'),
(60, 2, 'LOGIN', '127.0.0.1', '2026-06-05 19:40:52'),
(61, 2, 'LOGOUT', '127.0.0.1', '2026-06-05 19:42:18'),
(62, 2, 'LOGOUT', '127.0.0.1', '2026-06-05 19:52:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"2cca5bfe-beba-45fb-89d0-975d941e6a78\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":2:{s:15:\\\"extensionNumero\\\";s:4:\\\"9016\\\";s:7:\\\"message\\\";s:51:\\\"La extensión 9016 (despacho) se encuentra OFFLINE.\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780934021,\"delay\":null}', 0, NULL, 1780934021, 1780934021),
(2, 'default', '{\"uuid\":\"0e95c280-b472-4aa5-9b7a-8e43c691a50c\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"9007\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 9007 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 17:49:14\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780940954,\"delay\":null}', 0, NULL, 1780940954, 1780940954),
(3, 'default', '{\"uuid\":\"bec8a061-443d-44b7-a5f2-8be14db4f246\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"9007\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 9007 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 17:58:02\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780941482,\"delay\":null}', 0, NULL, 1780941482, 1780941482),
(4, 'default', '{\"uuid\":\"335bc66f-1233-434b-9994-a59199136951\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"9007\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 9007 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 17:59:02\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780941542,\"delay\":null}', 0, NULL, 1780941542, 1780941542),
(5, 'default', '{\"uuid\":\"fb457718-1405-49a5-944a-1c2222f82ed2\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"9009\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 9009 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:03:02\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780941782,\"delay\":null}', 0, NULL, 1780941782, 1780941782),
(6, 'default', '{\"uuid\":\"265e77ce-e415-48c2-b9b9-e1f4c99a89ba\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"9007\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 9007 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:05:05\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780941905,\"delay\":null}', 0, NULL, 1780941905, 1780941905),
(7, 'default', '{\"uuid\":\"b4eb23c2-3063-4453-ba87-73dc4e982e3a\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"9016\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 9016 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:10:06\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780942206,\"delay\":null}', 0, NULL, 1780942206, 1780942206),
(8, 'default', '{\"uuid\":\"248220cd-1411-4be6-b67f-855a7d621956\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"9005\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 9005 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:13:06\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780942386,\"delay\":null}', 0, NULL, 1780942386, 1780942386),
(9, 'default', '{\"uuid\":\"8e2ce98c-75d6-4f9b-bb0c-3a82e8cc3a89\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"8001\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 8001 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:26:07\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943167,\"delay\":null}', 0, NULL, 1780943167, 1780943167),
(10, 'default', '{\"uuid\":\"00a63d36-f07a-4236-bf80-12ec0a089066\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"8002\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 8002 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:26:08\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943168,\"delay\":null}', 0, NULL, 1780943168, 1780943168),
(11, 'default', '{\"uuid\":\"03db533f-844a-410a-a330-5d3296183015\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"8003\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 8003 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:26:08\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943168,\"delay\":null}', 0, NULL, 1780943168, 1780943168),
(12, 'default', '{\"uuid\":\"93da2aff-a6ae-4f52-b465-8e50de261bb0\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"8005\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 8005 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:26:08\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943168,\"delay\":null}', 0, NULL, 1780943168, 1780943168),
(13, 'default', '{\"uuid\":\"a4917b3d-3ae8-4e9e-9362-532036530e14\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"8006\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 8006 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:26:08\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943168,\"delay\":null}', 0, NULL, 1780943168, 1780943168),
(14, 'default', '{\"uuid\":\"1b4bddd3-41bf-4a40-9466-265095ae1c20\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"9001\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 9001 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:26:08\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943168,\"delay\":null}', 0, NULL, 1780943168, 1780943168),
(15, 'default', '{\"uuid\":\"cbc664ab-3eb4-4f21-bac3-20ae40490681\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"9003\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 9003 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:26:09\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943169,\"delay\":null}', 0, NULL, 1780943169, 1780943169),
(16, 'default', '{\"uuid\":\"20dcf88d-c59f-4c46-9cc4-e4936e2c2934\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"9005\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 9005 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:26:09\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943169,\"delay\":null}', 0, NULL, 1780943169, 1780943169),
(17, 'default', '{\"uuid\":\"c99e6e68-4e07-473f-b256-7d9a62646e68\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"9007\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 9007 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:26:09\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943169,\"delay\":null}', 0, NULL, 1780943169, 1780943169),
(18, 'default', '{\"uuid\":\"2b19a392-b55a-43d7-8a43-61f1c67f1274\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"9010\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 9010 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:26:09\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943169,\"delay\":null}', 0, NULL, 1780943169, 1780943169),
(19, 'default', '{\"uuid\":\"b5f0ae50-23a5-4c60-83d1-019a6b4b78b3\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"9014\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 9014 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:26:10\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943170,\"delay\":null}', 0, NULL, 1780943170, 1780943170),
(20, 'default', '{\"uuid\":\"2c20adcd-9a2d-4a18-a4b0-be7e58f31ae3\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"8004\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 8004 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:27:08\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943228,\"delay\":null}', 0, NULL, 1780943228, 1780943228),
(21, 'default', '{\"uuid\":\"61e3e65f-ffd4-4ef8-a262-471c24ef185a\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"8007\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 8007 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:27:08\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943228,\"delay\":null}', 0, NULL, 1780943228, 1780943228),
(22, 'default', '{\"uuid\":\"f8d0b231-5670-44a2-a6c5-0723dfb13a2a\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"8008\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 8008 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:27:08\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943228,\"delay\":null}', 0, NULL, 1780943228, 1780943228),
(23, 'default', '{\"uuid\":\"91630a99-1bf5-4d4c-88b9-065d78b15ece\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"9002\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 9002 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:27:08\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943228,\"delay\":null}', 0, NULL, 1780943228, 1780943228),
(24, 'default', '{\"uuid\":\"185b08cf-1d06-4fff-be86-c7694ba846be\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"8001\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 8001 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:30:02\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943402,\"delay\":null}', 0, NULL, 1780943402, 1780943402),
(25, 'default', '{\"uuid\":\"3160e8c2-e0b0-4401-ab79-ee8a7dcaa9b4\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"8002\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 8002 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:30:02\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943402,\"delay\":null}', 0, NULL, 1780943402, 1780943402),
(26, 'default', '{\"uuid\":\"f82f7e6e-a934-4ee1-9776-13d18a4ba324\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"8003\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 8003 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:30:02\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943402,\"delay\":null}', 0, NULL, 1780943402, 1780943402),
(27, 'default', '{\"uuid\":\"ac9b0160-7654-4130-b764-04f2cb345c3f\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"8004\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 8004 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:30:02\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943402,\"delay\":null}', 0, NULL, 1780943402, 1780943402),
(28, 'default', '{\"uuid\":\"b6cf2f22-db6d-463b-b88e-644a42250c18\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"8006\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 8006 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:30:02\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943402,\"delay\":null}', 0, NULL, 1780943402, 1780943402),
(29, 'default', '{\"uuid\":\"2c28bfc0-efcf-4a38-bfe0-b5a7ade5e370\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"8007\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 8007 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:30:02\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943402,\"delay\":null}', 0, NULL, 1780943402, 1780943402),
(30, 'default', '{\"uuid\":\"fc365d3f-69d7-4a6c-94c8-d17de94e53d6\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"8008\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 8008 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:30:02\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943402,\"delay\":null}', 0, NULL, 1780943402, 1780943402),
(31, 'default', '{\"uuid\":\"59f38c76-9b90-4d20-957d-1382fe62d77a\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"9002\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 9002 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:30:02\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943402,\"delay\":null}', 0, NULL, 1780943402, 1780943402),
(32, 'default', '{\"uuid\":\"61de8bd4-5539-4331-8cdb-6e9b091a727a\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"9005\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 9005 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:30:02\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943402,\"delay\":null}', 0, NULL, 1780943402, 1780943402),
(33, 'default', '{\"uuid\":\"cf2ed46a-ef9b-41ef-b251-937b614d9203\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"9007\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 9007 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:30:02\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943402,\"delay\":null}', 0, NULL, 1780943402, 1780943402),
(34, 'default', '{\"uuid\":\"395ba10f-413b-4d64-8be1-c5e08688a065\",\"displayName\":\"App\\\\Events\\\\ExtensionOfflineAlert\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:32:\\\"App\\\\Events\\\\ExtensionOfflineAlert\\\":3:{s:9:\\\"extension\\\";s:4:\\\"9016\\\";s:11:\\\"descripcion\\\";s:86:\\\"La extensión 9016 ha pasado a estado OFFLINE. Verificar conectividad del dispositivo.\\\";s:9:\\\"timestamp\\\";s:19:\\\"2026-06-08 18:32:03\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1780943523,\"delay\":null}', 0, NULL, 1780943523, 1780943523);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs_api_receptor`
--

CREATE TABLE `logs_api_receptor` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payload_recibido` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload_recibido`)),
  `codigo_respuesta` int(11) NOT NULL,
  `mensaje_error` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `logs_api_receptor`
--

INSERT INTO `logs_api_receptor` (`id`, `payload_recibido`, `codigo_respuesta`, `mensaje_error`, `created_at`, `updated_at`) VALUES
(1, '{\"usuario\":\"jperez\",\"evento\":\"LOGIN\",\"extension\":\"8001\",\"cola\":\"0911\",\"nombre\":\"Juan P\\u00e9rez\"}', 200, NULL, '2026-05-20 18:43:26', '2026-05-20 18:43:26'),
(2, '{\"usuario\":\"jperez\",\"evento\":\"LOGIN\",\"extension\":\"8001\",\"cola\":\"0911\",\"nombre\":\"Juan P\\u00e9rez\"}', 200, NULL, '2026-05-22 15:00:40', '2026-05-22 15:00:40'),
(3, '{\"usuario\":\"OperadorPrueba\",\"nombre\":\"Juan Perez\",\"cola\":\"ventas\",\"evento\":\"LOGIN\",\"extension\":\"8004\"}', 200, NULL, '2026-05-25 17:45:17', '2026-05-25 17:45:17'),
(4, '{\"usuario\":\"OperadorPrueba\",\"evento\":\"LOGOUT\"}', 200, NULL, '2026-05-25 17:47:53', '2026-05-25 17:47:53'),
(5, '{\"usuario\":\"OperadorPrueba\",\"nombre\":\"Juan Perez\",\"cola\":\"ventas\",\"evento\":\"LOGIN\",\"extension\":\"8004\"}', 200, NULL, '2026-05-25 17:52:52', '2026-05-25 17:52:52'),
(6, '{\"usuario\":\"OperadorPrueba\",\"evento\":\"LOGOUT\"}', 200, NULL, '2026-05-25 18:00:58', '2026-05-25 18:00:58'),
(7, '{\"usuario\":\"OperadorPrueba\",\"nombre\":\"Gabriel Ramos\",\"cola\":\"ventas\",\"evento\":\"LOGIN\",\"extension\":\"8009\"}', 500, 'Error al comunicarse con FreePBX: Undefined array key \"success\"', '2026-05-28 16:21:56', '2026-05-28 16:22:01'),
(8, '{\"usuario\":\"OperadorPrueba\",\"evento\":\"LOGOUT\"}', 200, NULL, '2026-05-28 16:37:58', '2026-05-28 16:37:58'),
(9, '{\"usuario\":\"OperadorPrueba\",\"nombre\":\"Gabriel Ramos\",\"cola\":\"ventas\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 500, 'Error al comunicarse con FreePBX: Undefined array key \"success\"', '2026-05-28 19:44:36', '2026-05-28 19:44:36'),
(10, '{\"usuario\":\"OperadorPrueba\",\"nombre\":\"Juan Perez\",\"cola\":\"ventas\",\"evento\":\"LOGIN\",\"extension\":\"8004\"}', 500, 'Error al comunicarse con FreePBX: Undefined array key \"success\"', '2026-05-28 19:45:11', '2026-05-28 19:45:11'),
(11, '{\"usuario\":\"OperadorPrueba\",\"nombre\":\"Juan Perez\",\"cola\":\"ventas\",\"evento\":\"LOGIN\"}', 500, 'Error al comunicarse con FreePBX: Undefined array key \"success\"', '2026-05-28 19:55:13', '2026-05-28 19:55:13'),
(12, '{\"usuario\":\"OperadorPrueba\",\"nombre\":\"Juan Perez\",\"cola\":\"ventas\",\"evento\":\"LOGIN\"}', 500, 'Error al comunicarse con FreePBX: Undefined array key \"success\"', '2026-05-28 19:56:26', '2026-05-28 19:56:26'),
(13, '{\"usuario\":\"OperadorPrueba\",\"nombre\":\"Juan Perez\",\"cola\":\"ventas\",\"evento\":\"LOGIN\"}', 500, 'Error al comunicarse con FreePBX: Undefined array key \"success\"', '2026-05-28 19:58:37', '2026-05-28 19:58:38'),
(14, '{\"usuario\":\"OperadorPrueba\",\"nombre\":\"Juan Perez\",\"cola\":\"ventas\",\"evento\":\"LOGIN\"}', 500, 'Error al comunicarse con FreePBX: Undefined array key \"success\"', '2026-05-29 13:23:41', '2026-05-29 13:23:41'),
(15, '{\"usuario\":\"OperadorPrueba\",\"nombre\":\"Juan Perez\",\"cola\":\"ventas\",\"evento\":\"LOGIN\"}', 500, 'Error en central telefónica: Instancia no procesada', '2026-05-29 14:05:21', '2026-05-29 14:05:21'),
(16, '{\"usuario\":\"OperadorPrueba\",\"nombre\":\"Juan Perez\",\"cola\":\"ventas\",\"evento\":\"LOGIN\"}', 500, 'Error en central telefónica: Instancia no procesada', '2026-05-29 14:24:52', '2026-05-29 14:24:52'),
(17, '{\"usuario\":\"OperadorPrueba\",\"nombre\":\"Juan Perez\",\"cola\":\"ventas\",\"evento\":\"LOGIN\"}', 500, 'Error en central telefónica: Instancia no procesada', '2026-05-29 14:33:14', '2026-05-29 14:33:15'),
(18, '{\"usuario\":\"OperadorPrueba\",\"evento\":\"LOGOUT\"}', 200, NULL, '2026-05-29 14:34:08', '2026-05-29 14:34:08'),
(19, '{\"usuario\":\"OperadorPrueba\",\"nombre\":\"Juan Perez\",\"cola\":\"ventas\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 500, 'Error en central telefónica: Instancia no procesada', '2026-05-29 14:35:39', '2026-05-29 14:35:40'),
(20, '{\"usuario\":\"OperadorPrueba\",\"nombre\":\"Juan Perez\",\"cola\":\"ventas\",\"evento\":\"LOGIN\",\"extension\":\"8003\"}', 500, 'Error en central telefónica: Instancia no procesada', '2026-05-29 14:35:56', '2026-05-29 14:35:57'),
(21, '{\"usuario\":\"OperadorPrueba\",\"nombre\":\"Juan Perez\",\"cola\":\"ventas\",\"evento\":\"LOGIN\",\"extension\":\"8003\"}', 500, 'Error en central telefónica: Error reading', '2026-05-29 15:00:28', '2026-05-29 15:00:29'),
(22, '{\"usuario\":\"OperadorPrueba\",\"nombre\":\"Juan Perez\",\"cola\":\"ventas\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 500, 'Error en central telefónica: Error reading', '2026-05-29 15:00:44', '2026-05-29 15:00:45'),
(23, '{\"usuario\":\"OperadorPrueba\",\"nombre\":\"Juan Perez\",\"cola\":\"ventas\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 200, NULL, '2026-05-29 15:10:12', '2026-05-29 15:10:12'),
(24, '{\"usuario\":\"OperadorPrueba\",\"evento\":\"LOGOUT\"}', 200, NULL, '2026-05-29 15:11:14', '2026-05-29 15:11:14'),
(25, '{\"usuario\":\"OperadorPrueba\",\"evento\":\"LOGOUT\"}', 200, NULL, '2026-05-29 15:43:05', '2026-05-29 15:43:05'),
(26, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Juan Perez\",\"cola\":\"ventas\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 200, NULL, '2026-05-29 15:44:38', '2026-05-29 15:44:38'),
(27, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Juan Perez\",\"cola\":\"ventas\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 500, 'Error en central telefónica: Error reading', '2026-05-29 15:45:56', '2026-05-29 15:45:57'),
(28, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Juan Perez\",\"cola\":\"ventas\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 200, NULL, '2026-05-29 15:54:03', '2026-05-29 15:54:03'),
(29, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"ventas\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 200, NULL, '2026-05-29 17:18:47', '2026-05-29 17:18:47'),
(30, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"ventas\",\"evento\":\"LOGIN\",\"extension\":\"8009\"}', 200, NULL, '2026-05-29 17:19:51', '2026-05-29 17:19:51'),
(31, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"ventas\",\"evento\":\"LOGOUT\",\"extension\":\"8009\"}', 200, NULL, '2026-05-29 18:40:44', '2026-05-29 18:40:44'),
(32, '{\"usuario\":\"OperadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"ventas\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 200, NULL, '2026-05-29 18:43:34', '2026-05-29 18:43:34'),
(33, '{\"usuario\":\"OperadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"ventas\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 200, NULL, '2026-05-29 18:52:16', '2026-05-29 18:52:16'),
(34, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 200, NULL, '2026-05-29 18:58:20', '2026-05-29 18:58:20'),
(35, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 200, NULL, '2026-05-29 18:58:43', '2026-05-29 18:58:43'),
(36, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 200, NULL, '2026-06-01 19:51:13', '2026-06-01 19:51:13'),
(37, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 500, 'Error en central telefónica: Error reading', '2026-06-01 19:51:47', '2026-06-01 19:51:48'),
(38, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 500, 'Fallo en la operación AMI (LOGIN): Error connecting to ami: Se produjo un error durante el intento de conexión ya que la parte conectada no respondió adecuadamente tras un periodo de tiempo, o bien se produjo un error en la conexión establecida ya que el host conectado no ha podido responder', '2026-06-04 13:51:06', '2026-06-04 13:51:11'),
(39, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 500, 'Fallo en la operación AMI (LOGIN): implode(): Argument #2 ($array) must be of type ?array, string given', '2026-06-04 14:04:57', '2026-06-04 14:04:57'),
(40, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 500, 'Fallo en la operación AMI (LOGOUT): implode(): Argument #2 ($array) must be of type ?array, string given', '2026-06-04 14:05:48', '2026-06-04 14:05:48'),
(41, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 500, 'Fallo en la operación AMI (LOGIN): implode(): Argument #2 ($array) must be of type ?array, string given', '2026-06-04 14:07:06', '2026-06-04 14:07:06'),
(42, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 500, 'Fallo en la operación AMI (LOGOUT): implode(): Argument #2 ($array) must be of type ?array, string given', '2026-06-04 14:07:51', '2026-06-04 14:07:51'),
(43, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGIN\",\"extension\":\"8009\"}', 500, 'Fallo en la operación AMI (LOGIN): implode(): Argument #2 ($array) must be of type ?array, string given', '2026-06-04 14:27:48', '2026-06-04 14:27:48'),
(44, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8009\"}', 500, 'Fallo en la operación AMI (LOGOUT): implode(): Argument #2 ($array) must be of type ?array, string given', '2026-06-04 14:28:27', '2026-06-04 14:28:27'),
(45, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8009\"}', 500, 'Fallo en la operación AMI (LOGOUT): implode(): Argument #2 ($array) must be of type ?array, string given', '2026-06-04 14:29:02', '2026-06-04 14:29:03'),
(46, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGIN\",\"extension\":\"8009\"}', 500, 'Fallo en la operación AMI (LOGIN): implode(): Argument #2 ($array) must be of type ?array, string given', '2026-06-04 14:47:39', '2026-06-04 14:47:39'),
(47, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGIN\",\"extension\":\"8009\"}', 500, 'Fallo en la operación AMI (LOGIN): implode(): Argument #2 ($array) must be of type ?array, string given', '2026-06-04 14:48:28', '2026-06-04 14:48:28'),
(48, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8009\"}', 500, 'Fallo en la operación AMI (LOGOUT): implode(): Argument #2 ($array) must be of type ?array, string given', '2026-06-04 14:49:07', '2026-06-04 14:49:07'),
(49, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8009\"}', 500, 'Fallo en la operación AMI (LOGOUT): implode(): Argument #2 ($array) must be of type ?array, string given', '2026-06-04 14:50:12', '2026-06-04 14:50:12'),
(50, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8009\"}', 500, 'Fallo en la operación AMI (LOGOUT): implode(): Argument #2 ($array) must be of type ?array, string given', '2026-06-04 14:50:52', '2026-06-04 14:50:52'),
(51, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8009\"}', 500, 'Fallo en la operación AMI (LOGOUT): implode(): Argument #2 ($array) must be of type ?array, string given', '2026-06-04 14:51:29', '2026-06-04 14:51:29'),
(52, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8009\"}', 500, 'Fallo en la operación AMI (LOGOUT): implode(): Argument #2 ($array) must be of type ?array, string given', '2026-06-04 14:52:35', '2026-06-04 14:52:35'),
(53, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGIN\",\"extension\":\"8009\"}', 500, 'Fallo en la operación AMI (LOGIN): implode(): Argument #2 ($array) must be of type ?array, string given', '2026-06-04 14:53:33', '2026-06-04 14:53:33'),
(54, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8009\"}', 500, 'Fallo en la operación AMI (LOGOUT): implode(): Argument #2 ($array) must be of type ?array, string given', '2026-06-04 14:54:48', '2026-06-04 14:54:48'),
(55, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 500, 'Fallo en la operación AMI (LOGIN): implode(): Argument #2 ($array) must be of type ?array, string given', '2026-06-04 16:43:17', '2026-06-04 16:43:17'),
(56, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 500, 'Fallo en la operación AMI (LOGOUT): implode(): Argument #2 ($array) must be of type ?array, string given', '2026-06-04 16:44:37', '2026-06-04 16:44:37'),
(57, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 500, 'Fallo en la operación AMI (LOGOUT): implode(): Argument #2 ($array) must be of type ?array, string given', '2026-06-04 16:45:16', '2026-06-04 16:45:16'),
(58, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 500, 'Fallo en la operación AMI (LOGOUT): Unable to remove interface: Not there', '2026-06-04 19:14:15', '2026-06-04 19:14:16'),
(59, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 500, 'Fallo en la operación AMI (LOGOUT): Unable to remove interface: Not there', '2026-06-04 19:14:59', '2026-06-04 19:14:59'),
(60, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 500, 'Fallo en la operación AMI (LOGOUT): Unable to remove interface: Not there', '2026-06-04 19:15:34', '2026-06-04 19:15:34'),
(61, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 200, NULL, '2026-06-04 19:15:49', '2026-06-04 19:15:49'),
(62, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 200, NULL, '2026-06-04 19:16:19', '2026-06-04 19:16:19'),
(63, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGIN\",\"extension\":\"8009\"}', 200, NULL, '2026-06-05 15:14:25', '2026-06-05 15:14:25'),
(64, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8009\"}', 200, NULL, '2026-06-05 15:15:06', '2026-06-05 15:15:06'),
(65, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 200, NULL, '2026-06-05 15:16:08', '2026-06-05 15:16:08'),
(66, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 200, NULL, '2026-06-05 15:16:34', '2026-06-05 15:16:34'),
(67, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 500, 'Fallo en la operación AMI (LOGOUT): Unable to remove interface: Not there', '2026-06-05 17:17:57', '2026-06-05 17:18:01'),
(68, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 200, NULL, '2026-06-05 17:31:48', '2026-06-05 17:31:48'),
(69, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 200, NULL, '2026-06-05 17:32:09', '2026-06-05 17:32:09'),
(70, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 500, 'Fallo en la operación AMI (LOGOUT): Unable to remove interface: Not there', '2026-06-05 17:33:10', '2026-06-05 17:33:10'),
(71, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 200, NULL, '2026-06-05 17:33:36', '2026-06-05 17:33:36'),
(72, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 200, NULL, '2026-06-05 17:35:17', '2026-06-05 17:35:17'),
(73, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 200, NULL, '2026-06-05 17:38:37', '2026-06-05 17:38:37'),
(74, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 200, NULL, '2026-06-05 17:40:20', '2026-06-05 17:40:20'),
(75, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 200, NULL, '2026-06-05 17:40:34', '2026-06-05 17:40:34'),
(76, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 200, NULL, '2026-06-05 17:43:06', '2026-06-05 17:43:06'),
(77, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 200, NULL, '2026-06-05 17:57:20', '2026-06-05 17:57:20'),
(78, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 200, NULL, '2026-06-05 17:58:31', '2026-06-05 17:58:31'),
(79, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 200, NULL, '2026-06-05 19:18:25', '2026-06-05 19:18:25'),
(80, '{\"usuario\":\"jperez\",\"nombre\":\"Juan Perez\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 200, NULL, '2026-06-05 19:19:33', '2026-06-05 19:19:33'),
(81, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 200, NULL, '2026-06-05 19:20:05', '2026-06-05 19:20:05'),
(82, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 200, NULL, '2026-06-05 19:23:30', '2026-06-05 19:23:30'),
(83, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 500, 'Fallo en la operación AMI (LOGOUT): Unable to remove interface: Not there', '2026-06-05 19:40:38', '2026-06-05 19:40:39'),
(84, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGIN\",\"extension\":\"8010\"}', 200, NULL, '2026-06-05 19:40:50', '2026-06-05 19:40:50'),
(85, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 200, NULL, '2026-06-05 19:42:15', '2026-06-05 19:42:15'),
(86, '{\"usuario\":\"operadorPrueba\",\"nombre\":\"Rosme Zabaleta\",\"cola\":\"0911\",\"evento\":\"LOGOUT\",\"extension\":\"8010\"}', 500, 'Fallo en la operación AMI (LOGOUT): Unable to remove interface: Not there', '2026-06-05 19:52:00', '2026-06-05 19:52:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_28_133505_create_operadores_config_table', 1),
(5, '2026_04_28_133511_create_historial_accesos_table', 1),
(6, '2026_04_28_133512_create_bitacora_ami_table', 1),
(7, '2026_04_28_133512_create_logs_api_receptor_table', 1),
(8, '2026_05_07_000001_update_operadores_config_ficha_username', 1),
(9, '2026_05_16_000001_add_role_to_users_table', 2),
(10, '2026_05_16_000002_add_dry_run_to_bitacora_ami_status', 2),
(11, '2026_05_20_192848_create_alertas_productividad_table', 3),
(12, '2026_05_20_192957_create_operador_sessions_table', 3),
(13, '2026_05_20_193103_add_monitoring_fields_to_bitacora_ami_table', 3),
(14, '2026_05_21_165701_create_extensions_table', 4),
(15, '2026_05_22_154600_add_freepbx_fields_to_extensions_table', 5),
(16, '2026_05_25_153925_add_grupo_horario_to_operadores_config_table', 6),
(18, '2026_05_28_160032_add_grupo_horario_to_extensions_table', 7),
(19, '2026_05_29_145300_add_role_to_users_table', 7),
(20, '2026_05_29_153102_add_cedula_to_users_table', 8),
(21, '2024_01_01_000001_create_report_sessions_table', 9),
(22, '2026_06_05_000001_add_last_ping_at_to_operador_sessions', 9),
(23, '2026_06_05_000002_create_api_error_logs_table', 9),
(24, '2026_06_08_000001_add_categoria_to_extensions_table', 10),
(25, '2026_06_08_000002_add_audit_fields_to_users_table', 10),
(26, '2026_06_08_000003_add_horario_fields_to_operadores_config', 10),
(27, '2026_06_08_000004_create_extension_operador_table', 11),
(28, '2026_06_08_000005_seed_extension_categories_and_status', 11),
(29, '2026_06_08_162826_add_is_active_to_users_and_extensions', 12),
(30, '2026_06_08_163627_add_motivo_inactividad_to_extensions_table', 13),
(32, '2026_06_08_185510_create_extension_operador_table', 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operadores_config`
--

CREATE TABLE `operadores_config` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ficha_username` varchar(50) NOT NULL,
  `nombre_operador` varchar(255) NOT NULL,
  `extension` varchar(10) NOT NULL,
  `queue_name` varchar(20) NOT NULL DEFAULT '0911',
  `grupo_horario` tinyint(3) UNSIGNED DEFAULT NULL COMMENT '1 = Comida 12pm/7pm, Sueño 10pm-2am | 2 = Comida 1pm/8pm, Sueño 2am-6am',
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `horario_turno` varchar(255) DEFAULT NULL,
  `horario_comida` varchar(255) DEFAULT NULL,
  `horario_descanso` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `operadores_config`
--

INSERT INTO `operadores_config` (`id`, `ficha_username`, `nombre_operador`, `extension`, `queue_name`, `grupo_horario`, `is_active`, `created_at`, `updated_at`, `horario_turno`, `horario_comida`, `horario_descanso`) VALUES
(1, 'jperez', 'Juan Perez', '0000', '0911', 1, 0, '2026-05-20 18:43:28', '2026-06-05 19:19:33', NULL, NULL, NULL),
(2, 'OperadorPrueba', 'Rosme Zabaleta', '8010', '0911', 2, 0, '2026-05-25 17:45:17', '2026-06-05 19:42:15', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operador_extension`
--

CREATE TABLE `operador_extension` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `extension_id` bigint(20) UNSIGNED NOT NULL,
  `operador_config_id` bigint(20) UNSIGNED NOT NULL,
  `asignado_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operador_sessions`
--

CREATE TABLE `operador_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `operador_config_id` bigint(20) UNSIGNED NOT NULL,
  `extension` varchar(10) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `hora_inicio_esperada` time DEFAULT NULL,
  `hora_fin_esperada` time DEFAULT NULL,
  `estado_actual` varchar(255) NOT NULL DEFAULT 'LOGIN',
  `last_ping_at` timestamp NULL DEFAULT NULL,
  `total_active_seconds` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `operador_sessions`
--

INSERT INTO `operador_sessions` (`id`, `operador_config_id`, `extension`, `fecha_inicio`, `fecha_fin`, `hora_inicio_esperada`, `hora_fin_esperada`, `estado_actual`, `last_ping_at`, `total_active_seconds`, `created_at`, `updated_at`) VALUES
(1, 1, '8001', '2026-05-22 15:00:41', '2026-06-04 14:28:27', NULL, NULL, 'LOGOUT', NULL, 0, '2026-05-22 15:00:41', '2026-06-04 14:28:27'),
(2, 2, '8004', '2026-05-25 17:45:18', '2026-05-25 17:47:54', NULL, NULL, 'LOGOUT', NULL, 0, '2026-05-25 17:45:18', '2026-05-25 17:47:54'),
(3, 2, '8004', '2026-05-25 17:52:53', '2026-05-25 18:01:03', NULL, NULL, 'LOGOUT', NULL, 0, '2026-05-25 17:52:53', '2026-05-25 18:01:03'),
(4, 2, '8010', '2026-05-29 15:10:12', '2026-05-29 15:11:15', NULL, NULL, 'LOGOUT', NULL, 0, '2026-05-29 15:10:12', '2026-05-29 15:11:15'),
(5, 2, '8010', '2026-05-29 15:54:04', '2026-06-04 14:05:48', NULL, NULL, 'LOGOUT', NULL, 0, '2026-05-29 15:54:04', '2026-06-04 14:05:48'),
(6, 2, '8010', '2026-05-29 17:18:48', '2026-05-29 18:43:35', NULL, NULL, 'LOGOUT', NULL, 0, '2026-05-29 17:18:48', '2026-05-29 18:43:35'),
(7, 1, '8009', '2026-05-29 17:19:52', '2026-05-29 18:40:45', NULL, NULL, 'LOGOUT', NULL, 0, '2026-05-29 17:19:52', '2026-05-29 18:40:45'),
(8, 2, '8010', '2026-05-29 18:52:17', '2026-05-29 18:58:21', NULL, NULL, 'LOGOUT', NULL, 0, '2026-05-29 18:52:17', '2026-05-29 18:58:21'),
(9, 2, '8010', '2026-05-29 18:58:44', '2026-06-01 19:51:14', NULL, NULL, 'LOGOUT', NULL, 0, '2026-05-29 18:58:44', '2026-06-01 19:51:14'),
(10, 2, '8010', '2026-06-04 19:15:49', '2026-06-04 19:16:19', NULL, NULL, 'LOGOUT', NULL, 0, '2026-06-04 19:15:49', '2026-06-04 19:16:19'),
(11, 2, '8009', '2026-06-05 15:14:25', '2026-06-05 15:15:06', NULL, NULL, 'LOGOUT', NULL, 0, '2026-06-05 15:14:25', '2026-06-05 15:15:06'),
(12, 1, '8010', '2026-06-05 15:16:08', '2026-06-05 15:16:35', NULL, NULL, 'LOGOUT', NULL, 0, '2026-06-05 15:16:08', '2026-06-05 15:16:35'),
(13, 1, '8010', '2026-06-05 17:31:49', '2026-06-05 17:32:09', NULL, NULL, 'LOGOUT', NULL, 0, '2026-06-05 17:31:49', '2026-06-05 17:32:09'),
(14, 1, '8010', '2026-06-05 17:33:36', '2026-06-05 17:35:17', NULL, NULL, 'LOGOUT', NULL, 0, '2026-06-05 17:33:36', '2026-06-05 17:35:17'),
(15, 1, '8010', '2026-06-05 17:38:37', '2026-06-05 17:40:20', NULL, NULL, 'LOGOUT', NULL, 0, '2026-06-05 17:38:37', '2026-06-05 17:40:20'),
(16, 1, '8010', '2026-06-05 17:40:34', '2026-06-05 17:43:06', NULL, NULL, 'LOGOUT', NULL, 0, '2026-06-05 17:40:34', '2026-06-05 17:43:06'),
(17, 1, '8010', '2026-06-05 17:57:20', '2026-06-05 17:58:31', NULL, NULL, 'LOGOUT', NULL, 0, '2026-06-05 17:57:20', '2026-06-05 17:58:31'),
(18, 1, '8010', '2026-06-05 19:18:25', '2026-06-05 19:19:33', NULL, NULL, 'LOGOUT', NULL, 0, '2026-06-05 19:18:25', '2026-06-05 19:19:33'),
(19, 2, '8010', '2026-06-05 19:20:06', '2026-06-05 19:23:30', NULL, NULL, 'LOGOUT', NULL, 0, '2026-06-05 19:20:06', '2026-06-05 19:23:30'),
(20, 2, '8010', '2026-06-05 19:40:50', '2026-06-05 19:42:15', NULL, NULL, 'LOGOUT', NULL, 0, '2026-06-05 19:40:50', '2026-06-05 19:42:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `report_sessions`
--

CREATE TABLE `report_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `operator_id` bigint(20) UNSIGNED NOT NULL,
  `session_start` timestamp NOT NULL,
  `session_end` timestamp NULL DEFAULT NULL,
  `state` enum('ACTIVE','BREAK','BATH','INACTIVE') NOT NULL DEFAULT 'INACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('VV7wgrFiX6Xip5RMume7Mo2m9qWAZ5piZxQReagL', 1, '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRUhYWDdRU2ZrQjN4ejQ5RVllSVJHNHdTNWVCZHZqQkdrckhuVzNVZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODA4MC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1781102634);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `cedula` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(45) DEFAULT NULL,
  `last_logout_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `cedula`, `email`, `role`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `last_login_at`, `last_login_ip`, `last_logout_at`) VALUES
(1, 'sharon', NULL, 'sharon.zabaleta318@gmail.com', 'admin', 1, NULL, '$2y$12$NgxqpncUubT9tbHA3JTN..05Z3cAlTDFU7V4U.z1E1xvIg.Bfi9Ru', NULL, '2026-05-07 14:55:26', '2026-06-10 14:31:29', '2026-06-10 14:31:29', '172.19.0.1', '2026-06-09 17:09:52'),
(3, 'Rosario Blanco', 'V-30032735', 'yeto@gmail.com', 'user', 1, NULL, '$2y$12$xKD3Kzy/8jytyY9LP8XiveV45IRBV4nMMk3gd.AMUK3h4fXbBYpda', NULL, '2026-05-29 15:41:22', '2026-06-09 17:11:00', '2026-06-09 17:10:09', '127.0.0.1', '2026-06-09 17:11:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alertas_productividad`
--
ALTER TABLE `alertas_productividad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alertas_productividad_operador_config_id_foreign` (`operador_config_id`);

--
-- Indices de la tabla `api_error_logs`
--
ALTER TABLE `api_error_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `api_error_logs_timestamp_index` (`timestamp`),
  ADD KEY `api_error_logs_resuelto_index` (`resuelto`);

--
-- Indices de la tabla `bitacora_ami`
--
ALTER TABLE `bitacora_ami`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indices de la tabla `extensions`
--
ALTER TABLE `extensions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `extensions_numero_unique` (`numero`);

--
-- Indices de la tabla `ext_operador`
--
ALTER TABLE `ext_operador`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ext_operador_extension_id_operador_config_id_unique` (`extension_id`,`operador_config_id`);

--
-- Indices de la tabla `ext_operador_pivote`
--
ALTER TABLE `ext_operador_pivote`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ext_operador_pivote_extension_id_operador_config_id_unique` (`extension_id`,`operador_config_id`),
  ADD KEY `ext_operador_pivote_extension_id_index` (`extension_id`),
  ADD KEY `ext_operador_pivote_operador_config_id_index` (`operador_config_id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `historial_accesos`
--
ALTER TABLE `historial_accesos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `historial_accesos_operador_config_id_foreign` (`operador_config_id`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `logs_api_receptor`
--
ALTER TABLE `logs_api_receptor`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `operadores_config`
--
ALTER TABLE `operadores_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `operadores_config_ficha_username_unique` (`ficha_username`);

--
-- Indices de la tabla `operador_extension`
--
ALTER TABLE `operador_extension`
  ADD PRIMARY KEY (`id`),
  ADD KEY `operador_extension_extension_id_foreign` (`extension_id`);

--
-- Indices de la tabla `operador_sessions`
--
ALTER TABLE `operador_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `operador_sessions_operador_config_id_foreign` (`operador_config_id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `report_sessions`
--
ALTER TABLE `report_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_sessions_operator_id_foreign` (`operator_id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_cedula_unique` (`cedula`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alertas_productividad`
--
ALTER TABLE `alertas_productividad`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `api_error_logs`
--
ALTER TABLE `api_error_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bitacora_ami`
--
ALTER TABLE `bitacora_ami`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT de la tabla `extensions`
--
ALTER TABLE `extensions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `ext_operador`
--
ALTER TABLE `ext_operador`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ext_operador_pivote`
--
ALTER TABLE `ext_operador_pivote`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_accesos`
--
ALTER TABLE `historial_accesos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `logs_api_receptor`
--
ALTER TABLE `logs_api_receptor`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `operadores_config`
--
ALTER TABLE `operadores_config`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `operador_extension`
--
ALTER TABLE `operador_extension`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `operador_sessions`
--
ALTER TABLE `operador_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `report_sessions`
--
ALTER TABLE `report_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alertas_productividad`
--
ALTER TABLE `alertas_productividad`
  ADD CONSTRAINT `alertas_productividad_operador_config_id_foreign` FOREIGN KEY (`operador_config_id`) REFERENCES `operadores_config` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `historial_accesos`
--
ALTER TABLE `historial_accesos`
  ADD CONSTRAINT `historial_accesos_operador_config_id_foreign` FOREIGN KEY (`operador_config_id`) REFERENCES `operadores_config` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `operador_extension`
--
ALTER TABLE `operador_extension`
  ADD CONSTRAINT `operador_extension_extension_id_foreign` FOREIGN KEY (`extension_id`) REFERENCES `extensions` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `operador_sessions`
--
ALTER TABLE `operador_sessions`
  ADD CONSTRAINT `operador_sessions_operador_config_id_foreign` FOREIGN KEY (`operador_config_id`) REFERENCES `operadores_config` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `report_sessions`
--
ALTER TABLE `report_sessions`
  ADD CONSTRAINT `report_sessions_operator_id_foreign` FOREIGN KEY (`operator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
