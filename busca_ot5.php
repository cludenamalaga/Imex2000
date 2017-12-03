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
		$cond_ot = " and reg_oc.no_ot ='".$no_ot."'";
	}

	// Si hay Fecha Inicial
	if (is_empty($fecha)){
		$cond_fecha = "";
	} else {
		$cond_fecha = " and reg_oc.fecha = '".date( "Y-m-d", strtotime(str_replace('/', '-', $fecha)))."'";
	}	

	$ordenar = " order by reg_oc.fecha desc";

	$sql= "select reg_oc.id,
				  reg_oc.id_ot,
				  reg_oc.no_ot,
				  ots.estado,
				  reg_oc.fecha,
				  reg_oc.id_personal,
				  personal.nombres,
				  personal.apellidos,
				  reg_oc.detalle,
				  reg_oc.total
			from  reg_oc, personal, ots
			where reg_oc.id_personal = personal.id_personal and
				  reg_oc.id_ot = ots.id_ot and
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

                              <input type="text" class="form-control" id="fec_tarea">
                          	  <input type="text" class="form-control" id="no_ot_tarea" placeholder="No. OT" style="width: 10%">';

                          	  // Personal
                              $sql2="select * from personal order by apellidos, nombres";
                              $query = mysql_query($sql2);  

                              $jsondata['tabla'] .= '<select id="cbo_personal" class="form-control">';

                              while ($row = mysql_fetch_array($query)){
                                      $jsondata['tabla'] .=  '<option value="'.$row['id_personal'].'">'.$row['apellidos'].' '.$row['nombres'].'</option>'; 
                              }
                              $jsondata['tabla'] .=  '</select>';


      $jsondata['tabla'] .=  '<input type="text" class="form-control" id="detalle" placeholder="Detalle">

      						  <input type="text" class="form-control" id="total" style="width: 12%">

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
									<th class='text-center'>DETALLE</th>
									<th class='text-center'>TOTAL</th>
									<th class='text-center'>OPCIONES</th>
								</thead>
								<tbody>";

		while ($row = mysql_fetch_array($resulta_todo)){ 

				$jsondata['tabla'] .=  "<tr id='".$row['id']."'>
											<td id='fecha_".$row['fecha']."' class='text-center'>".date( "d/m/Y", strtotime($row['fecha'])) ."</td>
				   							<td id='ot' class='text-center'>".str_pad($row['no_ot'], 5, "0", STR_PAD_LEFT)."</td>
				   							<td id='per_".$row['id_personal']."'>".$row['apellidos']." ".$row['nombres']."</td>
				   
											<td id='detalle' class='text-center'>".strtoupper($row['detalle'])."</td>

											<td class='text-center' id='total'>".$row['total']."</td>

											<td class='text-center'>
												<button type='button' class='btn btn-success btn-sm' onclick='consultar(\"".$row['id']."\")' data-toggle='modal' data-target='#ver_mo'><span class='glyphicon glyphicon-search'></span></button>
												<button type='button' class='btn btn-warning btn-sm' onclick='editar(\"".$row['id']."\")' data-toggle='modal' data-target='#edit_mo'><span class='glyphicon glyphicon-edit'></span></button>
												<button type='button' class='btn btn-danger btn-sm'  onclick='del_row(\"".$row['id']."\")'><span class='glyphicon glyphicon-remove'></span></button>
											</td>
										</tr>";

				// Marcando  registros para borrado si se graba 
			    $con_sql = "UPDATE reg_oc SET marcado = 'B' WHERE id = '".$row['id']."'";
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

                              <input type="text" class="form-control" id="fec_tarea">
                          	  <input type="text" class="form-control" id="no_ot_tarea" placeholder="No. OT" style="width: 10%">';

                              $sql2="select * from personal order by apellidos, nombres";
                              $query = mysql_query($sql2);  

                              $jsondata['tabla'] .= '<select id="cbo_personal" class="form-control">';

                              if (mysql_num_rows($query)){
                                      while ($row = mysql_fetch_array($query)){
                                              $jsondata['tabla'] .=  '<option value="'.$row['id_personal'].'">'.$row['apellidos'].' '.$row['nombres'].'</option>'; 
                                      }
                                      $jsondata['tabla'] .=  '</select>';
                              }

      $jsondata['tabla'] .=  '	<input type="text" class="form-control" id="detalle" placeholder="Detalle">

      							<input type="text" class="form-control" id="total" placeholder="Total" style="width: 12%">

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
									<th class='text-center'>DETALLE</th>
									<th class='text-center'>TOTAL</th>
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