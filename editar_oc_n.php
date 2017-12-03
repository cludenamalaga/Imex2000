<?php
	ob_start();
	session_start();

	include ('conexion.php');

	mysql_select_db('imex2000_sistema', $sistema);  

	$jsondata = array();

	$id_fila = $_POST["id_fila"];
	$ot = $_POST["no_ot_n"];
	$fecha = $_POST["fecha"];
	$id_per = $_POST["id_per"];
	$detalle = $_POST["detalle"];
	$total = $_POST["total"];

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
									 <td><strong>DETALLE</strong></td>
									 <td>	
									 	<input type='text' class='form-control' id='detalle_ed' value='".$detalle."'>
									 </td>
								</tr>	

								<tr>
									<td><strong>TOTAL (S/.)</strong></td>
									<td>
										<input type='text' class='form-control' id='total_ed' value='".$total."'>

										<input type='hidden' id='id_fila_oc' value='".$id_fila."'>
										<input type='hidden' id='var_oc_no_ot' value='".$no_ot."'>
									</td>
								</tr>

							</table>";

	echo json_encode($jsondata);
?>