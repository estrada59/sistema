<!DOCTYPE html>
<?php $_POST["pagina"]="ver_lista_precios";   ?>
<html lang="en">

<head>
    <?php   include "header.php";
            session_start(); 
            include "../include/sesion.php";
            comprueba_url();

            if(!isset($_POST['institucion'])){
                header('Location: controlador_ver_lista_precios.php');
            }
    ?>
    <link rel="stylesheet" href="css/style_validation_form.css">    
    <!-- jquery validation form -->
    <script src="js/livevalidation_standalone.js"></script>
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
       ?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Aumentar precios a institución
                                <?php 
                                include_once 'include/funciones_consultas.php';
                                $institucion = $_POST['institucion'];
                                $institucion = pasarMayusculas($institucion);
                                echo $institucion; 
                                ?>

                            <small></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                
                                <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                                Aumenta el precio a todos los estudios de acuerdo al porcentaje especificado.
                            </li>
                        </ol>
                    </div>
                    <div class="col-lg-3">
                        <?php
                        echo'
                            <form role="form" id="ver_lista" method="post" action="viewmod_ver_lista_precios_instituciones.php" accept-charset="UTF-8">
                                <input type="hidden" form="ver_lista" name="institucion" value="'.$institucion.'"/>
                            </form>
                            <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                Atrás
                            </button> 

                            </br> 
                            ';
                        ?>
                    </div>
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-5">

                        <div class="panel panel-success">
                            <div class="panel-heading">Indicar el aumento</div>
                            
                            <div class="panel-body">
                                <?php 
                                    echo'
                                    <div class="form-group">
                                        <label for="descuento"> Indicar el aumento</label>
                                        <input type="text"  form="aumentar_precio" name="descuento" id="descuento" value="" >%
                                    </div>';
                                ?>
                            </div>
                        </div>
                       
                    </div>
                    <div class="col-lg-3">
                        <form role="form" id="aumentar_precio" method="post" action="view_aumentar_precio.php" accept-charset="UTF-8">
                            <?php 
                                echo'
                                <input type="hidden" form="aumentar_precio" name="institucion" value="'.$_POST['institucion'].'"/>
                                ';
                            ?>
                        </form>
                        
                        <button type="submit" class="btn btn-danger btn-lg btn-block" aria-label="Left Align" form="aumentar_precio">
                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                            Aceptar
                        </button> 
                    </div>
                </div>
                
        
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

    <script type="text/javascript">
        var f4 = new LiveValidation('descuento', { validMessage: 'OK!', wait: 500});
        f4.add( Validate.Numericality );
        f4.add( Validate.Length, { minimum: 1,maximum:2 }  );
        f4.add( Validate.Exclusion, { within: [ '.' , ',', ';',':','-','_' ], partialMatch: true } );
        f4.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
    </script> 
</body>
</html>