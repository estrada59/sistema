<!DOCTYPE html>
<?php $_POST["pagina"]="ver_lista_precios"; ?>
<html lang="en">

<head>
    <?php   include "header.php";
            session_start(); 
            include "../include/sesion.php";
            comprueba_url();

            if($_SESSION['nivel_acceso'] != 1){
                header("Location: index.php");
                exit();
            }
    ?>
    <!-- css validation form -->
    <link rel="stylesheet" href="css/style_validation_form.css">    
    <!-- jquery validation form -->
    <script src="js/livevalidation_standalone.js"></script>
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
       <?php  
            if($_SESSION['nivel_acceso'] == 3){
                include "nav.php"; }
            if($_SESSION['nivel_acceso'] == 1){
                include "nav_priv.php"; }
            if($_SESSION['nivel_acceso'] == 2){
                include "nav_contador.php"; }
       ?>
        <div id="page-wrapper">
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Ingresar nueva lista de precios<small>
                            </small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                 
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <form role="form" id="atras" method="post" action="controlador_ver_lista_precios.php">
                        </form>
                        <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="atras">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            Atrás
                        </button>                            
                    </div>
                </div>
                </br>
                <!-- /.row -->

                <form role="form" id="insertar_lista" method="post" action="viewmod_agregar_nueva_lista_de_precios.php">
                    <div class="form-group">
                        <label for="fecha">Nombre de la institución</label>
                        <input type="text" class="form-control" form="insertar_lista" name="nombre_institucion" id="nombre" placeholder="Nombre de la institución" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha">Tipo de institución</label>
                        <select class="form-control" form="insertar_lista" name="tipo" id="tipo">
                            <option>PUBLICA</option>
                            <option>PARTICULAR</option>
                        </select>
                    </div>
                </form>
                <input id="submit" name="submit" class="btn btn-success btn-lg btn-block" form="insertar_lista" type="submit" value="Aceptar" class="btn btn-primary">

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
        var institucion = new LiveValidation('nombre', { validMessage: 'OK!', wait: 500});
		institucion.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        institucion.add(Validate.Length, {minimum: 2, maximum: 45 } );
        institucion.add( Validate.Exclusion, { within: [ '.' , ',', ';',':','-','á','é','í','ó','ú' ], partialMatch: true });
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
