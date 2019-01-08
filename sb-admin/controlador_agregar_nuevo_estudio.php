<!DOCTYPE html>

<?php $_POST["pagina"]="nuevo_estudio"; 
    session_start(); 
    include_once "../include/sesion.php";
    include_once "../include/mysql.php";
    comprueba_url();
    time_session();

    //así se restringe a cualquier usuario acceder a donde no debe
    if($_SESSION['nivel_acceso'] >= 3){  
        header("Location: index.php");
    //header("Location: ../index.php");
    //exit();
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
        <?php include_once "include/nav_session.php" ?>

        <div id="page-wrapper">
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Agregar nuevo estudio <small></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                 Agregar estudio
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
                                <h3 class="panel-title"> Datos del estudio </h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                                <form role="form" id="nuevo_estudio" method="post" action="viewmod_agregar_nuevo_estudio.php" accept-charset="UTF-8">
                                     
                                    <div class="form-group">
                                        <label for="tipo_estudio">Tipo de estudio</label>
                                            
                                        <select class="form-control" form="nuevo_estudio" name="tipo_estudio">
                                            <option>GAMMAGRAMA</option>
                                            <option>TRATAMIENTO</option>
                                            <option>  </option>
                                        </select>
                                    </div> 

                                    <div class="form-group">
                                        <label for="nombre">Nombre del estudio</label>
                                        <input type="text" class="form-control" form="nuevo_estudio" name="nombre" id="nombre" placeholder="Nombre del estudio">
                                    </div>  

                                    <div class="form-group">
                                
                                        <label for="nombre">Precio del estudio (PARTICULAR)</label>
                                       
                                        <input type="text" class="form-control" form="nuevo_estudio" name="precio" id="precio" placeholder="precio">
                                        
                                    </div> 
                                        
                                </form>

                            </div><!-- /. fin panel body -->

                            <div class="panel-footer">
                            <!--    <input id="submit" name="submit" class="btn btn-danger btn-lg btn-block" form="nuevo_estudio" type="submit" value="Aceptar" class="btn btn-primary">-->

                               
                                <button type="submit" class="btn btn-danger btn-lg btn-block" aria-label="Left Align" form="nuevo_estudio">
                                    <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                                    ACEPTAR
                                </button> 

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
