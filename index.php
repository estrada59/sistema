 <?php 
session_start(); 
include "include/sesion.php";

      /*print "<pre>";
      print_r($_POST);
      print_r($_SESSION);
      print "</pre>";*/
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Sistema Medicina Nuclear</title>
	<meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
	<!--<link rel="stylesheet" href="style.css">-->
  <!-- Versión compilada y comprimida del CSS de Bootstrap -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- Tema opcional -->
  <link rel="stylesheet" href="css/bootstrap-theme.min.css">

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
  <!-- css validation form -->
  <link rel="stylesheet" href="sb-admin/css/style_validation_form.css">
	<!-- jquery validation form -->
  <script src="sb-admin/js/livevalidation_standalone.js"></script>

  <!-- Favicons--> 
  <link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192"  href="favicon/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
  <link rel="manifest" href="favicon/manifest.json">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">
  <meta name="theme-color" content="#ffffff">
    
</head>

<body>

  <div class="container"> 
    <!-- Button to trigger modal -->
  <!--<a href="#myModal" role="button" class="btn btn-success" data-toggle="modal"><?php verifica_sesion();?></a>-->
  <!-- Modal cuidado class: hide-->
    <div id="myModal" data-backdrop="static"  class="modal fade" role="dialog" >
     <div class="modal-dialog">
     <div class="modal-content">
            
         <form id="form" role='form' action='login/index.php' method='post' class=' form-horizontal form-signin has-succes'>
            <div class="modal-header">                    
              
                <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>-->
        
                <div class="row">
            
                    <div class="col-md-6">
                      <h3 id="myModalLabel" > <?php message_session();?></h3>
            </div>
                    
                    <div class="col-md-1">
            <img src="images/log.jpg" class="img-rounded" alt="Medicina Nuclear" />
                    </div>
         
                 </div>
                 
            <!-- Modal-body -->   
            <?php show_textbox(); ?>
                    
            <div class="modal-footer">
            <!--<button class="btn" data-dismiss="modal" type='submit' aria-hidden="true"><?php message_session1(); ?></button>-->
            <input class=' btn btn-primary'  name='login' id="login" type='submit' value='<?php message_session() ?>'>
         </div>
    </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
 
   </div>

   <!-- Versión compilada y comprimida del JavaScript de Bootstrap -->
	<script src="js/bootstrap.min.js"></script>
    
    
    <!-- Validación de formularios -->
    <script type="text/javascript">
		var iduser = new LiveValidation('iduser', { validMessage: 'OK!', wait: 500});
		iduser.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
		iduser.add(Validate.Length, {minimum: 4 } );
	</script> 
    <script type="text/javascript">
		var idpass = new LiveValidation('idpassword', { validMessage: 'OK!', wait: 500});
		idpass.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
		idpass.add(Validate.Length, {minimum: 1 } );
    
    idpass.add( Validate.Exclusion, { within: [ '"' , '=', '/','=','~','*',"'" ], partialMatch: true } );
	</script>
  
    <!-- Actualizar parte de una página-->
    <!-- Acción sobre el botón con id=boton y actualizamos el div con id=capa -->
    <script type="text/javascript">
    	$(document).ready(function() {
        
			$('#myModal').modal('show');
      
      
        });
    </script>

    <script type="text/javascript">
      document.getElementById("idpassword").addEventListener("keyup", function(event) {event.preventDefault();
        if (event.keyCode == 13) {
            document.getElementById("login").click();
        }
      });
    </script>
    
    
</body>
</html>