<!DOCTYPE html>
<?php $_POST["pagina"]="ver_pacientes_del_dia"; ?>
<html lang="es">

<head>
    <?php 	include "header.php";
    		session_start(); 
			include "../include/sesion.php";
			comprueba_url();

            if(!isset($_POST['fecha_act'])){
                header('Location: controlador_ver_pacientes_del_dia.php');
            }
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
                            Ver resultados subidos
                            <small>
                            <?php 
                                include 'include/funciones_consultas.php';
                                
                                $fecha_act = $_POST['fecha_act'];
                                $fecha_l = fecha_letras($fecha_act);
                                echo $fecha_l;
                            ?>
                            </small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i>
                                Resultado subidos el día <?php echo $fecha_l; ?>
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
               
                <?php 
            
                    $fecha_act = $_POST['fecha_act'];
                    
                    if($_SESSION['nivel_acceso'] == 4 || $_SESSION['nivel_acceso'] == 1 ){ //muestra sin franjas de color
                        ver_resultados_pacientes($fecha_act);
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