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
	$id_mat = $_POST["id_mat"];
	$cantidad = $_POST["cant_mat"];

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
									<td><strong>MATERIAL</strong></td>
									<td>";
			                           	  // Materiales
			                              $sql3="select materiales.id_material,
			                              				materiales.material,
			                              				materiales.id_unidad,
			                              				materiales.precio,
			                              				unidades.unidad,
			                              				unidades.simbolo 
			                              		   from materiales, unidades
			                              		  where materiales.id_unidad = unidades.id_unidad
			                              		  order by materiales.material";

			                              $query3 = mysql_query($sql3);  

                   $jsondata['tabla'] .= '<select id="cbo_material_ed" class="form-control">';

				                              while ($row = mysql_fetch_array($query3)){

				                              		if ($row['id_material'] == $id_mat){
				                                      		$jsondata['tabla'] .=  '<option value="'.$row['id_material'].'" selected>'.strtoupper($row['material']).' ('.$row['simbolo'].')</option>'; 				                              				
				                              		} else {
				                                      		$jsondata['tabla'] .=  '<option value="'.$row['id_material'].'">'.strtoupper($row['material']).' ('.$row['simbolo'].')</option>'; 				                              			
				                              		}


				                              }
                  $jsondata['tabla'] .=  '</select>';

		$jsondata['tabla'] .= " 	</td>
								</tr>	
								<tr>
									<td><strong>CANTIDAD</strong></td>
									<td>
										<input type='number' min='1' class='form-control' id='canti_mat_ed' value='".$cantidad."'>

										<input type='hidden' id='id_fila_mat' value='".$id_fila."'>
										<input type='hidden' id='var_mat_no_ot' value='".$no_ot."'>
									</td>
								</tr>

							</table>";

	echo json_encode($jsondata);
?>