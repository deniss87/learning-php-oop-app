-- -----------------------------------------------------
--  Create database (safe only for local development)
-- -----------------------------------------------------
CREATE DATABASE IF NOT EXISTS php_oop_crud_app
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE php_oop_crud_app;

-- -----------------------------------------------------
--  Table: Category
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Category`;
CREATE TABLE `Category` (
  `category_id` smallint NOT NULL AUTO_INCREMENT,
  `category_name` varchar(64) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- -----------------------------------------------------
--  Table: Product
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Products`;
CREATE TABLE `Products` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `product_sku` varchar(128) NOT NULL,
  `product_name` varchar(128) NOT NULL,
  `product_price` float NOT NULL,
  `category_id` smallint NOT NULL,
  `product_size` int DEFAULT NULL,
  `product_weight` float DEFAULT NULL,
  `product_height` int DEFAULT NULL,
  `product_width` int DEFAULT NULL,
  `product_length` int DEFAULT NULL,
  PRIMARY KEY (`product_id`),
  KEY `product_sku` (`product_sku`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `Products_ibfk_1` FOREIGN KEY (`category_id`)
    REFERENCES `Category` (`category_id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- -----------------------------------------------------
--  Insert initial category data
-- -----------------------------------------------------
INSERT INTO `Category` (`category_id`, `category_name`) VALUES
(1, 'DVD'),
(2, 'Book'),
(3, 'Furniture');