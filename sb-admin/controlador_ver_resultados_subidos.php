<!DOCTYPE html>
<?php $_POST["pagina"]="ver_resuldados_subidos"; ?>
<html lang="es">

<head>
    <?php 	include "header.php";
    		session_start(); 
			include "../include/sesion.php";
			comprueba_url();
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
                include "nav_priv.php"; }
            if($_SESSION['nivel_acceso'] == 4){
                include "nav_op.php"; }
       ?>
        <div id="page-wrapper">
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Ver resultado subidos<small>
                            </small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                Resultados subidos el día: 
                                 <?php 
                                include 'include/funciones_consultas.php';
                                date_default_timezone_set ('America/Mexico_City');
                                $f = date('Y-m-d');                                
                                $fecha_l = fecha_letras($f);
                                echo $fecha_l;
                                ?>
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

                <form role="form" id="ver_pacientes" method="post" action="viewmod_ver_resultados_subidos.php">
                  <!--  <div class="form-group">
                        <label for="fecha">Fecha</label>
                        <input type="date" class="form-control" form="ver_pacientes" name="fecha_estudios" id="fecha_estudios" placeholder="Fecha estudio" required>
                    </div>-->
                    <?php
                        $fecha_act = date('Y-m-d');
                        echo '<input type="hidden" name="fecha_act" id="fecha_act" form="ver_pacientes" value="'.$fecha_act.'">';
                    ?>
                </form>
                <input id="submit" name="submit" class="btn btn-success btn-lg btn-block" form="ver_pacientes" type="submit" value="Aceptar" class="btn btn-primary">
                
                <?php 
                /*	
                    print "<pre>";
                    print_r($_POST);
                    print_r($_SESSION);
                    print "</pre>";
                */
                ?>
        
    </div>  <!--    fin col-lg-12   -->

            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

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
