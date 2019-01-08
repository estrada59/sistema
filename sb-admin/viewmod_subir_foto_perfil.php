<?php

function subir_archivos(){
  //echo '<pre>';
  //print_r($_FILES);
  //echo '</pre>';

  //comprobamos si ha ocurrido un error.
  if ($_FILES["imagen"]["error"] > 0){
    echo "ha ocurrido un error, vuelva a intentarlo.";
  } 
  else {
    //ahora vamos a verificar si el tipo de archivo es un tipo de imagen permitido.
    //y que el tamano del archivo no exceda los 100kb
    $allowedExts = array("image/jpg", "image/jpeg","image/png"); 
    //$permitidos = array("image/jpg", "image/jpeg", "image/gif", "image/png", "application/pdf");
    $limite_kb = 8000;

    if (in_array($_FILES['imagen']['type'], $allowedExts) && $_FILES['imagen']['size'] <= $limite_kb * 1024){
      //esta es la ruta donde copiaremos la imagen
      //recuerden que deben crear un directorio con este mismo nombre
      //en el mismo lugar donde se encuentra el archivo subir.php
      
      //$ruta = "images/pac/" . $_FILES['imagen']['name'];
      $nombre = $_POST['nombre'].'.jpg';
      $iduser = $_POST['iduser'];
      
      $ruta = "images/avatar/".$nombre;
      //echo $ruta;

        //aqui movemos el archivo desde la ruta temporal a nuestra ruta
        //usamos la variable $resultado para almacenar el resultado del proceso de mover el archivo
        //almacenara true o false
        $resultado = @move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta);
        if ($resultado){

          include_once "include/mysql.php";
          $mysql = new mysql();
          $link = $mysql->connect(); 

          $sql = $mysql->query($link," UPDATE users
                                        SET ruta_foto = '".$ruta."'
                                        where iduser= $iduser  ");
          $mysql->close();

          //**********************************************************

          // Fichero y nuevo tamaño
          $nombre_fichero = $ruta;
          $porcentaje = 0.5;

          // Tipo de contenido
          //header('Content-Type: image/jpeg');

          // Obtener los nuevos tamaños
          list($ancho, $alto) = getimagesize($nombre_fichero);
          //$nuevo_ancho = $ancho * $porcentaje;
          //$nuevo_alto = $alto * $porcentaje;
          $nuevo_ancho = 67;
          $nuevo_alto = 73;


          // Cargar
          $thumb = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
          $origen = imagecreatefromjpeg($nombre_fichero);

          // Cambiar el tamaño
          imagecopyresized($thumb, $origen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);

          // Imprimir
          //imagejpeg($thumb);
          imagejpeg($thumb, $ruta);
          imagedestroy($thumb);
          //**********************************************************

          echo' <div class="alert alert-success" role="alert">Haz actualizado tu foto de perfil</div>';
        } 
        else {
          echo' <div class="alert alert-danger" role="alert">Ha ocurrido un error al guardar su archivo, intentelo de nuevo</div>';
        }
    }
    else {
      echo' <div class="alert alert-danger" role="alert"><strong>Archivo no permitido o execede el tamaño máximo de 8 mb.
            Solo se permiten archivos en formato jpg</strong></div>';
    }
  }
}
?>

<!DOCTYPE html>
<?php $_POST["pagina"]="form"; ?>
<html lang="en">

<head>
    <?php   include "header.php";
        session_start(); 
      include "../include/sesion.php";
      comprueba_url();
      if(!isset($_POST['iduser'])){
        header("Location: index.php");
      }

  ?>
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
          if($_SESSION['nivel_acceso'] == 4){
            include "nav_op.php"; }
       ?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            <small><?php 
                            subir_archivos();
                            ?></small>
                        </h1>
                        <!--<ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Subir archivo
                            </li>
                        </ol>-->
                    </div>
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-3">
                        <form role="form" id="ver_lista" method="post" action="controlador_perfil_usuario.php" accept-charset="UTF-8">
                        </form>
                        <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            Atrás
                        </button>  
                    </div>
                </div>
                <?php 
                    //echo "<pre>";
                    //print_r($_POST);
                    //echo "<pre>";
                     /* print "<pre>"; print_r($_POST); print_r($_SESSION); print "</pre>";*/    
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