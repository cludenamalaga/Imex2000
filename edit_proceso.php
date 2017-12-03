<?php
	session_start();
	date_default_timezone_set("America/Lima");

	setlocale(LC_TIME, 'spanish');
	
	include ('conexion.php');
	mysql_select_db('imex2000_sistema', $sistema);
  
	$proceso = $_POST["proceso"];
	$con_sql1 = "select * from procesos where id_proceso = '".$proceso."'";

	// Sacando datos del Vendedor

	$resulta = mysql_query($con_sql1);
	
	if (mysql_num_rows($resulta)!=0) {
		$id_proceso = mysql_result($resulta, 0, "id_proceso");
		$ano = mysql_result($resulta, 0, "ano");
		$mes = mysql_result($resulta, 0, "mes");
		$descrip = mysql_result($resulta, 0, "descrip");
		$gastos = mysql_result($resulta, 0, "gastos");
		$utilidad = mysql_result($resulta, 0, "utilidad");
		$igv = mysql_result($resulta, 0, "igv");

		$gastos = $gastos * 100;
		$utilidad = $utilidad * 100;
		$igv = $igv * 100;
	}

	$rpta="	<table class='table table-striped table-bordered'>";

	$rpta.="	<tr>
					<td><strong>AÑO</strong></td>
					<td colspan='2'>
						<select id='ano' class='form-control' onchange='poner_desc()'>";
							for ($i = 2017; $i <= 2030; $i++) {
								if($i==$ano){
									$rpta.="<option value='".$i."' selected>".$i."</option>";
								} else {
									$rpta.="<option value='".$i."'>".$i."</option>";
								}
							    
							}
	$rpta.="			</select>
					</td>
				</tr>

				<tr>
					<td><strong>MES</strong></td>
					<td>
						<select id='mes' class='form-control' onchange='poner_desc()'>";
							for ($i = 1; $i <= 12; $i++) {
								if($i==$mes){
							    	$rpta.="<option value='".str_pad($i, 2, "0", STR_PAD_LEFT)."' selected>".ucwords(strftime("%B",mktime(0, 0, 0, $i, 1, 2000)))."</option>";
								} else {
							    	$rpta.="<option value='".str_pad($i, 2, "0", STR_PAD_LEFT)."'>".ucwords(strftime("%B",mktime(0, 0, 0, $i, 1, 2000)))."</option>";									
								}

							}
	$rpta.="			</select>
					</td>
				</tr>

				<tr>
					<td><strong>DESCRIPCION</strong></td>
					<td>
						<div id='descrip_proc'>
						</div>
					</td>
				</tr>	

				<tr>
					<td><strong>GASTOS (%)</strong></td>
					<td>
						<input type='text' id='gastos_ed' class='form-control' value='".$gastos."' onkeypress='return valida(event)'>
					</td>
				</tr>		

				<tr>
					<td><strong>UTILIDAD (%)</strong></td>
					<td>
						<input type='text' id='util_ed' class='form-control' value='".$utilidad."'onkeypress='return valida(event)'>
					</td>
				</tr>		

				<tr>
					<td><strong>IGV (%)</strong></td>
					<td>
						<input type='text' id='igv_ed' class='form-control' value='".$igv."' onkeypress='return valida(event)'>
						<input type='hidden' id='id_proceso' value='".$id_proceso."'>
					</td>
				</tr>														
		  	</table>";

	echo $rpta;
	//echo $con_sql1;
?>