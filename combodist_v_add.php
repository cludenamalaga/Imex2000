<?php
	session_start();
	include ('conexion.php');
	mysql_select_db('imex2000_sistema', $sistema);  
	  
	$p = $_POST["vardpto"];
	$q = $_POST["varprov"];

	$con_sql = "select * from ubigeo where dep= '".$p."' and prov= '".$q."' and dist <> '00' order by nombre asc";
	$result = mysql_query($con_sql);						

	echo "<select  class='form-control' id='distri' name='distri'><option value='X' selected>- Elija Distrito -</option>"; 
	 
	while ($fila = mysql_fetch_array($result))
	{ 
		echo "<option value='".$fila['dist']."'>".$fila['nombre']."</option>"; 
	}

	echo "</select>";
	mysql_free_result($result); 
?>