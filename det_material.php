<?php
	session_start();
	date_default_timezone_set("America/Lima");
	
	include ('conexion.php');
	mysql_select_db('imex2000_sistema', $sistema);
  
	$material = $_POST["material"];

	$con_sql1= 'select materiales.id_material,
		            materiales.material,
		            unidades.id_unidad,
		            unidades.unidad as nom_unidad,
		            unidades.simbolo as simb_unidad,
		            materiales.precio 
		      from materiales, unidades 
		      where materiales.id_unidad = unidades.id_unidad and
		      		materiales.id_material = "'.$material.'"
		      order by nom_unidad asc';

	$resulta = mysql_query($con_sql1);
	
	if (mysql_num_rows($resulta)!=0) {
		$id_material = mysql_result($resulta, 0, "id_material");
		$material = mysql_result($resulta, 0, "material");
		$simbolo = mysql_result($resulta, 0, "simb_unidad");
		$precio = mysql_result($resulta, 0, "precio");
	}

	$rpta="	<table class='table table-striped table-bordered'>";

	$rpta.="	<tr>
					<td><strong>MATERIAL</strong></td>
					<td colspan='2'>".$material."</td>
				</tr>

				<tr>
					<td><strong>UNIDAD</strong></td>
					<td colspan='2'>".$simbolo."</td>
				</tr>

				<tr>
					<td><strong>PRECIO (S/.)</strong></td>
					<td colspan='2'>".$precio."</td>
				</tr>

		  	</table>";

	echo $rpta;
	//echo $con_sql1;
?>