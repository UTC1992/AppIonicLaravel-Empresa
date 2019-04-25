CREATE TABLE IF NOT EXISTS `empresa_db`.`tbl_planes` (
  `id_plan` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(65) NULL,
  `descripcion` VARCHAR(200) NULL,
  `num_tecnicos` INT NULL,
  `id_mod` INT NULL,
  PRIMARY KEY (`id_plan`))
ENGINE = InnoDB

CREATE TABLE IF NOT EXISTS `empresa_db`.`tbl_plan_empresa` (
  `id_plan_empresa` INT NOT NULL AUTO_INCREMENT,
  `id_plan` INT NOT NULL,
  `id_emp` INT(11) NOT NULL,
  PRIMARY KEY (`id_plan_empresa`),
  INDEX `fk_tbl_plan_empresa_tbl_planes1_idx` (`id_plan` ASC),
  INDEX `fk_tbl_plan_empresa_tbl_empresa1_idx` (`id_emp` ASC),
  CONSTRAINT `fk_tbl_plan_empresa_tbl_planes1`
    FOREIGN KEY (`id_plan`)
    REFERENCES `empresa_db`.`tbl_planes` (`id_plan`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_plan_empresa_tbl_empresa1`
    FOREIGN KEY (`id_emp`)
    REFERENCES `empresa_db`.`tbl_empresa` (`id_emp`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB