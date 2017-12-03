<?php
	session_start();
	include ('conexion.php');
	mysql_select_db('imex2000_sistema', $sistema);  
  
	$p = $_POST["vardpto"];

	$con_sql = "select * from ubigeo where dep= '".$p."' and prov<>'00' and dist='00' order by nombre asc";
	$result = mysql_query($con_sql);						

	echo "<select  class='form-control' id='prov' name='prov' onChange='llena_dist_add(this)'><option value='X' selected>- Elija Provincia -</option>"; 
	 
	while ($fila = mysql_fetch_array($result))
	{ 
		echo "<option value='".$fila['prov']."'>".$fila['nombre']."</option>"; 
	}

	echo "</select>";
	mysql_free_result($result); 
?>