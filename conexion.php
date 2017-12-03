	<?php
		//Conexion a BD del Sistema
		$sistema = @mysql_connect('localhost', 'imexuser', 'imex7022');

		if  (!$sistema) {
	    	die('No pudo conectarse: ' . mysql_error());
		}

		mysql_set_charset('utf8',$sistema);
		
		mysql_select_db('imex2000_sistema', $sistema) or die('No puedo accesar a la BD del Sistema');
	?>