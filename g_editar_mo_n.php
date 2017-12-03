<?php
	ob_start();
	session_start();

	include ('conexion.php');

	mysql_select_db('imex2000_sistema', $sistema);  
	$jsondata = array();

	
	$id_fila = $_POST["id_fila"];
	$no_ot = $_POST["no_ot"];
	$fecha = $_POST["fecha"];
	$id_per = $_POST["id_per"];
	$per = $_POST["personal"];
	$tarea = $_POST["tarea"];
	$tipo_h = $_POST["tipo_hora"];
	$simb_th = $_POST["simb_t_hora"];
	$cnt_hr = $_POST["num_horas"];

	$no_ot = ltrim($no_ot, "0");

	//Sacando nombres ==================================================
	$sql_per="select * from personal where id_personal = '".$id_per."'";
    $query = mysql_query($sql_per);  

    $nombres =  mysql_result($query, 0, "apellidos")." ".mysql_result($query, 0, "nombres");
    //==================================================================

	//Sacando tipo hora ==================================================
	$sql_hr="select * from tipos_hora where simbolo = '".$simb_th."'";
    $query = mysql_query($sql_hr);  

    $tipo_hora =  mysql_result($query, 0, "tipo_hora");
    //==================================================================        

    $fecha_trf = date('Y-m-d', strtotime(str_replace('/', '-', $fecha )));

	$jsondata['fila'] = '		<td id="fecha_'.$fecha_trf.'" class="text-center">'.$fecha.'</td>
								<td id="ot" class="text-center">'
									.str_pad($no_ot, 5, "0", STR_PAD_LEFT).
							   '</td>
                                <td id="per_'.$id_per.'">'.$nombres.'</td>
                                <td id="trabajo">'.$tarea.'</td>
                                <td id="tipoh_'.$simb_th.'" class="text-center">'.$tipo_hora.'</td>
                                <td id="num_horas" class="text-center">'.$cnt_hr.'</td>
                                <td class="text-center">
                                	<button type="button" class="btn btn-success btn-sm" onclick="consultar_new('.$id_fila.')" data-toggle="modal" data-target="#ver_mo"><span class="glyphicon glyphicon-search"></span></button>
                                	<button type="button" class="btn btn-warning btn-sm" onclick="editar_new('.$id_fila.')" data-toggle="modal" data-target="#edit_mo_n"><span class="glyphicon glyphicon-edit"></span></button>
                                	<button type="button" class="btn btn-danger  btn-sm" onclick="del_row('.$id_fila.')"><span class="glyphicon glyphicon-remove"></span></button>
                                </td>';

    $jsondata['id_fila'] = $id_fila;
    $jsondata["no_ot"] = $_POST["no_ot"];

	echo json_encode($jsondata);
?>