<?php
	ob_start();
	session_start();

	include ('conexion.php');

	mysql_select_db('imex2000_sistema', $sistema);  

	$jsondata = array();

	$orden_trabajo = $_POST["ot_id"];

	$sql= "select ots.id_ot,
				ots.no_ot,
				ots.id_proceso,
				procesos.descrip,
				ots.referencia,
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
				ots.estado,
				ots.motivo,
				ots.observ
			from ots, talleres, clientes, procesos
			where 	ots.id_taller = talleres.id_taller and
					ots.id_cliente = clientes.id_cliente and
					ots.id_proceso = procesos.id_proceso and
					ots.id_ot ='".$orden_trabajo."'";

	$resulta = mysql_query($sql);

	if (mysql_num_rows($resulta) != 0){
		$id_ot 		= mysql_result($resulta, 0, "id_ot");
		$no_ot 		= mysql_result($resulta, 0, "no_ot");
		$desc_proc	= mysql_result($resulta, 0, "descrip");
		$referencia = mysql_result($resulta, 0, "referencia");
		$fecha 		= mysql_result($resulta, 0, "fecha");
		$id_taller  = mysql_result($resulta, 0, "id_taller");
		$taller     = mysql_result($resulta, 0, "taller");
		$id_cliente	= mysql_result($resulta, 0, "id_cliente");
		$raz_soc	= mysql_result($resulta, 0, "raz_soc");
		$descrip    = mysql_result($resulta, 0, "descripcion");	
		$inicio     = mysql_result($resulta, 0, "inicio");
		$fin        = mysql_result($resulta, 0, "fin");			
		$valor      = mysql_result($resulta, 0, "valor");
		$avance 	= mysql_result($resulta, 0, "avance");
		$estado		= mysql_result($resulta, 0, "estado");
		$motivo		= mysql_result($resulta, 0, "motivo");
		$obs		= mysql_result($resulta, 0, "observ");
	} 

	$jsondata['tabla'] = "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12' style='margin-top: 5px;'>
								<table id='tbl_result' class='table table-condensed' style='margin-bottom: 0px;'>
										<tr>
											<td><strong>OT</strong></td>
											<td id='idot_".$id_ot."'>".str_pad($no_ot, 5,'0',STR_PAD_LEFT)."</td>
											<td><strong>REFERENCIA</strong></td>
											<td>".$referencia."</td>
											<td><strong>PROCESO</strong></td>
											<td>".strtoupper($desc_proc)."</td>
											<td><strong>CLIENTE</strong></td>
											<td>".$raz_soc."</td>
										</tr>										
										<tr>
											<td><strong>DESCRIPCION</strong></td>
											<td colspan='7'>".$descrip."</td>
										</tr>											
								  </table>
							</div>";

	$jsondata['id_otx'] = $id_ot;					

	echo json_encode($jsondata);

?>