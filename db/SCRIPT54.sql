-- -----------------------------------------------------
-- Table `capitalfuturo`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `capitalfuturo`.`usuario` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `sobrenome` VARCHAR(45) NULL,
  `email` VARCHAR(200) NOT NULL,
  `nascimento` DATE NULL,
  `minibio` TEXT NULL,
  `genero` INT NOT NULL,
  `fone` VARCHAR(100) NULL DEFAULT 'null',
  `status` TINYINT(1) NOT NULL DEFAULT true,
  `senha` CHAR(32) NOT NULL,
  `logado` TINYINT(1) NOT NULL DEFAULT false,
  `fotoperfil` VARCHAR(250) NOT NULL DEFAULT '/dist/img/into/image_default.jpg',
  `fotocapa` VARCHAR(250) NULL DEFAULT 'null',
  `datcad` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `capitalfuturo`.`usuario_tem_usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `capitalfuturo`.`usuario_tem_usuario` (
  `usuario_anfitriao` INT UNSIGNED NOT NULL,
  `usuario_convidado` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`usuario_anfitriao`, `usuario_convidado`),
  INDEX `fk_usuario_has_usuario_usuario2_idx` (`usuario_convidado` ASC),
  INDEX `fk_usuario_has_usuario_usuario1_idx` (`usuario_anfitriao` ASC),
  CONSTRAINT `fk_usuario_has_usuario_usuario1`
    FOREIGN KEY (`usuario_anfitriao`)
    REFERENCES `capitalfuturo`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_has_usuario_usuario2`
    FOREIGN KEY (`usuario_convidado`)
    REFERENCES `capitalfuturo`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `capitalfuturo`.`convite`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `capitalfuturo`.`convite` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email_anfitriao` VARCHAR(100) NOT NULL,
  `email_convidado` VARCHAR(100) NOT NULL,
  `convite` CHAR(32) NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT false,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `capitalfuturo`.`chat`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `capitalfuturo`.`chat` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_de` INT(11) NOT NULL,
  `id_para` INT(11) NOT NULL,
  `mensagem` VARCHAR(255) NOT NULL,
  `time` INT(11) NOT NULL,
  `lido` TINYINT(1) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `capitalfuturo`.`usuario_tem_convite`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `capitalfuturo`.`usuario_tem_convite` (
  `usuario_anfitriao` INT UNSIGNED NOT NULL,
  `usuario_convidado` INT UNSIGNED NOT NULL,
  `convite_id` INT UNSIGNED NOT NULL,
  INDEX `fk_usuario_has_convite_convite1_idx` (`convite_id` ASC),
  INDEX `fk_usuario_has_convite_usuario1_idx` (`usuario_convidado` ASC),
  CONSTRAINT `fk_usuario_has_convite_usuario1`
    FOREIGN KEY (`usuario_convidado`)
    REFERENCES `capitalfuturo`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_has_convite_convite1`
    FOREIGN KEY (`convite_id`)
    REFERENCES `capitalfuturo`.`convite` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `capitalfuturo`.`convite_interno`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `capitalfuturo`.`convite_interno` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email_anfitriao` VARCHAR(100) NOT NULL,
  `email_convidado` VARCHAR(100) NOT NULL,
  `convite` CHAR(32) NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT false,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `capitalfuturo`.`usuario_tem_convite_interno`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `capitalfuturo`.`usuario_tem_convite_interno` (
  `usuario_anfitriao` INT UNSIGNED NOT NULL,
  `usuario_convidado` INT UNSIGNED NOT NULL,
  `convite_interno_id` INT UNSIGNED NOT NULL,
  INDEX `fk_usuario_has_convite_interno_usuario1_idx` (`usuario_anfitriao` ASC),
  INDEX `fk_usuario_tem_convite_interno_convite_interno1_idx` (`convite_interno_id` ASC),
  CONSTRAINT `fk_usuario_has_convite_interno_usuario1`
    FOREIGN KEY (`usuario_anfitriao`)
    REFERENCES `capitalfuturo`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_tem_convite_interno_convite_interno1`
    FOREIGN KEY (`convite_interno_id`)
    REFERENCES `capitalfuturo`.`convite_interno` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `capitalfuturo`.`bug`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `capitalfuturo`.`bug` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `bug` TEXT NOT NULL,
  `dat_bug` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `capitalfuturo`.`report_bug`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `capitalfuturo`.`report_bug` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` INT UNSIGNED NOT NULL,
  `bug_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_report_usuario1_idx` (`usuario_id` ASC),
  INDEX `fk_report_bug_bug1_idx` (`bug_id` ASC),
  CONSTRAINT `fk_report_usuario1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `capitalfuturo`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_report_bug_bug1`
    FOREIGN KEY (`bug_id`)
    REFERENCES `capitalfuturo`.`bug` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `capitalfuturo`.`categoria_projeto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `capitalfuturo`.`categoria_projeto` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `categoria` VARCHAR(80) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `capitalfuturo`.`projeto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `capitalfuturo`.`projeto` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `descricao` TEXT NOT NULL,
  `onde` VARCHAR(100) NULL,
  `custo` VARCHAR(45) NULL,
  `video` VARCHAR(250) NULL,
  `foto` VARCHAR(250) NULL,
  `orcamento` VARCHAR(250) NULL,
  `dat_ini` DATETIME NOT NULL,
  `dat_fim` DATETIME NOT NULL,
  `categoria_projeto_id` INT UNSIGNED NOT NULL,
  `usuario_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_projeto_categoria_projeto1_idx` (`categoria_projeto_id` ASC),
  INDEX `fk_projeto_usuario1_idx` (`usuario_id` ASC),
  CONSTRAINT `fk_projeto_categoria_projeto1`
    FOREIGN KEY (`categoria_projeto_id`)
    REFERENCES `capitalfuturo`.`categoria_projeto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_projeto_usuario1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `capitalfuturo`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `capitalfuturo`.`pref_invest`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `capitalfuturo`.`pref_invest` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `preferencia1` VARCHAR(25) NOT NULL,
  `preferencia2` VARCHAR(25) NOT NULL,
  `preferencia3` VARCHAR(25) NOT NULL,
  `usuario_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_pref_invest_usuario1_idx` (`usuario_id` ASC),
  CONSTRAINT `fk_pref_invest_usuario1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `capitalfuturo`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `capitalfuturo`.`social`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `capitalfuturo`.`social` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `bitbucket` VARCHAR(250) NULL,
  `dropbox` VARCHAR(250) NULL,
  `facebook` VARCHAR(250) NULL,
  `flickr` VARCHAR(250) NULL,
  `foursquare` VARCHAR(250) NULL,
  `github` VARCHAR(250) NULL,
  `googleplus` VARCHAR(250) NULL,
  `instagram` VARCHAR(250) NULL,
  `linkedin` VARCHAR(250) NULL,
  `tumblr` VARCHAR(250) NULL,
  `twitter` VARCHAR(250) NULL,
  `vk` VARCHAR(250) NULL,
  `usuario_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_social_usuario1_idx` (`usuario_id` ASC),
  CONSTRAINT `fk_social_usuario1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `capitalfuturo`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;