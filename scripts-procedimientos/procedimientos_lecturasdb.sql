DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ps_consolidado_lecturas`(
in table_name varchar(65),
in mes int(11)
)
BEGIN
SET @table_name = table_name;
SET @sql_text = concat('select * from ',@table_name,' where MONTH(created_at)=',mes);
PREPARE stmt FROM @sql_text;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
 END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ps_tecnicos_asignacion_lecturas`(
in table_name varchar(65)
)
BEGIN
SET @table_name = table_name;
SET @sql_text = concat('select T1.agencia,T1.sector,T1.ruta, count(T1.ruta) as cantidad_ruta,T3.nombres,T3.apellidos from ',@table_name ,' as T1 inner join orden_trabajo as T2 on T1.id=T2.id_lectura inner join empresa_db.tbl_tecnico as T3 on T3.id_tecn=T2.id_tecnico where T1.estado=1 and T2.estado=0 and T3.borrado=0 and T3.asignado=1 group by T1.ruta,T3.id_tecn,T1.sector,T1.agencia');
PREPARE stmt FROM @sql_text;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
 END$$
DELIMITER ;
