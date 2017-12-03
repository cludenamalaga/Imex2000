<?php
  ob_start();
  session_start();

  include ('conexion.php');

  mysql_select_db('imex2000_sistema', $sistema);  

  session_destroy();
  mysql_close($sistema);

  header('Location: index.php');
?>