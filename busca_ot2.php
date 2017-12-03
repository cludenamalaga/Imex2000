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

	$proceso = $_POST["proceso"];
	$no_ot = $_POST["orden"];
	$fec_actual = $_POST["fecha"];
	$ref = $_POST["ref"];
	$id_cliente = $_POST["id_clie"];
	$descrip = $_POST["descrip"];
	$estado = $_POST["estado"];

	// Si hay Proceso
	if (is_empty($proceso) or ($proceso=='T')){
		$cond_pro = "";
	} else {
		$cond_pro = " and ots.id_proceso ='".$proceso."'";
	}

	// Si hay OT
	if (is_empty($no_ot)){
		$cond_ot = "";
	} else {
		$cond_ot = " and ots.no_ot ='".$no_ot."'";
	}

	// Si hay Fecha
	if (is_empty($fec_actual)){
		$cond_fecha = "";
	} else {
		$cond_fecha = " and	ots.fecha = '".date( "Y-m-d", strtotime(str_replace('/', '-', $fec_actual)))."'";
	}	

	// Si hay referencia
	if (is_empty($ref)){
		$cond_ref ="";
	} else {
		$cond_ref = " and ots.referencia like '%".$ref."%'";
	}

	// Si hay Cliente
	if (is_empty($id_cliente) or ($id_cliente=='T')){
		$cond_clie ="";
	} else {
		$cond_clie = " and clientes.id_cliente = '".$id_cliente."'";
	}	

	// Si hay descrip
	if (is_empty($descrip)){
		$cond_desc ="";
	} else {
		$cond_desc = " and ots.descripcion like '%".$descrip."%'";
	}	

	// Si hay Estado
	if (is_empty($estado) or ($estado=='T')){
		$cond_estado ="";
	} else {
		$cond_estado = " and ots.estado ='".$estado."'";
	}	

	$ordenar = " order by ots.no_ot desc";

	$sql= "select ots.id_ot,
					ots.no_ot,
					ots.referencia,
					ots.id_proceso,
					procesos.descrip,
					ots.fecha,
					ots.id_taller,
					talleres.taller,
					ots.id_cliente,
					clientes.raz_soc,
					ots.descripcion,
					ots.inicio,
					ots.fin,
					ots.valor,
					ots.avance,
					ots.estado
				from ots, talleres, clientes, procesos
				where 	ots.id_taller = talleres.id_taller and
						ots.id_cliente = clientes.id_cliente and
						ots.id_proceso = procesos.id_proceso and
						ots.estado = '1'";

	$sql .= $cond_pro;
	$sql .= $cond_ot;
	$sql .= $cond_fecha;
	$sql .= $cond_desc;
	$sql .= $cond_ref;
	$sql .= $cond_clie;
	$sql .= $cond_estado;
	$sql .= $ordenar;

	$resulta = mysql_query($sql);

	if (mysql_num_rows($resulta) != 0){ //Si hay OT
		$jsondata['encontrado'] = "S";
		$jsondata['tabla'] = "<table id='tbl_result' class='table table-hover table-condensed table-striped'>
									<thead>
										<th class='text-center'>OT</th>
										<th class='text-center'>FECHA</th>
										<th class='text-center'>PROCESO</th>
										<th class='text-center'>REF</th>
										<th class='text-center'>CLIENTE</th>										
										<th class='text-center'>DESCRIPCION</th>
										<th class='text-center'>TALLER</th>
										<th class='text-center'>%</th>
										<th class='text-center'>ESTADO</th>
									</thead>
									<tbody>";
		while ($row = mysql_fetch_array($resulta)){ 
				if ($row['estado']==1){//Activo
					$jsondata['tabla'] .=   "<tr id='".$row['id_ot']."' style='cursor:pointer;' onclick='escojer(".$row['id_ot'].")'>";
				}

				   $jsondata['tabla'] .=   "<td>".$row['no_ot']."</td>
				   							<td>".date( "d/m/Y", strtotime($row['fecha'])) ."</td>
				   							<td>".$row['descrip']."</td>
				   							<td>".$row['referencia']."</td>
											<td>".$row['raz_soc']."</td>
											<td>".$row['descripcion']."</td>
											<td>".strtoupper($row['taller'])."</td>
											<td>".$row['avance']."</td>";

											if ($row['estado']==1){//Activo
													$jsondata['tabla'] .= "<td>ACTIVO</td>";
											}																					

											$jsondata['tabla'] .= "</tr>";
		}

		$jsondata['tabla'] .= "</tbody></table>";

	} else { //No hay OT
		$jsondata['encontrado'] = "N";

	}

	echo json_encode($jsondata);

?>