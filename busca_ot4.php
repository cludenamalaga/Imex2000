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
	$fecha = $_POST["fecha_b"];

	// Si hay OT
	if (is_empty($no_ot)){
		$cond_ot = "";
	} else {
		$cond_ot = " and reg_mat.no_ot ='".$no_ot."'";
	}

	// Si hay Fecha Inicial
	if (is_empty($fecha)){
		$cond_fecha = "";
	} else {
		$cond_fecha = " and reg_mat.fecha = '".date( "Y-m-d", strtotime(str_replace('/', '-', $fecha)))."'";
	}	

	$ordenar = " order by reg_mat.fecha desc";

	$sql= "select reg_mat.id,
				  reg_mat.id_ot,
				  reg_mat.no_ot,
				  reg_mat.fecha as fecha_regmat,
				  ots.fecha,
				  ots.no_ot as id_ots,
				  reg_mat.id_personal,
				  personal.nombres,
				  personal.apellidos,
				  personal.id_cargo,
				  reg_mat.id_material,
				  materiales.material,
				  unidades.simbolo,
				  reg_mat.cantidad,
				  reg_mat.precio
			from  reg_mat, ots, personal, materiales,unidades
			where reg_mat.id_ot = ots.id_ot and
				  reg_mat.id_personal = personal.id_personal and
				  reg_mat.id_material = materiales.id_material and
				  materiales.id_unidad = unidades.id_unidad and
				  ots.estado = '1'";

	$sql .= $cond_ot;
	$sql .= $cond_fecha;
	$sql .= $ordenar;

	$resulta_todo = mysql_query($sql);

	$jsondata['sql'] = $sql;


	if (mysql_num_rows($resulta_todo) != 0){ //Si hay Registro Mano Obra
		$jsondata['encontrado'] = "S";

        $jsondata['tabla'] ='
            <div class="row" style="margin-top: 0px;"> 
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center" style="margin-top: 0px;">
                      <form class="form-inline">

                              <input type="text" class="form-control" id="fec_tarea" style="width:90px">
                          	  <input type="text" class="form-control" id="no_ot_tarea" placeholder="No. OT" style="width:90px">';

                          	  // Personal
                              $sql2="select * from personal order by apellidos, nombres";
                              $query = mysql_query($sql2);  

                              $jsondata['tabla'] .= '<select id="cbo_personal" class="form-control">';

                              while ($row = mysql_fetch_array($query)){
                                      		$nombres   = ( strpos($row["nombres"]," ") == FALSE ? $row["nombres"] : substr($row["nombres"], 0, strpos($row["nombres"]," ")+1));
											//$apellidos = ( strpos($row["apellidos"]," ") == FALSE ? $row["apellidos"] : substr($row["apellidos"], 0, strpos($row["apellidos"]," ")+1));

                                      $jsondata['tabla'] .=  '<option value="'.$row['id_personal'].'">'.$row['apellidos'].' '.$nombres.'</option>'; 
                              }
                              $jsondata['tabla'] .=  '</select>';


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

                              $jsondata['tabla'] .= '<select id="cbo_material" class="form-control" style="width:500px">';

                              while ($row = mysql_fetch_array($query3)){
                                      $jsondata['tabla'] .=  '<option value="'.$row['id_material'].'">'.strtoupper($row['material']).' ('.strtoupper($row['simbolo']).')</option>'; 
                              }
                              $jsondata['tabla'] .=  '</select>';


      $jsondata['tabla'] .=  '<input type="number" min="1" class="form-control" id="cant" aria-describedby="basic-addon1" style="width: 8%">

                              <button type="button" class="btn btn-success" aria-label="Left Align" onclick="add_row()">
                                  <i class="fa fa-plus" aria-hidden="true"></i>
                              </button>      
                      </form> 
                </div>
            </div>'; 


		$jsondata['tabla'] .= "
            <div class='row' style='margin-top: 0px;'> 
                <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12' style='margin-top: 0px;'>
						<table id='tbl_manobra' class='table table-hover table-condensed table-striped'>
								<thead>
									<th class='text-center'>FECHA</th>
									<th class='text-center'>OT</th>
									<th>PERSONAL</th>
									<th class='text-center'>MATERIAL</th>
									<th class='text-center'>CANTIDAD</th>
									<th class='text-center'>OPCIONES</th>
								</thead>
								<tbody>";

		while ($row = mysql_fetch_array($resulta_todo)){ 

				$jsondata['tabla'] .=  "<tr id='".$row['id']."'>
											<td id='fecha_".$row['fecha_regmat']."' class='text-center'>".date( "d/m/Y", strtotime($row['fecha_regmat'])) ."</td>
				   							<td id='ot' class='text-center'>".str_pad($row['no_ot'], 5, "0", STR_PAD_LEFT)."</td>
				   							<td id='per_".$row['id_personal']."'>".$row['apellidos']." ".$row['nombres']."</td>
				   
											<td id='mat_".$row['id_material']."' class='text-center'>".strtoupper($row['material'])." (".strtoupper($row['simbolo']).")</td>

											<td class='text-center' id='cantidad'>".$row['cantidad']."</td>

											<td class='text-center'>
												<button type='button' class='btn btn-success btn-sm' onclick='consultar(\"".$row['id']."\")' data-toggle='modal' data-target='#ver_mo'><span class='glyphicon glyphicon-search'></span></button>
												<button type='button' class='btn btn-warning btn-sm' onclick='editar(\"".$row['id']."\")' data-toggle='modal' data-target='#edit_mo'><span class='glyphicon glyphicon-edit'></span></button>
												<button type='button' class='btn btn-danger btn-sm'  onclick='del_row(\"".$row['id']."\")'><span class='glyphicon glyphicon-remove'></span></button>
											</td>
										</tr>";

				// Marcando  registros para borrado si se graba 
			    $con_sql = "UPDATE reg_mat SET marcado = 'B' WHERE id = '".$row['id']."'";
			    $resulta = mysql_query($con_sql);
		}

		$jsondata['tabla'] .= "</tbody></table>
				</div>
			</div>";

	} else { //No hay OT

		$jsondata['tabla'] ='
            <div class="row" style="margin-top: 0px;"> 
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center" style="margin-top: 0px;">
                      <form class="form-inline">

                              <input type="text" class="form-control" id="fec_tarea" style="width:90px">
                          	  <input type="text" class="form-control" id="no_ot_tarea" placeholder="No. OT" style="width:90px">';

                              $sql2="select * from personal order by apellidos, nombres";
                              $query = mysql_query($sql2);  

                              $jsondata['tabla'] .= '<select id="cbo_personal" class="form-control">';

                              if (mysql_num_rows($query)){
                                      while ($row = mysql_fetch_array($query)){
                                      		$nombres   = ( strpos($row["nombres"]," ") == FALSE ? $row["nombres"] : substr($row["nombres"], 0, strpos($row["nombres"]," ")+1));
											//$apellidos = ( strpos($row["apellidos"]," ") == FALSE ? $row["apellidos"] : substr($row["apellidos"], 0, strpos($row["apellidos"]," ")+1));

                                              $jsondata['tabla'] .=  '<option value="'.$row['id_personal'].'">'.$row['apellidos'].' '.$nombres.'</option>'; 
                                      }
                                      $jsondata['tabla'] .=  '</select>';
                              }

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

                              $jsondata['tabla'] .= '<select id="cbo_material" class="form-control" style="width:500px">';

                              while ($row = mysql_fetch_array($query3)){
                                      $jsondata['tabla'] .=  '<option value="'.$row['id_material'].'">'.strtoupper($row['material']).' ('.$row['simbolo'].')</option>'; 
                              }
                              $jsondata['tabla'] .=  '</select>';

      $jsondata['tabla'] .=  '<input type="number" min="1" max="100" class="form-control" id="cant" aria-describedby="basic-addon1" style="width: 8%">

                              <button type="button" class="btn btn-success" aria-label="Left Align" onclick="add_row_new()">
                                  <i class="fa fa-plus" aria-hidden="true"></i>
                              </button>      
                      </form> 
                </div>
            </div>'; 

	$jsondata['tabla'] .= "
            <div class='row' style='margin-top: 0px;'> 
                <div id='zona_tbl_manobra' class='col-xs-12 col-sm-12 col-md-12 col-lg-12' style='margin-top: 0px;'>
						<table id='tbl_manobra' class='table table-hover table-condensed table-striped'>
								<thead>
									<th class='text-center'>FECHA</th>
									<th class='text-center'>OT</th>
									<th>PERSONAL</th>
									<th class='text-center'>MATERIAL</th>
									<th class='text-center'>CANTIDAD</th>
									<th class='text-center'>OPCIONES</th>
								</thead>
								<tbody>";

	$jsondata['tabla'] .= "		</tbody>
						</table>
				</div>
			</div>";

		$jsondata['encontrado'] = "N";

	}

	echo json_encode($jsondata);
?>