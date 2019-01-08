<?php

function subir_archivos(){
  //comprobamos si ha ocurrido un error.
  if ($_FILES["imagen"]["error"] > 0){
    echo "ha ocurrido un error";
  } 
  else {
    //ahora vamos a verificar si el tipo de archivo es un tipo de imagen permitido.
    //y que el tamano del archivo no exceda los 100kb
    $allowedExts = array("application/pdf","image/jpg", "image/jpeg"); 
    //$permitidos = array("image/jpg", "image/jpeg", "image/gif", "image/png", "application/pdf");
    $limite_kb = 8000;

    if (in_array($_FILES['imagen']['type'], $allowedExts) && $_FILES['imagen']['size'] <= $limite_kb * 1024){
      //esta es la ruta donde copiaremos la imagen
      //recuerden que deben crear un directorio con este mismo nombre
      //en el mismo lugar donde se encuentra el archivo subir.php
      
      //$ruta = "images/pac/" . $_FILES['imagen']['name'];
      $nombre = $_POST['nombre'];
      $idpaciente = $_POST['idpaciente'];
      date_default_timezone_set('America/Mexico_City');
      $fecha = date('Y_m_d');
      $hora = date('g_i');
      $ruta = "images/pac/" .$fecha.'_'.$hora.'_'.$idpaciente.'.pdf';
      //echo $ruta;
      //comprovamos si este archivo existe para no volverlo a copiar.
      //pero si quieren pueden obviar esto si no es necesario.
      //o pueden darle otro nombre para que no sobreescriba el actual.
  
      if (!file_exists($ruta)){
        //aqui movemos el archivo desde la ruta temporal a nuestra ruta
        //usamos la variable $resultado para almacenar el resultado del proceso de mover el archivo
        //almacenara true o false
        $resultado = @move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta);
        if ($resultado){

          include_once "include/mysql.php";
          $mysql = new mysql();
          $link = $mysql->connect(); 

          $sql = $mysql->query($link," INSERT INTO resultados (idresultado, idpaciente, ruta) 
                                              VALUES ('', $idpaciente,'$ruta') ");
          $mysql->close();

          echo "el archivo ha sido movido exitosamente";
        } 
        else {
          echo "ocurrio un error al mover el archivo.";
        }
      }
      else {
        echo $_FILES['imagen']['name'] . ", este archivo existe";
      }
    }
    else {
      echo "archivo no permitido, es tipo de archivo prohibido o excede el tamano de $limite_kb Kilobytes";
    }
  }
}
?>

<!DOCTYPE html>
<?php $_POST["pagina"]="ver_pacientes_del_dia"; ?>
<html lang="en">

<head>
    <?php   include "header.php";
        session_start(); 
      include "../include/sesion.php";
      comprueba_url();
      if(!isset($_POST['idpaciente'])){
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
                            Resultados<small> Muestra resultados de la subida de archivos si fué exitosa o no.</small>
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
                    subir_archivos();
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