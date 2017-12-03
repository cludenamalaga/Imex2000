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
		$id_unidad = mysql_result($resulta, 0, "id_unidad");
		$simbolo = mysql_result($resulta, 0, "simb_unidad");
		$precio = mysql_result($resulta, 0, "precio");
	}

	$rpta="	<table class='table table-striped table-bordered'>";

	$rpta.="	<tr>
					<td><strong>MATERIAL</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='material_ed' value='".$material."'></td>
				</tr>

				<tr>
					<td><strong>UNIDAD</strong></td>
					<td colspan='2'>";

					$con_sql2 = "select * from unidades order by unidad";
					$resulta2 = mysql_query($con_sql2);

					if (mysql_num_rows($resulta2)==0){
						$rpta.="No hay Unidades Almacenadas en Tabla UNIDADES";
					} else {
						$rpta.="<select id='cbo_unidades' class='form-control'>";

						while ($row = mysql_fetch_array($resulta2)){ 
							if ($row['id_unidad'] == $id_unidad){
								$rpta.="<option value='".$row['id_unidad']."' selected>".$row['unidad']." (".$row['simbolo'].")</option>";
							} else {
								$rpta.="<option value='".$row['id_unidad']."'>".$row['unidad']." (".$row['simbolo'].")</option>";
							}
						}

						$rpta.="</select>";
					}
					
	$rpta.="	</td>
				</tr>

				<tr>
					<td><strong>PRECIO (S/.)</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='precio_ed' value='".$precio."' onkeypress='return valida(event)'></td>
				</tr>";

	$rpta .="<input type='hidden' id='id_material_ed' value='".$id_material."'>";			

	$rpta .="</table>";

	echo $rpta;
	//echo $con_sql1;
?>