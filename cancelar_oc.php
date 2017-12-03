<?php
	  ob_start();
	  session_start();

	  include ('conexion.php');

	  mysql_select_db('imex2000_sistema', $sistema);  

	  // Desmarcando Registros pues hubo Grabacion
	  $con_sql = "UPDATE reg_oc SET marcado = ''";
      $resulta = mysql_query($con_sql);
?>