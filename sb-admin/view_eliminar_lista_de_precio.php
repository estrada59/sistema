<?php
include "include/mysql.php";
include_once "include/funciones_consultas.php";

function previsualizacion($institucion){


echo' 
    <div class="col-md-12 col-lg-12">  
        <div class="panel panel-primary">        
            <div class="panel-heading">
                <h3 class="panel-title"> Se actualizaron los registros </h3>
            </div>  <!--    fin panel heading   -->
            
            <div class="panel-body">';

echo '          <p><strong>Estos datos se ELIMINARON</strong> </p>';
echo '          <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            
                            <th data-field="lista de precios">Lista de precios</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            
                            <th data-field="lista de precios">'.pasarMayusculas($institucion).'</th>
                            
                        </tr>
                    <tbody>
                </table> 
            </div>

            <div class="panel-footer">
            </div>
        </div>
    </div>';
    update($institucion);
        
}
?>
<?php
function update($institucion){

    $mysql = new mysql();
    $mysqli = $mysql->connect();
           
    $sql = "UPDATE instituciones 
                SET estatus = 'INACTIVO'
                WHERE nombre = '$institucion';";

    $mysqli->query($sql);

    printf("%s\n", mysqli_info($mysqli));

    echo '<div class="hola">filas afectadas: (Se eliminó) '.$mysqli->affected_rows.' fila</div>';
    
    $mysql->close();
}
?>

<!DOCTYPE html>
<?php $_POST["pagina"]="form"; ?>
<html lang="es">
<head>
    <!-- aqui pondremos el contenido html -->
     <?php include "header.php"; 
        session_start(); 
            include "../include/sesion.php";
            comprueba_url();

        if(!isset($_POST["institucion"])){
            header('Location: index.php');
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
                             <?php    ?><small></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Se eliminó lista de precios satisfactoriamente
                            </li>
                        </ol>

                    </div>
                </div>
                <!-- /.row -->
            <!-- /.container-fluid -->
                <br>
            <?php previsualizacion($_POST["institucion"]);?>

            <form  role="form" id="editar_lista" method="post" action="controlador_eliminar_lista_de_precios.php">
                
            </form>
            <input id="submit" name="submit" class="btn btn-success btn-lg btn-block" form="editar_lista" type="submit" value="Aceptar" class="btn btn-primary">


        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>


</body>


</html>