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
		$cond_ot = " and ots.no_ot ='".$no_ot."'";
	}

	// Si hay Fecha Inicial
	if (is_empty($fecha)){
		$cond_fecha = "";
	} else {
		$cond_fecha = " and mano_obra.fecha = '".date( "Y-m-d", strtotime(str_replace('/', '-', $fecha)))."'";
	}	

	$ordenar = " order by fecha_regmo desc";

	$sql= "select mano_obra.id,
				  mano_obra.id_ot,
				  ots.fecha,
				  ots.no_ot,
				  mano_obra.fecha as fecha_regmo,
				  mano_obra.id_personal,
				  personal.nombres,
				  personal.apellidos,
				  personal.id_cargo,
				  mano_obra.trabajo,
				  cargos.cargo,
				  mano_obra.id_tipo_hora,
				  tipos_hora.tipo_hora,
				  tipos_hora.simbolo,
				  mano_obra.num_horas
			from 	mano_obra, ots, personal, tipos_hora, cargos
			where 	mano_obra.id_ot = ots.id_ot and 
					mano_obra.id_personal = personal.id_personal and
					personal.id_cargo = cargos.id_cargo and
					mano_obra.id_tipo_hora = tipos_hora.id_tipo_hora and
					ots.estado = '1'";

	$sql .= $cond_ot;
	$sql .= $cond_fecha;
	$sql .= $ordenar;

	$resulta_todo = mysql_query($sql);

	if (mysql_num_rows($resulta_todo) != 0){ //Si hay Registro Mano Obra
		$jsondata['encontrado'] = "S";

		// Desmarcando registros
	    $con_sqlb = "UPDATE mano_obra SET marcado = ''";
	    $resulta = mysql_query($con_sqlb);

        $jsondata['tabla'] ='
            <div class="row" style="margin-top: 0px;"> 
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center" style="margin-top: 0px;">
                      <form class="form-inline">

                              <input type="text" class="form-control" id="fec_tarea">
                          	  <input type="text" class="form-control" id="no_ot_tarea" placeholder="No. OT">';

                              $sql2="select * from personal order by apellidos, nombres";
                              $query = mysql_query($sql2);  

                              $jsondata['tabla'] .= '<select id="cbo_personal" class="form-control">';

                              if (mysql_num_rows($query)){
                                      while ($row = mysql_fetch_array($query)){
                                              $jsondata['tabla'] .=  '<option value="'.$row['id_personal'].'">'.$row['apellidos'].' '.$row['nombres'].'</option>'; 
                                      }
                                      $jsondata['tabla'] .=  '</select>';
                              }

      $jsondata['tabla'] .= '<input type="text" class="form-control" id="trabajo" placeholder="Tarea Realizada" aria-describedby="basic-addon1">'; 

      $jsondata['tabla'] .= '<select id="cbo_tiphora" class="form-control">
                                    <option value="N">Hora Normal</option>
                                    <option value="E">Hora Extra</option>
                                    <option value="D">Hora Doble</option>
                              </select>'; 

      $jsondata['tabla'] .=  '<input type="number" min="1" max="15" class="form-control" id="cant_hor" aria-describedby="basic-addon1" style="width: 8%">

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
									<th>TAREA</th>
									<th class='text-center'>TIPO HORA</th>
									<th class='text-center'>CANTIDAD</th>
									<th class='text-center'>OPCIONES</th>
								</thead>
								<tbody>";

		while ($row = mysql_fetch_array($resulta_todo)){ 

				$jsondata['tabla'] .=  "<tr id='".$row['id']."'>
											<td id='fecha_".$row['fecha']."' class='text-center'>".date( "d/m/Y", strtotime($row['fecha_regmo'])) ."</td>
				   							<td id='ot' class='text-center'>".str_pad($row['no_ot'], 5, "0", STR_PAD_LEFT)."</td>
				   							<td id='per_".$row['id_personal']."'>".$row['apellidos']." ".$row['nombres']."</td>
				   							<td id='trabajo'>".$row['trabajo']."</td>
				   							<td class='text-center' id='tipoh_".$row['simbolo']."'>".$row['tipo_hora']."</td>
											<td class='text-center' id='num_horas'>".$row['num_horas']."</td>
											<td class='text-center'>
												<button type='button' class='btn btn-success btn-sm' onclick='consultar(".$row['id'].")' data-toggle='modal' data-target='#ver_mo'><span class='glyphicon glyphicon-search'></span></button>
												<button type='button' class='btn btn-warning btn-sm' onclick='editar(".$row['id'].")' data-toggle='modal' data-target='#edit_mo'><span class='glyphicon glyphicon-edit'></span></button>
												<button type='button' class='btn btn-danger btn-sm'  onclick='del_row(".$row['id'].")'><span class='glyphicon glyphicon-remove'></span></button>
											</td>
										</tr>";

				// Marcando  registros para borrado si se graba 
			    $con_sql = "UPDATE mano_obra SET marcado = 'B' WHERE id = '".$row['id']."'";
			    $resulta = mysql_query($con_sql);
		}

		$jsondata['tabla'] .= "</tbody></table>
				</div>
			</div>";

	} else { //No hay OT
		// Desmarcando registros
	    $con_sqlb = "UPDATE mano_obra SET marcado = ''";
	    $resulta = mysql_query($con_sqlb);

		$jsondata['tabla'] ='
            <div class="row" style="margin-top: 0px;"> 
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center" style="margin-top: 0px;">
                      <form class="form-inline">

                              <input type="text" class="form-control" id="fec_tarea">
                          	  <input type="text" class="form-control" id="no_ot_tarea" placeholder="No. OT">';

                              $sql2="select * from personal order by apellidos, nombres";
                              $query = mysql_query($sql2);  

                              $jsondata['tabla'] .= '<select id="cbo_personal" class="form-control">';

                              if (mysql_num_rows($query)){
                                      while ($row = mysql_fetch_array($query)){
                                              $jsondata['tabla'] .=  '<option value="'.$row['id_personal'].'">'.$row['apellidos'].' '.$row['nombres'].'</option>'; 
                                      }
                                      $jsondata['tabla'] .=  '</select>';
                              }

      $jsondata['tabla'] .= '<input type="text" class="form-control" id="trabajo" placeholder="Tarea Realizada" aria-describedby="basic-addon1">'; 

      $jsondata['tabla'] .= '<select id="cbo_tiphora" class="form-control">
                                    <option value="N">Hora Normal</option>
                                    <option value="E">Hora Extra</option>
                                    <option value="D">Hora Doble</option>
                              </select>'; 

      $jsondata['tabla'] .=  '<input type="number" min="1" max="15" class="form-control" id="cant_hor" aria-describedby="basic-addon1" style="width: 8%">

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
									<th>TAREA</th>
									<th class='text-center'>TIPO HORA</th>
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