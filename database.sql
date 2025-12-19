-- Database Schema for Projet_Symfony

-- Table: user
CREATE TABLE `user` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `username` VARCHAR(180) NOT NULL,
    `email` VARCHAR(180) NOT NULL,
    `roles` JSON NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    UNIQUE INDEX `UNIQ_IDENTIFIER_USERNAME` (`username`),
    UNIQUE INDEX `UNIQ_IDENTIFIER_EMAIL` (`email`),
    PRIMARY KEY(`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Table: contact
CREATE TABLE `contact` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `subject` VARCHAR(255) NOT NULL,
    `message` LONGTEXT NOT NULL,
    `created_at` DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    PRIMARY KEY(`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Table: service
CREATE TABLE `service` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `description` LONGTEXT DEFAULT NULL,
    `price` NUMERIC(10, 2) DEFAULT NULL,
    `created_at` DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    `updated_at` DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
    PRIMARY KEY(`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Table: messenger_messages (standard Symfony messenger table)
CREATE TABLE `messenger_messages` (
    `id` BIGINT AUTO_INCREMENT NOT NULL,
    `body` LONGTEXT NOT NULL,
    `headers` LONGTEXT NOT NULL,
    `queue_name` VARCHAR(190) NOT NULL,
    `created_at` DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    `available_at` DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    `delivered_at` DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
    INDEX `IDX_75EA56E0FB7336F0` (`queue_name`),
    INDEX `IDX_75EA56E0E3BD61CE` (`available_at`),
    INDEX `IDX_75EA56E016BA31DB` (`delivered_at`),
    PRIMARY KEY(`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
