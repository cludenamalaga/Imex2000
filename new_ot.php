<?php
	ob_start();
	session_start();

	include ('conexion.php');

	mysql_select_db('imex2000_sistema', $sistema);  

	$sql = "select * from contadores";
	$resulta = mysql_query($sql);

	$no_ot 	= mysql_result($resulta, 0, "no_ot");

	$no_ot = $no_ot + 1;

	$jsondata['tabla'] = "<table id='tbl_ot_new' class='table table-hover table-condensed table-striped'>
										<tr>
											<td>ORDEN DE TRABAJO</td>
											<td>".str_pad($no_ot, 5,'0',STR_PAD_LEFT)."</td>
										</tr>

										<tr>
											<td>REFERENCIA</td>
											<td>
												<input type='text' id='ref' class='form-control'>
											</td>
										</tr>

										<tr>
											<td>PROCESO</td>
											<td>";

												$sql="select * from procesos order by ano, mes desc";
												$query = mysql_query($sql);

								$jsondata['tabla'] .= "<select id='cbo_proc' class='form-control'>";

												  while ($row = mysql_fetch_array($query)){
												        $jsondata['tabla'] .= "<option value='".$row['id_proceso']."'>".strtoupper($row['descrip'])."</option>"; 
												  }
								$jsondata['tabla'] .= "</select>";

					$jsondata['tabla'] .= "	</td>
										</tr>											
										<tr>
											<td>FECHA</td>
											<td>
												<input type='text' id='fecha' class='form-control'>
											</td>
										</tr>
										<tr>
											<td>TALLER</td>
											<td>";
											
											$sql = "select * from talleres order by taller";
											$resulta = mysql_query($sql);

							$jsondata['tabla'].="<select id='taller' class='form-control'>";
												 while ($row = mysql_fetch_array($resulta)){ 
														$jsondata['tabla'].="<option value='".$row['id_taller']."'>".strtoupper($row['taller'])."</option>";					 			
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
															$jsondata['tabla'].="<option value='".$row['id_cliente']."'>".strtoupper($row['raz_soc'])."</option>";
												 }

							$jsondata['tabla'].="</select>";

							$jsondata['tabla'].="</td>
										</tr>	
										<tr>
											<td>DESCRIPCION</td>
											<td>
												<input type='text' id='descrip' class='form-control'>
											</td>
										</tr>	
										<tr>
											<td>INICIO</td>
											<td>
												<input type='text' id='inicio' class='form-control'>
											</td>
										</tr>																
										<tr>
											<td>FIN</td>
											<td>
												<input type='text' id='fin' class='form-control'>
											</td>
										</tr>		
										<tr>
											<td>VALOR PRESUSUESTO (S/.)</td>
											<td>
												<input type='text' id='valor' class='form-control'>
											</td>
										</tr>	
								  </table>";
	
	echo json_encode($jsondata);	

?>