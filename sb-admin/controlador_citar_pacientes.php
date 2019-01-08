<!DOCTYPE html>

<?php 


    $_POST["pagina"]="citar_pacientes"; 
    session_start(); 
    include_once "../include/sesion.php";
    include_once "include/mysql.php";
    comprueba_url();
    time_session();

    if($_SESSION['nivel_acceso'] >= 5){
    header("Location: index.php");
    exit();
    }

?>
<html lang="en">

<head>
    <?php include "header.php"; ?>
     <!-- css validation form -->
    <link rel="stylesheet" href="css/style_validation_form.css">
    <!-- jquery validation form -->
    <script src="js/livevalidation_standalone.js"></script>
</head>

<body>
    <div id="wrapper">
        <!-- Navigation -->
       <?php
            if($_SESSION['nivel_acceso'] == 4){
                include "nav_op.php";  
            }
            if($_SESSION['nivel_acceso'] == 3){
                include "nav.php";  
            }
            if($_SESSION['nivel_acceso'] == 1){
                include "nav_priv.php"; }

       ?>

        <div id="page-wrapper">
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Citar pacientes<small></small>
                        </h1>

                        <ol class="breadcrumb">
                            <li class="active">
                                <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                                 Citar pacientes
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <!--        1er columnoa de captura         -->
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"> Citar pacientes. </h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                                
                                <!--<form role="form" id="particular" method="post" action="controlador_citar_pacientes_particulares.php" accept-charset="UTF-8">
                                </form>
                                <form role="form" id="IMSS" method="post" action="controlador_citar_pacientes_imss.php" accept-charset="UTF-8">
                                </form>-->

                                <div class="row">
                                
                                <?php

                                    $mysql =new mysql();
                                    $link = $mysql->connect(); 
                    
                                    $sql = $mysql->query($link,"SELECT  count(nombre) as total
                                                                    FROM instituciones 
                                                                    WHERE estatus='ACTIVO';");
                                    $row = $mysql->f_obj($sql);
                                    
                                    $total = $row->total;

                                    $total = $total/3;
                                    /*pendientes por terminar*/
                                 ?>
                                    <div class="col-md-4 col-lg-4">
                                    </div>

                                    <div class="col-md-4 col-lg-4">
                                        <?php
                                            
                                            include_once 'include/funciones_consultas.php';
                                            citar_pacientes();
                                        ?>
                                    </div>                    

                                    <div class="col-md-4 col-lg-4">
                                    </div>

                                </div>
                            </div><!-- /. fin panel body -->

                            <div class="panel-footer">   
                            </div>
                        </div><!-- /. fin panel primary -->
                    </div>
            </div> 
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

   

    <!-- Validación de formularios -->
    <script type="text/javascript">
        var nombre = new LiveValidation('nombre', { validMessage: 'OK!', wait: 500});
        nombre.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        nombre.add(Validate.Length, {minimum: 5 } );
    </script> 
    <script type="text/javascript">
        var precio = new LiveValidation('precio', { validMessage: 'OK!', wait: 500});
        precio.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        precio.add(Validate.Length, {minimum: 3 } );
        precio.add( Validate.Numericality );
    </script> 

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
