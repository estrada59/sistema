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
                header('Location: controlador_graficar_por_estudios_por_año.php');
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
                                $fecha = $_POST['fecha_estudios'];
        
                                $fecha_fin = ultimo_dia_semestre($fecha);
                                $fecha_ini = primer_dia_semestre($fecha); 

                                $fecha_ini = date("d-m-Y",strtotime($fecha_ini));
                                $fecha_fin = date("d-m-Y",strtotime($fecha_fin));                                    
                                
                                $mes =obtener_mes($fecha);
                                $mes = pasarMayusculas($mes);
                                echo 'Graficar estudios realizados en el año';
                                ?>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                                Lista de estudios del semestre <?php echo '<small> del '.$fecha_ini.' al '.$fecha_fin.'</small>'; ?>
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
                
                        <form role="form" id="imprimir_reporte" method="post" action="view_imprimir_reporte_semestral.php" target="_blank">
                            <input type="hidden" form="imprimir_reporte" name="fecha" value="<?php echo $fecha; ?>"/>
                            <!--<input type="hidden" form="imprimir_reporte" name="institucion" value="'.$institucion.'"/>-->
                        </form>

                        <button type="submit" class="btn btn-primary btn-lg btn-block"  aria-label="Left Align" form="imprimir_reporte">
                                <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                                IMPRIMIR REPORTE
                        </button>
                        <br>
                        
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <!--        1er columnoa de captura  LUNES       -->
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title">Lista de estudios realizados y ATENDIDOS SEMESTRE: <?php echo ' del '.$fecha_ini.' al '.$fecha_fin; ?></h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                            
                            <!-- CHART Aquí se invoca   -->
                            <!--<div id="piechart_3d_por_atender" style="width: 900px; height: 900px;"></div>-->
                            <div  id="bar_2d_atendido" style="width: 1000px; height: 500px;"></div>
                            <!-- Fin CHART   -->

                                <?php
                                    include_once 'include/funciones_consultas.php';
                    
                                    $fecha = $_POST['fecha_estudios'];
        
                                    $fecha_fin = ultimo_dia_semestre($fecha);
                                    $fecha_ini = primer_dia_semestre($fecha); 
                                    $datos = array();
                                    $estatus = 'ATENDIDO';
                                    
                                    $datos_atendido = cantidad_de_estudios($fecha_ini, $fecha_fin,$_POST['pagina'],$estatus);
                                    
                                    /*echo '<pre>';
                                    print_r($datos_atendido);
                                    echo '</pre>';*/
                                    $num = count($datos_atendido);
               
                

                                echo '<div class=" table-responsive">
                                        <table class="table table-bordered table-hover table-striped" >
                                            <thead>
                                                <tr>
                                                    <th data-field="numero" width="15">Nombre de estudio</th>
                                                    <th data-field="estudio" width="70">Cantidad</th>';
                                        
                                        echo '  </tr>
                                            </thead>
                                            <tbody>';
                                                $total_de_estudios=0;

                                                for($i=0; $i < $num; $i++){
                                                    echo '<tr>';
                                                    echo        '<td>'.$datos_atendido[$i]['estudio'].'</td>';
                                                    echo        '<td>'.$datos_atendido[$i]['cantidad'].'</td>';
                                                    echo '</tr>';
                                                    $total_de_estudios +=$datos_atendido[$i]['cantidad'];
                                                }
                                                    echo '<tr>';
                                                    echo '      <td> Total de estudios atendidos (sumatoria): </td>';
                                                    echo '      <td> '.$total_de_estudios.' </td>';
                                                    echo '</tr>';

                                        echo '</tbody>
                                        </table>
                                    </div>';
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
                                <h3 class="panel-title">Lista de estudios POR ATENDER <?php echo ' del '.$fecha_ini.' al '.$fecha_fin; ?></h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                            
                            <!-- CHART Aquí se invoca   -->
                            <div id="bar_2d_por_atender" style="width: 900px; height: 500px;"></div>
                            <!-- Fin CHART   -->

                                <?php
                                    include_once 'include/funciones_consultas.php';
                    
                                    $fecha = $_POST['fecha_estudios'];

                                    $fecha_fin = ultimo_dia_semestre($fecha);
                                    $fecha_ini = primer_dia_semestre($fecha);  
                                    $datos = array();
                                    $estatus = 'POR ATENDER';
                                    
                                    $datos_por_atender = cantidad_de_estudios($fecha_ini, $fecha_fin,$_POST['pagina'],$estatus);
                                    
                                    //echo '<pre>';
                                    //print_r($datos_atendido);
                                    //echo '</pre>';
                                    $num = count($datos_por_atender);
               
                

                                echo '<div class=" table-responsive">
                                        <table class="table table-bordered table-hover table-striped" >
                                            <thead>
                                                <tr>
                                                    <th data-field="numero" width="15">Nombre de estudio</th>
                                                    <th data-field="estudio" width="70">Cantidad</th>';
                                        
                                        echo '  </tr>
                                            </thead>
                                            <tbody>';
                                                $total_de_estudios_por_atender=0;
                                                
                                                for($i=0; $i < $num; $i++){
                                                    echo '<tr>';
                                                    echo        '<td>'.$datos_por_atender[$i]['estudio'].'</td>';
                                                    echo        '<td>'.$datos_por_atender[$i]['cantidad'].'</td>';
                                                    echo '</tr>';
                                                    $total_de_estudios_por_atender +=$datos_por_atender[$i]['cantidad'];
                                                }
                                                    echo '<tr>';
                                                    echo '      <td> Total de estudios por atender (sumatoria): </td>';
                                                    echo '      <td> '.$total_de_estudios_por_atender.' </td>';
                                                    echo '</tr>';


                                        echo '</tbody>
                                        </table>
                                    </div>';
               
                                    
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
                                <h3 class="panel-title">Lista de estudios CANCELADOS <?php echo ' del '.$fecha_ini.' al '.$fecha_fin; ?></h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                            
                            <!-- CHART Aquí se invoca   -->
                            <div id="bar_2d_cancelado" style="width: 900px; height: 500px;"></div>
                            <!-- Fin CHART   -->

                                <?php
                                    include_once 'include/funciones_consultas.php';
                    
                                    $fecha = $_POST['fecha_estudios'];
        
                                    $fecha_fin = ultimo_dia_semestre($fecha);
                                    $fecha_ini = primer_dia_semestre($fecha);  
                                    $datos = array();
                                    $estatus = 'CANCELADO';
                                   
                                    $datos_cancelado = cantidad_de_estudios($fecha_ini, $fecha_fin,$_POST['pagina'],$estatus);
                                   
                                    //echo '<pre>';
                                    //print_r($datos_atendido);
                                    //echo '</pre>';
                                    $num = count($datos_cancelado);
               
                                echo '<div class=" table-responsive">
                                        <table class="table table-bordered table-hover table-striped" >
                                            <thead>
                                                <tr>
                                                    <th data-field="numero" width="15">Nombre de estudio</th>
                                                    <th data-field="estudio" width="70">Cantidad</th>';
                                        
                                        echo '  </tr>
                                            </thead>
                                            <tbody>';
                                                $total_de_estudios_cancelados=0;

                                                for($i=0; $i < $num; $i++){
                                                    echo '<tr>';
                                                    echo        '<td>'.$datos_cancelado[$i]['estudio'].'</td>';
                                                    echo        '<td>'.$datos_cancelado[$i]['cantidad'].'</td>';
                                                    echo '</tr>';
                                                    $total_de_estudios_cancelados +=$datos_cancelado[$i]['cantidad'];
                                                }
                                                    echo '<tr>';
                                                    echo '      <td> Total de estudios cancelados (sumatoria): </td>';
                                                    echo '      <td> '.$total_de_estudios_cancelados.' </td>';
                                                    echo '</tr>';
                                        echo '</tbody>
                                        </table>
                                    </div>';
               
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

    
    <!-- GRÁFICAS DE BARRAS-->
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
            <?php 
                $num = count($datos_atendido);
                $cad_encabezado ="['ESTUDIOS REALIZADOS ANUALMENTE', ";
                for($i=0; $i < $num; $i++)
                {
                    if($i == $num-1){
                        $cad_encabezado = $cad_encabezado." '".$datos_atendido[$i]["estudio"]."', { role: 'annotation' } ],";
                    }
                    else{
                        $cad_encabezado = $cad_encabezado." '".$datos_atendido[$i]["estudio"]."',";
                    }
                }
                echo $cad_encabezado;

                $cad_datos ="['ESTUDIOS REALIZADOS TODAS LAS INSTITUCIONES INCLUYENDO PARTICULARES', ";

                for($i=0; $i < $num; $i++)
                {
                    if($i == $num-1){
                        $cad_datos = $cad_datos." ".$datos_atendido[$i]["cantidad"].", '']";
                    }
                    else{
                        $cad_datos = $cad_datos." ".$datos_atendido[$i]["cantidad"].",";
                    }
                }
                echo $cad_datos;
            ?>
        ]);

        var options = {
            legend: { position: 'top', maxLines: 200 },
            bar: { groupWidth: '80%' },
            isStacked: false,
            legend: { position: "none" },       
        };

        var chart = new google.charts.Bar(document.getElementById('bar_2d_atendido'));
        chart.draw(data, google.charts.Bar.convertOptions(options));

      }
    </script>

    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
            <?php 
                $num = count($datos_por_atender);
                $cad_encabezado ="['ESTUDIOS REALIZADOS ANUALMENTE', ";
                for($i=0; $i < $num; $i++)
                {
                    if($i == $num-1){
                        $cad_encabezado = $cad_encabezado." '".$datos_por_atender[$i]["estudio"]."', { role: 'annotation' } ],";
                    }
                    else{
                        $cad_encabezado = $cad_encabezado." '".$datos_por_atender[$i]["estudio"]."',";
                    }
                }
                echo $cad_encabezado;

                $cad_datos ="['ESTUDIOS POR ATENDER TODAS LAS INSTITUCIONES INCLUYENDO PARTICULARES', ";

                for($i=0; $i < $num; $i++)
                {
                    if($i == $num-1){
                        $cad_datos = $cad_datos." ".$datos_por_atender[$i]["cantidad"].", '']";
                    }
                    else{
                        $cad_datos = $cad_datos." ".$datos_por_atender[$i]["cantidad"].",";
                    }
                }
                echo $cad_datos;
            ?>
        ]);

        var options = {
            legend: { position: 'top', maxLines: 200 },
            bar: { groupWidth: '80%' },
            isStacked: false,
            legend: { position: "none" },       
        };

        var chart = new google.charts.Bar(document.getElementById('bar_2d_por_atender'));
        chart.draw(data, google.charts.Bar.convertOptions(options));

      }
    </script>


    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
            <?php 
                $num = count($datos_cancelado);
                $cad_encabezado ="['ESTUDIOS REALIZADOS ANUALMENTE', ";
                for($i=0; $i < $num; $i++)
                {
                    if($i == $num-1){
                        $cad_encabezado = $cad_encabezado." '".$datos_cancelado[$i]["estudio"]."', { role: 'annotation' } ],";
                    }
                    else{
                        $cad_encabezado = $cad_encabezado." '".$datos_cancelado[$i]["estudio"]."',";
                    }
                }
                echo $cad_encabezado;

                $cad_datos ="['ESTUDIOS CANCELADOS TODAS LAS INSTITUCIONES INCLUYENDO PARTICULARES', ";

                for($i=0; $i < $num; $i++)
                {
                    if($i == $num-1){
                        $cad_datos = $cad_datos." ".$datos_cancelado[$i]["cantidad"].", '']";
                    }
                    else{
                        $cad_datos = $cad_datos." ".$datos_cancelado[$i]["cantidad"].",";
                    }
                }
                echo $cad_datos;
            ?>
        ]);

        var options = {
            legend: { position: 'top', maxLines: 200 },
            bar: { groupWidth: '80%' },
            isStacked: false,
            legend: { position: "none" },       
        };

        var chart = new google.charts.Bar(document.getElementById('bar_2d_cancelado'));
        chart.draw(data, google.charts.Bar.convertOptions(options));

      }
    </script>
</body>
</html>
