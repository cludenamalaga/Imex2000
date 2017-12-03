<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ingreso | IMEX 2000</title>
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

    <style type="text/css">
    	body {
    		background-image: url("images/bg.jpg");
    	}

    	#olvido:hover {
    		text-decoration: underline;
    		cursor: pointer;
    	}

    	#env_correo:hover {
    		cursor: pointer;
    		font-weight: bold;
    	}    	
    </style>

    <script>
		$(function() {
			$("#ingreso").click(function () {

			      $.post('busca_user.php',
		            { id: $("#id").val(),
		              pwd: $("#pwd").val()
		              },
		            function(datos) {   
		              $("#msje").html(datos.mensaje);
		              if(datos.ingreso == "S"){
		              		window.location.replace("inicio.php");
		              		$(location).attr('href','inicio.php'); 
		              }
		          },"json");		

			}); 

			$("#pwd").keypress(function(e){
				if (e.which == 13){
					$("#ingreso").click();
				}
			});

			$("#btn_envpwd").hide();

			$("#olvido").click(function () {
				if ($('#btn_envpwd').is(':hidden')){
					$("#correo").val("");
					$("#msje2").html("");
					$("#btn_envpwd").show();
				} else {
					$("#btn_envpwd").hide();
				};  
			});

			$("#env_correo").click(function() {
    			// Expresion regular para validar el correo
				var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;

				// Se utiliza la funcion test() nativa de JavaScript
				if (regex.test($('#correo').val().trim())) {
					$("#msje2").html('Enviando su clave, espere por favor...');
					$.post('mail_olvido.php',
					{ correo: $("#correo").val()},
					function(datos) {   
					  $("#msje2").html(datos);
					},"html");	
				} else {
				    $("#msje2").html('Correo no es válido');
				}
			});
		});
	</script>

  </head>
  <body>
  	<div class="row" style="height: 1000px;">
	  		<div class="col-xs-1 col-sh-3 col-md-4 col-lg-4 text-center"></div>

	  		<div class="col-xs-10 col-sh-6 col-md-4 col-lg-4 text-center">
	  				<div style="margin-left: 60px; margin-right: 60px;">
			  			<div id="titulo" style="font-size: 25px; margin-top: 40px; margin-bottom: 20px; color: white;">
			  				IMEX 2000 S.A.
			  			</div>

			  			<div id="logo" style="margin-top: 20px; margin-bottom: 20px; color: white;">
			  				<i class="fa fa-user-circle fa-5x" aria-hidden="true"></i>
			  			</div>

						<div class="input-group margin-bottom-sm" style="margin-bottom:10px;">
						  <span class="input-group-addon"><i class="fa fa-user-o fa-fw"></i></span>
						  <input id="id" class="form-control" type="text" placeholder="Usuario">
						</div>

						<div class="input-group"  style="margin-bottom:10px;">
						  <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
						  <input id="pwd" class="form-control" type="password" placeholder="Clave">
						</div>  

						<a id="ingreso" class="btn btn-lg btn-primary" style="cursor: pointer; color: #FFF">
			  				<i class="fa fa-sign-in fa-md"></i> Ingresar
			  			</a>

			  			<div id="msje" style="margin-top: 10px; height: 20px; margin-bottom: 1px; color:white;">

			  			</div>

			 			<div id="remember" style="margin-top: 10px; margin-bottom: 1px;color:white">
			 					<div id="olvido">Olvide mi clave</div>
			 					<div id="btn_envpwd">
									<div class="input-group margin-bottom-sm" style="margin-bottom:10px;">
									  	 <input id="correo" class="form-control" type="text" placeholder="Ingrese su correo">
									  	 <span id="env_correo" class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i> Enviar</span>
									</div> 		
									<div id="msje2">
										
									</div>			
			 					</div>
			  			</div>  	
			  		</div>
	  		</div>

			<div class="col-xs-1 col-sh-3 col-md-4 col-lg-4 text-center"></div>
  	</div>
  </body>
</html>