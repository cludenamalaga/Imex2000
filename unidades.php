<?php
  ob_start();
  session_start();

  if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] == ' ') {
        header('Location: index.php'); 
  }

  include ('conexion.php');

  mysql_select_db('imex2000_sistema', $sistema);  
?>

<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Unidades | IMEX 2000</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- jQuery  -->
  <script src="libs/jquery/jquery-3.2.1.min.js"></script>

  <!-- Popper -->
  <script src="libs/popper/umd/popper.min.js"></script>
  <script src="libs/popper/umd/popper-utils.min.js"></script>

  <!-- Bootstrap -->
  <script src="libs/bootstrap/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="libs/bootstrap/css/bootstrap.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="libs/font-awesome/css/font-awesome.min.css">

  <!-- Admin LTE -->
  <script src="libs/admin/js/app.min.js"></script>
  <link rel="stylesheet" href="libs/admin/css/AdminLTE.min.css">
  <link rel="stylesheet" href="libs/admin/css/skins/skin-blue.min.css">

  <!-- Ionicons -->
  <link rel="stylesheet" href="libs/ionicons/css/ionicons.min.css">

  <!-- Confirm -->
  <link rel="stylesheet" href="libs/confirm/dist/jquery-confirm.min.css">
  <script src="libs/confirm/dist/jquery-confirm.min.js"></script>

  <!-- Datatables -->
  <script src="libs/datatables/datatables.min.js"></script>
  <link href="libs/datatables/datatables.min.css" rel="stylesheet"></script>

  <script type="text/javascript">
        $(function() {
            $('#tbl_unidades').DataTable({
                language : {
                    "sProcessing":     "Procesando...",
                    "sLengthMenu":     "Mostrar _MENU_ registros",
                    "sZeroRecords":    "No se encontraron resultados",
                    "sEmptyTable":     "Ningún dato disponible en esta tabla",
                    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":    "",
                    "sSearch":         "Buscar:",
                    "sUrl":            "",
                    "sInfoThousands":  ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
              }
            });                       
        });   

        function ver(cod_unidad){
            $.post('det_unidad.php',
              { unidad: cod_unidad },
              function(datos) {   
                $("#result_show").html(datos);
            },"html");
        }         

        function editar(cod_unidad){
            $.post('edit_unidad.php',
              { unidad: cod_unidad },
              function(datos) {   
                $("#result_edit").html(datos);
            },"html");
        }     

        function grabar(){
              if ($('#unidad_ed').val()==''){
                    $('#mensaje_ed').html("Datos Incompletos");
                    $('#mensaje_ed').addClass("error");
                    $('#unidad_ed').focus();                        
                }
                else {
                  if ($('#simbolo_ed').val()==''){
                        $('#mensaje_ed').html("Datos Incompletos");
                        $('#mensaje_ed').addClass("error");
                        $('#simbolo_ed').focus();                            
                    }
                    else {
                        $.post('g_act_unidad.php',
                          { id_unidad: $('#id_unidad_ed').val(),
                            unidad: $('#unidad_ed').val(),
                            simbolo: $('#simbolo_ed').val() },
                          function(datos) {  
                              
                            if(datos.trim() == "S"){
                                $("#mensaje_ed").html("Datos de la Unidad han sido Actualizados"); 
                                $('#mensaje_ed').addClass("ok");
                                $('#btn_grabar_un').attr("disabled", true);                                   
                            }

                        },"html");
 
                  }
              }
        }

        function refrescar(){
          window.location="unidades.php";
        }        

        function cerrar(){
          $("#mensaje_ed").html("");
          window.location="unidades.php";
        }     

        function adicionar(){
            $.post('new_unidad.php',
              function(datos) {   
                $("#result_new").html(datos);
            },"html");
        }   

        function grabar_new(){
              if ($('#unidad_new').val()==''){
                    $('#mensaje_new').html("Datos Incompletos");
                    $('#mensaje_new').addClass("error");
                    $('#unidad_new').focus();                        
                }
                else {
                  if ($('#simbolo_new').val()==''){
                        $('#mensaje_new').html("Datos Incompletos");
                        $('#mensaje_new').addClass("error");
                        $('#simbolo_new').focus();                            
                    }
                    else {
                        $.post('g_new_unidad.php',
                          { unidad: $('#unidad_new').val(),
                            simbolo: $('#simbolo_new').val() },
                          function(datos) {  
                              
                            if(datos.trim() == "S"){
                                $("#mensaje_new").html("Nueva Unidad ha sido Grabada!"); 
                                $('#mensaje_new').addClass("ok");
                                $('#btn_grabar_new').attr("disabled", true);                                   
                            }

                        },"html");
                  }
              }
        }

        function cerrar_new(){
          $("#mensaje_new").html("");
          window.location="unidades.php";
        }        

        function borrar (cod_unidad) {
            var unidad = $("tr[id='"+cod_unidad+"']").children("td[id='unidad']").html();
            var id_unidad = cod_unidad;

            $.confirm({
                title: 'Advertencia',
                icon: 'fa fa-exclamation-triangle',
                type: 'red',
                boxWidth: '40%',
                useBootstrap: false,
                content: '¿ Estas seguro de borrar la Unidad <b>' + unidad + '</b> ?',
                buttons: {
                    Si: {
                        text: 'Sí, Borrarlo', 
                        btnClass: 'btn-red',
                        action: function () {
                              $.post('b_unidad.php',
                                { cod_un: id_unidad},
                                function(datos) {  
                                  if(datos.trim() == "S"){ // Usuario Borrado
                                      confirma = $.confirm({
                                                    title: 'Aviso',
                                                    content: 'La Unidad ha sido borrada!',
                                                    buttons: {
                                                      ok:{
                                                        btnClass: 'btn-red',
                                                        action: function () {
                                                          refrescar();
                                                          confirma.close();
                                                        }
                                                      }
                                                    }
                                      });

                                  }

                              },"html");   

                              
                        }
                    },
                    Cancelar: {
                        text: 'Cancelar', 
                        action: function () {
                       
                        }
                    }
                }
            });

        }

        function valida(e){
            tecla = (document.all) ? e.keyCode : e.which;

            //Tecla de retroceso para borrar, siempre la permite
            if (tecla==8){
                return true;
            }
            
            // Patron de entrada, en este caso solo acepta numeros
            patron =/[0-9-.]/;
            tecla_final = String.fromCharCode(tecla);
            return patron.test(tecla_final);
        }
  </script>  

  <style type="text/css">
        .error {
          background-color: #E30C0C;
          color:#FFFFFF;
        } 

        .ok {
          background-color: #118219;
          color:#FFFFFF;
        }   
    </style>

</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="inicio.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>IMX</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>IMEX 2000</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Cambiar Nav</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="libs/admin/img/profile.png" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">
                <?php
                    echo $_SESSION["nom_usuario"];
                ?>
              </span>
            </a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="libs/admin/img/profile.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <?php
              if ($_SESSION["nivel_usuario"] == "A"){
                  echo $_SESSION["nom_usuario"]."<br><b>Administrador</b>";
                } 

              if ($_SESSION["nivel_usuario"] == "U"){
                  echo $_SESSION["nom_usuario"]."<br><b>Usuario</b>";
              }

              if ($_SESSION["nivel_usuario"] == "M"){
                  echo $_SESSION["nom_usuario"]."<br><b>Almacén</b>";
              } 
          ?>          
        </div>
      </div>

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">

        <li class="header">SISTEMA ORDENES DE TRABAJO</li>

        <li>
            <a href="inicio.php"><i class="fa fa-home" aria-hidden="true"></i> <span>Inicio</span></a>
        </li>

        <?php
            if ($_SESSION["nivel_usuario"] != "M"){          
                echo '<li>
                          <a href="ots.php"><i class="ion-wrench" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<span> Ordenes de Trabajo</span></a>
                      </li>

                      <li>
                          <a href="reg_mo.php"><i class="fa fa-male" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<span> Registro Mano de Obra</span></a>
                      </li>   

                      <li>
                          <a href="reg_mat.php"><i class="fa fa-truck" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<span> Registro Materiales</span></a>
                      </li>     

                      <li>
                          <a href="reg_oc.php"><i class="fa fa-book" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<span> Registro Otros Costos</span></a>
                      </li>  
                      
                      <li>
                          <a href="consulta_ot.php"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<span> Consulta General OTs</span></a>
                      </li>';
            }  

            if ($_SESSION["nivel_usuario"] == "A"){ 
               echo '<li class="treeview active">
                      <a href="#">
                        <i class="fa fa-database"></i> <span>Mantenimiento Tablas</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                      </a>
                      <ul class="treeview-menu">
                        <li><a href="clientes.php"><i class="fa fa-circle-o"></i> Clientes</a></li>
                        <li><a href="personal.php"><i class="fa fa-circle-o"></i> Personal</a></li>
                        <li><a href="cargos.php"><i class="fa fa-circle-o"></i> Cargos y Precios</a></li>
                        <li><a href="tipohor.php"><i class="fa fa-circle-o"></i> Tipos de Horas</a></li>
                        <li><a href="material.php"><i class="fa fa-circle-o"></i> Materiales</a></li>
                        <li class="active"><a href="unidades.php"><i class="fa fa-circle-o"></i> Unidades</a></li>
                        <li><a href="procesos.php"><i class="fa fa-circle-o"></i> Procesos</a></li>
                        <li><a href="talleres.php"><i class="fa fa-circle-o"></i> Talleres</a></li>
                        <li><a href="estados.php"><i class="fa fa-circle-o"></i> Estados</a></li>
                      </ul>
                    </li>

                    <li>
                        <a href="usuarios.php"><i class="fa fa-user" aria-hidden="true"></i> <span>Usuarios</span></a>
                    </li>';  
            }  

            if ($_SESSION["nivel_usuario"] == "M"){ 
               echo '<li class="treeview">
                      <a href="#">
                        <i class="fa fa-database"></i> <span>Mantenimiento Tablas</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                      </a>
                      <ul class="treeview-menu">
                        <li><a href="material.php"><i class="fa fa-circle-o"></i> Materiales</a></li>
                      </ul>
                    </li>';  
            }                          
        ?>

        <li>
            <a href="salir.php"><i class="fa fa-sign-out" aria-hidden="true"></i> <span>Salir</span></a>
        </li>   

      </ul>
      <!-- /.sidebar-menu -->

    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Unidades
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
          <div>
              <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"> 
                <button type="button" class="btn btn-success" onclick="adicionar()" data-toggle="modal" data-target="#add_unidad"><span class="glyphicon glyphicon-plus-sign"></span> Adicionar Unidad</button>
              </div>

              <div class="col-xs-9 col-sm-9 col-md-8 col-lg-8 col-lg-offset-1">

              </div> 
          </div>
      </div>

      <div class="row" style="margin-top: 10px">     
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> 
              <div>
                  <table id="tbl_unidades" class="table table-striped table-bordered .table-hover">
                    <thead>
                      <tr bgcolor="#B9C3C5">
                        <th>Unidad</th>
                        <th>Simbolo</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                         $sql = 'select * from unidades order by unidad asc';
                         $query = mysql_query($sql); 

                         while ($row = mysql_fetch_array($query)){ 
                              echo '<tr id="'.$row['id_unidad'].'">';
                              echo '<td id="unidad">'. $row['unidad'] . '</td>';
                              echo '<td>'. $row['simbolo'] . '</td>';
                              echo '<td width=120>';
                              echo '<div class="btn-group">';

                              echo '<button type="button" class="btn btn-success btn-sm" onclick="ver('.$row['id_unidad'].')" data-toggle="modal" data-target="#ver_unidad"><span class="glyphicon glyphicon-search"></span></button>';

                              echo '<button type="button" class="btn btn-warning btn-sm" onclick="editar('.$row['id_unidad'].')" data-toggle="modal" data-target="#edit_unidad"><span class="glyphicon glyphicon-edit"></span></button>';

                              echo '<button type="button" class="btn btn-danger btn-sm" onclick="borrar('.$row['id_unidad'].')"><span class="glyphicon glyphicon-remove"></span></button>';

                              echo '</div></td>';
                              echo '</tr>';
                          }
                        ?>
                    </tbody>
                  </table>
              </div>
        </div>
      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      Departamento de Sistemas
    </div>
    <!-- Default to the left -->
    <strong>&copy; 2017 <a href="#">IMEX 2000 SA</a>.</strong> Todos los derechos reservados.
  </footer>

</div>
<!-- ./wrapper -->

<!--- Ventana Mostrar -->
<div id="ver_unidad" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><strong>MOSTRAR UNIDAD</strong></h4>
      </div>
      <div id="result_show" class="modal-body">


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!--- Ventana Editar -->
<div id="edit_unidad" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><strong>EDITAR UNIDAD</strong></h4>
      </div>
      <div id="result_edit" class="modal-body">


      </div>
      
      <div id='mensaje_ed' class="text-center">

      </div>  
      <div class="modal-footer">
            <button type="button" id="btn_grabar_un" class="btn btn-success" onclick="grabar()">Grabar</button>
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="cerrar()">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!--- Ventana Adicionar -->
<div id="add_unidad" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><strong>ADICIONAR UNIDAD</strong></h4>
      </div>
      <div id="result_new" class="modal-body">


      </div>
      
      <div id='mensaje_new' class="text-center">

      </div>  
      <div class="modal-footer">
            <button type="button" id="btn_grabar_new" class="btn btn-success" onclick="grabar_new()">Grabar</button>
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="cerrar_new()">Cerrar</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>
