CREATE TABLE `tbl_planes` (
  `id_plan` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(65) DEFAULT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `num_tecnicos` int(11) DEFAULT NULL,
  `id_mod` int(11) DEFAULT NULL,
  `costo` decimal(10,2) DEFAULT NULL,
  `tiempo_suscripcion` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_plan`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;


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