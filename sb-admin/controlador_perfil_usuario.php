<!DOCTYPE html>
<?php $_POST["pagina"]="inicio"; ?>
<html lang="en">

<head>
    <?php 	include "header.php";
    		session_start(); 
			include_once "../include/sesion.php";
            include_once "../include/mysql.php";
			comprueba_url();
            time_session();
            
 	?>
</head>

<body>
    <div id="wrapper">
        <!-- Navigation -->
       <?php 
            if($_SESSION['nivel_acceso'] == 3){
                include "nav.php";  
            }
            if($_SESSION['nivel_acceso'] == 1){
                include "nav_priv.php"; 
            }
            if($_SESSION['nivel_acceso'] == 2){
                include "nav_contador.php"; 
            }
            if($_SESSION['nivel_acceso'] == 4){
                include "nav_op.php";
            }
       ?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Perfil de usuario<small></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Configuraciones de perfil de usuario
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

                <?php 
                  /*  print "<pre>";
                    print_r($_POST);
                    print_r($_SESSION);
                    print "</pre>";
            */
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-primary">
                
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <span class="glyphicon glyphicon-bookmark"></span> Cambiar foto de perfil</h3>
                        </div>
                
                        <div class="panel-body">
                            
                            <div class="row">

                                <div class="col-lg-6">
                                <?php

                                     $id = $_SESSION['id'];
                                     $usuario = $_SESSION['usuario'];
                                     $nivel_acceso = $_SESSION['nivel_acceso'];

                                    //echo $id.'  '.$usuario.'   '.$nivel_acceso;
                                    echo'
                                        <form id="subir_archivo'.$id.'" action="viewmod_subir_foto_perfil.php"  method="POST" enctype="multipart/form-data">
                                            <!--<input type="file" form=subir_archivo'.$id.' name="imagen"  data-toggle="fancyfile" data-text="false" class="input-medium">-->
                                            <input type="file" form=subir_archivo'.$id.' name="imagen" > 
                                            <input type="hidden" form="subir_archivo'.$id.'" name = "iduser" value="'.$id.'"/>
                                            <input type="hidden" form="subir_archivo'.$id.'" name = "nombre" value="'.$usuario.'"/>
                                            <input type="hidden" form="subir_archivo'.$id.'" name = "nivel_acceso" value="'.$nivel_acceso.'"/>
                                        </form> ';

                                    echo '</br>';
                                ?>
                                </div>
                                <div class="col-lg-6">
                                <?php
                                    echo '
                                        <button type="submit" form="subir_archivo'.$id.'" name="subir" value="subir" class="btn btn-success  btn-block" >
                                            <span class="glyphicon " aria-hidden="true"></span>
                                            Subir
                                        </button>';
                                ?>
                                </div>
                            </div> <!-- /. div row -->
                        </div>  <!--    /. div panel body -->
                
                    </div>
                </div>
           
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
        </div>
        <!-- /#wrapper -->

    <!-- esto es pa subir archivos
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-fancyfile.min.js"></script>
    -->

    <script type="text/javascript">
        function disableF5(e) { if ((e.which || e.keyCode) == 116) e.preventDefault(); };
        // To disable f5
        $(document).bind("keydown", disableF5);
            /* OR jQuery >= 1.7 */
        $(document).on("keydown", disableF5);
        // To re-enable f5
        /* jQuery < 1.7 */
        /*$(document).unbind("keydown", disableF5);
            $(document).off("keydown", disableF5);*/
    </script>

    <script type="text/javascript">
        document.onkeydown = function (event) { 
            if (!event) { /* This will happen in IE */
                //
                event = window.event;
            }      
            var keyCode = event.keyCode;
            
            if (keyCode == 8 &&
                ((event.target || event.srcElement).tagName != "TEXTAREA") && 
                ((event.target || event.srcElement).tagName != "INPUT")) { 
                
                if (navigator.userAgent.toLowerCase().indexOf("msie") == -1) {
                    event.stopPropagation();
                } else {
                    alert("prevented");
                    event.returnValue = false;
                }
                return false;
            }
        };  
    </script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

 
</body>

</html>
