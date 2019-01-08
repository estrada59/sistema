<?php session_start(); ?>

<!DOCTYPE html>
<?php $_POST["pagina"]="anticipos_por_fecha"; ?>
<html lang="en">

<head>
    <?php 	include "header.php"; 
			include "../include/sesion.php";
			comprueba_url();

            if(!isset($_POST['fecha_estudios'])){
                header('Location: index.php');
            }
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
                            Pacientes de mes del 
                                <?php 
                                include 'include/funciones_consultas.php';
                                $mes = obtener_mes($_POST['fecha_estudios']);
                                $mes = pasarMayusculas($mes);
                                echo $mes;
                                ?>
                            
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Inicio
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
               
                <?php 
                    $fecha_estudios = $_POST['fecha_estudios'];
                    reimprimir_recibos($fecha_estudios);
                     /*	print "<pre>"; print_r($_POST); print_r($_SESSION); print "</pre>";*/    
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