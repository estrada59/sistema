<?php
include "include/mysql.php";

function previsualizacion(){

$idgammagramas  =   $_POST["idgammagramas"];    
$estudio        =   $_POST["estudio"];
$precio         =   $_POST["precio"];

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
                            <th data-field="id">Item ID</th>
                            <th data-field="price">Nombre del estudio</th>
                            <th data-field="price">Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th data-field="id">'.$idgammagramas.'</th>
                            <th data-field="price">'.$estudio.'</th>
                            <th data-field="name">'.$precio.'</th>
                        </tr>
                    <tbody>
                </table> 
            </div>

            <div class="panel-footer">
            </div>
        </div>
    </div>';

        update($idgammagramas);
}
?>
<?php
function update($idgammagramas){

    $mysql = new mysql();
    $mysqli = $mysql->connect();
            
    $sql = "DELETE FROM estudio WHERE idgammagramas = $idgammagramas";

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

        if(!isset($_POST["idgammagramas"])){
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
                                <i class="fa fa-dashboard"></i> Modificación de lista de precios
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
            <!-- /.container-fluid -->
            <?php previsualizacion();?>

            <form  role="form" id="editar_lista" method="post" action="viewmod_ver_lista_precios.php">
                
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