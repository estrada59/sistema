<?php 
session_start(); 
include_once "../include/mysql.php"; 
$result="";
?>

<!DOCTYPE html>
<html lang="es">
	<head>
    	<meta charset="utf-8" />
	    <title>Notificaciones</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="style.css">
		
		<!-- Versión compilada y comprimida del CSS de Bootstrap -->
  		<link rel="stylesheet" href="../css/bootstrap.min.css">
  		<!-- Tema opcional -->
  		<link rel="stylesheet" href="../css/bootstrap-theme.min.css">
    	<!--<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
	    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css">	-->
</head>


<body>

<?php

  /*print "<pre>";
  print_r($_POST);
  print_r($_SESSION);
  print "</pre>";*/


if(!isset($_SESSION['usuario'])) 
{ 
		
    if(isset($_POST['login'])) 
    { 
    	if($_POST['login']=='Iniciar sesión')
    	{
    		
    		$user = $_POST['user'];
			$password = $_POST['password'];
		
	 		$mysql = new mysql();
    		$link = $mysql->connect(); 

    		$sql = $mysql->query($link,"SELECT idusuario, usuario, nivel_acceso 
    								FROM usuarios 
    								WHERE usuario = '$user' and contraceña = SHA1('$password')");
    
			$result = $mysql->f_num($sql);  

	    	$count = 0; 
    
    		while($row = $mysql->f_obj($sql) )
	    	{ 
	        	$count++; 
	        	$result = $row;
    		} 

			
    		if($count == 1) 
        	{ 	
            	$_SESSION['usuario'] = $result->usuario; 
				$_SESSION['nivel_acceso'] = $result->nivel_acceso;
				$idusuario = $result->idusuario;

				//comprobar si no hay sesion previa
    			$sql = $mysql->query($link,"SELECT activo 
    								FROM usuarios 
    								WHERE idusuario = $idusuario");

				$row2 = $mysql->f_obj($sql);
				$activo = $row2->activo;
				$_SESSION['activo']= $activo;

				$ip = getRealIP();
				//print_r($_SESSION);
				//print_r($_POST);

				if($activo == 0 || $activo == 1)
				{
					//-------------tiempo de sesion----------------
					$_SESSION['ultima_actividad'] = time();
					$_SESSION['id'] = $idusuario;
					//---------------------------------------------

					$_SESSION['activo'] = 1;
					$activo = 1;

					date_default_timezone_set('America/Mexico_City');
					$fecha = date("Y-m-d"); 
					$hora =date("H:i:s");

					$sql = $mysql->query($link,"UPDATE usuarios 
										SET hora_acceso ='".$hora."',
											fecha_acceso = '".$fecha."',
											activo = '.$activo.',
											ip = '$ip'  
										WHERE idusuario = '".$idusuario."'");
					
					echo "<script language=Javascript> location.href=\"../sb-admin/index.php\"; </script>"; 
					echo '
						<div class="modal fade" role="dialog" id="Ventana_advertencia1">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-body">
				    					<div class="alert alert-success">
											<p>Bienvenido al sistema.</p>
										</div>
									</div>
									<div class="modal-footer"> 
		   								<button onClick="entrar1()" class="btn btn-success">
										<li class="glyphicon glyphicon-ok-sign"></li>  Aceptar</button>
									</div>
								</div>
							</div>	
						</div>';
					$mysql->close();
				}
					
        	} 
        	else 
        	{ 
				echo '
				<div class="modal fade" role="dialog" id="Ventana_advertencia">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
						    	<div class="alert alert-warning">
									<p>Su usuario o contraseña están mal, vuelva a intentarlo por favor.</p>
								</div>
							</div>
							<div class="modal-footer"> 
		   						<button onClick="entrar()" class="btn btn-warning">
									<li class="glyphicon glyphicon-exclamation-sign"></li>  Aceptar</button>
							</div>
						</div>
					</div>
				</div>';
        	} 
			$mysql->close();
		}
    }
	else{
		echo "upss..";

		echo '
			<div class="modal fade" role="dialog" id="Ventana_advertencia">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-body">
						    <div class="alert alert-warning">
								<div> <a href="../index.php">Regresa por aquí</a></div>
							</div>
						</div>
						<div class="modal-footer"> 
	   						<button onClick="entrar()" class="btn btn-warning">
								<li class="glyphicon glyphicon-exclamation-sign"></li>  Aceptar</button>
						</div>
					</div>
				</div>
			</div>';
	}
}
else { 
	echo "<script language=Javascript> location.href=\"logout.php\"; </script>"; 
} 
?>


<?php
function getRealIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
        return $_SERVER['HTTP_CLIENT_IP'];
       
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
   
    return $_SERVER['REMOTE_ADDR'];
} 
?>



	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>   
	<!--<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script> -->
	
    <!-- Versión compilada y comprimida del JavaScript de Bootstrap -->
	<script src="../js/bootstrap.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
		<!-- Muestra ventana -->
        $('#Ventana_advertencia').modal('show');
		
		<!-- Si das click en cualquier parte redirecciona y cierra ventana -->
		$('#Ventana_advertencia').on('hidden.bs.modal', function () {
			window.location="../index.php";
		})	
    });
</script>
<script type="text/javascript">
    $(document).ready(function(){
		<!-- Muestra ventana -->
        $('#Ventana_advertencia1').modal('show');
		
		<!-- Si das click en cualquier parte redirecciona y cierra ventana -->
		$('#Ventana_advertencia1').on('hidden.bs.modal', function () {
			window.location="../sb-admin/index.php";
		})	
    });
</script>

<script language=Javascript>
function entrar(){
	//window.location = "../index.php";
	}
	
</script>
<script language=Javascript>
function entrar1(){
	window.location = "../sb-admin/index.php";
	}
	
</script>

<script language=Javascript>
function entrar3(){
	window.location = "../sb-admin/index.php";
	}
	
</script>
</body>
</html>  

