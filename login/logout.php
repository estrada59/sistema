<?php 
	include "../include/mysql.php";
	session_start();

	date_default_timezone_set('America/Mexico_City');

	$fecha = date("Y-m-d"); 
	$hora =date("H:i:s");
	
	$usuario = $_SESSION['usuario'];
	$nivel_acceso = $_SESSION['nivel_acceso'];
	$idusuario = $_SESSION['id'];
		
	$mysql = new mysql();
    $link = $mysql->connect(); 

    $sql = $mysql->query($link, " 	UPDATE usuarios 
										SET activo = 0,
											fecha_salida = '".$fecha."',
											hora_salida=  '".$hora."'
										WHERE idusuario = '".$idusuario."' ");
   
	$mysql->close();		 

	session_unset(); 
    session_destroy();  

	header("Location: ../index.php");
	
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Saliendo del sistema</title>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>	
   
</head>

<body>
		<div class="modal fade" role="dialog" id="Ventana_advertencia">
				<div class="modal-dialog">
					<div class="modal-content">
					<div class="modal-body">
				    	<div class="alert alert-danger">
							<p>Sesion terminada.</p>
						</div>
					</div>
					<div class="modal-footer"> 
	   					<button onClick="salir()" class="btn btn-danger"><i class="glyphicon glyphicon-ban-circle"></i> Aceptar</button>
    			<?php /*echo '<script language=Javascript>function salir(){window.location = "../index.php";}</script>';*/?>
					</div>
					</div>
				</div>	
			</div>
   
            
	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>

<script language=Javascript>
function salir(){
	window.location = "../index.php";
	}
</script>

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

</body>
</html>