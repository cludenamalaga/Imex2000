<?php
	ob_start();
	session_start();

	include ('conexion.php');

	mysql_select_db('imex2000_sistema', $sistema);  

	$jsondata = array();

	$orden_trabajo = $_POST["orden"];

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

	if ($estado == 4) { //Anulado
			$jsondata['tabla4'] = "<table id='tbl_result' class='table table-hover table-condensed table-striped'>
										<tr>
											<td>ORDEN DE TRABAJO</td>
											<td>".str_pad($no_ot, 5,'0',STR_PAD_LEFT)."</td>
										</tr>
										<tr>
											<td>REFERENCIA</td>
											<td>".$referencia."</td>
										</tr>
										<tr>
											<td>PROCESO</td>
											<td>".strtoupper($desc_proc)."</td>
										</tr>										
										<tr>
											<td>FECHA</td>
											<td>".date( "d/m/Y", strtotime($fecha))."</td>
										</tr>
										<tr>
											<td>TALLER</td>
											<td>".strtoupper($taller)."</td>
										</tr>
										<tr>
											<td>CLIENTE</td>
											<td>".$raz_soc."</td>
										</tr>	
										<tr>
											<td>DESCRIPCION</td>
											<td>".$descrip."</td>
										</tr>	
										<tr>
											<td>INICIO</td>";
											if ($inicio=='0000-00-00'){
												$jsondata['tabla'].="<td>NO INGRESADO</td>";
											} else {
												$jsondata['tabla'].="<td>".date( "d/m/Y", strtotime($inicio))."</td>";
											}

				   $jsondata['tabla4'].="</tr>
				   						<tr>	
											<td>FIN</td>";
											if ($inicio=='0000-00-00'){
												$jsondata['tabla'].="<td>NO INGRESADO</td>";
											} else {
												$jsondata['tabla'].="<td>".date( "d/m/Y", strtotime($fin))."</td>";
											}

				   $jsondata['tabla4'].="</tr>
				   						<tr>		
											<td>VALOR PRESUSUESTO (S/.)</td>
											<td>".$valor."</td>
										</tr>
										<tr>
											<td>% AVANCE</td>
											<td>".$avance."</td>
										</tr>																				
										<tr>
											<td>ESTADO</td>
											<td style='color:red'>ANULADA</td>
										</tr>		
										<tr>
											<td>MOTIVO</td>
											<td>".$motivo."</td>
										</tr>													
								  </table>";

		$jsondata['estado'] = "ANUL";

	}

	if ($estado == 3) { //Cerrado
			$jsondata['tabla3'] = "<table id='tbl_result' class='table table-hover table-condensed table-striped'>
										<tr>
											<td>ORDEN DE TRABAJO</td>
											<td>".str_pad($no_ot, 5,'0',STR_PAD_LEFT)."</td>
										</tr>
										<tr>
											<td>REFERENCIA</td>
											<td>".$referencia."</td>
										</tr>
										<tr>
											<td>PROCESO</td>
											<td>".strtoupper($desc_proc)."</td>
										</tr>											
										<tr>
											<td>FECHA</td>
											<td>".date( "d/m/Y", strtotime($fecha))."</td>
										</tr>
										<tr>
											<td>TALLER</td>
											<td>".strtoupper($taller)."</td>
										</tr>
										<tr>
											<td>CLIENTE</td>
											<td>".$raz_soc."</td>
										</tr>	
										<tr>
											<td>DESCRIPCION</td>
											<td>".$descrip."</td>
										</tr>	
										<tr>
											<td>INICIO</td>";
											if ($inicio=='0000-00-00'){
												$jsondata['tabla'].="<td>NO INGRESADO</td>";
											} else {
												$jsondata['tabla'].="<td>".date( "d/m/Y", strtotime($inicio))."</td>";
											}

				   $jsondata['tabla3'].="</tr>
				   						<tr>	
											<td>FIN</td>";
											if ($inicio=='0000-00-00'){
												$jsondata['tabla'].="<td>NO INGRESADO</td>";
											} else {
												$jsondata['tabla'].="<td>".date( "d/m/Y", strtotime($fin))."</td>";
											}

				   $jsondata['tabla3'].="</tr>
				   						<tr>		
											<td>VALOR PRESUSUESTO (S/.)</td>
											<td>".$valor."</td>
										</tr>
										<tr>
											<td>% AVANCE</td>
											<td>".$avance."</td>
										</tr>																				
										<tr>
											<td>ESTADO</td>
											<td style='color:green'>CERRADA</td>
										</tr>		
										<tr>
											<td>OBSERVACIONES</td>
											<td>".$obs."</td>
										</tr>													
								  </table>";

		$jsondata['estado'] = "CERR";
	}	

	if ($estado == 1) { //Activo	
			$jsondata['tabla'] = "<table id='tbl_result' class='table table-hover table-condensed table-striped'>
										<tr>
											<td>ORDEN DE TRABAJO</td>
											<td>".str_pad($no_ot, 5,'0',STR_PAD_LEFT)."</td>
										</tr>
										<tr>
											<td>REFERENCIA</td>
											<td>
												<input type='text' id='ref_ed' class='form-control' value='".$referencia."'>
											</td>
										</tr>
										<tr>
											<td>PROCESO</td>
											<td>".strtoupper($desc_proc)."</td>
										</tr>											
										<tr>
											<td>FECHA</td>
											<td>".date( "d/m/Y", strtotime($fecha))."</td>
										</tr>
										<tr>
											<td>TALLER</td>
											<td>";
											
											$sql = "select * from talleres order by taller";
											$resulta = mysql_query($sql);

							$jsondata['tabla'].="<select id='taller' class='form-control'>";
												 while ($row = mysql_fetch_array($resulta)){ 
												 		if ($row['id_taller'] == $id_taller){
															$jsondata['tabla'].="<option value='".$row['id_taller']."' selected>".strtoupper($row['taller'])."</option>";
												 		} else {
															$jsondata['tabla'].="<option value='".$row['id_taller']."'>".strtoupper($row['taller'])."</option>";					 			
												 		}	
												 }

							$jsondata['tabla'].="</select>";


						$jsondata['tabla'].="</td>
										</tr>
										<tr>
											<td>CLIENTE</td>
											<td>";

											$sql = "select * from clientes order by raz_soc";
											$resulta = mysql_query($sql);

							$jsondata['tabla'].="<select id='cliente' class='form-control'>";
												 while ($row = mysql_fetch_array($resulta)){ 
												 		if ($row['id_cliente'] == $id_cliente){
															$jsondata['tabla'].="<option value='".$row['id_cliente']."' selected>".strtoupper($row['raz_soc'])."</option>";
												 		} else {
															$jsondata['tabla'].="<option value='".$row['id_cliente']."'>".strtoupper($row['raz_soc'])."</option>";					 			
												 		}	
												 }

							$jsondata['tabla'].="</select>";

							$jsondata['tabla'].="</td>
										</tr>	
										<tr>
											<td>DESCRIPCION</td>
											<td>
												<input type='text' id='descrip_ed' class='form-control' value='".$descrip."'>
											</td>
										</tr>	
										<tr>
											<td>INICIO</td>
											<td>";
											if ($inicio=='0000-00-00'){
												$jsondata['tabla'].="<input type='text' id='inicio_ed' class='form-control'>";
											} else {
												$jsondata['tabla'].="<input type='text' id='inicio_ed' class='form-control' value='".date( "d/m/Y", strtotime($inicio))."'>";
											}

						$jsondata['tabla'].="</td>
										</tr>																			
										<tr>
											<td>FIN</td>
											<td>";

											if ($fin=='0000-00-00'){
												$jsondata['tabla'].="<input type='text' id='fin_ed' class='form-control'>";
											} else {
												$jsondata['tabla'].="<input type='text' id='fin_ed' class='form-control' value='".date( "d/m/Y", strtotime($fin))."'>";
											}

						$jsondata['tabla'].="</td>
										</tr>		
										<tr>
											<td>VALOR PRESUSUESTO (S/.)</td>
											<td>
												<input type='text' id='valor_ed' class='form-control' value='".$valor."'>
											</td>
										</tr>
										<tr>
											<td>% AVANCE</td>
											<td>
												<input type='number' id='avance_ed' class='form-control' value='".$avance."' min='0' max='100'>
											</td>
										</tr>																				
										<tr>
											<td>ESTADO</td>
											<td>";

											$sql = "select * from estados order by estado";
											$resulta = mysql_query($sql);

							$jsondata['tabla'].="<select id='estados' class='form-control'>";
												 while ($row = mysql_fetch_array($resulta)){ 
												 		if ($row['id_estado'] == $estado){
															$jsondata['tabla'].="<option value='".$row['id_estado']."' selected>".strtoupper($row['estado'])."</option>";
												 		} else {
															$jsondata['tabla'].="<option value='".$row['id_estado']."'>".strtoupper($row['estado'])."</option>";					 			
												 		}	
												 }
							$jsondata['tabla'].="</select>";

						$jsondata['tabla'].="<input type='hidden' id='ot_ed' value='".$id_ot."'>
											</td>
										</tr>				
								  </table>";

			$jsondata['fecha'] = date( "d/m/Y", strtotime($fecha));

			if($inicio=='0000-00-00'){
				$jsondata['inicio'] = "B";
			} else {
				$jsondata['inicio'] = date( "d/m/Y", strtotime($inicio));
			}

			if($fin=='0000-00-00'){
				$jsondata['fin'] = "B";
			} else {
				$jsondata['fin'] = date( "d/m/Y", strtotime($fin));
			}	

			$jsondata['estado'] = "ACTI";
	}

	echo json_encode($jsondata);

?>