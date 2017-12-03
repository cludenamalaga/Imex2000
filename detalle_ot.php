<?php
	ob_start();
	session_start();

	include ('conexion.php');

	mysql_select_db('imex2000_sistema', $sistema);  

	$jsondata = array();

	$id_ot = $_POST["id"];

	$sql = "SELECT
				ots.id_ot,
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
				estados.estado AS estado_ot,
				ots.motivo,
				ots.observ
			FROM
				ots,
				procesos,
				talleres,
				clientes,
				estados
			WHERE
				ots.id_cliente = clientes.id_cliente
			AND ots.id_taller = talleres.id_taller
			AND ots.id_proceso = procesos.id_proceso
			AND ots.estado = estados.id_estado
			AND ots.id_ot ='".$id_ot."' 
			ORDER BY ots.fecha, ots.no_ot desc";

	$resulta = mysql_query($sql);

	if (mysql_num_rows($resulta)!=0){
			$no_ot = mysql_result($resulta, 0, "no_ot");
			$ref = mysql_result($resulta, 0, "referencia");
			$descrip = mysql_result($resulta, 0, "descrip");
			$fecha_emi = mysql_result($resulta, 0, "fecha");
			$presup = mysql_result($resulta, 0, "valor");
			$raz_soc = mysql_result($resulta, 0, "raz_soc");
			$desc_trab = mysql_result($resulta, 0, "descripcion");
			$ava= mysql_result($resulta, 0, "avance");
			$esta_ot= mysql_result($resulta, 0, "estado_ot");


			$jsondata['encontrado'] = "S";

			$jsondata['tabla'] = "	<table class='table' style='font-size:12px; background-color:#FBF8B3'>
												<tr>
													<td><strong>NUMERO OT</strong></td>
													<td>".str_pad($no_ot, 5, "0", STR_PAD_LEFT)."</td>
													<td><strong>REFERENCIA</strong></td>
													<td>".$ref."</td>
													<td><strong>PROCESO</strong></td>
													<td>".$descrip."</td>
													<td><strong>FECHA EMISION</strong></td>
													<td>".date("d/m/Y", strtotime($fecha_emi))."</td>								
													<td><strong>PRESUPUESTO</strong></td>
													<td>".number_format($presup,2,'.',',')."</td>						
												</tr>
												<tr style='background-color:#FBF8B3'>
													<td><strong>CLIENTE</strong></td>
													<td>".$raz_soc."</td>
													<td><strong>DESCRIPCION</strong></td>
													<td colspan='4'>".$desc_trab."</td>
													<td colspan='2'>
														<strong>AVANCE %</strong>&nbsp;&nbsp;&nbsp;&nbsp;".$ava."
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														<strong>ESTADO</strong>&nbsp;&nbsp;&nbsp;&nbsp;".strtoupper($esta_ot)."
													</td>
												</tr>
									</table>";

			$jsondata['tabla'] .= "	<table class='table' style='font-size:12px; background-color:#E2E5F3'>
										<tr style='background-color:#BFC4DC'>
											<td><strong>ORDENES DE MANO DE OBRA</strong></td>
										</tr>
										<tr>
											<td>";
													// Sacando mano de obra
													$sql_mo ="select mano_obra.fecha,
																	 mano_obra.id_personal,
																	 personal.nombres,
																	 personal.apellidos,
																	 mano_obra.cargo,
																	 mano_obra.trabajo,
																	 mano_obra.id_tipo_hora,
																	 tipos_hora.tipo_hora,
																	 tipos_hora.simbolo,
																	 mano_obra.precio_tipo_hora,
																	 mano_obra.num_horas
																from mano_obra, personal, tipos_hora
																where mano_obra.id_personal = personal.id_personal and
																	  mano_obra.id_tipo_hora = tipos_hora.id_tipo_hora and
																	  mano_obra.id_ot = '".$id_ot."'
																order by mano_obra.fecha asc";

													$result_mo = mysql_query($sql_mo);
							
													if (mysql_num_rows($result_mo)==0){
														$jsondata['tabla'] .= "No hay Ordenes de Trabajo";
													} else {
													$jsondata['tabla'] .= "<table class='table table-condensed' style='background-color:#E2E5F3'>
																				<tr>
																					<td class='text-center'><strong>FECHA</strong></td>
																					<td><strong>PERSONAL</strong></td>
																					<td><strong>CARGO</strong></td>
																					<td><strong>TRABAJO</strong></td>
																					<td class='text-center'><strong>HORAS</strong></td>
																					<td class='text-center'><strong>TIPO</strong></td>
																					<td class='text-right'><strong>PRECIO</strong></td>
																					<td class='text-right'><strong>TOTAL</strong></td>
																				</tr>";														
														while ($row = mysql_fetch_array($result_mo)){ 

															$jsondata['tabla'] .= " </tr>
																						<td class='text-center'>".date("d/m/Y", strtotime($row['fecha']))."</td>
																						<td style='width:20%'>".$row['apellidos']." ".$row['nombres']."</td>
																						<td style='width:15%'>".$row['cargo']."</td>
																						<td>".$row['trabajo']."</td>
																						<td class='text-center'>".$row['num_horas']."</td>
																						<td class='text-center'>".$row['simbolo']."</td>
																						<td class='text-right'>".number_format($row['precio_tipo_hora'],2,'.',',')."</td>
																						<td class='text-right'>".number_format($row['precio_tipo_hora'] * $row['num_horas'],2,'.',',')."</td>
																					</tr>";	
														}

														$sql_suma = "select sum(precio_tipo_hora * num_horas) as total from mano_obra where mano_obra.id_ot='".$id_ot."'";
														$result_suma = mysql_query($sql_suma);

														$suma_mob = mysql_result($result_suma, 0, "total");


														$jsondata['tabla'] .= " </tr>
																					<td colspan='7' class='text-right' style='color:green'><strong>TOTAL</strong></td>
																					<td class='text-right' style='color:green'><strong>".number_format($suma_mob,2,'.',',')."</strong></td>
																				</tr>";
													$jsondata['tabla'] .= "</table>";	



													}

				     $jsondata['tabla'] .= "	</td>
											</tr>
										</table>";

			// Tabla de Materiales
			$jsondata['tabla'] .= "	<table class='table' style='font-size:12px; background-color:#D5E4E7'>
										<tbody>
												<tr style='background-color:#88C0CC'>
													<td><strong>ORDENES DE MATERIALES</strong></td>
												</tr>
												<tr>
													<td>";
													// Sacando mano de obra
													$sql_mo ="select reg_mat.fecha,
																	 reg_mat.id_personal,
																	 personal.nombres,
																	 personal.apellidos,
																	 reg_mat.cargo,
																	 reg_mat.id_material,
																	 materiales.material,
																	 materiales.id_unidad,
																	 reg_mat.precio,
																	 reg_mat.cantidad,
																	 unidades.unidad,
																	 unidades.simbolo
																from reg_mat, personal, materiales, unidades
																where reg_mat.id_personal = personal.id_personal and
																	  reg_mat.id_material = materiales.id_material and
																	  materiales.id_unidad = unidades.id_unidad and
																	  reg_mat.id_ot = '".$id_ot."'
																order by reg_mat.fecha asc";

													$result_mo = mysql_query($sql_mo);
							
													if (mysql_num_rows($result_mo)==0){
														$jsondata['tabla'] .= "No hay Ordenes de Materiales";
													} else {
													$jsondata['tabla'] .= "<table class='table table-condensed' style='background-color:#D5E4E7'>
																				<tr>
																					<td class='text-center'><strong>FECHA</strong></td>
																					<td><strong>PERSONAL</strong></td>
																					<td><strong>CARGO</strong></td>
																					<td><strong>MATERIAL</strong></td>
																					<td class='text-center'><strong>CANTIDAD</strong></td>
																					<td class='text-right'><strong>PRECIO</strong></td>
																					<td class='text-right'><strong>TOTAL</strong></td>
																				</tr>";														
														while ($row = mysql_fetch_array($result_mo)){ 

															$jsondata['tabla'] .= " </tr>
																						<td class='text-center'>".date("d/m/Y", strtotime($row['fecha']))."</td>
																						<td style='width:20%'>".$row['apellidos']." ".$row['nombres']."</td>
																						<td style='width:15%'>".$row['cargo']."</td>
																						<td>".$row['material']."(".$row['simbolo'].")</td>
																						<td class='text-center'>".$row['cantidad']."</td>
																						<td class='text-right'>".$row['precio']."</td>
																						<td class='text-right'>".number_format($row['precio'] * $row['cantidad'],2,'.',',')."</td>
																					</tr>";	
														}

														$sql_suma_mat = "select sum(precio * cantidad) as total from reg_mat where reg_mat.id_ot='".$id_ot."'";
														$result_suma_mat = mysql_query($sql_suma_mat);

														$suma_mat = mysql_result($result_suma_mat, 0, "total");


														$jsondata['tabla'] .= " </tr>
																					<td colspan='6' class='text-right' style='color:green'><strong>TOTAL</strong></td>
																					<td class='text-right' style='color:green'><strong>".number_format($suma_mat,2,'.',',')."</strong></td>
																				</tr>";
													$jsondata['tabla'] .= "</table>";	



													}

					     $jsondata['tabla'] .= "	</td>
												</tr>";


			$jsondata['tabla'] .= "		</tbody>
									</table>";		


			// Tabla de Otros Costos
			$jsondata['tabla'] .= "	<table class='table' style='font-size:12px; background-color:#FAF6CB'>
										<tbody>
												<tr style='background-color:#E9C968'>
													<td><strong>OTROS COSTOS</strong></td>
												</tr>
												<tr>
													<td>";
													// Sacando mano de obra
													$sql_oc ="select reg_oc.fecha,
																	 reg_oc.id_personal,
																	 personal.nombres,
																	 personal.apellidos,
																	 reg_oc.detalle,
																	 reg_oc.total
																from reg_oc, personal
																where reg_oc.id_personal = personal.id_personal and
																	  reg_oc.id_ot = '".$id_ot."'
																order by reg_oc.fecha asc";

													$result_oc = mysql_query($sql_oc);
							
													if (mysql_num_rows($result_oc)==0){
														$jsondata['tabla'] .= "No hay Otros Costos";
													} else {

													$jsondata['tabla'] .= "<table class='table table-condensed' style='background-color:#FAF6CB'>
																				<tr>
																					<td class='text-center'><strong>FECHA</strong></td>
																					<td><strong>PERSONAL</strong></td>
																					<td><strong>DETALLE</strong></td>
																					<td class='text-right'><strong>TOTAL</strong></td>
																				</tr>";														
														while ($row = mysql_fetch_array($result_oc)){ 

															$jsondata['tabla'] .= " </tr>
																						<td class='text-center'>".date("d/m/Y", strtotime($row['fecha']))."</td>
																						<td>".$row['apellidos']." ".$row['nombres']."</td>
																						<td>".$row['detalle']."</td>
																						<td class='text-right'>".$row['total']."</td>
																					</tr>";	
														}

														$sql_suma_oc = "select sum(total) as total_oc from reg_oc where reg_oc.id_ot='".$id_ot."'";
														$result_suma_oc = mysql_query($sql_suma_oc);

														$suma_oc = mysql_result($result_suma_oc, 0, "total_oc");


														$jsondata['tabla'] .= " </tr>
																					<td colspan='3' class='text-right' style='color:green'><strong>TOTAL</strong></td>
																					<td class='text-right' style='color:green'><strong>".number_format($suma_oc,2,'.',',')."</strong></td>
																				</tr>";
													$jsondata['tabla'] .= "</table>";	

													}

					     $jsondata['tabla'] .= "	</td>
												</tr>";


			$jsondata['tabla'] .= "		</tbody>
									</table>";		


	} else {
		$jsondata['encontrado'] = "N";
	}

	echo json_encode($jsondata);
?>