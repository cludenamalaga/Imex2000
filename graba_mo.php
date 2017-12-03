<?php
	ob_start();
	session_start();

	include ('conexion.php');

	mysql_select_db('imex2000_sistema', $sistema); 

	$jsondata = array();

	$no_ot = $_POST["no_ot"];
	$fecha = $_POST["fecha"];
	$id_per = $_POST["id_per"];
	$tarea = $_POST["tarea"];
	$tipo_hora = $_POST["tipo_hora"];
	$num_horas = $_POST["num_horas"];

	// Sancando id de la ot de acuerto al no_ot
	$con_sql = "select * from ots WHERE no_ot='".$no_ot."'";		
	$resulta = mysql_query($con_sql);	

	$id_ot = mysql_result($resulta, 0, "id_ot");

	// Borrando los registro de la OT que han sido Marcados
	$con_sql = "DELETE FROM mano_obra WHERE id_ot='".$id_ot."' and marcado = 'B'";		
	$resulta = mysql_query($con_sql);

	// Llenamos con los nuevos registros
	$fecha = date("Y-m-d", strtotime(str_replace('/', '-', $fecha)));

	// Sacando el id y el precio por el tipo de hora
	// =================================================================
	// Primero averiguando que tipo de cargo tiene el perosonal
	$sql = "select * from personal where id_personal = '".$id_per."'";
	$resulta = mysql_query($sql);

	$id_cargo = mysql_result($resulta, 0, "id_cargo");

	// Sacando el Cargo para almacenarlo
	$sql = "select * from cargos where id_cargo = '".$id_cargo."'";
	$resulta = mysql_query($sql);

	$cargo = mysql_result($resulta, 0, "cargo");	

	// Sacando el id del tipo hora
	$sql = "select * from tipos_hora where simbolo = '".$tipo_hora."'";
	$resulta = mysql_query($sql);

	$id_tipo_hora = mysql_result($resulta, 0, "id_tipo_hora");	

	// Sacando el precio por hora segun el tipo de hora y al cargo
	$sql = "select * from precio_xtiphora where id_cargo = '".$id_cargo."' and id_tipo_hora = '".$id_tipo_hora."'";
	$resulta = mysql_query($sql);

	$precio_hora = mysql_result($resulta, 0, "precio");	


	//Grabando la Mano de Obra
	$con_sql = "INSERT mano_obra SET 
								id_ot ='".$id_ot."',
								no_ot ='".$no_ot."',
								fecha ='".$fecha."',
								id_personal ='".$id_per."',
								cargo = '".$cargo."',
								trabajo ='".addslashes($tarea)."',
								id_tipo_hora ='".$id_tipo_hora."',
								precio_tipo_hora ='".$precio_hora."',
								num_horas='".$num_horas."'";
	$resulta = mysql_query($con_sql);


	$jsondata['id_ot'] = $id_ot;
	$jsondata['fecha'] = $fecha;
	$jsondata['id_per'] = $id_per;
	$jsondata['tarea'] = $tarea;
	$jsondata['tipo_hora'] = $tipo_hora;
	$jsondata['num_horas'] = $num_horas;
	$jsondata['sql'] = $con_sql;

	echo json_encode($jsondata);

 ?>