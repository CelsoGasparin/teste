SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mvc_creator
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mvc_creator
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mvc_creator` ;
USE `mvc_creator` ;

-- -----------------------------------------------------
-- Table `mvc_creator`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mvc_creator`.`usuario` (
  `id_usuario` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(60) NOT NULL,
  `email` VARCHAR(60) NOT NULL,
  `senha_usuario` VARCHAR(255) NOT NULL,
  `tipo_perfil` ENUM("admin", "usuario") NOT NULL,
  PRIMARY KEY (`id_usuario`));


-- -----------------------------------------------------
-- Table `mvc_creator`.`banco`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mvc_creator`.`banco` (
  `id_banco` INT NOT NULL AUTO_INCREMENT,
  `fk_usuario` INT NOT NULL,
  `nome_banco` VARCHAR(60) NOT NULL,
  `usuario_banco` VARCHAR(60) NOT NULL,
  `senha_banco` VARCHAR(255) NOT NULL,
  `host` VARCHAR(20) NOT NULL,
  `porta` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`id_banco`),
  INDEX `fk_usuario_idx` (`fk_usuario` ASC) VISIBLE,
  CONSTRAINT `fk_usuario`
    FOREIGN KEY (`fk_usuario`)
    REFERENCES `mvc_creator`.`usuario` (`id_usuario`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mvc_creator`.`estilo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mvc_creator`.`estilo` (
  `id_estilo` INT NOT NULL AUTO_INCREMENT,
  `css_customizado` MEDIUMTEXT NULL,
  `conteudo_principal` MEDIUMTEXT NULL,
  `cabecalho` VARCHAR(45) NULL,
  `links` TEXT NULL,
  `cor_primaria` VARCHAR(45) NOT NULL,
  `cor_secundaria` VARCHAR(45) NOT NULL,
  `tamanho_fonte` INT NOT NULL,
  PRIMARY KEY (`id_estilo`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mvc_creator`.`log`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mvc_creator`.`log` (
  `id_log` INT NOT NULL AUTO_INCREMENT,
  `fk_usuario` INT NOT NULL,
  `acao` VARCHAR(255) NOT NULL,
  `data` DATETIME NOT NULL,
  PRIMARY KEY (`id_log`),
  INDEX `fk_usuario_idx` (`fk_usuario` ASC) VISIBLE,
  CONSTRAINT `fk_usuario`
    FOREIGN KEY (`fk_usuario`)
    REFERENCES `mvc_creator`.`usuario` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mvc_creator`.`projeto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mvc_creator`.`projeto` (
  `id_projeto` INT NOT NULL AUTO_INCREMENT,
  `id_usuario` INT NOT NULL,
  `fk_banco` INT NOT NULL,
  `fk_estilo` INT NULL,
  `ultimo_download` INT NULL,
  `nome_projeto` VARCHAR(60) NOT NULL,
  `data_criacao` DATETIME NOT NULL,
  `prazo_de_vida` INT NOT NULL,
  `caminho_armazenamento` VARCHAR(255) NOT NULL,
  `comentarios` TINYINT NOT NULL,
  `views` TINYINT NOT NULL,
  PRIMARY KEY (`id_projeto`),
  INDEX `id_usuario_idx` (`id_usuario` ASC) VISIBLE,
  INDEX `fk_banco_idx` (`fk_banco` ASC) VISIBLE,
  INDEX `fk_estilo_idx` (`fk_estilo` ASC) VISIBLE,
  UNIQUE INDEX `fk_estilo_UNIQUE` (`fk_estilo` ASC) VISIBLE,
  INDEX `fk_log_idx` (`ultimo_download` ASC) VISIBLE,
  CONSTRAINT `id_usuario`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `mvc_creator`.`usuario` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_banco`
    FOREIGN KEY (`fk_banco`)
    REFERENCES `mvc_creator`.`banco` (`id_banco`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_estilo`
    FOREIGN KEY (`fk_estilo`)
    REFERENCES `mvc_creator`.`estilo` (`id_estilo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_log`
    FOREIGN KEY (`ultimo_download`)
    REFERENCES `mvc_creator`.`log` (`id_log`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `mvc_creator`.`tabela`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mvc_creator`.`tabela` (
  `id_tabela` INT NOT NULL AUTO_INCREMENT,
  `fk_banco` INT NOT NULL,
  `nome_tabela` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`id_tabela`),
  INDEX `fk_banco_idx` (`fk_banco` ASC) VISIBLE,
  CONSTRAINT `fk_banco`
    FOREIGN KEY (`fk_banco`)
    REFERENCES `mvc_creator`.`banco` (`id_banco`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mvc_creator`.`atributo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mvc_creator`.`atributo` (
  `id_atributo` INT NOT NULL AUTO_INCREMENT,
  `fk_tabela` INT NOT NULL,
  `fk_atributo` INT DEFAULT NULL,
  `nome_atributo` VARCHAR(60) NOT NULL,
  `tipo` TINYTEXT NOT NULL,
  `PK` TINYINT NOT NULL,
  `NN` TINYINT NOT NULL,
  `AI` TINYINT NOT NULL,
  `UQ` TINYINT NOT NULL,
  PRIMARY KEY (`id_atributo`),
  INDEX `fk_tabela_idx` (`fk_tabela` ASC) VISIBLE,
  INDEX `fk_atributo_idx` (`fk_atributo` ASC) VISIBLE,
  CONSTRAINT `fk_tabela`
    FOREIGN KEY (`fk_tabela`)
    REFERENCES `mvc_creator`.`tabela` (`id_tabela`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_atributo`
    FOREIGN KEY (`fk_atributo`)
    REFERENCES `mvc_creator`.`atributo` (`id_atributo`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;