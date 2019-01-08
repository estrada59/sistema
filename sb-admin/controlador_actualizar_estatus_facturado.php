<!DOCTYPE html>
<?php $_POST["pagina"]="facturas"; 
$var = $_POST["pagina_destino"];

?>
<html lang="en">

<head>
    <?php   include "header.php"; 
            session_start(); 
            include "../include/sesion.php";
            comprueba_url();

            if(!isset($_POST["idpaciente"]) && !isset($_POST['estatus'])){
                    header('Location: index.php');
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
                include "nav.php";  
            }
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
                            Modificar estatus de factura del cliente <small></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> 
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

                <div class = "row">
                    <div class="col-lg-3">
                        <?php
                            //$var = $_POST["pagina_destino"];
                            $fecha = $_POST['fecha'];
                            date_default_timezone_set('America/Mexico_City'); 
                            $fecha = date('Y-m-d', strtotime($fecha));

                            switch ($var) {
                                
                                case 'facturas':
                                    if($_SESSION['nivel_acceso']<=3){
                                        echo'
                                     <form role="form" id="ver_lista" method="post" action="viewmod_ver_pacientes_por_mes_factura.php" accept-charset="UTF-8">
                                        <input type="hidden" form="ver_lista" name="fecha_estudios" value="'.$fecha.'"/>
                                    </form>
                                    <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        Atrás
                                    </button> ';
                                    
                                    }
                                     break;
                                 
                                default:
                                    $fecha = $_POST['fecha'];
                                    echo'
                                     <form role="form" id="ver_lista" method="post" action="viewmod_ver_pacientes_por_mes_factura.php" accept-charset="UTF-8">
                                        <input type="hidden" form="ver_lista" name="fecha_estudios" value="'.$fecha.'"/> 
                                    </form>
                                    <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        Atrás
                                    </button>';
                                    break;
                             }
                        ?>
                                                    
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-lg-4">
                        <div class="panel panel-primary">
                            
                            <div class="panel-heading"></div>
                            
                            <div class="panel-body">
                                <p><strong>Nombre del paciente: </strong><?php 
                                    echo  $_POST['nombre']; ?>
                                </p>
                                <p><strong>Estudio: </strong><?php 
                                    echo  $_POST['estudio']; ?>
                                </p>
                                <p><strong>Fecha de estudio: </strong><?php
                                    include_once "include/funciones_consultas.php";
                                    $fecha = fecha_letras($_POST['fecha']);
                                    echo $fecha;  ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                    <?php 
                        if($_SESSION['nivel_acceso']== 3 || $_SESSION['nivel_acceso']== 2){
                                
                                echo'
                        <div class="panel panel-primary">
                            
                            <div class="panel-heading"></div>
                            
                            <div class="panel-body">
                                <form role="form" id="requiere_factura" method="post" action="viewmod_editar_estatus_factura.php" accept-charset="UTF-8">
                                
                                    <input type="hidden" form="requiere_factura" name="fecha_estudios" value="'.$fecha.'"/>
                                    <input type="hidden" form="requiere_factura" name="estatus" value="ATENDIDO"/>
                                    <input type="hidden" form="requiere_factura" name="idpaciente" value="'.$_POST["idpaciente"].'"/>
                                    <input type="hidden" form="requiere_factura" name="nombre" value="'.$_POST["nombre"].'"/> 
                                    <input type="hidden" form="requiere_factura" name="estudio" value="'.$_POST["estudio"].'"/>
                                    <input type="hidden" form="requiere_factura" name="fecha" value="'.$_POST["fecha"].'"/>
                                    <input type="hidden" form="requiere_factura" name="pag_retorno" value="'.$_POST['pagina_destino'].'"/>

                                <div class="form-group">
                                     <label>¿YA ESTA FACTURADO EL PAGO DEL PACIENTE?</label>
                                        <select class="form-control combobox" form="requiere_factura" name="factura" id="factura" required>
                            ';
                                
                                if( isset($_POST['estatus_factura'])){
                                    if($_POST['estatus_factura'] == 'SI'){
                                        echo '  
                                            <option>SI</option>
                                            <option>NO</option>
                                        </select>';
                                    }
                                    if($_POST['estatus_factura'] == 'NO'){
                                        echo '  
                                            <option>NO</option>
                                            <option>SI</option>
                                        </select>';
                                    }
                                    
                                }
                                        
                                            
                            echo'            
                                </div>    
                               </form>                    
                            </div>    
                        </div>';
                        }

                        if($_SESSION['nivel_acceso'] <= 1){ //solo se muestra a la lic
                                echo'<form role="form" id="requiere_factura" method="post"  accept-charset="UTF-8">
                                        <label>Números de facturas:</label>';
                                $idpaciente = $_POST["idpaciente"];
                                
                                include_once "include/mysql.php";
                                $mysql = new mysql();
                                $link = $mysql->connect();
            
                                $sql = $mysql->query($link, "SELECT numero_facturas 
                                                                FROM facturas 
                                                                WHERE idpaciente= $idpaciente");
                                
                                $num = $mysql->f_num($sql);
                                
                                if($num){

                                    $obj = $mysql->f_obj($sql);
                                    $observaciones = $obj->numero_facturas; 
                                    echo'<textarea  type="text" class="form-control" rows="5" form="requiere_factura" name="numero_facturas" id="numero_facturas" placeholder="Escribe acá los numeros de facturas" required disabled>'.$observaciones.'</textarea>';
                                    $mysql->close();
                                }
                                else
                                {
                                    echo'<textarea type="text" class="form-control" rows="5" form="requiere_factura" name="numero_facturas" id="numero_facturas"  placeholder="Escribe acá los numeros de facturas" required disabled></textarea>   ';
                                }

                                echo'</form>';
                            }
                    ?>
                    </div>

                    <div class="col-lg-4">
                        <?php
                            if($_SESSION['nivel_acceso']== 3 || $_SESSION['nivel_acceso']== 2){
                                echo'<form role="form" id="requiere_factura" method="post"  accept-charset="UTF-8">
                                        <label>Números de facturas:</label>';
                                $idpaciente = $_POST["idpaciente"];
                                
                                include_once "include/mysql.php";
                                $mysql = new mysql();
                                $link = $mysql->connect();
            
                                $sql = $mysql->query($link, "SELECT numero_facturas 
                                                                FROM facturas 
                                                                WHERE idpaciente= $idpaciente");
                                
                                $num = $mysql->f_num($sql);
                                
                                if($num){

                                    $obj = $mysql->f_obj($sql);
                                    $observaciones = $obj->numero_facturas; 
                                    echo'<textarea  type="text" class="form-control" rows="5" form="requiere_factura" name="numero_facturas" id="numero_facturas" placeholder="Escribe acá los numeros de facturas" required>'.$observaciones.'</textarea>';
                                    $mysql->close();
                                }
                                else
                                {
                                    echo'<textarea type="text" class="form-control" rows="5" form="requiere_factura" name="numero_facturas" id="numero_facturas"  placeholder="Escribe acá los numeros de facturas" required></textarea>   ';
                                }

                                echo'</form>';
                            }   
                               
                        ?>
                        
                    </div>
                </div>
                <br>
                <?php

                $fecha = $_POST["fecha"];
                if ($_SESSION['nivel_acceso'] == 3 || $_SESSION['nivel_acceso']== 2){
                echo '
                <div class = "row">
                    
                    <div class="col-lg-4">
                                                   
                    </div>

                    <div class="col-lg-4">
                        <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="requiere_factura">
                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                Aceptar
                        </button>                            
                    </div>

                    <div class="col-lg-4">
                                                   
                    </div>
                </div>';
                }
                ?>
             
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
