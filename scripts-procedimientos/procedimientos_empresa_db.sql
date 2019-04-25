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

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_reporte_medidor_cortes`(
in empresa int,
in inicio date,
in fin date,
in medidor varchar(20) )
BEGIN
select T0.n9meco as medidor,T0.n9leco as lectura,  T1.created_at as fecha, T0.n9nomb as nombre_cliente, 
T0.n9refe as referencia ,T0.cusecu as direccion, T0.CUCOON, T0.CUCOOE,
IF(T0.n9cono like '%10%', "Notificaciones",IF(T0.n9cono like '%40%', "Reconexiones",IF(T0.n9cono like '%30%', "Cortes",IF(T0.n9cono like '%50%', "Retiro de medidor",NULL)))) AS actividad,
T1.observacion, T0.referencia as referencia_estado, CONCAT(T2.nombres," ",T2.apellidos) as tecnico,
IF(T1.estado=0, "Asignado",IF(T1.estado=1 ,"Finalizado",IF(T1.estado=2,"Finalizado sin terminar","Otro"))) as estado_actividad,
IF(T2.borrado=0, "Activo",IF(T2.borrado=1,"Borrardo","Otro")) as estado_tecnico from tbl_actividaddiaria as T0
inner join tbl_ordentrabajo as T1 on T0.id_act= T1.id_act
inner join tbl_tecnico as T2 on T2.id_tecn = T1.id_tecn
where Date(T1.created_at) between inicio and fin
and T0.n9meco=medidor and T0.id_emp=empresa;
    END$$
DELIMITER ;
