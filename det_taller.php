<?php
	session_start();
	date_default_timezone_set("America/Lima");
	
	include ('conexion.php');
	mysql_select_db('imex2000_sistema', $sistema);
  
	$taller = $_POST["taller"];
	$con_sql1 = "select * from talleres where id_taller = '".$taller."'";

	// Sacando datos del Vendedor

	$resulta = mysql_query($con_sql1);
	
	if (mysql_num_rows($resulta)!=0) {
		$id_taller = mysql_result($resulta, 0, "id_taller");
		$taller = mysql_result($resulta, 0, "taller");
	}

	$rpta="	<table class='table table-striped table-bordered'>";

	$rpta.="	<tr>
					<td><strong>TALLER</strong></td>
					<td colspan='2'>".$taller."</td>
				</tr>
		  	</table>";

	echo $rpta;
	//echo $con_sql1;
?>