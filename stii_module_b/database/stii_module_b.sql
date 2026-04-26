CREATE DATABASE IF NOT EXISTS `stii_module_b`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE `stii_module_b`;

-- ------------------------------------------------------------
-- Table: companies
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `companies` (
    `id`                       INT            NOT NULL AUTO_INCREMENT,
    `company_name`             VARCHAR(255)   NOT NULL,
    `company_address`          TEXT           DEFAULT NULL,
    `company_telephone_number` VARCHAR(50)    DEFAULT NULL,
    `company_email_address`    VARCHAR(255)   DEFAULT NULL,
    `owner_name`               VARCHAR(255)   DEFAULT NULL,
    `owner_mobile_number`      VARCHAR(50)    DEFAULT NULL,
    `owner_email_address`      VARCHAR(255)   DEFAULT NULL,
    `contact_name`             VARCHAR(255)   DEFAULT NULL,
    `contact_mobile_number`    VARCHAR(50)    DEFAULT NULL,
    `contact_email_address`    VARCHAR(255)   DEFAULT NULL,
    `is_active`                TINYINT(1)     NOT NULL DEFAULT 1,
    `created_at`               TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: products
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
    `id`                  INT             NOT NULL AUTO_INCREMENT,
    `company_id`          INT             NOT NULL,
    `gtin`                VARCHAR(14)     NOT NULL,
    `name`                VARCHAR(255)    NOT NULL,
    `name_fr`             VARCHAR(255)    DEFAULT NULL,
    `description`         TEXT            DEFAULT NULL,
    `description_fr`      TEXT            DEFAULT NULL,
    `brand_name`          VARCHAR(255)    DEFAULT NULL,
    `country_origin`      VARCHAR(100)    DEFAULT NULL,
    `gross_weight`        DECIMAL(10, 2)  DEFAULT NULL,
    `net_content_weight`  DECIMAL(10, 2)  DEFAULT NULL,
    `weight_unit`         VARCHAR(20)     DEFAULT NULL,
    `image_path`          VARCHAR(500)    DEFAULT NULL,
    `is_hidden`           TINYINT(1)      NOT NULL DEFAULT 0,
    `created_at`          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_gtin` (`gtin`),
    CONSTRAINT `fk_products_company`
        FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Sample data
-- ------------------------------------------------------------
INSERT INTO `companies`
    (`company_name`, `company_address`, `company_telephone_number`, `company_email_address`,
    `owner_name`, `owner_mobile_number`, `owner_email_address`,
    `contact_name`, `contact_mobile_number`, `contact_email_address`, `is_active`)
VALUES
    ('FreshCo Inc.', 'Zamboanga City, Philippines', '+63-912-345-6789', 'info@freshco.ph',
    'Juan Dela Cruz', '+63-912-000-0001', 'juan@freshco.ph',
    'Maria Santos', '+63-912-000-0002', 'maria@freshco.ph', 1);

INSERT INTO `products`
    (`company_id`, `gtin`, `name`, `name_fr`, `description`, `description_fr`,
    `brand_name`, `country_origin`, `gross_weight`, `net_content_weight`, `weight_unit`, `is_hidden`)
VALUES
    (1, '1234567890128', 'Apple Juice', 'Jus de Pomme',
    'Fresh 100% natural apple juice.', 'Jus de pomme 100% naturel.',
    'FreshBrand', 'Philippines', 1.50, 1.20, 'kg', 0);
