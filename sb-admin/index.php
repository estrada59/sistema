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
                                <div id="chart_div"></div>
                            </div>
                        </div>

                        <br>
                        <br>

                        <div class="row">
                            <div class="col-md-5">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        Contratos instituciones
                                    </div>
                                    <div class="panel-body">';
                                        include_once "include/funciones_consultas.php";
                                        monto_ejecutado_por_instituciones_resumen();
                        echo'       </div>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="panel panel-success">

                                    <div class="panel-heading">
                                        Ventas particulares
                                    </div>

                                    <div class="panel-body">';
                                    echo'
                                            <!-- CHART Aquí se invoca   -->
                
                                                <div id="ventas_particulares" class="chart"></div>

                                                <div id="ventas_instituciones" class="chart"></div>
                                                
                                            <!-- Fin CHART   -->';

                                    date_default_timezone_set('America/Mexico_City'); 
                                    $fecha_act = date('Y-m-d');

                                    $fecha = explode("-", $fecha_act);
                                    // print_r($fecha);

                                    $year = $fecha[0];  //AÑO ACTUAL

                                    $mysql = new mysql();
                                    $link = $mysql->connect(); 

                                    $datos = array();
                                    
                                    
                                    $meses = array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");

                                    // print_r($meses);

                                    $cont_meses = 1;

                                    for($i=0; $i<12; $i++){
                                        
                                        $total_particular = 0;
                                        $total_instituciones = 0;

                                        $sql = $mysql->query($link, "SELECT instituciones.nombre
                                                                        FROM instituciones
                                                                        WHERE instituciones.tipo = 'PARTICULAR'");
                                        
                                        $fecha_inicio = $year.'-'.$cont_meses.'-'.'01';
                                        $fecha_inicio = date('Y-m-d', strtotime($fecha_inicio));

                                        $fecha_fin = last_month_day($fecha_inicio);

                                        // echo $fecha_inicio.'   /n  ';
                                        // echo $fecha_fin.'   /n  ';

                                        while ($instituciones = $mysql->f_obj($sql)) {
                                            //echo $instituciones->nombre;

                                            $institucion = mb_strtolower($instituciones->nombre, "UTF-8" );
                                            $institucion = str_replace("_", " ", $institucion);
                                            $estatus = 'ATENDIDO';

                                            // echo $institucion;

                                            $sql2 = $mysql->query($link, "SELECT COUNT(t1.idpacientes)  AS total
                                                                            FROM pacientes t1 
                                                                            WHERE (t1.fecha >= '$fecha_inicio' AND  t1.fecha <= '$fecha_fin') and t1.institucion= '$institucion' and t1.estatus = '$estatus'");

                                            $row = $mysql->f_obj($sql2);
                                             
                                            $total_particular += $row->total;
                                            // echo'<pre>';
                                            // print_r($row);    
                                            // echo'</pre>';
                                        }


                                        /**********************
                                        **********************/

                                        $sql = $mysql->query($link, "SELECT instituciones.nombre
                                                                        FROM instituciones
                                                                        WHERE instituciones.tipo != 'PARTICULAR'");
                                        
                                        $fecha_inicio = $year.'-'.$cont_meses.'-'.'01';
                                        $fecha_inicio = date('Y-m-d', strtotime($fecha_inicio));

                                        $fecha_fin = last_month_day($fecha_inicio);

                                        // echo $fecha_inicio.'   /n  ';
                                        // echo $fecha_fin.'   /n  ';

                                        while ($instituciones = $mysql->f_obj($sql)) {
                                            //echo $instituciones->nombre;

                                            $institucion = mb_strtolower($instituciones->nombre, "UTF-8" );
                                            $institucion = str_replace("_", " ", $institucion);
                                            $estatus = 'ATENDIDO';

                                            // echo $institucion;

                                            $sql3 = $mysql->query($link, "SELECT COUNT(t1.idpacientes)  AS total
                                                                            FROM pacientes t1 
                                                                            WHERE (t1.fecha >= '$fecha_inicio' AND  t1.fecha <= '$fecha_fin') and t1.institucion= '$institucion' and t1.estatus = '$estatus'");

                                            $row2 = $mysql->f_obj($sql3);
                                             
                                            $total_instituciones += $row2->total;
                                            // echo'<pre>';
                                            // print_r($row);    
                                            // echo'</pre>';
                                        }




                                        $datos[$i]['Mes'] = $meses[$i];
                                        $datos[$i]['Particulares'] = $total_particular;
                                        $datos[$i]['Instituciones'] = $total_instituciones;
                                        
                                        $cont_meses ++;
                                        // echo $meses[$i].':  '.$total.'      ';

                                    }
                                    // echo'<pre>';
                                    // print_r($datos);
                                    // echo'</pre>';    

                                    $datos_ventas_totales = $datos;
                                    
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
                    $num = count($datos_ventas_totales);

                    for($i=0; $i < $num; $i++)
                    {
                        if($i == $num-1){
                            echo '["'.$datos_ventas_totales[$i]['Mes'].'",'.$datos_ventas_totales[$i]['Particulares'].']';    
                        }else{
                            echo '["'.$datos_ventas_totales[$i]['Mes'].'",'.$datos_ventas_totales[$i]['Particulares'].'],';    
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
                    $num = count($datos_ventas_totales);

                    for($i=0; $i < $num; $i++)
                    {
                        if($i == $num-1){
                            echo '["'.$datos_ventas_totales[$i]['Mes'].'",'.$datos_ventas_totales[$i]['Instituciones'].']';    
                        }else{
                            echo '["'.$datos_ventas_totales[$i]['Mes'].'",'.$datos_ventas_totales[$i]['Instituciones'].'],';    
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

    <!-- VENTAS TOTALES -->

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
            $num = count($datos_ventas_totales);

            for($i=0; $i < $num; $i++)
            {
                if($i == $num-1){
                    echo "['".$datos_ventas_totales[$i]['Mes']."',".$datos_ventas_totales[$i]['Particulares'].",".$datos_ventas_totales[$i]['Instituciones']."]";    
                }else{
                    echo "['".$datos_ventas_totales[$i]['Mes']."',".$datos_ventas_totales[$i]['Particulares'].",".$datos_ventas_totales[$i]['Instituciones']."],";    
                }   
            }
            echo "]);";
        ?>

        var options = {
          title: 'VENTAS TOTALES <?php echo " ".$year." ' "?>,
        //   curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

        chart.draw(data, options);
      }
    </script>



    
 
</body>

</html>
