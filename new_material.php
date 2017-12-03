<?php
	session_start();
	date_default_timezone_set("America/Lima");
	
	include ('conexion.php');
	mysql_select_db('imex2000_sistema', $sistema);
  
	$rpta="	<table class='table table-striped table-bordered'>";

	$rpta.="	<tr>
					<td><strong>MATERIAL</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='material_new'></td>
				</tr>

				<tr>
					<td><strong>UNIDAD</strong></td>
					<td colspan='2'>";

					$con_sql2 = "select * from unidades order by unidad";
					$resulta2 = mysql_query($con_sql2);

					if (mysql_num_rows($resulta2)==0){
						$rpta.="No hay Unidades Almacenadas en Tabla UNIDADES";
					} else {
						$rpta.="<select id='cbo_unidades' class='form-control'>
									<option value='X'>- Elija Unidad -</option>";

						while ($row = mysql_fetch_array($resulta2)){ 
								$rpta.="<option value='".$row['id_unidad']."'>".$row['unidad']." (".$row['simbolo'].")</option>";
						}

						$rpta.="</select>";
					}

	$rpta.="	</td>
				</tr>

				<tr>
					<td><strong>PRECIO (S/.)</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='precio_new' onkeypress='return valida(event)'></td>
				</tr>";		

	$rpta .="</table>";

	echo $rpta;
	//echo $con_sql1;
?>