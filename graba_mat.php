<?php
	ob_start();
	session_start();

	include ('conexion.php');

	mysql_select_db('imex2000_sistema', $sistema); 

	$jsondata = array();

	$b_no_ot = $_POST["bus_no_ot"];
	$no_ot = $_POST["no_ot"];
	$fecha = $_POST["fecha"];
	$id_per = $_POST["id_per"];
	$id_mat = $_POST["id_mat"];
	$cnt_mat = $_POST["canti_mat"];


	// Sancando id de la ot de acuerto al no_ot
	$con_sql1 = "select * from ots WHERE no_ot='".$no_ot."'";		
	$resulta1 = mysql_query($con_sql1);	

	if (mysql_num_rows($resulta1)!=0) { // Si hay OT
		$id_ot = mysql_result($resulta1, 0, "id_ot");

		// Borrando los registro de la OT que han sido Marcados
		$con_sql2 = "DELETE FROM reg_mat WHERE id_ot='".$id_ot."' and marcado = 'B'";		
		$resulta2 = mysql_query($con_sql2);

		// Llenamos con los nuevos registros
		$fecha = date("Y-m-d", strtotime(str_replace('/', '-', $fecha)));

		// Sacando el id y el precio por el tipo de hora
		// =================================================================
		// Primero averiguando que tipo de cargo tiene el perosonal
		$sql3 = "select * from personal where id_personal = '".$id_per."'";
		$resulta3 = mysql_query($sql3);

		$id_cargo = mysql_result($resulta3, 0, "id_cargo");

		// Sacando el Cargo para almacenarlo
		$sql4 = "select * from cargos where id_cargo = '".$id_cargo."'";
		$resulta4 = mysql_query($sql4);

		$cargo = mysql_result($resulta4, 0, "cargo");	

		// Sacando el precio
		$sql5 = "select * from materiales where id_material = '".$id_mat."'";
		$resulta5 = mysql_query($sql5);

		$precio = mysql_result($resulta5, 0, "precio");	

		//Grabando la Mano de Obra
		$con_sql6 = "INSERT reg_mat SET 
									id_ot ='".$id_ot."',
									no_ot ='".$no_ot."',
									fecha ='".$fecha."',
									id_personal ='".$id_per."',
									cargo = '".$cargo."',
									id_material ='".$id_mat."',
									cantidad ='".$cnt_mat."',
									precio ='".$precio."'";
		$resulta6 = mysql_query($con_sql6);

		$jsondata['sql_borrado'] = $con_sql2;

	}

	echo json_encode($jsondata);

 ?>