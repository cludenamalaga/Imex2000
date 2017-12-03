<?php
	session_start();
	date_default_timezone_set("America/Lima");
	
	include ('conexion.php');
	mysql_select_db('imex2000_sistema', $sistema);
  

	$rpta="	<table class='table table-striped table-bordered'>";

	$rpta.="	<tr>
					<td><strong>AÑO</strong></td>
					<td colspan='2'>
						<select id='ano' class='form-control' onchange='poner_desc()'>
							<option value='2017'>2017</option>
							<option value='2018'>2018</option>
							<option value='2019'>2019</option>
							<option value='2020'>2020</option>
							<option value='2021'>2021</option>
							<option value='2022'>2022</option>
							<option value='2023'>2023</option>
							<option value='2024'>2024</option>
							<option value='2025'>2025</option>
							<option value='2026'>2026</option>
							<option value='2027'>2027</option>
							<option value='2028'>2028</option>
							<option value='2029'>2029</option>
							<option value='2030'>2030</option>
						</select>
					</td>
				</tr>

				<tr>
					<td><strong>MES</strong></td>
					<td>
						<select id='mes' class='form-control' onchange='poner_desc()'>
							<option value='01'>01 - Enero</option>
							<option value='02'>02 - Febrero</option>
							<option value='03'>03 - Marzo</option>
							<option value='04'>04 - Abril</option>
							<option value='05'>05 - Mayo</option>
							<option value='06'>06 - Junio</option>
							<option value='07'>07 - Julio</option>
							<option value='08'>08 - Agosto</option>
							<option value='09'>09 - Septiembre</option>
							<option value='10'>10 - Octubre</option>
							<option value='11'>11 - Noviembre</option>
							<option value='12'>12 - Diciembre</option>
						</select>
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
						<input type='text' id='gastos_new' class='form-control' onkeypress='return valida(event)'>
					</td>
				</tr>		

				<tr>
					<td><strong>UTILIDAD (%)</strong></td>
					<td>
						<input type='text' id='util_new' class='form-control' onkeypress='return valida(event)'>
					</td>
				</tr>		

				<tr>
					<td><strong>IGV (%)</strong></td>
					<td>
						<input type='text' id='igv_new' class='form-control' onkeypress='return valida(event)'>
					</td>
				</tr>														
		  	</table>";

	echo $rpta;
	//echo $con_sql1;
?>