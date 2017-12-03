<?php
	ob_start();
	session_start();

	include ('conexion.php');

	mysql_select_db('imex2000_sistema', $sistema);  

	$jsondata = array();

	$id_fila = $_POST["id_fila"];
	$ot = $_POST["no_ot_n"];
	$fecha = $_POST["fecha"];
	$tarea = $_POST["tarea"];
	$id_per = $_POST["id_per"];
	$tipo_hora = $_POST["tipo_hora"];
	$num_horas = $_POST["num_horas"];

	$no_ot = ltrim($ot, "0");

	$jsondata['tabla'] = "	<table class='table table-striped'>
								<tr>
									<td><strong>NO. OT</strong></td>
									<td>".str_pad($no_ot, 5, "0", STR_PAD_LEFT)."</td>
								</tr>
								<tr>
									<td><strong>FECHA</strong></td>
									<td>
										<input type='text' id='fecha_ed' value ='".$fecha."' class='form-control'>
									</td>
								</tr>
								<tr>
									<td><strong>PERSONAL</strong></td>
									<td>";

				                          $sql="select * from personal order by apellidos, nombres";
			                              $query = mysql_query($sql);  

			                              $jsondata['tabla'] .= '<select id="cbo_personal_ed" class="form-control">';

			                              if (mysql_num_rows($query)){
			                                      while ($row = mysql_fetch_array($query)){

			                                      		if ($row['id_personal'] == $id_per){
			                                      				$jsondata['tabla'] .=  '<option value="'.$row['id_personal'].'" selected>'.$row['apellidos'].' '.$row['nombres'].'</option>'; 
			                                      		} else {
			                                      				$jsondata['tabla'] .=  '<option value="'.$row['id_personal'].'">'.$row['apellidos'].' '.$row['nombres'].'</option>'; 
			                                      		}
			                                      }
			                                      $jsondata['tabla'] .=  '</select>';
			                              }			

	$jsondata['tabla'] .= "          </td>
								</tr>
								<tr>
									<td><strong>TAREA REALIZADA</strong></td>
									<td>
										<input type='text' class='form-control' id='tarea_ed' value='".$tarea."'>
									</td>
								</tr>									
								<tr>
									<td><strong>TIPO HOR</strong></td>
									<td>";

				if ($tipo_hora == "N"){
						 $jsondata['tabla'] .= "<select id='cbo_tiphora_ed' class='form-control'>
				                                    <option value='N'selected>Hora Normal</option>
				                                    <option value='E'>Hora Extra</option>
				                                    <option value='D'>Hora Doble</option>
		                              			</select>";
		                              		}

				if ($tipo_hora == "E"){
						 $jsondata['tabla'] .= "<select id='cbo_tiphora_ed' class='form-control'>
				                                    <option value='N'>Hora Normal</option>
				                                    <option value='E' selected>Hora Extra</option>
				                                    <option value='D'>Hora Doble</option>
		                              			</select>";
		                              		} 

				if ($tipo_hora == "D"){
							 $jsondata['tabla'] .= "<select id='cbo_tiphora_ed' class='form-control'>
					                                    <option value='N'>Hora Normal</option>
					                                    <option value='E'>Hora Extra</option>
					                                    <option value='D' selected>Hora Doble</option>
			                              			</select>";
			                              		}   

    	$jsondata['tabla'] .= "     </td>
								</tr>								
								<tr>
									<td><strong>NUMERO HORAS</strong></td>
									<td>
										<input type='number' min='1' max='15' class='form-control' id='cant_hor_ed' value='".$num_horas."'>
										<input type='hidden' id='id_fila' value='".$id_fila."'>
										<input type='hidden' id='var_no_ot' value='".$no_ot."'>
									</td>
								</tr>

							</table>";

	$jsondata['id_fila'] = $id_fila;
	$jsondata['no_ot'] = $no_ot;

	echo json_encode($jsondata);
?>