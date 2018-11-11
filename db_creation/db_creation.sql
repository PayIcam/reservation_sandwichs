-- MySQL Script generated by MySQL Workbench
-- Sat Oct 27 18:01:11 2018
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema reservation_cafet
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema reservation_cafet
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `reservation_cafet` DEFAULT CHARACTER SET utf8 ;
USE `reservation_cafet` ;

-- -----------------------------------------------------
-- Table `reservation_cafet`.`config`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservation_cafet`.`config` ;

CREATE TABLE IF NOT EXISTS `reservation_cafet`.`config` (
  `days_displayed` INT UNSIGNED NOT NULL,
  `default_quota` INT UNSIGNED NOT NULL,
  `default_reservation_closure_time` TIME NOT NULL,
  `default_pickup_time` TIME NOT NULL)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reservation_cafet`.`days`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservation_cafet`.`days` ;

CREATE TABLE IF NOT EXISTS `reservation_cafet`.`days` (
  `day_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `quota` INT UNSIGNED NOT NULL,
  `reservation_opening_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reservation_closure_date` DATETIME NOT NULL,
  `pickup_date` DATETIME NOT NULL,
  `is_removed` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`day_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reservation_cafet`.`sandwiches`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservation_cafet`.`sandwiches` ;

CREATE TABLE IF NOT EXISTS `reservation_cafet`.`sandwiches` (
  `sandwich_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `default_quota` INT UNSIGNED NOT NULL,
  `is_removed` TINYINT(1) UNSIGNED NOT NULL,
  PRIMARY KEY (`sandwich_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reservation_cafet`.`day_has_sandwiches`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservation_cafet`.`day_has_sandwiches` ;

CREATE TABLE IF NOT EXISTS `reservation_cafet`.`day_has_sandwiches` (
  `day_id` INT UNSIGNED NOT NULL,
  `sandwich_id` INT UNSIGNED NOT NULL,
  `quota` INT UNSIGNED NOT NULL,
  `is_removed` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`day_id`, `sandwich_id`),
  INDEX `fk_sandwiches_has_days_days1_idx` (`day_id` ASC),
  INDEX `fk_sandwiches_has_days_sandwiches_idx` (`sandwich_id` ASC),
  CONSTRAINT `fk_sandwiches_has_days_sandwiches`
    FOREIGN KEY (`sandwich_id`)
    REFERENCES `reservation_cafet`.`sandwiches` (`sandwich_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sandwiches_has_days_days1`
    FOREIGN KEY (`day_id`)
    REFERENCES `reservation_cafet`.`days` (`day_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reservation_cafet`.`purchases_possibilities`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservation_cafet`.`purchases_possibilities` ;

CREATE TABLE IF NOT EXISTS `reservation_cafet`.`purchases_possibilities` (
  `possibility_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `article_id` INT UNSIGNED NOT NULL,
  `is_removed` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`possibility_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reservation_cafet`.`reservations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reservation_cafet`.`reservations` ;

CREATE TABLE IF NOT EXISTS `reservation_cafet`.`reservations` (
  `reservation_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `firstname` VARCHAR(45) NOT NULL,
  `lastname` VARCHAR(45) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `promo` VARCHAR(45) NOT NULL,
  `status` ENUM('A', 'V', 'W') NOT NULL DEFAULT 'W',
  `reservation_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_date` DATETIME NULL DEFAULT NULL,
  `pickup_date` DATETIME NULL DEFAULT NULL,
  `possibility_id` INT UNSIGNED NOT NULL,
  `day_id` INT UNSIGNED NOT NULL,
  `sandwich_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`reservation_id`),
  INDEX `fk_reservations_purchases_possibilities1_idx` (`possibility_id` ASC),
  INDEX `fk_reservations_day_has_sandwiches1_idx` (`day_id` ASC, `sandwich_id` ASC),
  CONSTRAINT `fk_reservations_purchases_possibilities1`
    FOREIGN KEY (`possibility_id`)
    REFERENCES `reservation_cafet`.`purchases_possibilities` (`possibility_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_reservations_day_has_sandwiches1`
    FOREIGN KEY (`day_id` , `sandwich_id`)
    REFERENCES `reservation_cafet`.`day_has_sandwiches` (`day_id` , `sandwich_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
