-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 24, 2025 at 06:54 AM
-- Server version: 5.7.23-0ubuntu0.16.04.1
-- PHP Version: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hive_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `id_attribute` smallint(5) UNSIGNED NOT NULL,
  `id_attribute_type` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `id_attribute_html` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `alias` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`id_attribute`, `id_attribute_type`, `id_attribute_html`, `alias`) VALUES
(2, 2, 1, 'Color'),
(19, 3, 1, 'Impresión frontal'),
(20, 1, 1, 'Sizes');

-- --------------------------------------------------------

--
-- Table structure for table `attributes_language`
--

CREATE TABLE `attributes_language` (
  `id_attribute` smallint(5) UNSIGNED NOT NULL,
  `id_language` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attributes_language`
--

INSERT INTO `attributes_language` (`id_attribute`, `id_language`, `name`, `description`) VALUES
(2, 1, 'Color', 'Product color'),
(2, 2, 'Color', 'Color del producto'),
(19, 1, 'Printing', 'Print on the front'),
(19, 2, 'Impresión', 'Impresión en la parte delantera'),
(20, 1, 'Size', 'Standard sizes'),
(20, 2, 'Tamaño', 'Tamaños estandar');

-- --------------------------------------------------------

--
-- Table structure for table `attributes_value`
--

CREATE TABLE `attributes_value` (
  `id_attribute_value` smallint(5) UNSIGNED NOT NULL,
  `id_attribute` smallint(5) UNSIGNED NOT NULL,
  `value` varchar(255) NOT NULL DEFAULT '',
  `priority` tinyint(3) UNSIGNED NOT NULL,
  `alias` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attributes_value`
--

INSERT INTO `attributes_value` (`id_attribute_value`, `id_attribute`, `value`, `priority`, `alias`) VALUES
(36, 19, '/img/attributes/attr-19-36.png', 2, 'Chicas'),
(37, 19, '/img/attributes/attr-19-37.png', 1, 'Chico'),
(45, 2, '#ffffff', 1, 'White'),
(46, 2, '#000000', 2, 'Black'),
(47, 2, '#89d6ff', 3, 'Light blue'),
(48, 2, '#e5f6ff', 4, 'Cyan'),
(49, 2, '#aedb7b', 5, 'Light green'),
(50, 20, '', 1, 'XXS'),
(51, 20, '', 2, 'XS'),
(52, 20, '', 3, 'S'),
(53, 20, '', 4, 'M'),
(54, 20, '', 5, 'L'),
(55, 20, '', 6, 'XL'),
(56, 20, '', 7, 'XXL'),
(59, 19, '/img/attributes/attr-19-59.jpg', 3, 'Casco');

-- --------------------------------------------------------

--
-- Table structure for table `attributes_value_language`
--

CREATE TABLE `attributes_value_language` (
  `id_attribute_value` smallint(5) UNSIGNED NOT NULL,
  `id_language` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attributes_value_language`
--

INSERT INTO `attributes_value_language` (`id_attribute_value`, `id_language`, `name`, `description`) VALUES
(36, 1, 'Chica', 'Picture of a girl'),
(36, 2, 'Chica', 'Imagen de una chica'),
(37, 1, 'Chico', 'Picture of a boy'),
(37, 2, 'Chico', 'Imagen de un chico'),
(45, 1, 'White', 'White'),
(45, 2, 'Blanco', ''),
(46, 1, 'Black', 'Black color'),
(46, 2, 'Negro', 'Color negro'),
(47, 1, 'Light blue', ''),
(47, 2, 'Azul claro', ''),
(48, 1, 'Cyan', ''),
(48, 2, 'Cian', ''),
(49, 1, 'Light green', ''),
(49, 2, 'Verde claro', ''),
(50, 1, 'XXS', ''),
(50, 2, 'XXS', ''),
(51, 1, 'XS', ''),
(51, 2, 'XS', ''),
(52, 1, 'S', ''),
(52, 2, 'S', ''),
(53, 1, 'M', ''),
(53, 2, 'M', ''),
(54, 1, 'L', ''),
(54, 2, 'L', ''),
(55, 1, 'XL', ''),
(55, 2, 'XL', ''),
(56, 1, 'XXL', ''),
(56, 2, 'XXL', ''),
(59, 1, 'Casco', ''),
(59, 2, 'Casco', '');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_cart` varchar(30) NOT NULL,
  `id_user` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `id_user_address` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `id_user_billing_address` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `id_shipping_method` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `id_payment_method` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `comments` varchar(255) NOT NULL DEFAULT '',
  `email_send_date` date DEFAULT NULL,
  `email_send_count` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `insert_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `id_cart`, `id_user`, `id_user_address`, `id_user_billing_address`, `id_shipping_method`, `id_payment_method`, `comments`, `email_send_date`, `email_send_count`, `insert_date`) VALUES
(1, '651ad3a1003ed', 1, 0, 0, 0, 0, '', NULL, 0, '2023-10-02 10:28:49'),
(2, '651b26665e101', 1, 0, 0, 0, 0, '', NULL, 0, '2023-10-02 16:21:58'),
(3, '653252b2964d9', 1, 0, 0, 0, 0, '', NULL, 0, '2023-10-20 06:13:06'),
(4, '65d28178aae29', 0, 0, 0, 0, 0, '', NULL, 0, '2024-02-18 17:15:20'),
(5, '65e1fe1e811ac', 1, 0, 0, 0, 0, '', NULL, 0, '2024-03-01 11:11:10'),
(6, '65fd7957b7383', 1, 0, 0, 0, 0, '', NULL, 0, '2024-03-22 08:28:07'),
(7, '66327ae67778d', 1, 0, 0, 0, 0, '', NULL, 0, '2024-05-01 13:24:54'),
(8, '66eff9d713d4f', 1, 0, 0, 0, 0, '', NULL, 0, '2024-09-22 07:04:55'),
(9, '66f1401936965', 1, 0, 0, 0, 0, '', NULL, 0, '2024-09-23 06:16:57'),
(10, '678f92a4d476e', 1, 0, 0, 0, 0, '', NULL, 0, '2025-01-21 07:27:16'),
(11, '67a1360b06783', 1, 0, 0, 0, 0, '', NULL, 0, '2025-02-03 16:32:59');

-- --------------------------------------------------------

--
-- Table structure for table `carts_codes`
--

CREATE TABLE `carts_codes` (
  `id_cart_code` mediumint(8) UNSIGNED NOT NULL,
  `id_cart` varchar(30) NOT NULL,
  `id_code` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `carts_codes`
--

INSERT INTO `carts_codes` (`id_cart_code`, `id_cart`, `id_code`) VALUES
(3, '678f92a4d476e', 2);

-- --------------------------------------------------------

--
-- Table structure for table `carts_products`
--

CREATE TABLE `carts_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_cart` varchar(30) NOT NULL,
  `id_product` mediumint(8) UNSIGNED NOT NULL,
  `id_product_related` int(10) UNSIGNED NOT NULL,
  `id_category` smallint(5) UNSIGNED NOT NULL,
  `amount` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `carts_products`
--

INSERT INTO `carts_products` (`id`, `id_cart`, `id_product`, `id_product_related`, `id_category`, `amount`) VALUES
(1, '651ad3a1003ed', 10, 47, 18, 3),
(3, '651ad3a1003ed', 10, 36, 18, 3),
(4, '651ad3a1003ed', 10, 33, 18, 7),
(5, '651b26665e101', 10, 40, 18, 1),
(6, '651b26665e101', 10, 37, 18, 1),
(7, '651b26665e101', 10, 34, 18, 1),
(8, '653252b2964d9', 10, 40, 18, 1),
(9, '653252b2964d9', 10, 36, 18, 1),
(10, '653252b2964d9', 10, 34, 18, 2),
(11, '651ad3a1003ed', 9, 42, 19, 1),
(12, '65e1fe1e811ac', 10, 40, 18, 1),
(13, '65fd7957b7383', 10, 40, 18, 2),
(14, '65fd7957b7383', 10, 37, 18, 1),
(15, '66327ae67778d', 10, 40, 18, 1),
(16, '65fd7957b7383', 9, 42, 19, 1),
(17, '66eff9d713d4f', 10, 40, 18, 9),
(18, '66f1401936965', 10, 40, 18, 2),
(19, '66f1401936965', 10, 32, 18, 1),
(20, '66f1401936965', 10, 33, 18, 1),
(21, '66eff9d713d4f', 10, 33, 18, 1),
(22, '66eff9d713d4f', 10, 37, 18, 1),
(24, '678f92a4d476e', 10, 32, 18, 1),
(25, '678f92a4d476e', 10, 37, 18, 1),
(26, '678f92a4d476e', 9, 50, 19, 1),
(27, '67a1360b06783', 10, 34, 18, 1),
(29, '67a1360b06783', 9, 50, 19, 2);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id_category` smallint(5) UNSIGNED NOT NULL,
  `id_parent` smallint(5) UNSIGNED NOT NULL DEFAULT '1',
  `id_category_view` smallint(5) UNSIGNED NOT NULL DEFAULT '1',
  `visits` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `alias` varchar(20) NOT NULL,
  `id_state` tinyint(3) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id_category`, `id_parent`, `id_category_view`, `visits`, `alias`, `id_state`) VALUES
(1, 0, 1, 10, 'Main', 2),
(13, 1, 1, 1, 'Men', 2),
(14, 1, 1, 0, 'Women', 2),
(17, 18, 1, 1, 'Helmets', 2),
(18, 1, 1, 1, 'Equipment', 2),
(19, 13, 1, 9, 'Sweatshirt', 2),
(20, 18, 1, 35, 'Air Jordan mujer', 2);

-- --------------------------------------------------------

--
-- Table structure for table `categories_custom_routes`
--

CREATE TABLE `categories_custom_routes` (
  `id_category_custom_route` mediumint(8) UNSIGNED NOT NULL,
  `id_category` smallint(5) UNSIGNED NOT NULL,
  `id_language` tinyint(3) UNSIGNED NOT NULL,
  `route` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `categories_language`
--

CREATE TABLE `categories_language` (
  `id_category` smallint(5) UNSIGNED NOT NULL,
  `id_language` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `slug` varchar(255) NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` varchar(999) NOT NULL,
  `meta_keywords` varchar(999) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories_language`
--

INSERT INTO `categories_language` (`id_category`, `id_language`, `name`, `description`, `slug`, `meta_title`, `meta_description`, `meta_keywords`) VALUES
(13, 1, 'Men', '', 'men', 'Men', '', ''),
(13, 2, '', '', 'hombre', '', '', ''),
(14, 1, 'Women', '', 'women', 'Women', '', ''),
(14, 2, '', '', 'mujer', '', '', ''),
(17, 1, 'Helmets', '', 'helmets', 'Helmets', '', ''),
(17, 2, 'Cascos', '', 'cascos', 'Cascos', '', ''),
(18, 1, 'Equipment', '', 'equipment', 'Equipment', '', ''),
(18, 2, 'Equipamiento', '', 'equipamiento', 'Equipamiento', '', ''),
(19, 1, 'Sweatshirt', '', 'sweatshirt', 'Sweatshirt', '', 'sweatshirt'),
(19, 2, 'Sudaderas', '', 'sudaderas', 'Sudaderas', '', 'sudaderas'),
(20, 1, 'Air Jordan woman', '', 'air-jordan-20', 'Air Jordan woman', '', ''),
(20, 2, 'Air Jordan mujer', '', 'air-jordan-20', 'Air Jordan mujer', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `categories_routes`
--

CREATE TABLE `categories_routes` (
  `id_category` smallint(5) UNSIGNED NOT NULL,
  `id_language` tinyint(3) UNSIGNED NOT NULL,
  `route` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories_routes`
--

INSERT INTO `categories_routes` (`id_category`, `id_language`, `route`) VALUES
(13, 1, '/men'),
(13, 2, '/hombre'),
(14, 1, '/women'),
(14, 2, '/mujer'),
(17, 1, '/equipment/helmets'),
(17, 2, '/equipamiento/cascos'),
(18, 1, '/equipment'),
(18, 2, '/equipamiento'),
(19, 1, '/men/sweatshirt'),
(19, 2, '/hombre/sudaderas'),
(20, 1, '/equipment/air-jordan-20'),
(20, 2, '/equipamiento/air-jordan-20');

-- --------------------------------------------------------

--
-- Table structure for table `categories_views`
--

CREATE TABLE `categories_views` (
  `id_category_view` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(90) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories_views`
--

INSERT INTO `categories_views` (`id_category_view`, `name`) VALUES
(1, 'categorie');

-- --------------------------------------------------------

--
-- Table structure for table `codes`
--

CREATE TABLE `codes` (
  `id_code` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(90) NOT NULL,
  `code` varchar(30) NOT NULL,
  `type` tinyint(3) UNSIGNED NOT NULL,
  `amount` float UNSIGNED NOT NULL,
  `available` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `registered` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `exclude_sales` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `minimum` float UNSIGNED NOT NULL,
  `per_user` smallint(5) UNSIGNED NOT NULL DEFAULT '1',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `compatible` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `free_shipping` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `times_used` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `insert_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_state` tinyint(3) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `codes`
--

INSERT INTO `codes` (`id_code`, `name`, `code`, `type`, `amount`, `available`, `registered`, `exclude_sales`, `minimum`, `per_user`, `start_date`, `end_date`, `compatible`, `free_shipping`, `times_used`, `insert_date`, `id_state`) VALUES
(2, 'Prueba codigo de porcentaje 5', 'PORCENTAJE5', 1, 5, 32, 0, 1, 200, 3, '2024-02-01', '2035-12-31', 0, 0, 0, '2023-06-29 05:22:24', 2),
(4, 'Código de descuento del 10%', 'PORCENTAJE10', 1, 10, 5, 0, 0, 0, 5, '2024-10-02', '2035-10-02', 0, 1, 0, '2024-10-02 10:21:01', 2);

-- --------------------------------------------------------

--
-- Table structure for table `codes_rules`
--

CREATE TABLE `codes_rules` (
  `id_code_rule` mediumint(8) UNSIGNED NOT NULL,
  `id_code` mediumint(8) UNSIGNED NOT NULL,
  `id_code_rule_type` tinyint(3) UNSIGNED NOT NULL,
  `id_code_rule_add_type` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `codes_rules`
--

INSERT INTO `codes_rules` (`id_code_rule`, `id_code`, `id_code_rule_type`, `id_code_rule_add_type`) VALUES
(4, 2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `codes_rules_elements`
--

CREATE TABLE `codes_rules_elements` (
  `id_code_rule_element` mediumint(8) UNSIGNED NOT NULL,
  `id_code_rule` mediumint(8) UNSIGNED NOT NULL,
  `id_element` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `codes_rules_elements`
--

INSERT INTO `codes_rules_elements` (`id_code_rule_element`, `id_code_rule`, `id_element`) VALUES
(9, 4, 13),
(10, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ct_attributes_html`
--

CREATE TABLE `ct_attributes_html` (
  `id_attribute_html` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ct_attributes_html`
--

INSERT INTO `ct_attributes_html` (`id_attribute_html`, `name`) VALUES
(1, 'Box'),
(2, 'List');

-- --------------------------------------------------------

--
-- Table structure for table `ct_attributes_type`
--

CREATE TABLE `ct_attributes_type` (
  `id_attribute_type` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ct_attributes_type`
--

INSERT INTO `ct_attributes_type` (`id_attribute_type`, `name`) VALUES
(1, 'Text'),
(2, 'Color'),
(3, 'Image');

-- --------------------------------------------------------

--
-- Table structure for table `ct_codes_rules_add_type`
--

CREATE TABLE `ct_codes_rules_add_type` (
  `id_code_rule_add_type` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ct_codes_rules_add_type`
--

INSERT INTO `ct_codes_rules_add_type` (`id_code_rule_add_type`, `name`) VALUES
(1, 'Included'),
(2, 'Excluded');

-- --------------------------------------------------------

--
-- Table structure for table `ct_codes_rules_type`
--

CREATE TABLE `ct_codes_rules_type` (
  `id_code_rule_type` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ct_codes_rules_type`
--

INSERT INTO `ct_codes_rules_type` (`id_code_rule_type`, `name`) VALUES
(1, 'Product'),
(2, 'Category');

-- --------------------------------------------------------

--
-- Table structure for table `ct_codes_type`
--

CREATE TABLE `ct_codes_type` (
  `id_ct_codes_type` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ct_codes_type`
--

INSERT INTO `ct_codes_type` (`id_ct_codes_type`, `name`) VALUES
(1, 'Percentage'),
(2, 'Price');

-- --------------------------------------------------------

--
-- Table structure for table `ct_continents`
--

CREATE TABLE `ct_continents` (
  `id_continent` tinyint(5) UNSIGNED NOT NULL,
  `en` varchar(40) NOT NULL,
  `es` varchar(40) NOT NULL,
  `id_state` tinyint(3) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ct_continents`
--

INSERT INTO `ct_continents` (`id_continent`, `en`, `es`, `id_state`) VALUES
(1, 'Europe', 'Europa', 2),
(2, 'America', 'América', 1),
(3, 'Asia', 'Asia', 1),
(4, 'Africa', 'África', 1),
(5, 'Oceanía', 'Oceanía', 1),
(6, 'Antarctica', 'Antártida', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ct_countries`
--

CREATE TABLE `ct_countries` (
  `id_country` tinyint(5) UNSIGNED NOT NULL,
  `id_continent` tinyint(5) UNSIGNED NOT NULL,
  `en` varchar(40) NOT NULL,
  `es` varchar(40) NOT NULL,
  `id_state` tinyint(3) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ct_countries`
--

INSERT INTO `ct_countries` (`id_country`, `id_continent`, `en`, `es`, `id_state`) VALUES
(1, 1, 'Albanian', 'Albania', 2),
(2, 1, 'Germany', 'Alemania', 2),
(3, 1, 'Andorra', 'Andorra', 2),
(4, 1, 'Armenia', 'Armenia', 2),
(5, 1, 'Austria', 'Austria', 2),
(6, 1, 'Azerbaijan', 'Azerbaiyán', 2),
(7, 1, 'Belgium', 'Bélgica', 2),
(8, 1, 'Belarus', 'Bielorrusia', 2),
(9, 1, 'Bosnia and Herzegovina', 'Bosnia y Herzegovina', 2),
(10, 1, 'Bulgaria', 'Bulgaria', 2),
(11, 1, 'Cyprus', 'Chipre', 2),
(12, 1, 'Croatia', 'Croacia', 2),
(13, 1, 'Denmark', 'Dinamarca', 2),
(14, 1, 'Slovakia', 'Eslovaquia', 2),
(15, 1, 'Slovenia', 'Eslovenia', 2),
(16, 1, 'Spain', 'España', 2),
(17, 1, 'Estonia', 'Estonia', 2),
(18, 1, 'Finland', 'Finlandia', 2),
(19, 1, 'France', 'Francia', 2),
(20, 1, 'Georgia', 'Georgia', 2),
(21, 1, 'Greece', 'Grecia', 2),
(22, 1, 'Hungary', 'Hungría', 2),
(23, 1, 'Ireland', 'Irlanda', 2),
(24, 1, 'Iceland', 'Islandia', 2),
(25, 1, 'Italy', 'Italia', 2),
(26, 1, 'Latvia', 'Letonia', 2),
(27, 1, 'Liechtenstein', 'Liechtenstein', 2),
(28, 1, 'Lithuania', 'Lituania', 2),
(29, 1, 'Luxembourg', 'Luxemburgo', 2),
(30, 1, 'North Macedonia', 'Macedonia del Norte', 2),
(31, 1, 'Malta', 'Malta', 2),
(32, 1, 'Moldova', 'Moldavia', 2),
(33, 1, 'Monaco', 'Mónaco', 2),
(34, 1, 'Montenegro', 'Montenegro', 2),
(35, 1, 'Norway', 'Noruega', 2),
(36, 1, 'Netherlands', 'Países Bajos', 2),
(37, 1, 'Poland', 'Polonia', 2),
(38, 1, 'Portugal', 'Portugal', 2),
(39, 1, 'United Kingdom', 'Reino Unido', 2),
(40, 1, 'Czech Republic', 'República Checa', 2),
(41, 1, 'Romania', 'Rumania', 2),
(42, 1, 'Russia', 'Rusia', 2),
(43, 1, 'San Marino', 'San Marino', 2),
(44, 1, 'Serbian', 'Serbia', 2),
(45, 1, 'Sweden', 'Suecia', 2),
(46, 1, 'Swiss', 'Suiza', 2),
(47, 1, 'Turkey', 'Turquía', 2),
(48, 1, 'Ukraine', 'Ucrania', 2),
(49, 1, 'Vatican', 'Vaticano', 2);

-- --------------------------------------------------------

--
-- Table structure for table `ct_languages`
--

CREATE TABLE `ct_languages` (
  `id_language` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(90) NOT NULL,
  `alias` varchar(90) NOT NULL,
  `id_state` tinyint(3) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ct_languages`
--

INSERT INTO `ct_languages` (`id_language`, `name`, `alias`, `id_state`) VALUES
(1, 'en', 'English', 2),
(2, 'es', 'Spanish', 2);

-- --------------------------------------------------------

--
-- Table structure for table `ct_provinces`
--

CREATE TABLE `ct_provinces` (
  `id_province` tinyint(5) UNSIGNED NOT NULL,
  `id_country` smallint(5) UNSIGNED NOT NULL,
  `en` varchar(40) NOT NULL,
  `es` varchar(40) NOT NULL,
  `id_state` tinyint(3) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ct_provinces`
--

INSERT INTO `ct_provinces` (`id_province`, `id_country`, `en`, `es`, `id_state`) VALUES
(1, 16, 'Álava', 'Álava', 2),
(2, 16, 'Albacete', 'Albacete', 2),
(3, 16, 'Alicante', 'Alicante', 2),
(4, 16, 'Almería', 'Almería', 2),
(5, 16, 'Asturias', 'Asturias', 2),
(6, 16, 'Ávila', 'Ávila', 2),
(7, 16, 'Badajoz', 'Badajoz', 2),
(8, 16, 'Barcelona', 'Barcelona', 2),
(9, 16, 'Burgos', 'Burgos', 2),
(10, 16, 'Cáceres', 'Cáceres', 2),
(11, 16, 'Cadiz', 'Cádiz', 2),
(12, 16, 'Cantabria', 'Cantabria', 2),
(13, 16, 'Castellón', 'Castellón', 2),
(14, 16, 'Ciudad Real', 'Ciudad Real', 2),
(15, 16, 'Córdoba', 'Córdoba', 2),
(16, 16, 'La Coruña', 'La Coruña', 2),
(17, 16, 'Cuenca', 'Cuenca', 2),
(18, 16, 'Gerona', 'Gerona', 2),
(19, 16, 'Granada', 'Granada', 2),
(20, 16, 'Guadalajara', 'Guadalajara', 2),
(21, 16, 'Guipúzcoa', 'Guipúzcoa', 2),
(22, 16, 'Huelva', 'Huelva', 2),
(23, 16, 'Huesca', 'Huesca', 2),
(24, 16, 'The Balearic Islands', 'Islas Baleares', 2),
(25, 16, 'Jaén', 'Jaén', 2),
(26, 16, 'León', 'León', 2),
(27, 16, 'Lérida', 'Lérida', 2),
(28, 16, 'Lugo', 'Lugo', 2),
(29, 16, 'Madrid', 'Madrid', 2),
(30, 16, 'Málaga', 'Málaga', 2),
(31, 16, 'Murcia', 'Murcia', 2),
(32, 16, 'Navarre', 'Navarra', 2),
(33, 16, 'Orense', 'Orense', 2),
(34, 16, 'Palencia', 'Palencia', 2),
(35, 16, 'Las Palmas', 'Las Palmas', 2),
(36, 16, 'Pontevedra', 'Pontevedra', 2),
(37, 16, 'La Rioja', 'La Rioja', 2),
(38, 16, 'Salamanca', 'Salamanca', 2),
(39, 16, 'Segovia', 'Segovia', 2),
(40, 16, 'Seville', 'Sevilla', 2),
(41, 16, 'Soria', 'Soria', 2),
(42, 16, 'Tarragona', 'Tarragona', 2),
(43, 16, 'Santa Cruz de Tenerife', 'Santa Cruz de Tenerife', 2),
(44, 16, 'Teruel', 'Teruel', 2),
(45, 16, 'Toledo', 'Toledo', 2),
(46, 16, 'Valencia', 'Valencia', 2),
(47, 16, 'Valladolid', 'Valladolid', 2),
(48, 16, 'Vizcaya', 'Vizcaya', 2),
(49, 16, 'Zamora', 'Zamora', 2),
(50, 16, 'Zaragoza', 'Zaragoza', 2);

-- --------------------------------------------------------

--
-- Table structure for table `ct_states`
--

CREATE TABLE `ct_states` (
  `id_state` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ct_states`
--

INSERT INTO `ct_states` (`id_state`, `name`) VALUES
(1, 'Disabled'),
(2, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id_image` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(90) NOT NULL,
  `url` varchar(255) NOT NULL,
  `upload_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id_image`, `name`, `url`, `upload_date`) VALUES
(65, 'blackbird-mips_34-rear_main-lights.jpg', '/img/products/blackbird-mips_34-rear_main-lights.jpg', '2023-08-03 05:18:05'),
(66, 'shop.blackbird.3.jpg', '/img/products/shop.blackbird.3.jpg', '2023-08-03 05:18:05'),
(67, 'blackbird-mips_34-rear_no-lights.jpg', '/img/products/blackbird-mips_34-rear_no-lights.jpg', '2023-08-03 05:18:05'),
(68, 'blackbird-mips_34-rear_all-lights.jpg', '/img/products/blackbird-mips_34-rear_all-lights.jpg', '2023-08-03 05:18:05'),
(69, 'juniper-mips_34-trasero_main-lights.jpg', '/img/products/juniper-mips_34-trasero_main-lights.jpg', '2023-08-03 05:20:34'),
(70, 'juniper-mips_34-trasero_all-lights.jpg', '/img/products/juniper-mips_34-trasero_all-lights.jpg', '2023-08-03 05:20:34'),
(71, 'juniper-mips_34-trasero_no-lights.jpg', '/img/products/juniper-mips_34-trasero_no-lights.jpg', '2023-08-03 05:20:34'),
(72, 'shop.juniper.3.jpg', '/img/products/shop.juniper.3.jpg', '2023-08-03 05:20:34'),
(73, 'stingray-mips_34-rear_main-lights.jpg', '/img/products/stingray-mips_34-rear_main-lights.jpg', '2023-08-03 05:23:08'),
(74, 'stingray-mips_34-rear_all-lights.jpg', '/img/products/stingray-mips_34-rear_all-lights.jpg', '2023-08-03 05:23:08'),
(75, 'stingray-mips_34-rear_no-lights.jpg', '/img/products/stingray-mips_34-rear_no-lights.jpg', '2023-08-03 05:23:08'),
(76, 'shop.stingray.3.jpg', '/img/products/shop.stingray.3.jpg', '2023-08-03 05:23:08'),
(77, 'maverick-mips_34-rear_main-lights.jpg', '/img/products/maverick-mips_34-rear_main-lights.jpg', '2023-08-03 05:24:28'),
(78, 'maverick-mips_34-rear_all-lights.jpg', '/img/products/maverick-mips_34-rear_all-lights.jpg', '2023-08-03 05:24:28'),
(79, 'maverick_34-rear_no-lights.jpg', '/img/products/maverick_34-rear_no-lights.jpg', '2023-08-03 05:24:28'),
(80, 'shop.maverick.3.jpg', '/img/products/shop.maverick.3.jpg', '2023-08-03 05:24:28'),
(123, 'black-gray-01.jpg', '/img/products/black-gray-01.jpg', '2023-08-03 06:32:40'),
(125, 'black-gray.jpg', '/img/products/black-gray.jpg', '2023-08-03 06:32:41'),
(126, 'black-gray-back.jpg', '/img/products/black-gray-back.jpg', '2023-08-03 06:32:41');

-- --------------------------------------------------------

--
-- Table structure for table `newsletters`
--

CREATE TABLE `newsletters` (
  `id_newsletter` int(10) UNSIGNED NOT NULL,
  `email` varchar(150) NOT NULL,
  `validated_email` tinyint(4) NOT NULL DEFAULT '0',
  `validation_code` varchar(30) NOT NULL,
  `insert_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `newsletters`
--

INSERT INTO `newsletters` (`id_newsletter`, `email`, `validated_email`, `validation_code`, `insert_date`, `status`) VALUES
(1, 'd.martin@walma.es', 0, '64784ce43d368', '2023-06-01 03:46:44', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id_order` mediumint(8) UNSIGNED NOT NULL,
  `order_code` varchar(30) NOT NULL,
  `id_cart` varchar(30) NOT NULL,
  `id_user` mediumint(8) UNSIGNED NOT NULL,
  `id_user_address` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `id_user_billing_address` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `gift` tinyint(3) UNSIGNED NOT NULL,
  `card` tinyint(3) UNSIGNED NOT NULL,
  `comments` varchar(255) NOT NULL,
  `id_code` mediumint(8) UNSIGNED NOT NULL,
  `id_payment_method` tinyint(3) UNSIGNED NOT NULL,
  `shipping_costs` float UNSIGNED NOT NULL,
  `cart_price` float UNSIGNED NOT NULL,
  `stripe_id` varchar(255) NOT NULL,
  `paid` tinyint(3) UNSIGNED NOT NULL,
  `sent` tinyint(3) UNSIGNED NOT NULL,
  `delivered` tinyint(3) UNSIGNED NOT NULL,
  `returned` tinyint(3) UNSIGNED NOT NULL,
  `tracking_code` varchar(255) NOT NULL,
  `feedback` text NOT NULL,
  `rate` tinyint(3) UNSIGNED NOT NULL,
  `sent_date` datetime DEFAULT NULL,
  `returned_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `orders_incidents`
--

CREATE TABLE `orders_incidents` (
  `id_order_incident` mediumint(8) UNSIGNED NOT NULL,
  `id_order` int(11) NOT NULL,
  `comment` text NOT NULL,
  `insert_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id_payment_method` tinyint(3) UNSIGNED NOT NULL,
  `alias` varchar(100) NOT NULL DEFAULT '',
  `min_order_value` mediumint(8) UNSIGNED DEFAULT '0',
  `max_order_value` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `id_state` tinyint(3) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id_payment_method`, `alias`, `min_order_value`, `max_order_value`, `id_state`) VALUES
(1, 'Tarjeta bancaria', 0, 0, 2),
(2, 'Cetelem', 50, 4000, 2);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id_product` mediumint(8) UNSIGNED NOT NULL,
  `id_product_view` tinyint(3) UNSIGNED NOT NULL,
  `price` float UNSIGNED NOT NULL DEFAULT '0',
  `weight` float UNSIGNED NOT NULL DEFAULT '0',
  `priority` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `public_date` datetime DEFAULT NULL,
  `insert_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `alias` varchar(255) NOT NULL,
  `main_image` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `hover_image` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `visits` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `id_state` tinyint(3) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id_product`, `id_product_view`, `price`, `weight`, `priority`, `public_date`, `insert_date`, `alias`, `main_image`, `hover_image`, `visits`, `id_state`) VALUES
(9, 1, 65, 0.2, 0, NULL, '2023-07-21 06:20:28', 'Sweatshirt Pyrenees', 149, 151, 90, 2),
(10, 1, 295.9, 0.1, 0, NULL, '2023-07-21 06:30:17', 'Helmet Unit 1 FARO', 85, 86, 644, 2);

-- --------------------------------------------------------

--
-- Table structure for table `products_attributes`
--

CREATE TABLE `products_attributes` (
  `id_product` mediumint(8) UNSIGNED NOT NULL,
  `id_attribute` int(11) NOT NULL,
  `priority` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products_attributes`
--

INSERT INTO `products_attributes` (`id_product`, `id_attribute`, `priority`) VALUES
(9, 2, 1),
(9, 20, 2),
(10, 2, 1),
(10, 20, 2);

-- --------------------------------------------------------

--
-- Table structure for table `products_categories`
--

CREATE TABLE `products_categories` (
  `id_product` mediumint(8) UNSIGNED NOT NULL,
  `id_category` smallint(5) UNSIGNED NOT NULL,
  `main` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products_categories`
--

INSERT INTO `products_categories` (`id_product`, `id_category`, `main`) VALUES
(9, 13, 0),
(9, 19, 1),
(10, 1, 0),
(10, 17, 0),
(10, 18, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products_custom_routes`
--

CREATE TABLE `products_custom_routes` (
  `id_product_custom_route` mediumint(8) UNSIGNED NOT NULL,
  `id_product` mediumint(8) UNSIGNED NOT NULL,
  `id_category` smallint(5) UNSIGNED NOT NULL,
  `id_language` tinyint(3) UNSIGNED NOT NULL,
  `route` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products_custom_routes`
--

INSERT INTO `products_custom_routes` (`id_product_custom_route`, `id_product`, `id_category`, `id_language`, `route`) VALUES
(7, 10, 18, 1, '/equipment/test-number-d1'),
(8, 10, 18, 2, '/equipamiento/prueba-numero-d1');

-- --------------------------------------------------------

--
-- Table structure for table `products_images`
--

CREATE TABLE `products_images` (
  `id_product_image` mediumint(8) UNSIGNED NOT NULL,
  `id_product` mediumint(8) UNSIGNED NOT NULL,
  `id_image` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `priority` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products_images`
--

INSERT INTO `products_images` (`id_product_image`, `id_product`, `id_image`, `priority`) VALUES
(85, 10, 65, 1),
(86, 10, 66, 4),
(87, 10, 67, 3),
(88, 10, 68, 2),
(89, 10, 69, 5),
(90, 10, 70, 6),
(91, 10, 71, 7),
(92, 10, 72, 8),
(93, 10, 73, 9),
(94, 10, 74, 10),
(95, 10, 75, 11),
(96, 10, 76, 12),
(97, 10, 77, 13),
(98, 10, 78, 14),
(99, 10, 79, 15),
(100, 10, 80, 16),
(149, 9, 123, 1),
(151, 9, 125, 2),
(152, 9, 126, 3);

-- --------------------------------------------------------

--
-- Table structure for table `products_language`
--

CREATE TABLE `products_language` (
  `id_product` mediumint(8) UNSIGNED NOT NULL,
  `id_language` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `slug` varchar(90) NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` varchar(999) NOT NULL,
  `meta_keywords` varchar(999) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products_language`
--

INSERT INTO `products_language` (`id_product`, `id_language`, `name`, `description`, `slug`, `meta_title`, `meta_description`, `meta_keywords`) VALUES
(9, 1, 'Sweatshirt Pyrenees', 'Lost In Bahamas sweatshirts are of Premium quality. Totally respectful with the environment, made with 100% organic cotton with GOTS certification and recycled and sustainable materials.', 'sweatshirt-pyrenees', 'Sweatshirt Pyrenees', 'Lost In Bahamas sweatshirts are of Premium quality. Totally respectful with the environment, made with 100% organic cotton with GOTS certification and recycled and sustainable materials.', 'sweatshirt,lost in bahamas'),
(9, 2, 'Sudadera Pirineos', 'Las sudaderas Lost In Bahamas son de una calidad Premium. Totalmente respetuosas con el medio ambiente, elaboradas con algodón 100% orgánico con certificación GOTS y materiales reciclados y sostenibles.', 'sudadera-pirineos', 'Sudadera Pirineos', 'Las sudaderas Lost In Bahamas son de una calidad Premium. Totalmente respetuosas con el medio ambiente, elaboradas con algodón 100% orgánico con certificación GOTS y materiales reciclados y sostenibles.', 'sudadera,lost in bahamas'),
(10, 1, 'Helmet Unit 1 FARO', 'The UNIT 1 FARO Smart Helmet combines style, safety and technology in the best driving experience.<br><br>Fully waterproof and highly ventilated, this Red Dot award winning helmet features over <b>500 lumens</b> of integrated lights, weather monitoring, crash detection, rigid ABS and EPS shell, and the <b>Mips safety system</b>.<br><br>Automatic turn signals and brake lights are available via the navigation remote control (sold separately).', 'helmet-unit-1-faro', 'Helmet Unit 1 FARO', 'The UNIT 1 FARO Smart Helmet combines style, safety and technology in the best driving experience.', 'helmet,unit 1,faro'),
(10, 2, 'Casco Unit 1 FARO', 'El FARO Smart Helmet de UNIT 1 combina estilo, seguridad y tecnología en la mejor experiencia de conducción.<br><br>Este casco ganador del premio Red Dot, completamente impermeable y altamente ventilado, cuenta con más de <b>500 lúmenes</b> de luces integradas, monitoreo del clima, detección de choques, revestimiento ABS rígido y EPS, y el <b>sistema de seguridad Mips</b>.<br><br>Las señales de giro y las luces de freno automáticas están disponibles a través del control remoto de navegación (se vende por separado).', 'casco-unit-1-faro', 'Casco Unit 1 FARO', 'El FARO Smart Helmet de UNIT 1 combina estilo, seguridad y tecnología en la mejor experiencia de conducción.', 'casco,unit 1,faro');

-- --------------------------------------------------------

--
-- Table structure for table `products_related`
--

CREATE TABLE `products_related` (
  `id_product_related` int(10) UNSIGNED NOT NULL,
  `id_product` mediumint(8) UNSIGNED NOT NULL,
  `stock` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `price_change` float NOT NULL DEFAULT '0',
  `weight_change` float NOT NULL DEFAULT '0',
  `offer` float UNSIGNED NOT NULL DEFAULT '0',
  `offer_start_date` date DEFAULT NULL,
  `offer_end_date` date DEFAULT NULL,
  `main` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `id_state` tinyint(3) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products_related`
--

INSERT INTO `products_related` (`id_product_related`, `id_product`, `stock`, `price_change`, `weight_change`, `offer`, `offer_start_date`, `offer_end_date`, `main`, `id_state`) VALUES
(32, 10, 12, 0, 0, 0, NULL, NULL, 0, 2),
(33, 10, 10, 0, 0, 0, NULL, NULL, 0, 2),
(34, 10, 10, 0, 0, 0, NULL, NULL, 0, 2),
(35, 10, 10, 0, 0, 0, NULL, NULL, 0, 2),
(36, 10, 10, 0, 0, 0, NULL, NULL, 0, 2),
(37, 10, 10, 0, 0, 0, NULL, NULL, 0, 2),
(38, 10, 0, 0, 0, 0, NULL, NULL, 0, 2),
(39, 10, 10, 0, 0, 0, NULL, NULL, 0, 1),
(40, 10, 12, -30.5, 0, 30.5, '2024-09-24', '2025-02-28', 1, 2),
(46, 10, 10, 0, 0, 0, NULL, NULL, 0, 2),
(47, 10, 5, 0, 0, 0, NULL, NULL, 0, 2),
(50, 9, 2, 0, 0.01, 0, NULL, NULL, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `products_related_attributes`
--

CREATE TABLE `products_related_attributes` (
  `id_product_related` int(10) UNSIGNED NOT NULL,
  `id_attribute` smallint(5) UNSIGNED NOT NULL,
  `id_attribute_value` smallint(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products_related_attributes`
--

INSERT INTO `products_related_attributes` (`id_product_related`, `id_attribute`, `id_attribute_value`) VALUES
(32, 2, 46),
(32, 20, 52),
(33, 2, 46),
(33, 20, 53),
(34, 2, 46),
(34, 20, 54),
(35, 2, 49),
(35, 20, 52),
(36, 2, 49),
(36, 20, 53),
(37, 2, 49),
(37, 20, 54),
(38, 2, 48),
(38, 20, 52),
(39, 2, 48),
(39, 20, 53),
(40, 2, 48),
(40, 20, 54),
(46, 2, 47),
(46, 20, 52),
(47, 2, 47),
(47, 20, 53),
(50, 2, 45),
(50, 20, 53);

-- --------------------------------------------------------

--
-- Table structure for table `products_related_images`
--

CREATE TABLE `products_related_images` (
  `id_products_related_image` mediumint(8) UNSIGNED NOT NULL,
  `id_product_related` int(10) UNSIGNED NOT NULL,
  `id_product_image` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products_related_images`
--

INSERT INTO `products_related_images` (`id_products_related_image`, `id_product_related`, `id_product_image`) VALUES
(323, 33, 85),
(324, 33, 88),
(325, 33, 87),
(326, 33, 86),
(331, 35, 89),
(332, 35, 90),
(333, 35, 91),
(334, 35, 92),
(335, 36, 89),
(336, 36, 90),
(337, 36, 91),
(338, 36, 92),
(339, 37, 89),
(340, 37, 90),
(341, 37, 91),
(342, 37, 92),
(343, 38, 93),
(344, 38, 94),
(345, 38, 95),
(346, 38, 96),
(359, 46, 97),
(360, 46, 98),
(361, 46, 99),
(362, 46, 100),
(363, 47, 97),
(364, 47, 98),
(365, 47, 99),
(366, 47, 100),
(392, 34, 85),
(393, 34, 88),
(394, 34, 87),
(395, 34, 86),
(400, 32, 85),
(401, 32, 88),
(402, 32, 87),
(403, 32, 86),
(404, 39, 93),
(405, 39, 94),
(406, 39, 95),
(407, 39, 96),
(612, 40, 93),
(613, 40, 94),
(614, 40, 95),
(615, 40, 96),
(651, 50, 149),
(652, 50, 151),
(653, 50, 152);

-- --------------------------------------------------------

--
-- Table structure for table `products_routes`
--

CREATE TABLE `products_routes` (
  `id_product` mediumint(8) UNSIGNED NOT NULL,
  `id_category` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `id_language` tinyint(3) UNSIGNED NOT NULL,
  `route` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products_routes`
--

INSERT INTO `products_routes` (`id_product`, `id_category`, `id_language`, `route`) VALUES
(9, 13, 1, '/men/sweatshirt-pyrenees'),
(9, 13, 2, '/hombre/sudadera-pirineos'),
(9, 19, 1, '/men/sweatshirt/sweatshirt-pyrenees'),
(9, 19, 2, '/hombre/sudaderas/sudadera-pirineos'),
(10, 1, 1, '/helmet-unit-1-faro'),
(10, 1, 2, '/casco-unit-1-faro'),
(10, 17, 1, '/equipment/helmets/helmet-unit-1-faro'),
(10, 17, 2, '/equipamiento/cascos/casco-unit-1-faro'),
(10, 18, 1, '/equipment/helmet-unit-1-faro'),
(10, 18, 2, '/equipamiento/casco-unit-1-faro');

-- --------------------------------------------------------

--
-- Table structure for table `products_views`
--

CREATE TABLE `products_views` (
  `id_product_view` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(90) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products_views`
--

INSERT INTO `products_views` (`id_product_view`, `name`) VALUES
(1, 'product');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_methods`
--

CREATE TABLE `shipping_methods` (
  `id_shipping_method` smallint(5) UNSIGNED NOT NULL,
  `alias` varchar(100) NOT NULL,
  `min_order_value` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `max_order_value` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `min_order_weight` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `max_order_weight` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `id_state` tinyint(3) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `shipping_methods`
--

INSERT INTO `shipping_methods` (`id_shipping_method`, `alias`, `min_order_value`, `max_order_value`, `min_order_weight`, `max_order_weight`, `id_state`) VALUES
(1, 'Standard', 0, 0, 0, 0, 2),
(2, 'Diego', 1, 2, 10, 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `shipping_methods_language`
--

CREATE TABLE `shipping_methods_language` (
  `id_shipping_method` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `id_language` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `shipping_methods_language`
--

INSERT INTO `shipping_methods_language` (`id_shipping_method`, `id_language`, `name`) VALUES
(1, 1, 'Standard'),
(1, 2, 'Estandar');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_methods_prices`
--

CREATE TABLE `shipping_methods_prices` (
  `id_shipping_method_price` smallint(5) UNSIGNED NOT NULL,
  `id_shipping_zone` smallint(5) UNSIGNED NOT NULL,
  `id_shipping_method_weight` smallint(5) UNSIGNED NOT NULL,
  `price` float UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `shipping_methods_prices`
--

INSERT INTO `shipping_methods_prices` (`id_shipping_method_price`, `id_shipping_zone`, `id_shipping_method_weight`, `price`) VALUES
(1, 1, 1, 4),
(2, 1, 2, 6);

-- --------------------------------------------------------

--
-- Table structure for table `shipping_methods_weights`
--

CREATE TABLE `shipping_methods_weights` (
  `id_shipping_method_weight` smallint(5) UNSIGNED NOT NULL,
  `id_shipping_method` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `min_weight` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `max_weight` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `shipping_methods_weights`
--

INSERT INTO `shipping_methods_weights` (`id_shipping_method_weight`, `id_shipping_method`, `min_weight`, `max_weight`) VALUES
(1, 1, 0, 2),
(2, 1, 2, 5),
(3, 1, 5, 10),
(4, 1, 10, 20),
(5, 1, 20, 40),
(6, 1, 40, 70),
(7, 1, 70, 100),
(8, 1, 100, 150),
(9, 1, 150, 200),
(10, 1, 200, 250);

-- --------------------------------------------------------

--
-- Table structure for table `shipping_methods_zones`
--

CREATE TABLE `shipping_methods_zones` (
  `id_shipping_method_zone` mediumint(8) UNSIGNED NOT NULL,
  `id_shipping_method` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `id_shipping_zone` smallint(5) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `shipping_methods_zones`
--

INSERT INTO `shipping_methods_zones` (`id_shipping_method_zone`, `id_shipping_method`, `id_shipping_zone`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `shipping_zones`
--

CREATE TABLE `shipping_zones` (
  `id_shipping_zone` smallint(3) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `id_state` tinyint(3) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `shipping_zones`
--

INSERT INTO `shipping_zones` (`id_shipping_zone`, `name`, `id_state`) VALUES
(1, 'Europe', 2);

-- --------------------------------------------------------

--
-- Table structure for table `shipping_zone_countries`
--

CREATE TABLE `shipping_zone_countries` (
  `id_shipping_zone_countrie` smallint(5) UNSIGNED NOT NULL,
  `id_shipping_zone` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `id_country` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `shipping_zone_countries`
--

INSERT INTO `shipping_zone_countries` (`id_shipping_zone_countrie`, `id_shipping_zone`, `id_country`) VALUES
(1, 1, 16),
(2, 1, 38);

-- --------------------------------------------------------

--
-- Table structure for table `stock_notices`
--

CREATE TABLE `stock_notices` (
  `id_stock_notice` int(10) UNSIGNED NOT NULL,
  `id_product` mediumint(8) UNSIGNED NOT NULL,
  `id_product_related` int(10) UNSIGNED NOT NULL,
  `id_category` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(90) NOT NULL,
  `email` varchar(255) NOT NULL,
  `id_user` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `insert_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stock_notices`
--

INSERT INTO `stock_notices` (`id_stock_notice`, `id_product`, `id_product_related`, `id_category`, `name`, `email`, `id_user`, `insert_date`) VALUES
(2, 10, 38, 18, 'Diego', 'hello@hiveframework.com', 1, '2024-09-23 09:13:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` mediumint(8) UNSIGNED NOT NULL,
  `email` varchar(90) NOT NULL,
  `pass` varchar(50) NOT NULL,
  `name` varchar(90) NOT NULL,
  `lastname` varchar(120) NOT NULL,
  `remember_code` varchar(50) NOT NULL DEFAULT '',
  `validation_code` varchar(50) NOT NULL,
  `validated_email` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `last_access` datetime DEFAULT NULL,
  `insert_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_register` varchar(20) NOT NULL DEFAULT '',
  `ip_last_access` varchar(20) NOT NULL DEFAULT '',
  `id_state` tinyint(3) UNSIGNED NOT NULL DEFAULT '2'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `email`, `pass`, `name`, `lastname`, `remember_code`, `validation_code`, `validated_email`, `last_access`, `insert_date`, `ip_register`, `ip_last_access`, `id_state`) VALUES
(1, 'hello@hiveframework.com', 'd0ea394d5e1d47edf168685fd15fbbd2', 'Diego', 'Martin Herranz', '64a01f3804355', '6380ad1bddebb', 1, '2025-03-24 05:53:13', '2022-12-15 05:42:10', '90.171.237.131', '80.28.132.35', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users_addresses`
--

CREATE TABLE `users_addresses` (
  `id_user_address` mediumint(8) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `name` varchar(90) NOT NULL,
  `lastname` varchar(120) NOT NULL,
  `id_continent` tinyint(3) UNSIGNED NOT NULL,
  `id_country` smallint(5) UNSIGNED NOT NULL,
  `id_province` smallint(5) UNSIGNED NOT NULL,
  `location` varchar(150) NOT NULL,
  `address` varchar(255) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `main_address` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `update_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_addresses`
--

INSERT INTO `users_addresses` (`id_user_address`, `id_user`, `name`, `lastname`, `id_continent`, `id_country`, `id_province`, `location`, `address`, `postal_code`, `telephone`, `main_address`, `update_date`) VALUES
(1, 1, 'Diego', 'Martin Herranz', 1, 16, 29, 'Tres Cantos', 'Avenida de la Industria 3', '28071', '627878789', 1, '2024-06-19 10:27:24');

-- --------------------------------------------------------

--
-- Table structure for table `users_admin`
--

CREATE TABLE `users_admin` (
  `id_admin` tinyint(3) UNSIGNED NOT NULL,
  `email` varchar(90) NOT NULL,
  `pass` varchar(90) NOT NULL,
  `name` varchar(90) NOT NULL,
  `lastname` varchar(120) NOT NULL,
  `remember_code` varchar(50) NOT NULL DEFAULT '',
  `id_admin_type` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `last_access` datetime DEFAULT NULL,
  `insert_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_last_access` varchar(20) NOT NULL DEFAULT '',
  `id_state` tinyint(3) UNSIGNED NOT NULL DEFAULT '2'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_admin`
--

INSERT INTO `users_admin` (`id_admin`, `email`, `pass`, `name`, `lastname`, `remember_code`, `id_admin_type`, `last_access`, `insert_date`, `ip_last_access`, `id_state`) VALUES
(1, 'hello@hiveframework.com', 'd0ea394d5e1d47edf168685fd15fbbd2', 'Diego', 'Martin', '64b25e80ceb27', 1, '2025-03-24 05:53:16', '2022-12-09 05:42:37', '80.28.132.35', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users_admin_type`
--

CREATE TABLE `users_admin_type` (
  `id_admin_type` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(90) NOT NULL,
  `can_delete` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `can_edit` tinyint(3) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_admin_type`
--

INSERT INTO `users_admin_type` (`id_admin_type`, `name`, `can_delete`, `can_edit`) VALUES
(1, 'Super Admin', 1, 1),
(2, 'Manager', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_billing_addresses`
--

CREATE TABLE `users_billing_addresses` (
  `id_user_billing_address` mediumint(8) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `name` varchar(90) NOT NULL,
  `lastname` varchar(120) NOT NULL,
  `id_continent` tinyint(3) UNSIGNED NOT NULL,
  `id_country` smallint(5) UNSIGNED NOT NULL,
  `id_province` smallint(5) UNSIGNED NOT NULL,
  `location` varchar(150) NOT NULL,
  `address` varchar(255) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `main_address` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `update_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_billing_addresses`
--

INSERT INTO `users_billing_addresses` (`id_user_billing_address`, `id_user`, `name`, `lastname`, `id_continent`, `id_country`, `id_province`, `location`, `address`, `postal_code`, `telephone`, `main_address`, `update_date`) VALUES
(1, 1, 'Diego', 'Martin', 1, 16, 29, 'Tres Cantos', 'Avenida de la Industria 3', '28092', '692834520', 1, '2024-06-19 10:27:19'),
(2, 1, 'Pepe', 'Luis', 1, 16, 8, 'Madrid', 'Inventada 3', '28029', '656454345', 0, '2024-06-19 10:27:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id_attribute`);

--
-- Indexes for table `attributes_language`
--
ALTER TABLE `attributes_language`
  ADD PRIMARY KEY (`id_attribute`,`id_language`);

--
-- Indexes for table `attributes_value`
--
ALTER TABLE `attributes_value`
  ADD PRIMARY KEY (`id_attribute_value`),
  ADD KEY `id_attribute` (`id_attribute`);

--
-- Indexes for table `attributes_value_language`
--
ALTER TABLE `attributes_value_language`
  ADD PRIMARY KEY (`id_attribute_value`,`id_language`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_cart` (`id_cart`);

--
-- Indexes for table `carts_codes`
--
ALTER TABLE `carts_codes`
  ADD PRIMARY KEY (`id_cart_code`);

--
-- Indexes for table `carts_products`
--
ALTER TABLE `carts_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_category`),
  ADD KEY `id_parent` (`id_parent`);

--
-- Indexes for table `categories_custom_routes`
--
ALTER TABLE `categories_custom_routes`
  ADD PRIMARY KEY (`id_category_custom_route`);

--
-- Indexes for table `categories_language`
--
ALTER TABLE `categories_language`
  ADD PRIMARY KEY (`id_category`,`id_language`);

--
-- Indexes for table `categories_routes`
--
ALTER TABLE `categories_routes`
  ADD PRIMARY KEY (`id_category`,`id_language`);

--
-- Indexes for table `categories_views`
--
ALTER TABLE `categories_views`
  ADD PRIMARY KEY (`id_category_view`);

--
-- Indexes for table `codes`
--
ALTER TABLE `codes`
  ADD PRIMARY KEY (`id_code`);

--
-- Indexes for table `codes_rules`
--
ALTER TABLE `codes_rules`
  ADD PRIMARY KEY (`id_code_rule`);

--
-- Indexes for table `codes_rules_elements`
--
ALTER TABLE `codes_rules_elements`
  ADD PRIMARY KEY (`id_code_rule_element`);

--
-- Indexes for table `ct_attributes_html`
--
ALTER TABLE `ct_attributes_html`
  ADD PRIMARY KEY (`id_attribute_html`);

--
-- Indexes for table `ct_attributes_type`
--
ALTER TABLE `ct_attributes_type`
  ADD PRIMARY KEY (`id_attribute_type`);

--
-- Indexes for table `ct_codes_rules_add_type`
--
ALTER TABLE `ct_codes_rules_add_type`
  ADD PRIMARY KEY (`id_code_rule_add_type`);

--
-- Indexes for table `ct_codes_rules_type`
--
ALTER TABLE `ct_codes_rules_type`
  ADD PRIMARY KEY (`id_code_rule_type`);

--
-- Indexes for table `ct_codes_type`
--
ALTER TABLE `ct_codes_type`
  ADD PRIMARY KEY (`id_ct_codes_type`);

--
-- Indexes for table `ct_continents`
--
ALTER TABLE `ct_continents`
  ADD PRIMARY KEY (`id_continent`);

--
-- Indexes for table `ct_countries`
--
ALTER TABLE `ct_countries`
  ADD PRIMARY KEY (`id_country`);

--
-- Indexes for table `ct_languages`
--
ALTER TABLE `ct_languages`
  ADD PRIMARY KEY (`id_language`);

--
-- Indexes for table `ct_provinces`
--
ALTER TABLE `ct_provinces`
  ADD PRIMARY KEY (`id_province`);

--
-- Indexes for table `ct_states`
--
ALTER TABLE `ct_states`
  ADD PRIMARY KEY (`id_state`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id_image`);

--
-- Indexes for table `newsletters`
--
ALTER TABLE `newsletters`
  ADD PRIMARY KEY (`id_newsletter`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`);

--
-- Indexes for table `orders_incidents`
--
ALTER TABLE `orders_incidents`
  ADD PRIMARY KEY (`id_order_incident`),
  ADD KEY `id_order` (`id_order`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id_payment_method`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id_product`);

--
-- Indexes for table `products_attributes`
--
ALTER TABLE `products_attributes`
  ADD PRIMARY KEY (`id_product`,`id_attribute`);

--
-- Indexes for table `products_categories`
--
ALTER TABLE `products_categories`
  ADD PRIMARY KEY (`id_product`,`id_category`);

--
-- Indexes for table `products_custom_routes`
--
ALTER TABLE `products_custom_routes`
  ADD PRIMARY KEY (`id_product_custom_route`);

--
-- Indexes for table `products_images`
--
ALTER TABLE `products_images`
  ADD PRIMARY KEY (`id_product_image`);

--
-- Indexes for table `products_language`
--
ALTER TABLE `products_language`
  ADD PRIMARY KEY (`id_product`,`id_language`);

--
-- Indexes for table `products_related`
--
ALTER TABLE `products_related`
  ADD PRIMARY KEY (`id_product_related`);

--
-- Indexes for table `products_related_attributes`
--
ALTER TABLE `products_related_attributes`
  ADD PRIMARY KEY (`id_product_related`,`id_attribute`) USING BTREE;

--
-- Indexes for table `products_related_images`
--
ALTER TABLE `products_related_images`
  ADD PRIMARY KEY (`id_products_related_image`);

--
-- Indexes for table `products_routes`
--
ALTER TABLE `products_routes`
  ADD PRIMARY KEY (`id_product`,`id_category`,`id_language`);

--
-- Indexes for table `products_views`
--
ALTER TABLE `products_views`
  ADD PRIMARY KEY (`id_product_view`);

--
-- Indexes for table `shipping_methods`
--
ALTER TABLE `shipping_methods`
  ADD PRIMARY KEY (`id_shipping_method`);

--
-- Indexes for table `shipping_methods_language`
--
ALTER TABLE `shipping_methods_language`
  ADD PRIMARY KEY (`id_shipping_method`,`id_language`);

--
-- Indexes for table `shipping_methods_prices`
--
ALTER TABLE `shipping_methods_prices`
  ADD PRIMARY KEY (`id_shipping_method_price`);

--
-- Indexes for table `shipping_methods_weights`
--
ALTER TABLE `shipping_methods_weights`
  ADD PRIMARY KEY (`id_shipping_method_weight`);

--
-- Indexes for table `shipping_methods_zones`
--
ALTER TABLE `shipping_methods_zones`
  ADD PRIMARY KEY (`id_shipping_method_zone`);

--
-- Indexes for table `shipping_zones`
--
ALTER TABLE `shipping_zones`
  ADD PRIMARY KEY (`id_shipping_zone`);

--
-- Indexes for table `shipping_zone_countries`
--
ALTER TABLE `shipping_zone_countries`
  ADD PRIMARY KEY (`id_shipping_zone_countrie`);

--
-- Indexes for table `stock_notices`
--
ALTER TABLE `stock_notices`
  ADD PRIMARY KEY (`id_stock_notice`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users_addresses`
--
ALTER TABLE `users_addresses`
  ADD PRIMARY KEY (`id_user_address`);

--
-- Indexes for table `users_admin`
--
ALTER TABLE `users_admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users_admin_type`
--
ALTER TABLE `users_admin_type`
  ADD PRIMARY KEY (`id_admin_type`);

--
-- Indexes for table `users_billing_addresses`
--
ALTER TABLE `users_billing_addresses`
  ADD PRIMARY KEY (`id_user_billing_address`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id_attribute` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `attributes_value`
--
ALTER TABLE `attributes_value`
  MODIFY `id_attribute_value` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `attributes_value_language`
--
ALTER TABLE `attributes_value_language`
  MODIFY `id_attribute_value` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `carts_codes`
--
ALTER TABLE `carts_codes`
  MODIFY `id_cart_code` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `carts_products`
--
ALTER TABLE `carts_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `categories_custom_routes`
--
ALTER TABLE `categories_custom_routes`
  MODIFY `id_category_custom_route` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories_views`
--
ALTER TABLE `categories_views`
  MODIFY `id_category_view` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `codes`
--
ALTER TABLE `codes`
  MODIFY `id_code` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `codes_rules`
--
ALTER TABLE `codes_rules`
  MODIFY `id_code_rule` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `codes_rules_elements`
--
ALTER TABLE `codes_rules_elements`
  MODIFY `id_code_rule_element` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ct_attributes_html`
--
ALTER TABLE `ct_attributes_html`
  MODIFY `id_attribute_html` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ct_attributes_type`
--
ALTER TABLE `ct_attributes_type`
  MODIFY `id_attribute_type` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ct_codes_rules_add_type`
--
ALTER TABLE `ct_codes_rules_add_type`
  MODIFY `id_code_rule_add_type` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ct_codes_rules_type`
--
ALTER TABLE `ct_codes_rules_type`
  MODIFY `id_code_rule_type` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ct_codes_type`
--
ALTER TABLE `ct_codes_type`
  MODIFY `id_ct_codes_type` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ct_continents`
--
ALTER TABLE `ct_continents`
  MODIFY `id_continent` tinyint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ct_countries`
--
ALTER TABLE `ct_countries`
  MODIFY `id_country` tinyint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `ct_languages`
--
ALTER TABLE `ct_languages`
  MODIFY `id_language` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ct_provinces`
--
ALTER TABLE `ct_provinces`
  MODIFY `id_province` tinyint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `ct_states`
--
ALTER TABLE `ct_states`
  MODIFY `id_state` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id_image` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `newsletters`
--
ALTER TABLE `newsletters`
  MODIFY `id_newsletter` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders_incidents`
--
ALTER TABLE `orders_incidents`
  MODIFY `id_order_incident` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id_payment_method` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id_product` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products_custom_routes`
--
ALTER TABLE `products_custom_routes`
  MODIFY `id_product_custom_route` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products_images`
--
ALTER TABLE `products_images`
  MODIFY `id_product_image` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT for table `products_related`
--
ALTER TABLE `products_related`
  MODIFY `id_product_related` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `products_related_images`
--
ALTER TABLE `products_related_images`
  MODIFY `id_products_related_image` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=654;

--
-- AUTO_INCREMENT for table `products_views`
--
ALTER TABLE `products_views`
  MODIFY `id_product_view` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shipping_methods`
--
ALTER TABLE `shipping_methods`
  MODIFY `id_shipping_method` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `shipping_methods_prices`
--
ALTER TABLE `shipping_methods_prices`
  MODIFY `id_shipping_method_price` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `shipping_methods_weights`
--
ALTER TABLE `shipping_methods_weights`
  MODIFY `id_shipping_method_weight` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `shipping_methods_zones`
--
ALTER TABLE `shipping_methods_zones`
  MODIFY `id_shipping_method_zone` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shipping_zones`
--
ALTER TABLE `shipping_zones`
  MODIFY `id_shipping_zone` smallint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shipping_zone_countries`
--
ALTER TABLE `shipping_zone_countries`
  MODIFY `id_shipping_zone_countrie` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stock_notices`
--
ALTER TABLE `stock_notices`
  MODIFY `id_stock_notice` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_addresses`
--
ALTER TABLE `users_addresses`
  MODIFY `id_user_address` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_admin`
--
ALTER TABLE `users_admin`
  MODIFY `id_admin` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users_admin_type`
--
ALTER TABLE `users_admin_type`
  MODIFY `id_admin_type` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users_billing_addresses`
--
ALTER TABLE `users_billing_addresses`
  MODIFY `id_user_billing_address` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
