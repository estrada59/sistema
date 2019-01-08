<?php

function ver_archivos(){
  //conexion a la base de datos
  include_once "include/mysql.php";
  $mysql = new mysql();
  $link = $mysql->connect(); 

  //si la variable imagen no ha sido definida nos dara un advertencia.
  $idpaciente = $_POST['idpaciente'];

  //vamos a crear nuestra consulta SQL
  $sql = $mysql->query($link, "SELECT ruta FROM resultados WHERE idpaciente = $idpaciente");
  //con mysql_query la ejecutamos en nuestra base de datos indicada anteriormente
  //de lo contrario mostraremos el error que ocaciono la consulta y detendremos la ejecucion.
  echo '
  <div class="table-responsive">
    <table class="table table-hover table-bordered">
      <tr>
        <td>nombre</td>
        <td>imagen</td>
      </tr>

  ';
  while ($row = $mysql->f_obj($sql)) {
    //ruta va a obtener un valor parecido a "imagenes/nombre_imagen.jpg" por ejemplo
    $ruta = $row->ruta;
    echo '<tr>
            <td width = "30">
            '.$ruta.'
            </td>';
    //ahora solamente debemos mostrar la imagen
    echo '<td widt = 50>
            <img src='.$ruta.' class="img-responsive img-rounded" style="max-width: 100%; height: auto; display: block;" />
          </td>';        
    
    echo '</tr>';
  } 
  echo '</table>
  </div>';
}
 
?>

<!DOCTYPE html>
<?php $_POST["pagina"]="ver_resuldados_subidos"; ?>
<html lang="en">

<head>
    <?php   include "header.php";
        session_start(); 
      include "../include/sesion.php";
      comprueba_url();
     /* if(!isset($_POST['idpaciente'])){
        header("Location: index.php");
      }*/

  ?>
</head>

<body>
    <div id="wrapper">

        <!-- Navigation -->
       <?php  
          if($_SESSION['nivel_acceso'] == 4){
          include "nav_op.php";  
        }
        else{include "nav_priv.php"; }
       ?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Archivo subido con éxito<small></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Subir archivo
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
               
                <?php 

                    ver_archivos();
                    echo "<pre>";
                    print_r($_POST);
                    echo "<pre>";
                     /* print "<pre>"; print_r($_POST); print_r($_SESSION); print "</pre>";*/    
                ?>
            </div>  <!--    fin col-lg-12   -->
            <!-- /.container-fluid -->
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