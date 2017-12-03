<?php
	ob_start();
	session_start();

	include ('conexion.php');

	mysql_select_db('imex2000_sistema', $sistema);  
	$jsondata = array();

	$fecha = $_POST["fecha"];
	$ot = $_POST["ot"];
	$id_per = $_POST["id_per"];
	$tarea = $_POST["tarea"];
	$tipo_h = $_POST["tipo_h"];
	$cnt_hr = $_POST["cnt_hr"];

	// Validando si existe la OT
	$sql = "select * from ots where no_ot = '".$ot."' and estado = '1'";
	$resulta = mysql_query($sql);

	if(mysql_num_rows($resulta)==0){ // No hay OT
		$jsondata['encontrado']="N";

	} else {
		$jsondata['encontrado']="S";

		$id = mysql_num_rows($resulta) + 1;

		//Sacando nombres ==================================================
		$sql_per="select * from personal where id_personal = '".$id_per."'";
        $query = mysql_query($sql_per);  

        $nombres =  mysql_result($query, 0, "apellidos")." ".mysql_result($query, 0, "nombres");
        //==================================================================


		//Sacando tipo hora ==================================================
		$sql_hr="select * from tipos_hora where simbolo = '".$tipo_h."'";
        $query = mysql_query($sql_hr);  

        $tipo_hora =  mysql_result($query, 0, "tipo_hora");
        //==================================================================        

		$jsondata['fila'] = '<tr id="'.$id.'">
									<td id="fecha_'.$fecha.'" class="text-center">'.$fecha.'</td>
									<td id="ot" class="text-center">'
										.str_pad($ot, 5, "0", STR_PAD_LEFT).
								   '</td>
                                    <td id="per_'.$id_per.'">'.$nombres.'</td>
                                    <td id="trabajo">'.$tarea.'</td>
                                    <td id="tipoh_'.$tipo_h.'" class="text-center">'.$tipo_hora.'</td>
                                    <td id="num_horas" class="text-center">'.$cnt_hr.'</td>
                                    <td class="text-center">
                                    	<button type="button" class="btn btn-success btn-sm" onclick="consultar_new('.$id.')" data-toggle="modal" data-target="#ver_mo"><span class="glyphicon glyphicon-search"></span></button>
                                    	<button type="button" class="btn btn-warning btn-sm" onclick="editar_new('.$id.')" data-toggle="modal" data-target="#edit_mo_n"><span class="glyphicon glyphicon-edit"></span></button>
                                    	<button type="button" class="btn btn-danger  btn-sm" onclick="del_row('.$id.')"><span class="glyphicon glyphicon-remove"></span></button>
                                    </td>
                             </tr>';
	}

	echo json_encode($jsondata);
?>