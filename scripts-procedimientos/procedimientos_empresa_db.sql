DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_estadistica_diario`(
in empresa int,
in inicio date,
in fin date)
BEGIN

	select T1.n9cono, COUNT(T1.n9cono) as cantidad, IF(T1.n9cono like '%10%', "NOTIFICACIONES",IF(T1.n9cono like '%40%', "RECONEXIONES",IF(T1.n9cono like '%30%', "CORTE",IF(T1.n9cono like '%50%', "RETIRO DE MEDIDOR",NULL)))) AS actividad  from tbl_actividaddiaria as T1 
	where (T1.estado=2 or T1.estado=3) and T1.id_emp=empresa
    and date(T1.created_at) BETWEEN  inicio and fin
    group by T1.n9cono;
    END$$
DELIMITER ;


DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_estadistica_mes`(
in empresa int,
in mes int)
BEGIN

	select T1.n9cono, COUNT(T1.n9cono) as cantidad, IF(T1.n9cono like '%10%', "NOTIFICACIONES",IF(T1.n9cono like '%40%', "RECONEXIONES",IF(T1.n9cono like '%30%', "CORTE",IF(T1.n9cono like '%50%', "RETIRO DE MEDIDOR",NULL)))) AS actividad  from tbl_actividaddiaria as T1 
	where (T1.estado=2 or T1.estado=3) and T1.id_emp=empresa and
    MONTH(T1.created_at)=mes
    group by T1.n9cono;
    END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_estadistica_tecnicos`(
in empresa int,
in inicio date,
in fin date)
BEGIN

	select date(T1.created_at) as fecha,T3.id_tecn,T3.nombres,T3.apellidos,T1.n9cono,count(T1.n9cono) as cantidad, IF(T1.n9cono like '%10%', "NOTIFICACIONES",IF(T1.n9cono like '%40%', "RECONEXIONES",IF(T1.n9cono like '%30%', "CORTE",IF(T1.n9cono like '%50%', "RETIRO DE MEDIDOR",NULL)))) AS actividad  from tbl_actividaddiaria as T1 
	inner join tbl_ordentrabajo as T2 on T1.id_act=T2.id_act
	inner join tbl_tecnico as T3 on T3.id_tecn= T2.id_tecn
	where (T1.estado=2 or T1.estado=3) and T1.id_emp=empresa
    and DATE(T1.created_at) BETWEEN  inicio and fin
    group by T1.n9cono, T3.id_tecn,date(T1.created_at);
    END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_estadistica_tecnicos_mes`(
in empresa int,
in mes int)
BEGIN

	select date(T1.created_at) as fecha,T3.id_tecn,T3.nombres,T3.apellidos,T1.n9cono,count(T1.n9cono) as cantidad, IF(T1.n9cono like '%10%', "NOTIFICACIONES",IF(T1.n9cono like '%40%', "RECONEXIONES",IF(T1.n9cono like '%30%', "CORTE",IF(T1.n9cono like '%50%', "RETIRO DE MEDIDOR",NULL)))) AS actividad  from tbl_actividaddiaria as T1 
	inner join tbl_ordentrabajo as T2 on T1.id_act=T2.id_act
	inner join tbl_tecnico as T3 on T3.id_tecn= T2.id_tecn
	where (T1.estado=2 or T1.estado=3) and T1.id_emp=empresa and
    MONTH(T1.created_at)=mes
    group by T1.n9cono, T3.id_tecn,date(T1.created_at);
    END$$
DELIMITER ;

