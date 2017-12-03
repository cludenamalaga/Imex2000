<?php
	ob_start();
	session_start();

	include ('conexion.php');

	mysql_select_db('imex2000_sistema', $sistema);  

	function is_empty($var, $allow_false = false, $allow_ws = false) {
	    if (!isset($var) || is_null($var) || ($allow_ws == false && trim($var) == "" && !is_bool($var)) || ($allow_false === false && is_bool($var) && $var === false) || (is_array($var) && empty($var))) {   
	        return true;
	    } else {
	        return false;
	    }
	}

	$jsondata = array();

	$no_ot = $_POST["orden"];
	$proceso = $_POST["proceso"];

	// Si hay OT
	if (is_empty($no_ot)){
		$cond_ot = "";
	} else {
		$cond_ot = " and ots.no_ot ='".$no_ot."'";

		$titulo = "CONSULTA GENERAL DE OTS (OT ".str_pad($no_ot, 5, "0", STR_PAD_LEFT).")";
	}

	// Si hay Proceso
	if ($proceso == 'X'){
		$cond_fecha = "";
	} else {
		$sql_proc = "select * from procesos where id_proceso = '".$proceso."'";
		$result_proc = mysql_query($sql_proc);

		$ano = mysql_result($result_proc, 0, "ano");
		$mes = mysql_result($result_proc, 0, "mes");
		$desc_proc = mysql_result($result_proc, 0, "descrip");

		$cond_fecha = " and id_proceso = ".$proceso;

		$titulo = "CONSULTA GENERAL DE OTS (PROCESO ".trim(strtoupper($desc_proc)).")";
	}	

	$ordenar = " order by ots.fecha desc";

	$sql= "SELECT 	ots.id_ot,
					ots.fecha,
					ots.no_ot,
					ots.descripcion,
					clientes.raz_soc,
					ots.inicio,
					ots.fin,
					ots.avance, 
					ots.estado,
					estados.estado,
					ots.valor
				FROM
					ots, clientes, estados
				WHERE
					ots.id_cliente = clientes.id_cliente AND
				ots.estado= estados.id_estado";

	if ( (is_empty($no_ot) and ($proceso == "X") ) or (!is_empty($no_ot) and ($proceso != "X")) ) {
		$sql = "";
	} else {
		if (!is_empty($no_ot) and ($proceso == "X")) {
			$sql .= $cond_ot;
		} else {
			if (is_empty($no_ot) and ($proceso != "X")) {
				$sql .= $cond_fecha;
			}

		}
	}

	$sql .= $ordenar;

	$resulta_todo = mysql_query($sql);

	if (mysql_num_rows($resulta_todo) != 0){ //Si hay Registro Mano Obra
		$jsondata['encontrado'] = "S";

		$jsondata['tabla'] .= "
					<table id='tbl_ots' class='table table-hover table-condensed table-striped' style='font-size:11px;'>
							<caption><strong><font style='font-size:14px'>".$titulo."</font></strong></caption>
							<thead>
								<th style='width:4%'></th>
								<th class='text-center'>EMISION</th>
								<th class='text-center'>OT</th>
								<th>DESCRIPCION</th>
								<th>RAZON SOCIAL</th>
								<th class='text-center'>INICIO</th>
								<th class='text-center'>FIN</th>
								<th class='text-right'>PRESUP</th>
								<th class='text-right'>%</th>
								<th class='text-right'>MO</th>
								<th class='text-right'>MAT</th>
								<th class='text-right'>OC</th>
								<th class='text-right'>SUB.TOT</th>
								<th class='text-right'>GASTOS</th>
								<th class='text-right'>UTILIDAD</th>
								<th class='text-right'>TOTAL</th>
							</thead>

							<tbody>";

		while ($row = mysql_fetch_array($resulta_todo)){ 

				$jsondata['tabla'] .=  "<tr id='".$row['id_ot']."'>
											<td></td>
											<td class='text-center'>".date( "d/m/Y", strtotime($row['fecha'])) ."</td>
				   							<td class='text-center'>".$row['no_ot']."</td>
				   							<td>".trim($row['descripcion'])."</td>
				   							<td>".trim($row['raz_soc'])."</td>";		   

											if ($row['inicio'] == "0000-00-00"){
						$jsondata['tabla'] .=  "<td class='text-center'></td>";
												} else {
						$jsondata['tabla'] .=  "<td class='text-center'>".date( "d/m/Y", strtotime($row['inicio']))."</td>";
											}

											if ($row['fin'] == "0000-00-00"){
						$jsondata['tabla'] .=  "<td class='text-center'></td>";
												} else {
						$jsondata['tabla'] .=  "<td class='text-center'>".date( "d/m/Y", strtotime($row['fin']))."</td>";
											}

					$jsondata['tabla'] .=	"	<td class='text-right' style='color:blue'>
													<strong>". number_format($row['valor'], 2, '.', ',')."</strong>
												</td>
												<td class='text-right'>".$row['avance']."</td>";


							$sql1 = "select sum(precio_tipo_hora * num_horas) as tot_mo 
											from mano_obra
											where mano_obra.no_ot = '".$row['no_ot']."'";

							$result1 = mysql_query($sql1);
							$tot_mo = mysql_result($result1, 0, "tot_mo");

					$jsondata['tabla'] .=	"	<td class='text-right'>". number_format($tot_mo, 2, '.', ',')."</td>";
					//----------------------------------------------------------------------------------------

							$sql2 = "select sum(cantidad * precio) as tot_mat 
											from reg_mat
											where reg_mat.no_ot = '".$row['no_ot']."'";

							$result2 = mysql_query($sql2);
							$tot_mat = mysql_result($result2, 0, "tot_mat");

					$jsondata['tabla'] .=	"	<td class='text-right'>". number_format($tot_mat, 2, '.', ',')."</td>";

					//----------------------------------------------------------------------------------------
							$sql3 = "select sum(total) as tot_oc 
											from reg_oc
											where reg_oc.no_ot = '".$row['no_ot']."'";

							$result3 = mysql_query($sql3);
							$tot_oc = mysql_result($result3, 0, "tot_oc");

					$jsondata['tabla'] .=	"	<td class='text-right'>". number_format($tot_oc, 2, '.', ',')."</td>";

					//----------------------------------------------------------------------------------------
					$sub_tot = $tot_mo + $tot_mat + $tot_oc;

					$jsondata['tabla'] .=	"	<td class='text-right'>". number_format($sub_tot, 2, '.', ',')."</td>";

					//----------------------------------------------------------------------------------------

					$ano_em = date("Y",strtotime($row['fecha']));
					$mes_em = date("m",strtotime($row['fecha']));

					$sql4 ="select * from procesos where ano='".$ano_em."' and mes='".$mes_em."'";
					$result4 = mysql_query($sql4);

					$porc_gastos = mysql_result($result4, 0, "gastos");
					$porc_uti    = mysql_result($result4, 0, "utilidad");
					$porc_igv    = mysql_result($result4, 0, "igv");

					$gastos = $sub_tot * $porc_gastos;
					$uti 	= $sub_tot * $porc_uti;
					$igv 	= $sub_tot * $porc_igv;

					$total = $sub_tot + $gastos + $uti;

					$jsondata['tabla'] .=	"	<td class='text-right'>". number_format($gastos, 2, '.', ',')."</td>";
					$jsondata['tabla'] .=	"	<td class='text-right'>". number_format($uti, 2, '.', ',')."</td>";
					$jsondata['tabla'] .=	"	<td class='text-right' style='color:blue'>
													<strong>". number_format($total, 2, '.', ',')."</strong>
												</td>";

					$jsondata['tabla'] .=	"</tr>";
		}


		$jsondata['tabla'] .= "</tbody>
					</table>
				</div>
			</div>";

	} else {
		$jsondata['encontrado'] = "N";
	}
	
	$jsondata['sql'] = $sql;

	echo json_encode($jsondata);
?>