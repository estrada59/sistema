<!DOCTYPE html>
<?php $_POST["pagina"]="ver_pacientes_del_dia"; ?>
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
            include_once 'include/nav_session.php';
        ?>
        
        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Pacientes del día 

                            <small>
                            <?php 
                                include 'include/funciones_consultas.php';
                                
                                //$fecha_act = $_POST['fecha_estudios'];
                                date_default_timezone_set('America/Mexico_City'); 
                                $fecha_act = date('Y-m-d');
                                $fecha_l = fecha_letras($fecha_act);
                                echo $fecha_l;
                            ?>
                            </small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i>
                                Pacientes citados el día <?php echo $fecha_l; ?>
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
               
                <?php 
                   
                    if($_SESSION['nivel_acceso'] <= 3 ){
                        ver_pacientes_del_dia($fecha_act,$_POST["pagina"]); //muestra con franjas de color
                    }
                    if($_SESSION['nivel_acceso'] <= 4 ){ //muestra sin franjas de color
                        ver_pacientes_del_dia_operario($fecha_act,$_POST["pagina"]);
                    }
                    if($_SESSION['nivel_acceso'] >= 5 ){ //muestra sin franjas de color
                        ver_pacientes_del_dia_operario($fecha_act,$_POST["pagina"]);
                    }
                     /*	print "<pre>"; print_r($_POST); print_r($_SESSION); print "</pre>";*/    
                ?>
            </div>  <!--    fin col-lg-12   -->
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- JAVASCRIPT -->
   <!-- esto es pa subir archivos-->
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-fancyfile.min.js"></script>
    

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