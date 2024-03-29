<?php

/**
 * @author www.intercambiosvirtuales.org
 * @copyright 2013
 */

date_default_timezone_set('America/Buenos_Aires');

class ServiciosHistorial {
	
	//10
	function traerFechasRestantes($reffecha,$idjugador,$idequipo,$refsuspendido) {
		$sqlRest = "SELECT count(*) FROM dbsuspendidosfechas where reffecha <= ".$reffecha." and refequipo =".$idequipo." and refjugador =".$idjugador." and refsuspendido =".$refsuspendido;
		//return $sqlRest;
		$resRest = $this-> query($sqlRest,0);
		$restan = 0;
		if (mysql_num_rows($resRest)>0) {
			$restan = mysql_result($resRest,0,0);	
		}
		return $restan;
	}
	
	
	//0
	function TraerFechasPorTorneoZona($idTorneo, $idZona) {
		$sql = "select
					distinct f.reffecha , ff.tipofecha
				from		dbfixture f
				inner
				join		dbtorneoge tge
				on			tge.idtorneoge = f.reftorneoge_a or tge.idtorneoge = f.reftorneoge_b
				inner
				join		dbtorneos t
				on			tge.reftorneo = t.idtorneo
				inner
				join		tbfechas ff
				on			ff.idfecha = f.reffecha
				where		f.chequeado = 1 and t.idtorneo = ".$idTorneo." and tge.refgrupo = ".$idZona."
				
				order by	f.refFecha desc
				";
		return $this-> query($sql,0);	
	}
	
	
	//2
	function traerResultadosPorTorneoZonaFecha($idtorneo,$idzona,$idfecha) {
		$sql = "select 

		       (select ea.nombre from dbequipos ea where ea.idequipo = t.equipoa) as equipo1,
		       t.resultadoa,
		       t.resultadob,
		       (select ea.nombre from dbequipos ea where ea.idequipo = t.equipob) as equipo2, 
		       t.fechajuego,
		       t.fecha,
		       t.hora,
			   t.idfixture,
			   t.cancha
		 
				from (
				select 
				fi.idfixture,
				(select e.idequipo 
				        from dbtorneoge tge

				        inner 
				        join dbtorneos t
				        on tge.reftorneo = t.idtorneo
				        
						inner 
				        join tbtipotorneo tp
				        on t.reftipotorneo = tp.idtipotorneo

				        inner 
				        join dbequipos e
				        on e.idequipo = tge.refequipo
				        
				        inner 
				        join dbgrupos g
				        on g.idgrupo = tge.refgrupo
				        where tge.idtorneoge = fi.reftorneoge_a and g.idgrupo=".$idzona." and t.idtorneo = ".$idtorneo.") as equipoa,
				
				(case when fi.resultado_a is null then (select
												(case when sum(gg.goles) is null then (case when fi.chequeado = 1 then 0 else null end) else sum(gg.goles) end)
												from		tbgoleadores gg
												where gg.reffixture = fi.idfixture and gg.refequipo = (select tge.refequipo 
																										from dbtorneoge tge
																										inner 
																										join dbtorneos t
																										on tge.reftorneo = t.idtorneo
																										inner 
																										join dbequipos e
																										on e.idequipo = tge.refequipo
																										inner 
																										join dbgrupos g
																										on g.idgrupo = tge.refgrupo
																										where tge.idtorneoge = fi.reftorneoge_a))
				else fi.resultado_a end) as resultadoa,
				
				(select e.idequipo 
				        from dbtorneoge tge

				        inner 
				        join dbtorneos t
				        on tge.reftorneo = t.idtorneo
				        
						inner 
				        join tbtipotorneo tp
				        on t.reftipotorneo = tp.idtipotorneo

				        inner 
				        join dbequipos e
				        on e.idequipo = tge.refequipo
				        
				        inner 
				        join dbgrupos g
				        on g.idgrupo = tge.refgrupo
				        where tge.idtorneoge = fi.reftorneoge_b and g.idgrupo=".$idzona." and t.idtorneo = ".$idtorneo.") as equipob,
				
				(case when fi.resultado_b is null then (select
															(case when sum(gg.goles) is null then (case when fi.chequeado = 1 then 0 else null end) else sum(gg.goles) end)
															from		tbgoleadores gg
															where gg.reffixture = fi.idfixture and gg.refequipo = (select tge.refequipo 
						from dbtorneoge tge
						inner 
						join dbtorneos t
						on tge.reftorneo = t.idtorneo
						inner 
						join dbequipos e
						on e.idequipo = tge.refequipo
						inner 
						join dbgrupos g
						on g.idgrupo = tge.refgrupo
						where tge.idtorneoge = fi.reftorneoge_b))
							else fi.resultado_b end) as resultadob,
				
				(select g.nombre
				        from dbtorneoge tge

				        inner 
				        join dbtorneos t
				        on tge.reftorneo = t.idtorneo
				        
						inner 
				        join tbtipotorneo tp
				        on t.reftipotorneo = tp.idtipotorneo

				        inner 
				        join dbequipos e
				        on e.idequipo = tge.refequipo
				        
				        inner 
				        join dbgrupos g
				        on g.idgrupo = tge.refgrupo
				        where tge.idtorneoge = fi.reftorneoge_b and g.idgrupo=".$idzona." and t.idtorneo = ".$idtorneo.") as zona,
				        
				        
				fi.fechajuego,
				f.idfecha as fecha,
				DATE_FORMAT(fi.hora,'%k:%i') as hora,
				fi.cancha
				
				
				from dbfixture as fi
				        inner 
				        join tbfechas AS f
				        on fi.reffecha = f.idfecha
				
				        inner 
				        join dbtorneoge tge
				        on tge.idtorneoge = fi.reftorneoge_b

				        inner 
				        join dbtorneos t
				        on tge.reftorneo = t.idtorneo
				        
						inner 
				        join tbtipotorneo tp
				        on t.reftipotorneo = tp.idtipotorneo

				        inner 
				        join dbgrupos g
				        on g.idgrupo = tge.refgrupo
				
				where g.idgrupo=".$idzona." and t.idtorneo = ".$idtorneo."
				order by fecha desc
				) as t
				where t.fecha = ".$idfecha;
		$res = $this->query($sql,0);
		return $res;
	}
	

	//1
	function TraerFixturePorZonaTorneo($idtorneo,$zona,$idfecha) {
		
		$refTorneo = $idtorneo;
		
		$sql = '
			select
			fix.nombre,
			fix.partidos,
			fix.ganados,
			fix.empatados,
			fix.perdidos,
			fix.golesafavor,
			(case when rr.idreemplazo is null then fix.golesencontra + COALESCE(rrr.golesencontra,0) else fix.golesencontra + rr.golesencontra end) as golesencontra,
			fix.golesafavor - (case when rr.idreemplazo is null then fix.golesencontra + COALESCE(rrr.golesencontra,0) else fix.golesencontra + rr.golesencontra end) as diferencia,
			(case when rr.idreemplazo is null then fix.pts + COALESCE(rrr.puntos,0) else fix.pts + rr.puntos end) as pts,
			fix.idequipo,
			fix.puntos,
			fix.equipoactivo,
			cast((fix.golesafavor / fix.partidos) as decimal(4,2)) as porcentajegoles,
			round((fix.pts * 100) / (fix.partidos * 3)) as efectividad,
			/*(select count(*) from tbsuspendidos where refequipo = fix.idequipo and (motivos = "Roja Directa" or motivos = "Doble Amarilla")) as rojas,*/
			coalesce(ro.rojas,0) as rojas,
			/*(select sum(amarillas) from tbamonestados where refequipo = fix.idequipo and amarillas <> 2) as amarillas,*/
			coalesce(aaa.amarillas,0) as amarillas,
			(case when rr.idreemplazo is null then 0 else 1 end) as reemplzado,
(case when rrr.idreemplazo is null then 0 else 1 end) as volvio,
	(case
        when rv.idreemplazovolvio is null then 0
        else 1
    end) as reemplzadovolvio
			from
			(
				select 
		       r.nombre,
		       count(*) as partidos,
		       sum(case when r.resultado_a > r.resultado_b then 1 else 0 end) as ganados, 
		       sum(case when r.resultado_a = r.resultado_b then 1 else 0 end) as empatados,
		       sum(case when r.resultado_a < r.resultado_b then 1 else 0 end) as perdidos,
		       sum(r.resultado_a) as golesafavor,
		       sum(r.resultado_b) as golesencontra,
		       (sum(r.resultado_a) - sum(r.resultado_b)) as diferencia,
		       ((sum(case when r.resultado_a > r.resultado_b then 1 else 0 end) * 3) +
		        (sum(case when r.resultado_a = r.resultado_b then 1 else 0 end) * 1)) as pts,
		        r.idequipo,
				fp.puntos,
				(case when r.equipoactivo = 0 then false else true end) as equipoactivo,
		r.idtorneo
		
				from (
				SELECT
				e.idequipo,
				e.nombre,
				t.activo,
				t.idtorneo,
				f.tipofecha,
				fi.hora,
				(case when fi.resultado_a is null then (select
												(case when sum(gg.goles) is null then (case when fi.chequeado = 1 then 0 else null end) else sum(gg.goles) end)
												from		tbgoleadores gg
												where gg.reffixture = fi.idfixture and gg.refequipo = (select tge.refequipo 
																										from dbtorneoge tge
																										inner 
																										join dbtorneos t
																										on tge.reftorneo = t.idtorneo
																										inner 
																										join dbequipos e
																										on e.idequipo = tge.refequipo
																										inner 
																										join dbgrupos g
																										on g.idgrupo = tge.refgrupo
																										where tge.idtorneoge = fi.reftorneoge_a))
				else fi.resultado_a end) as resultado_a,
				(case when fi.resultado_b is null then (select
															(case when sum(gg.goles) is null then (case when fi.chequeado = 1 then 0 else null end) else sum(gg.goles) end)
															from		tbgoleadores gg
															where gg.reffixture = fi.idfixture and gg.refequipo = (select tge.refequipo 
						from dbtorneoge tge
						inner 
						join dbtorneos t
						on tge.reftorneo = t.idtorneo
						inner 
						join dbequipos e
						on e.idequipo = tge.refequipo
						inner 
						join dbgrupos g
						on g.idgrupo = tge.refgrupo
						where tge.idtorneoge = fi.reftorneoge_b))
							else fi.resultado_b end) as resultado_b,
				fi.reffecha,
				tge.refgrupo,
				tge.activo as equipoactivo
				FROM
				dbtorneoge tge
				Inner Join dbequipos e ON tge.refequipo = e.idequipo
				inner join dbgrupos g on tge.refgrupo = g.idgrupo
				Inner Join dbtorneos t ON t.idtorneo = tge.reftorneo
				Inner Join dbfixture fi ON tge.idtorneoge = fi.reftorneoge_a
				inner join tbtipotorneo tp ON tp.idtipotorneo = t.reftipotorneo
				inner join tbfechas f ON fi.refFecha = f.idfecha
				where tge.refgrupo = '.$zona.'
				and t.idtorneo = '.$idtorneo.'
				and fi.reffecha <= '.$idfecha.' 
				UNION all
				
				SELECT
				e.idequipo,
				e.nombre,
				t.activo,
				t.idtorneo,
				f.tipofecha,
				fi.hora,
				(case when fi.resultado_b is null then (select
															(case when sum(gg.goles) is null then (case when fi.chequeado = 1 then 0 else null end) else sum(gg.goles) end)
															from		tbgoleadores gg
															where gg.reffixture = fi.idfixture and gg.refequipo = (select tge.refequipo 														
						from dbtorneoge tge
						inner 
						join dbtorneos t
						on tge.reftorneo = t.idtorneo
						inner 
						join dbequipos e
						on e.idequipo = tge.refequipo
						inner 
						join dbgrupos g
						on g.idgrupo = tge.refgrupo
						where tge.idtorneoge = fi.reftorneoge_b))
							else fi.resultado_b end) as resultado_b,
				(case when fi.resultado_a is null then (select
												(case when sum(gg.goles) is null then (case when fi.chequeado = 1 then 0 else null end) else sum(gg.goles) end)
												from		tbgoleadores gg
												where gg.reffixture = fi.idfixture and gg.refequipo = (select tge.refequipo 
																										from dbtorneoge tge
																										inner 
																										join dbtorneos t
																										on tge.reftorneo = t.idtorneo
																										inner 
																										join dbequipos e
																										on e.idequipo = tge.refequipo
																										inner 
																										join dbgrupos g
																										on g.idgrupo = tge.refgrupo
																										where tge.idtorneoge = fi.reftorneoge_a))
				else fi.resultado_a end) as resultado_a,
				fi.reffecha,
				tge.refgrupo,
				tge.activo as equipoactivo	
				FROM
				dbtorneoge tge
				Inner Join dbequipos e ON tge.refequipo = e.idequipo
				inner join dbgrupos g on tge.refgrupo = g.idgrupo
				Inner Join dbtorneos t ON t.idtorneo = tge.reftorneo
				Inner Join dbfixture fi ON tge.idtorneoge = fi.reftorneoge_b
				inner join tbtipotorneo tp ON tp.idtipotorneo = t.reftipotorneo
				inner join tbfechas f ON fi.refFecha = f.idfecha
				where tge.refgrupo = '.$zona.'
				and t.idtorneo = '.$idtorneo.'
				and fi.reffecha <= '.$idfecha.' 
				) as r
				inner
				join	(select refequipo,puntos as puntos, reftorneo from tbconducta where reffecha ='.$idfecha.'
				) fp
				on		r.idequipo = fp.refequipo and fp.reftorneo = r.idtorneo
				inner join dbtorneos t ON t.idtorneo = fp.reftorneo
				group by r.nombre,r.idequipo 
) as fix

left join dbreemplazo rr on rr.refequiporeemplazado = fix.idequipo and rr.reffecha <= '.$idfecha.' and rr.reftorneo = fix.idtorneo
left join dbreemplazo rrr on rrr.refequipo = fix.idequipo and rrr.reffecha <= '.$idfecha.' and rrr.reftorneo = fix.idtorneo
left join
	dbreemplazovolvio rv ON rv.refreemplazo = rrr.idreemplazo and rv.refzona = '.$zona.'
	
	left join
	(select

	sa.nombre,
	sum(sa.puntos) as amarillas,
	sa.idequipo
from (
select 
					f.tipofecha,
						e.nombre,
						count(a.amarillas) as puntos,
						f.idfecha,
						e.idequipo
				from
					tbamonestados a
				inner join dbequipos e ON e.idequipo = a.refequipo
				inner join dbfixture fix ON fix.idfixture = a.reffixture
				inner join tbfechas f ON f.idfecha = fix.reffecha
				inner join dbtorneoge tge ON tge.refequipo = e.idequipo
					and fix.reftorneoge_a = tge.idtorneoge
				where
					a.amarillas = 1 and tge.reftorneo = '.$refTorneo.'
						and fix.reffecha <= '.$idfecha.'
				group by f.tipofecha , e.nombre , f.idfecha , e.idequipo 
				
				union all 
				
				select 
					f.tipofecha,
						e.nombre,
						count(a.amarillas) as puntos,
						f.idfecha,
						e.idequipo
				from
					tbamonestados a
				inner join dbequipos e ON e.idequipo = a.refequipo
				inner join dbfixture fix ON fix.idfixture = a.reffixture
				inner join tbfechas f ON f.idfecha = fix.reffecha
				inner join dbtorneoge tge ON tge.refequipo = e.idequipo
					and fix.reftorneoge_b = tge.idtorneoge
				where
					a.amarillas = 1 and tge.reftorneo = '.$refTorneo.'
						and fix.reffecha <= '.$idfecha.'
				group by f.tipofecha , e.nombre , f.idfecha , e.idequipo) sa
			group by 
			sa.nombre,
			sa.idequipo) aaa ON aaa.idequipo = fix.idequipo
			
			
	left join
	(select

	sa.nombre,
	sum(sa.puntos) as rojas,
	sa.idequipo
from (
select 
					f.tipofecha,
						e.nombre,
						sum(1) as puntos,
						f.idfecha,
						e.idequipo
				from
					tbsuspendidos a
				inner join dbequipos e ON e.idequipo = a.refequipo
				inner join (select 
					refsuspendido, min(reffecha) as idfecha
				from
					dbsuspendidosfechas
				group by refsuspendido) sp ON sp.refsuspendido = a.idsuspendido
				inner join tbfechas f ON f.idfecha = sp.idfecha - 1
				inner join dbfixture fix ON fix.idfixture = a.reffixture
				inner join dbtorneoge tge ON tge.refequipo = e.idequipo
					and fix.reftorneoge_a = tge.idtorneoge
				where
					tge.reftorneo = '.$refTorneo.' and fix.reffecha <= '.$idfecha.' and (a.motivos like "%Roja Directa%" or a.motivos like "%Doble Amarilla%") 
				group by f.tipofecha , e.nombre , f.idfecha , e.idequipo 
				
				union all 
				
				select 
					f.tipofecha,
						e.nombre,
						sum(1) as puntos,
						f.idfecha,
						e.idequipo
				from
					tbsuspendidos a
				inner join dbequipos e ON e.idequipo = a.refequipo
				inner join (select 
					refsuspendido, min(reffecha) as idfecha
				from
					dbsuspendidosfechas
				group by refsuspendido) sp ON sp.refsuspendido = a.idsuspendido
				inner join tbfechas f ON f.idfecha = sp.idfecha - 1
				inner join dbfixture fix ON fix.idfixture = a.reffixture
				inner join dbtorneoge tge ON tge.refequipo = e.idequipo
					and fix.reftorneoge_b = tge.idtorneoge
				where
					tge.reftorneo = '.$refTorneo.' and fix.reffecha <= '.$idfecha.' and (a.motivos like "%Roja Directa%" or a.motivos like "%Doble Amarilla%") 
				group by f.tipofecha , e.nombre , f.idfecha , e.idequipo) sa
			group by 
			sa.nombre,
			sa.idequipo) ro ON ro.idequipo = fix.idequipo
	
	
				order by (case when rr.idreemplazo is null then fix.pts + COALESCE(rrr.puntos,0) else fix.pts + rr.puntos end) desc, fix.puntos,
	  fix.golesafavor - (case when rr.idreemplazo is null then fix.golesencontra + COALESCE(rrr.golesencontra,0) else fix.golesencontra + rr.golesencontra end) desc,
	  fix.golesafavor desc,
	  (case when rr.idreemplazo is null then fix.golesencontra + COALESCE(rrr.golesencontra,0) else fix.golesencontra + rr.golesencontra end),
	  fix.ganados desc';
		$res = $this->query($sql,0);
		return $res;	
		//return $sql;	
	}
	
	
	//3
	function Goleadores($idtorneo,$zona,$idfecha) {
		$sql = 'select
				t.apyn,t.nombre,t.cantidad,t.reemplzado, t.volvio, t.refequipo, t.refjugador, t.reemplzadovolvio
				from
				(
				select
				r.apyn, r.nombre, sum(r.goles) as cantidad,r.reemplzado, r.volvio,r.refequipo, r.refjugador,r.reemplzadovolvio
				from
				(
					select
					j.apyn, e.nombre, a.goles, 
					(case when rr.idreemplazo is null then 0 else 1 end) as reemplzado,
					(case when rrr.idreemplazo is null then 0 else 1 end) as volvio,
					a.refequipo,
					a.refjugador,
					(case
						when rv.idreemplazovolvio is null then 0
						else 1
					end) as reemplzadovolvio
					from	tbgoleadores a
					inner
					join	dbfixture fi
					on		a.reffixture = fi.Idfixture
					inner
					join	dbjugadores j
					on		j.idjugador = a.refjugador
					inner
					join	dbequipos e
					on		e.idequipo = a.refequipo
					inner 
					join dbtorneoge tge
					on tge.idtorneoge = fi.reftorneoge_b
				
					inner 
					join dbtorneos t
					on tge.reftorneo = t.idtorneo
					
					inner 
					join tbtipotorneo tp
					on t.reftipotorneo = tp.idtipotorneo
					
left join dbreemplazo rr on rr.refequiporeemplazado = a.refequipo and rr.reffecha <= '.$idfecha.' and rr.reftorneo = t.idtorneo
left join dbreemplazo rrr on rrr.refequipo = a.refequipo and rrr.reffecha <= '.$idfecha.' and rrr.reftorneo = t.idtorneo
left join
	dbreemplazovolvio rv ON rv.refreemplazo = rrr.idreemplazo and rv.refzona = '.$zona.'			
					where	t.idtorneo = '.$idtorneo.' and tge.refgrupo = '.$zona.' and fi.reffecha <= '.$idfecha.'

				) r
				group by r.apyn, r.nombre,r.reemplzado, r.volvio,r.refequipo, r.refjugador, r.reemplzadovolvio
				) t
				order by t.cantidad desc';
			return $this-> query($sql,0);
	}
	
	
	
	//5
	function SuspendidosNuevo($idtorneo,$zona,$reffecha) {
		
				$sql = 'select
				r.apyn, r.nombre, r.motivos, r.cantidadfechas as cantidad,r.reffecha, r.refjugador, r.refequipo
				, r.refsuspendido ,r.reemplzado , r.volvio, r.reemplzadovolvio

				from
				(
				select
				j.apyn, e.nombre, ss.motivos, ss.cantidadfechas,min(sp.reffecha) - 1 as reffecha, ss.refjugador, ss.refequipo,sp.refsuspendido,
(case when rr.idreemplazo is null then 0 else 1 end) as reemplzado,
(case when rrr.idreemplazo is null then 0 else 1 end) as volvio,
	(case
        when rv.idreemplazovolvio is null then 0
        else 1
    end) as reemplzadovolvio
				from		tbsuspendidos ss
				inner
				join		dbsuspendidosfechas sp
				on			ss.refjugador = sp.refjugador and ss.refequipo = sp.refequipo and ss.idsuspendido = sp.refsuspendido
				inner
				join	dbjugadores j
				on		j.idjugador = ss.refjugador and j.expulsado <> 1
				inner
				join	dbequipos e
				on		e.idequipo = ss.refequipo
				inner join (select distinct ff.Idfixture,t.idtorneo from dbfixture ff
				inner join dbtorneoge tge ON tge.idtorneoge = ff.reftorneoge_a or tge.idtorneoge = ff.reftorneoge_b
				inner join dbtorneos t ON tge.reftorneo = t.idtorneo
				inner join tbtipotorneo tp ON t.reftipotorneo = tp.idtipotorneo
				where t.idtorneo = '.$idtorneo.' and tge.refgrupo = '.$zona.') d
				on			d.idfixture = ss.reffixture
									
left join dbreemplazo rr on rr.refequiporeemplazado = e.idequipo and rr.reffecha <= '.$reffecha.' and rr.reftorneo = d.idtorneo
left join dbreemplazo rrr on rrr.refequipo = e.idequipo and rrr.reffecha <= '.$reffecha.' and rrr.reftorneo = d.idtorneo								
left join
	dbreemplazovolvio rv ON rv.refreemplazo = rrr.idreemplazo and rv.refzona = '.$zona.' 
										
				where	sp.reffecha <= '.$reffecha.' +1
				group by j.apyn, e.nombre, ss.motivos, ss.cantidadfechas, ss.refjugador, ss.refequipo,sp.refsuspendido,j.expulsado
				) r
				
				order by r.nombre, r.apyn,r.cantidadfechas desc';
			return $this-> query($sql,0);
			//return $sql;
	}
	
	
	function SuspendidosUltimaFecha($idtorneo,$zona,$reffecha) {
		$resCantDeEquipos = $this->traerResultadosPorTorneoZonaFecha($idtorneo,$zona,$reffecha);
		
		$cantEquipos = (mysql_num_rows($resCantDeEquipos)*4) + 20;
		
		
				$sql = 'select
				r.apyn, r.nombre, r.motivos, r.cantidadfechas as cantidad,r.reffecha, r.refjugador, r.refequipo
				, r.refsuspendido ,r.reemplzado , r.volvio, r.reemplzadovolvio

				from
				(
				select
				j.apyn, e.nombre, ss.motivos, ss.cantidadfechas,min(sp.reffecha) as reffecha, ss.refjugador, ss.refequipo,sp.refsuspendido,
(case when rr.idreemplazo is null then 0 else 1 end) as reemplzado,
(case when rrr.idreemplazo is null then 0 else 1 end) as volvio,
	(case
        when rv.idreemplazovolvio is null then 0
        else 1
    end) as reemplzadovolvio
				from		tbsuspendidos ss
				inner
				join		dbsuspendidosfechas sp
				on			ss.refjugador = sp.refjugador and ss.refequipo = sp.refequipo and ss.idsuspendido = sp.refsuspendido
				inner
				join	dbjugadores j
				on		j.idjugador = ss.refjugador and j.expulsado <> 1
				inner
				join	dbequipos e
				on		e.idequipo = ss.refequipo
				inner join (select distinct ff.Idfixture,t.idtorneo, ff.reffecha from dbfixture ff
				inner join dbtorneoge tge ON tge.idtorneoge = ff.reftorneoge_a or tge.idtorneoge = ff.reftorneoge_b
				inner join dbtorneos t ON tge.reftorneo = t.idtorneo
				inner join tbtipotorneo tp ON t.reftipotorneo = tp.idtipotorneo
				where t.reftipotorneo = '.$idtorneo.' and tge.refgrupo = '.$zona.') d
				on			d.idfixture = ss.reffixture
									
left join dbreemplazo rr on rr.refequiporeemplazado = e.idequipo and rr.reffecha <= '.$reffecha.' and rr.reftorneo = d.idtorneo
left join dbreemplazo rrr on rrr.refequipo = e.idequipo and rrr.reffecha <= '.$reffecha.' and rrr.reftorneo = d.idtorneo								
left join
	dbreemplazovolvio rv ON rv.refreemplazo = rrr.idreemplazo and rv.refzona = '.$zona.' 
										
				where	d.reffecha = '.$cantEquipos.'
				group by j.apyn, e.nombre, ss.motivos, ss.cantidadfechas, ss.refjugador, ss.refequipo,sp.refsuspendido,j.expulsado
				) r
				
				order by r.nombre, r.apyn,r.cantidadfechas desc';
			return $this-> query($sql,0);
			//return $sql;
	}
	
	//6
	function SuspendidosPorSiempre($idtorneo,$zona,$reffecha) {
		
			$sql = 'select
				r.apyn, r.nombre, r.motivos, r.cantidadfechas as cantidad,r.reffecha, r.refjugador, r.refequipo
				, r.refsuspendido ,r.reemplzado , r.volvio, r.reemplzadovolvio

				from
				(
				select
				j.apyn, e.nombre, ss.motivos, ss.cantidadfechas,min(sp.reffecha) - 1 as reffecha, ss.refjugador, ss.refequipo,sp.refsuspendido,
(case when rr.idreemplazo is null then 0 else 1 end) as reemplzado,
(case when rrr.idreemplazo is null then 0 else 1 end) as volvio,
	(case
        when rv.idreemplazovolvio is null then 0
        else 1
    end) as reemplzadovolvio
				from		tbsuspendidos ss
				inner
				join		dbsuspendidosfechas sp
				on			ss.refjugador = sp.refjugador and ss.refequipo = sp.refequipo and ss.idsuspendido = sp.refsuspendido
				inner
				join	dbjugadores j
				on		j.idjugador = ss.refjugador and j.expulsado = 1
				inner
				join	dbequipos e
				on		e.idequipo = ss.refequipo
				inner join (select distinct ff.Idfixture,t.idtorneo from dbfixture ff
				inner join dbtorneoge tge ON tge.idtorneoge = ff.reftorneoge_a or tge.idtorneoge = ff.reftorneoge_b
				inner join dbtorneos t ON tge.reftorneo = t.idtorneo
				inner join tbtipotorneo tp ON t.reftipotorneo = tp.idtipotorneo
				where t.idtorneo = '.$idtorneo.' and tge.refgrupo = '.$zona.') d
				on			d.idfixture = ss.reffixture
									
left join dbreemplazo rr on rr.refequiporeemplazado = e.idequipo and rr.reffecha <= '.$reffecha.' and rr.reftorneo = d.idtorneo
left join dbreemplazo rrr on rrr.refequipo = e.idequipo and rrr.reffecha <= '.$reffecha.' and rrr.reftorneo = d.idtorneo								
left join
	dbreemplazovolvio rv ON rv.refreemplazo = rrr.idreemplazo and rv.refzona = '.$zona.' 
										
				where	sp.reffecha <= '.$reffecha.' +1
				group by j.apyn, e.nombre, ss.motivos, ss.cantidadfechas, ss.refjugador, ss.refequipo,sp.refsuspendido,j.expulsado
				) r
				
				order by r.nombre, r.apyn,r.cantidadfechas desc';
			return $this-> query($sql,0);
	}
	
	
	
	//7
	function TraerJugadoresFixtureA($idfixture) {
		$sql = "
				select
				j.idjugador as idjugadorA,
				(case when ss.idsuspendido is not null then '1' else null end) as RojasA,
				a.amarillas as amarillasA,
				g.goles as golesA,
				j.apyn as apynA
				from		dbjugadores j
				inner
				join		dbequipos e
				on			j.idequipo = e.idequipo
				inner
				join		dbtorneoge tge
				on			tge.refequipo = e.idequipo
				inner
				join		dbfixture fi
				on			fi.reftorneoge_a = tge.idtorneoge 
				
				left
				join		tbgoleadores g
				on			g.refjugador = j.idjugador and g.refequipo = j.idequipo and g.reffixture = fi.idfixture
				
				left
				join		tbamonestados a
				on			a.refjugador = j.idjugador and a.refequipo = j.idequipo and a.reffixture = fi.idfixture and a.amarillas <> 2
				
				left
				join		tbsuspendidos ss
				on			ss.refjugador = j.idjugador and ss.refequipo = j.idequipo and ss.reffixture = fi.idfixture and (ss.motivos like '%Roja Directa%' or ss.motivos like '%Doble Amarilla%')
				
				where		fi.idfixture = ".$idfixture;
		return $this-> query($sql,0);	
		
	}
	
	//8
	function TraerJugadoresFixtureB($idfixture) {
		$sql = "
				select
				j.idjugador as idjugadorB,
				(case when ss.idsuspendido is not null then '1' else null end) as RojasB,
				a.amarillas as amarillasB,
				g.goles as golesB,
				j.apyn as apynB
				from		dbjugadores j
				inner
				join		dbequipos e
				on			j.idequipo = e.idequipo
				inner
				join		dbtorneoge tge
				on			tge.refequipo = e.idequipo
				inner
				join		dbfixture fi
				on			fi.reftorneoge_b = tge.idtorneoge 
				
				left
				join		tbgoleadores g
				on			g.refjugador = j.idjugador and g.refequipo = j.idequipo and g.reffixture = fi.idfixture
				
				left
				join		tbamonestados a
				on			a.refjugador = j.idjugador and a.refequipo = j.idequipo and a.reffixture = fi.idfixture and a.amarillas <> 2
				
				left
				join		tbsuspendidos ss
				on			ss.refjugador = j.idjugador and ss.refequipo = j.idequipo and ss.reffixture = fi.idfixture and (ss.motivos like '%Roja Directa%' or ss.motivos like '%Doble Amarilla%')
				
				where		fi.idfixture = ".$idfixture;
		return $this-> query($sql,0);	
		
	}
	

	
	//4
	function traerAcumuladosAmarillasPorTorneoZona($idtipoTorneo,$idzona,$idfecha) {
		$sql = "select
				t.refequipo, t.nombre, t.apyn, t.dni, (case when t.cantidad > 3 then mod(t.cantidad,3) else t.cantidad end) as cantidad,ultimafecha,fecha,t.reemplzado, t.volvio, t.refjugador, t.reemplzadovolvio
				from
				(
				select
					a.refequipo, e.nombre, j.apyn, j.dni, count(a.amarillas) as cantidad,max(fi.reffecha) as ultimafecha, max(ff.tipofecha) as fecha
					, (case when rr.idreemplazo is null then false else true end) as reemplzado
					, (case when rrr.idreemplazo is null then 0 else 1 end) as volvio,
					a.refjugador,
					(case
						when rv.idreemplazovolvio is null then 0
						else 1
					end) as reemplzadovolvio
					from		tbamonestados a
					inner
					join		dbequipos e
					on			e.idequipo = a.refequipo
					inner
					join		dbjugadores j
					on			j.idjugador = a.refjugador
					/*inner
					join		dbfixture fi
					on			fi.idfixture = a.reffixture*/
					inner 
					join 		(select fix.idfixture,fix.reffecha,tt.idtorneo from dbfixture fix
									inner join dbtorneoge tge ON fix.reftorneoge_a = tge.idtorneoge
									or fix.reftorneoge_b = tge.idtorneoge
									inner join dbtorneos tt ON tt.idtorneo = tge.reftorneo
									and tt.idtorneo in (".$idtipoTorneo.")
									group by idfixture,reffecha) fi
					on			fi.idfixture = a.reffixture
					inner
					join		tbfechas ff
					on			ff.idfecha = fi.reffecha
left join dbreemplazo rr on rr.refequiporeemplazado = e.idequipo and rr.reffecha <= ".$idfecha." and rr.reftorneo = fi.idtorneo
left join dbreemplazo rrr on rrr.refequipo = e.idequipo and rrr.reffecha <= ".$idfecha." and rrr.reftorneo = fi.idtorneo
left join
	dbreemplazovolvio rv ON rv.refreemplazo = rrr.idreemplazo and rv.refzona = ".$idzona."
					
					where	a.refequipo in (select
											distinct e.idequipo
											from		dbtorneoge tge
											inner
											join		dbequipos e
											on			e.idequipo = tge.refequipo
											inner
											join		dbfixture fix
											on			fix.reftorneoge_a = tge.idtorneoge or fix.reftorneoge_b = tge.idtorneoge
											inner
											join		dbtorneos t
											on			t.idtorneo = tge.reftorneo
											inner
											join		tbtipotorneo tp
											on			tp.idtipotorneo = t.reftipotorneo
											where		t.idtorneo in (".$idtipoTorneo.") and tge.refgrupo = ".$idzona.")
					and a.amarillas <> 2
					and fi.reffecha <= ".$idfecha."
					group by a.refequipo, e.nombre, j.apyn, j.dni, a.refjugador
				) t
					where (cantidad <> 3 and ultimafecha < ".$idfecha.") or (cantidad = 3 and ultimafecha = ".$idfecha.") or (cantidad < 3 and ultimafecha = ".$idfecha.") or (cantidad > 3 and ultimafecha = ".$idfecha.")
					
					order by (case when t.cantidad > 3 then mod(t.cantidad,3) else t.cantidad end) desc,t.nombre, t.apyn";	
		return $this-> query($sql,0);
	}
	

	
	function query($sql,$accion) {
		
		
		require_once 'appconfig.php';

		$appconfig	= new appconfig();
		$datos		= $appconfig->conexion();
		$hostname	= $datos['hostname'];
		$database	= $datos['database'];
		$username	= $datos['username'];
		$password	= $datos['password'];
		
		$conex = mysql_connect($hostname,$username,$password) or die ("no se puede conectar".mysql_error());
		
		mysql_select_db($database);
		
		$result = mysql_query($sql,$conex);
		if ($accion && $result) {
			$result = mysql_insert_id();
		}
		mysql_close($conex);
		return $result;
		
	}
	
	}
?>