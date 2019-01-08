<!DOCTYPE html>

<?php $_POST["pagina"]="agregar_cliente"; 
    session_start(); 
    include "../include/sesion.php";
    comprueba_url();

    //así se restringe a cualquier usuario acceder a donde no debe
    if($_SESSION['nivel_acceso'] > 2){  
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
       <?php 
            if($_SESSION['nivel_acceso'] >=0 && $_SESSION['nivel_acceso'] <= 1){
                include "nav_priv.php"; 
            }
            else{ include "nav.php"; }

       ?>

        <div id="page-wrapper">
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Agregar nuevo cliente <small></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                 Agregar cliente
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->


                <div class="row">
                    <div class="col-md-6 col-lg-6">
                        <!--        1er columnoa de captura         -->
                        

                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"> Razón social </h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                                <form role="form" id="nuevo_cliente" method="post" action="viewmod_agregar_nuevo_cliente.php" accept-charset="UTF-8">
                                     
                                    <div class="form-group">
                                        <label for="nombre">Razón social</label>
                                        <input type="text" class="form-control" form="nuevo_cliente" name="razon_social" id="razon_social" placeholder="Razón social">
                                    </div>

                                </form>

                            </div><!-- /. fin panel body -->

                        </div><!-- /. fin panel primary -->

                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"> R.F.C. </h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                                <form role="form" id="nuevo_cliente" method="post"  accept-charset="UTF-8">
                                
                                    <div class="form-group">
                                        <label for="nombre">R.F.C.</label>
                                        <input type="text" class="form-control" form="nuevo_cliente" name="rfc" id="rfc" placeholder="R. F. C.">
                                    </div>
                                </form>

                            </div><!-- /. fin panel body -->

                        </div><!-- /. fin panel primary -->

                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"> NOMBRE COMERCIAL </h3>
                            </div>  <!--    fin panel heading   -->
                            <div class="panel-body">
                                <form role="form" id="nuevo_cliente" method="post" accept-charset="UTF-8">
                                
                                    <div class="form-group">
                                        <label for="nombre">NOMBRE COMERCIAL</label>
                                        <input type="text" class="form-control" form="nuevo_cliente" name="nombre_comercial" id="nombre_comercial" placeholder="Ingrese el nombre comercial de la institución...">
                                    </div>
                                </form>
                            </div><!-- /. fin panel body -->
                        </div><!-- /. fin panel primary -->

                    </div>
                    <div class="col-md-6 col-lg-6">
                        <!--        1er columnoa de captura         -->
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"> DOMICILIO </h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                                <form role="form" id="nuevo_cliente" method="post"  accept-charset="UTF-8">

                                    <div class="form-group">
                                        <label for="nombre">Calle</label>
                                        <input type="text" class="form-control" form="nuevo_cliente" name="calle" id="calle" placeholder="Calle">
                                    </div>

                                    <div class="form-group">
                                        <label for="nombre">No.</label>
                                        <input type="text" class="form-control" form="nuevo_cliente" name="numero" id="numero" placeholder="Número">
                                    </div>

                                    <div class="form-group">
                                        <label for="nombre">Colonia</label>
                                        <input type="text" class="form-control" form="nuevo_cliente" name="colonia" id="colonia" placeholder="Colonia">
                                    </div>  

                                    <div class="form-group">
                                        <label for="nombre">Código Postal</label>
                                        <input type="text" class="form-control" form="nuevo_cliente" name="codigo_postal" id="codigo_postal" placeholder="Código Postal">
                                    </div> 

                                    <div class="form-group">
                                        <label for="nombre">Municipio o Delegación</label>
                                        <input type="text" class="form-control" form="nuevo_cliente" name="municipio" id="municipio" placeholder="Municipio">
                                    </div>

                                    <div class="form-group">
                                        <label for="nombre">Estado</label>
                                        <input type="text" class="form-control" form="nuevo_cliente" name="estado" id="estado" placeholder="Estado">
                                    </div>

                                    <div class="form-group">
                                        <label for="nombre">País</label>
                                        <input type="text" class="form-control" form="nuevo_cliente" name="pais" id="pais" placeholder="País">
                                    </div>

                                </form>

                            </div><!-- /. fin panel body -->
                        </div><!-- /. fin panel primary -->
                    </div>
                    <div class="col-md-12 col-lg-12">
                        <button type="submit" class="btn btn-danger btn-lg btn-block" aria-label="Left Align" form="nuevo_cliente">
                            <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                            ACEPTAR
                        </button>
                    </div>
                     
            </div> 
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->


    <!-- Validación de formularios -->
    <script type="text/javascript">
        var razon_social = new LiveValidation('razon_social', { validMessage: 'OK!', wait: 500});
        razon_social.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        razon_social.add(Validate.Length, {minimum: 2, maximum: 150 } );
        razon_social.add( Validate.Exclusion, { within: [ ',', ';',':','-','_' ], partialMatch: true } );
    </script>

    <script type="text/javascript">
        var rfc = new LiveValidation('rfc', { validMessage: 'OK!', wait: 500});
        rfc.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        rfc.add(Validate.Length, {minimum: 12, maximum: 13 } );
        rfc.add(Validate.Exclusion, { within: ['.', ',', ';',':','-','_' ], partialMatch: true } );
    </script> 

    <script type="text/javascript">
        var nombre_comercial = new LiveValidation('nombre_comercial', { validMessage: 'OK!', wait: 500});
        nombre_comercial.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        nombre_comercial.add(Validate.Length, {minimum: 2, maximum: 80 } );
        nombre_comercial.add(Validate.Exclusion, { within: ['.', ',', ';',':','-','_',' ' ], partialMatch: true } );
    </script>

    <script type="text/javascript">
        var calle = new LiveValidation('calle', { validMessage: 'OK!', wait: 500});
        calle.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        calle.add(Validate.Length, {minimum: 2, maximum: 200 } );
        calle.add( Validate.Exclusion, { within: ['.', ',', ';',':','-','_' ], partialMatch: true } );
    </script> 

    <script type="text/javascript">
        var numero = new LiveValidation('numero', { validMessage: 'OK!', wait: 500});
        numero.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        numero.add( Validate.Numericality );
        numero.add( Validate.Length, { minimum: 1, maximum:5 } );
    </script> 

    <script type="text/javascript">
        var colonia = new LiveValidation('colonia', { validMessage: 'OK!', wait: 500});
        colonia.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        colonia.add(Validate.Length, {minimum: 2, maximum: 150 } );
        colonia.add(Validate.Exclusion, { within: ['.', ',', ';',':','-','_' ], partialMatch: true } );
    </script>

    <script type="text/javascript">
        var codigo_postal = new LiveValidation('codigo_postal', { validMessage: 'OK!', wait: 500});
        codigo_postal.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        codigo_postal.add(Validate.Numericality );
        codigo_postal.add(Validate.Length, { minimum: 5, maximum:7 } );
    </script>

    <script type="text/javascript">
        var municipio = new LiveValidation('municipio', { validMessage: 'OK!', wait: 500});
        municipio.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        municipio.add(Validate.Length, {minimum: 2, maximum: 150 } );
        municipio.add(Validate.Exclusion, { within: ['.', ',', ';',':','-','_' ], partialMatch: true } );
    </script>

    <script type="text/javascript">
        var estado = new LiveValidation('estado', { validMessage: 'OK!', wait: 500});
        estado.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        estado.add(Validate.Length, {minimum: 2, maximum: 150 } );
        estado.add(Validate.Exclusion, { within: ['.', ',', ';',':','-','_' ], partialMatch: true } );
    </script>

    <script type="text/javascript">
        var pais = new LiveValidation('pais', { validMessage: 'OK!', wait: 500});
        pais.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        pais.add(Validate.Length, {minimum: 2, maximum: 150 } );
        pais.add(Validate.Exclusion, { within: ['.', ',', ';',':','-','_' ], partialMatch: true } );
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
