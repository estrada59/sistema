<!DOCTYPE html>
<?php $_POST["pagina"]="grafica"; ?>
<html lang="es">

<head>
    
   <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

    <?php   include "header.php";
            session_start(); 
            include "../include/sesion.php";
            comprueba_url();

            /* if($_SESSION['nivel_acceso'] >= 4){
                header("Location: index.php");
                exit();
            }*/

            if(!isset($_POST['fecha_estudios'])){
                header('Location: controlador_graficar_pacientes_por_año.php');
            }
    ?>
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
                            
                                <?php   
                                include_once 'include/funciones_consultas.php';
                                include_once 'include/mysql.php';
                                
                                $fecha = $_POST['fecha_estudios'];
        
                                $fecha_fin = last_year_day($fecha);
                                $fecha_ini = first_year_day($fecha); 

                                $fecha_ini = date("d-m-Y",strtotime($fecha_ini));
                                $fecha_fin = date("d-m-Y",strtotime($fecha_fin));                                    
                                
                                $mes =obtener_mes($fecha);
                                $mes = pasarMayusculas($mes);
                                echo 'Graficar pacientes citados en el año';
                                ?>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                                Lista de pacientes citados <?php echo '<small> del '.$fecha_ini.' al '.$fecha_fin.'</small>'; ?>
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
               
                <?php 
                //print_r($semana);
                 /* print "<pre>"; print_r($_POST); print_r($_SESSION); print "</pre>";*/
                ?>

                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <!--        1er columnoa de captura  LUNES       -->
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title">Lista de pacientes citados ATENDIDOS <?php echo ' del '.$fecha_ini.' al '.$fecha_fin; ?></h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                            
                            <!-- CHART Aquí se invoca   -->
                            <div id="piechart_3d_atendido" style="width: 900px; height: 500px;"></div>
                            <!-- Fin CHART   -->

                                <?php
                                    include_once 'include/funciones_consultas.php';
                    
                                    $fecha = $_POST['fecha_estudios'];
        
                                    $fecha_fin = last_year_day($fecha);
                                    $fecha_ini = first_year_day($fecha); 
                                    $datos = array();
                                    $estatus = 'ATENDIDO';
                                    $datos_atendido = cantidad_de_pacientes_por_mes($fecha_ini, $fecha_fin,$_POST['pagina'],$estatus );

                                    //echo '<pre>';
                                    //print_r($datos);
                                    //echo '</pre>';
                                ?>                
                            </div><!-- /. fin panel body -->
                            <!--<div class="panel-footer">
                            </div>-->
                        </div><!-- /. fin panel primary -->

                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <?php   
                                        $fecha_ini = date("d-m-Y",strtotime($fecha_ini));
                                        $fecha_fin = date("d-m-Y",strtotime($fecha_fin)); 
                                ?>
                                <h3 class="panel-title">Lista de pacientes citados POR ATENDER <?php echo ' del '.$fecha_ini.' al '.$fecha_fin; ?></h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                            
                            <!-- CHART Aquí se invoca   -->
                            <div id="piechart_3d_por_atender" style="width: 900px; height: 500px;"></div>
                            <!-- Fin CHART   -->

                                <?php
                                    include_once 'include/funciones_consultas.php';
                    
                                    $fecha = $_POST['fecha_estudios'];
        
                                    $fecha_fin = last_year_day($fecha);
                                    $fecha_ini = first_year_day($fecha); 
                                    $datos = array();
                                    $estatus = 'POR ATENDER';
                                    $datos_por_atender = cantidad_de_pacientes_por_mes($fecha_ini, $fecha_fin,$_POST['pagina'], $estatus);

                                    //echo '<pre>';
                                    //print_r($datos);
                                    //echo '</pre>';
                                ?>                
                            </div><!-- /. fin panel body -->
                            <!--<div class="panel-footer">
                            </div>-->
                        </div><!-- /. fin panel primary -->

                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <?php   
                                        $fecha_ini = date("d-m-Y",strtotime($fecha_ini));
                                        $fecha_fin = date("d-m-Y",strtotime($fecha_fin)); 
                                ?>
                                <h3 class="panel-title">Lista de pacientes citados CANCELADOS <?php echo ' del '.$fecha_ini.' al '.$fecha_fin; ?></h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                            
                            <!-- CHART Aquí se invoca   -->
                            <div id="piechart_3d_cancelado" style="width: 900px; height: 500px;"></div>
                            <!-- Fin CHART   -->

                                <?php
                                    include_once 'include/funciones_consultas.php';
                    
                                    $fecha = $_POST['fecha_estudios'];
        
                                    $fecha_fin = last_year_day($fecha);
                                    $fecha_ini = first_year_day($fecha); 
                                    $datos = array();
                                    $estatus = 'CANCELADO';
                                    $datos_cancelado = cantidad_de_pacientes_por_mes($fecha_ini, $fecha_fin,$_POST['pagina'], $estatus);

                                    //echo '<pre>';
                                    //print_r($datos);
                                    //echo '</pre>';
                                ?>                
                            </div><!-- /. fin panel body -->
                            <!--<div class="panel-footer">
                            </div>-->
                        </div><!-- /. fin panel primary -->
            </div>  <!--    fin col-lg-12   -->
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <!-- Esto es para subir archivo-->
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-fancyfile.min.js"></script>
    
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

    



    <script type="text/javascript">

                              
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data_por_atender = google.visualization.arrayToDataTable([
            <?php
                echo '["institucion", "cantidad de pacientes"],';
                $num = count($datos_por_atender);

                for($i=0; $i < $num; $i++)
                {
                    if($i == $num-1){
                        echo '["'.$datos_por_atender[$i]['institucion'].'",'.$datos_por_atender[$i]['cantidad'].']';    
                    }else{
                        echo '["'.$datos_por_atender[$i]['institucion'].'",'.$datos_por_atender[$i]['cantidad'].'],';    
                    }   
                }
                echo "]);";
            ?>

        var options_por_atender = {
          title: 'PORCENTAJES DE PACIENTES CITADOS POR ATENDER',
          is3D: true,
        };

        var chart_por_atender = new google.visualization.PieChart(document.getElementById('piechart_3d_por_atender'));
        chart_por_atender.draw(data_por_atender, options_por_atender);
      }
    </script>

    <script type="text/javascript">

                              
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data_cancelado = google.visualization.arrayToDataTable([
            <?php
                echo '["institucion", "cantidad de pacientes"],';
                $num = count($datos_cancelado);

                for($i=0; $i < $num; $i++)
                {
                    if($i == $num-1){
                        echo '["'.$datos_cancelado[$i]['institucion'].'",'.$datos_cancelado[$i]['cantidad'].']';    
                    }else{
                        echo '["'.$datos_cancelado[$i]['institucion'].'",'.$datos_cancelado[$i]['cantidad'].'],';    
                    }   
                }
                echo "]);";
            ?>

        var options_cancelado = {
          title: 'PORCENTAJES DE PACIENTES CANCELADOS',
          is3D: true,
        };

        var chart_cancelado = new google.visualization.PieChart(document.getElementById('piechart_3d_cancelado'));
        chart_cancelado.draw(data_cancelado, options_cancelado);
      }
    </script>


    <script type="text/javascript">

                              
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data_atendido = google.visualization.arrayToDataTable([
            <?php
                echo '["institucion", "cantidad de pacientes"],';
                $num = count($datos_atendido);

                for($i=0; $i < $num; $i++)
                {
                    if($i == $num-1){
                        echo '["'.$datos_atendido[$i]['institucion'].'",'.$datos_atendido[$i]['cantidad'].']';    
                    }else{
                        echo '["'.$datos_atendido[$i]['institucion'].'",'.$datos_atendido[$i]['cantidad'].'],';    
                    }   
                }
                echo "]);";
            ?>

        var options_atendido = {
          title: 'PORCENTAJES DE PACIENTES ATENDIDOS',
          is3D: true,
        };

        var chart_atendido = new google.visualization.PieChart(document.getElementById('piechart_3d_atendido'));
        chart_atendido.draw(data_atendido, options_atendido);
      }
    </script>

</body>
</html>
