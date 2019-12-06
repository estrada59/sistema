<!DOCTYPE html>
<?php $_POST["pagina"]="inicio"; ?>
<html lang="es">

<head>

    <!--Load the AJAX API-->

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

    <?php 	include "header.php";
    		session_start(); 
			include_once "../include/sesion.php";
            include_once "../include/mysql.php";
			comprueba_url();
            time_session();       
     ?>
     
     <style>
        img {
            max-width: 100%;
            height: auto;
            }

        .chart {
        width: 100%; 
        
        min-height: 450px;
        }
        .row {
        margin:0 !important;
        }
        
     </style>

</head>

<body>
    <div id="wrapper">

        <!-- Navigation -->
       <?php include_once "include/nav_session.php" ?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Inicio<small></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Inicio
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

                <div class="row">
                    
                </div>
                <?php 
                    // print "<pre>";
                    // print_r($_POST);
                    // print_r($_SESSION);
                    // print "</pre>";
            
                ?>
                <?php
                    if($_SESSION['nivel_acceso'] == 1)
                    {

                        echo'
                        <div class="row">
                            <div class="col-md-12">
                                <div id="chart_div_ant"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div id="chart_div_act"></div>
                            </div>
                        </div>';
                        
                        include_once "include/funciones_consultas.php";

                        $fecha_act = date('Y-m-d');
                        $fecha_ant = date("Y-m-d",strtotime("-1 year"));

                        $fecha_año_act = explode("-", $fecha_act);
                        $fecha_año_ant = explode("-", $fecha_ant);
                            // print_r($fecha_año_ant);

                        $year_act = $fecha_año_act[0];  //AÑO ACTUAL
                        $year_ant = $fecha_año_ant[0]; //AÑO ANTERIOR
                        
                        $datos_ventas_totales_act = ventas_por_instituciones_meses($fecha_act);
                        $datos_ventas_totales_ant = ventas_por_instituciones_meses($fecha_ant);

                        // echo'<pre>';
                        //     print_r($datos_ventas_totales_ant);
                        // echo'</pre>';

                        echo'

                        <br>
                        <br>

                        <div class="row">
                            <div class="col-md-5">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        Contratos instituciones
                                    </div>
                                    <div class="panel-body">';
                                        
                                        monto_ejecutado_por_instituciones_resumen();
                        echo'       </div>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="panel panel-success">

                                    <div class="panel-heading">
                                        Ventas particulares '.$year_act.'
                                    </div>

                                    <div class="panel-body">';
                                    echo'
                                            <!-- CHART Aquí se invoca   -->
                
                                                <div id="ventas_particulares" class="chart"></div>

                                                <div id="ventas_instituciones" class="chart"></div>
                                                
                                            <!-- Fin CHART   -->';
                                    
                                echo'</div>  <!-- fin body -->';
                            echo'</div>  <!-- fin panel -->';
                        echo'</div>
                        </div>';

                        /*     DATOS PARA <SCRIPT>  PRIMER SEMESTRE     */

                        date_default_timezone_set('America/Mexico_City'); 
                        $fecha_act = date('Y-m-d');

                        $fecha_fin = ultimo_dia_semestre($fecha_act);
                        $fecha_ini = primer_dia_semestre($fecha_act); 

                        $estatus = 'ATENDIDO';
                        $fecha_ini = date('Y-m-d',strtotime($fecha_ini));
                        $fecha_fin = date('Y-m-d',strtotime($fecha_fin));

                        $datos_atendido_institucion_1 = cantidad_de_pacientes_por_mes($fecha_ini, $fecha_fin,$_POST['pagina'],$estatus );

                        /*     DATOS PARA <SCRIPT>  SEGUNDO SEMESTRE     */

                        $fecha_fin = ultimo_dia_segundo_semestre($fecha_act);
                        $fecha_ini = primer_dia_segundo_semestre($fecha_act); 

                        $estatus = 'ATENDIDO';
                        $fecha_ini = date('Y-m-d',strtotime($fecha_ini));
                        $fecha_fin = date('Y-m-d',strtotime($fecha_fin));

                        $datos_atendido_institucion_2 = cantidad_de_pacientes_por_mes($fecha_ini, $fecha_fin,$_POST['pagina'],$estatus );
                        
                        // GENERA HTML5, GRAFICA Y TABLAS
                        graficas_semestrales_inicio($_POST['pagina']);
                    }
                    else{
                        // echo'<a class="btn btn-primary" href="confirmacion_cita.php" role="button">Enviar SMS</a>';
                    }
                    
                    
                ?>
                
                <div class="row">
                        
                </div>

                <script type="text/javascript">
                    $(document).ready(function() {

                        $('#myClass').DataTable( {
                            language: {
                                url: 'js/dataTables_es.lang'
                            },
                            "paging":   false,
                            "ordering": false,
                            "info":     false
                            });

                        $('#myClass2').DataTable( {
                            language: {
                                url: 'js/dataTables_es.lang'
                            },
                            "paging":   false,
                            "ordering": false,
                            "info":     false
                        });

                        $('#myClass3').DataTable( {
                            language: {
                                url: 'js/dataTables_es.lang'
                            },
                            "paging":   false,
                            "ordering": true,
                            "info":     false
                        });
                    } );
                </script>
                
                <div class="row">
                    <div class="col-md-12" align="center">
                        <img src="images/logo_numedics.svg" width="600" height="250" id="bg"  alt="NUMEDICS">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <a href="http://www.medicinanucleardechiapas.com/" target="_blank" class="btn btn-success btn-lg btn-block" role="button">
                            <span class="glyphicon glyphicon-globe"></span> Página web MEDICINA NUCLEAR </a>
                    </div>
                    
                    <div class="col-md-6">
                        <a href="http://www.numedics.com.mx/" target="_blank" class="btn btn-success btn-lg btn-block" role="button">
                            <span class="glyphicon glyphicon-globe"></span> Página web NUMEDICS</a>
                    </div>
                </div>

                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
        </div>
        <!-- /#wrapper -->


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

    <!-- Gráficas porcentaje de pacientes atendidos primer semestre del año -->

    <script type="text/javascript">
                           
        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data_por_atender = google.visualization.arrayToDataTable([
                <?php
                    echo '["institucion", "cantidad de pacientes"],';
                    $num = count($datos_atendido_institucion_1);

                    for($i=0; $i < $num; $i++)
                    {
                        if($i == $num-1){
                            echo '["'.$datos_atendido_institucion_1[$i]['institucion'].'",'.$datos_atendido_institucion_1[$i]['cantidad'].']';    
                        }else{
                            echo '["'.$datos_atendido_institucion_1[$i]['institucion'].'",'.$datos_atendido_institucion_1[$i]['cantidad'].'],';    
                        }   
                    }
                    echo "]);";
                ?>

            var options_por_atender = {
                title: 'PORCENTAJES DE PACIENTES ATENDIDOS',
                is3D: true
            };

            

            var chart_por_atender = new google.visualization.PieChart(document.getElementById('primer_semestre'));
            chart_por_atender.draw(data_por_atender, options_por_atender);

            $(window).resize(function(){
                drawChart();
            });

        }
    </script>

    <!-- Gráficas porcentaje de pacientes atendidos segundo semestre del año -->

    <script type="text/javascript">
        
        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data_por_atender = google.visualization.arrayToDataTable([
                <?php
                    echo '["institucion", "cantidad de pacientes"],';
                    $num = count($datos_atendido_institucion_2);

                    for($i=0; $i < $num; $i++)
                    {
                        if($i == $num-1){
                            echo '["'.$datos_atendido_institucion_2[$i]['institucion'].'",'.$datos_atendido_institucion_2[$i]['cantidad'].']';    
                        }else{
                            echo '["'.$datos_atendido_institucion_2[$i]['institucion'].'",'.$datos_atendido_institucion_2[$i]['cantidad'].'],';    
                        }   
                    }
                    echo "]);";
                ?>

            var options_por_atender = {
                title: 'PORCENTAJES DE PACIENTES ATENDIDOS',
                is3D: true
            };

            

            var chart_por_atender = new google.visualization.PieChart(document.getElementById('segundo_semestre'));
            chart_por_atender.draw(data_por_atender, options_por_atender);

            $(window).resize(function(){
                drawChart();
            });

        }
    </script>

    <!-- Gráficas porcentaje de ventas particulares -->

    <script type="text/javascript">
        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var ventas = google.visualization.arrayToDataTable([
                <?php
                    echo '["Mes", "Cantidad"],';
                    $num = count($datos_ventas_totales_act);

                    for($i=0; $i < $num; $i++)
                    {
                        if($i == $num-1){
                            echo '["'.$datos_ventas_totales_act[$i]['Mes'].'",'.$datos_ventas_totales_act[$i]['Particulares'].']';    
                        }else{
                            echo '["'.$datos_ventas_totales_act[$i]['Mes'].'",'.$datos_ventas_totales_act[$i]['Particulares'].'],';    
                        }   
                    }
                    echo "]);";
                ?>

            var options_ventas = {
                title: 'Porcentajes ventas PARTICULARES por mes',
                is3D: true
            };

            

            var ventas_particulares = new google.visualization.PieChart(document.getElementById('ventas_particulares'));
            ventas_particulares.draw(ventas, options_ventas);

            $(window).resize(function(){
                drawChart();
            });

        }
   
    </script>

    <!-- Gráficas porcentaje de ventas instituciones -->

    <script type="text/javascript">
        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var ventas = google.visualization.arrayToDataTable([
                <?php
                    echo '["Mes", "Cantidad"],';
                    $num = count($datos_ventas_totales_act);

                    for($i=0; $i < $num; $i++)
                    {
                        if($i == $num-1){
                            echo '["'.$datos_ventas_totales_act[$i]['Mes'].'",'.$datos_ventas_totales_act[$i]['Instituciones'].']';    
                        }else{
                            echo '["'.$datos_ventas_totales_act[$i]['Mes'].'",'.$datos_ventas_totales_act[$i]['Instituciones'].'],';    
                        }   
                    }
                    echo "]);";
                ?>

            var options_ventas = {
                title: 'Porcentajes ventas a INSTITUCIONES por mes',
                is3D: true
            };

            var ventas_particulares = new google.visualization.PieChart(document.getElementById('ventas_instituciones'));
            ventas_particulares.draw(ventas, options_ventas);

            $(window).resize(function(){
                drawChart();
            });

        }
   
    </script>

    <!-- VENTAS TOTALES ACTUALES-->

    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
        //   ['MESES', 'PARTICULARES', 'INSTITUCIONES'],
        //   ['ENE',  31,      0],
        //   ['FEB',  31,      0],
        //   ['MAR',  42,       0],
        //   ['ABR',  30,      0],
        //   ['MAY',  35,      0],
        //   ['JUN',  26,       0],
        //   ['JUL',  13,      0],
        //   ['AGO',  0,      0],
        //   ['SEP',  0,       0],
        //   ['OCT',  0,      0],
        //   ['NOV',  0,      0],
        //   ['DIC',  0,      0]
          
        // ]);

        <?php
            echo "['MESES', 'PARTICULARES', 'INSTITUCIONES'],";
            $num = count($datos_ventas_totales_act);
            
            for($i=0; $i < $num; $i++)
            {
                if($i == $num-1){
                    echo "['".$datos_ventas_totales_act[$i]['Mes']."',".$datos_ventas_totales_act[$i]['Particulares'].",".$datos_ventas_totales_act[$i]['Instituciones']."]";    
                }else{
                    echo "['".$datos_ventas_totales_act[$i]['Mes']."',".$datos_ventas_totales_act[$i]['Particulares'].",".$datos_ventas_totales_act[$i]['Instituciones']."],";    
                }   
            }
            echo "]);";
        ?>

        var options = {
          title: 'VENTAS TOTALES <?php echo " ".$year_act." ' "?>,
        //   curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div_act'));

        chart.draw(data, options);
      }
    </script>

    <!-- VENTAS TOTALES Anteriores-->

    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
       

        <?php
            echo "['MESES', 'PARTICULARES', 'INSTITUCIONES'],";
            $num = count($datos_ventas_totales_ant);
            
            for($i=0; $i < $num; $i++)
            {
                if($i == $num-1){
                    echo "['".$datos_ventas_totales_ant[$i]['Mes']."',".$datos_ventas_totales_ant[$i]['Particulares'].",".$datos_ventas_totales_ant[$i]['Instituciones']."]";    
                }else{
                    echo "['".$datos_ventas_totales_ant[$i]['Mes']."',".$datos_ventas_totales_ant[$i]['Particulares'].",".$datos_ventas_totales_ant[$i]['Instituciones']."],";    
                }   
            }
            echo "]);";
        ?>

        var options = {
          title: 'VENTAS TOTALES <?php echo " ".$year_ant." ' "?>,
        //   curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div_ant'));

        chart.draw(data, options);
      }
    </script>



    
 
</body>

</html>
