<!DOCTYPE html>

<?php $_POST["pagina"]="instituciones"; 
    session_start(); 
    include_once "../include/sesion.php";
    include_once "../include/mysql.php";
    comprueba_url();
    time_session();

    if($_SESSION['nivel_acceso'] >= 4){
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
            include_once 'include/nav_session.php';
       ?>

        <div id="page-wrapper">
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Crear contrato<small></small>
                        </h1>

                        <ol class="breadcrumb">
                            <li class="active">
                                <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                                 Crear contrato
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

                <div class="row">
                    
                    <div class="col-md-3 col-lg-3">
                    </div>
                    
                    <div class="col-md-6 col-lg-6">
                        <!--        agregar lista de precios y eliminar         -->
                        <form role="form" id="atras" method="post" action="controlador_monto_ejecutado.php" accept-charset="UTF-8">
                            <input id="submit" name="submit" form = "atras" class="btn btn-success btn-lg btn-block"  type="submit" value="Atrás">
                        </form>
                        
                        <br>

                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"> Crear contrato </h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                                <?php
                                    include_once "include/funciones_consultas.php";
                                    //monto_ejecutado_por_instituciones();
                                    // echo '<pre>';
                                    // print_r($_POST);
                                    // echo '</pre>';
                                    
                                    $id_institucion = $_POST['id_contrato'];

                                    $mysql = new mysql();
                            
                                    $link = $mysql->connect();
                                    $sql = $mysql->query($link,"SELECT  idinstitucion,
                                                                        nombre,
                                                                        tipo
                                                                    FROM instituciones 
                                                                    WHERE tipo='PUBLICA'  AND idinstitucion = $id_institucion;");
           
                                    echo '
                                    <form role="form" id="crear_contrato" method="post" action="view_crear_contrato.php">
                                        <fieldset >
                                            
                                            <div class="form-group">

                                                <label>Institucion</label>

                                                <select form="crear_contrato" name="id_contrato" class="form-control">';
                                                    
                                                    while ($row = $mysql->f_obj($sql))
                                                    {
                                                        $nombre = str_replace("_", " ", $row->nombre);
                                                        $nombre_minusculas = $row->nombre;
                                                        echo'<option value="'.$row->idinstitucion.'">'.pasarMayusculas($nombre).'</option>';
                                                    }

                                                    $mysql->close();

                                    echo'       </select>
                                            </div>

                                            <div class="form-group">

                                                <label>Monto Máximo de contrato</label>

                                                <input type="text" form="crear_contrato" class="form-control" id="monto_maximo" name="monto_maximo" placeholder="Ingrese el monto máximo del contrato" required>

                                            </div>

                                            <div class="form-group">

                                                <label>Fecha de inicio de contrato </label>

                                                <input type="date" form="crear_contrato" class="form-control" name="fecha_inicio_contrato" required>

                                            </div>

                                            <div class="form-group">

                                                <label>Fecha terminación de contrato </label>

                                                <input type="date"  form="crear_contrato"class="form-control" name="fecha_fin_contrato" required>

                                                <input type="hidden" form="crear_contrato" name="nombre_institucion" value="'.pasarMayusculas($nombre).'">

                                                <input type="hidden" form="crear_contrato" name="nombre_minusculas" value="'.$nombre_minusculas.'">

                                            </div>

                                            <button type="submit" form="crear_contrato" class="btn btn-primary">Crear contrato</button>

                                        </fieldset>
                                    </form>
                                    ';
                                ?>
                            </div><!-- /. fin panel body -->

                            <div class="panel-footer">   
                            </div>
                        </div><!-- /. fin panel primary -->
                    </div>

                    <div class="col-md-3 col-lg-3">
                    </div>
            </div> 
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Validación de formularios -->
    
    <script type="text/javascript">
        var monto_maximo = new LiveValidation('monto_maximo', { validMessage: 'OK!', wait: 500});
        monto_maximo.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        monto_maximo.add(Validate.Length, {minimum: 3 } );
        monto_maximo.add( Validate.Numericality, {minimum: 1} );
    </script> 

</body>

</html>
