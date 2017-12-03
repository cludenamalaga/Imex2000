<?php
	ob_start();
	session_start();

	include ('conexion.php');

	mysql_select_db('imex2000_sistema', $sistema); 

	$jsondata = array();

	$b_no_ot = $_POST["bus_no_ot"];

	// Sancando id de la ot de acuerto al no_ot
	$con_sql1 = "select * from ots WHERE no_ot='".$b_no_ot."'";		
	$resulta1 = mysql_query($con_sql1);	

	if (mysql_num_rows($resulta1)!=0) { // Si hay OT
		$id_ot = mysql_result($resulta1, 0, "id_ot");

		// Borrando los registro de la OT que han sido Marcados
		$con_sql2 = "DELETE FROM reg_mat WHERE id_ot='".$id_ot."' and marcado = 'B'";		
		$resulta2 = mysql_query($con_sql2);
	}

	echo json_encode($jsondata);

 ?>