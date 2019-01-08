
<!DOCTYPE html>
<?php $_POST["pagina"]="form"; 
    session_start(); 
    include "../include/sesion.php";
    comprueba_url();

    if(!isset($_POST['fecha_estudios'])){
        header("Location: index.php");
    }

?>
<html lang="es">
<head>
    <!-- aqui pondremos el contenido html -->
     <?php include "header.php"; ?>
 
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
                             Lista de medicos que envían pacientes<small></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                                Lista de medicos que envian estudios
                            </li>
                        </ol>
                    </div>
                    <div class="col-lg-3">
                            <form role="form" id="ver_lista" method="post" action="controlador_imprimir_comision.php" accept-charset="UTF-8">
                            </form>
                            <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                Atrás
                            </button>  
                            </br>                          
                    </div>
                </div>
                <!-- /.row -->

                <!--   Lista de pacientes del Dr. Martínez-->
                <?php
                    include_once "include/funciones_consultas.php";
                    
                    $fecha = $_POST['fecha_estudios'];
        
                    $fecha_fin = last_month_day($fecha);
                    $fecha_ini = first_month_day($fecha);  

                    include_once "include/mysql.php";

                    $mysql = new mysql();
                    $link = $mysql->connect(); 

                    $sql = $mysql->query($link,"SELECT  
                                                        t1.fecha,
                                                
                                                        CONCAT(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) AS nombre,
        
                                                        (SELECT concat(t2.tipo,' ',t2.nombre) AS estudio 
                                                            FROM estudio t2 
                                                            WHERE idgammagramas = t1.estudio_idgammagramas) AS estudio,

                                                        t1.institucion,

                                                        (SELECT concat(t3.grado,' ',t3.nombre,' ',t3.ap_paterno,' ',t3.ap_materno) AS medico 
                                                            FROM doctores t3 
                                                            WHERE iddoctores = t1.doctores_iddoctores) AS medico

                                                FROM pacientes t1 
                                                WHERE  (estudio_idgammagramas >= 3  AND estudio_idgammagramas <= 10) 
                                                        AND 
                                                        (estatus = 'ATENDIDO' AND (fecha >= '$fecha_ini' AND fecha <= '$fecha_fin')) ORDER BY fecha;");
                    
                    
                    if($_SESSION['nivel_acceso'] <= 2){
                        echo'
                        <form role="form" id="imprimir_lista_precios" method="post" action="view_imprimir_comision_cardiacos.php" target="_blank">
                            
                            <input type="hidden" form="imprimir_lista_precios" name="fecha_estudios" value="'.$fecha.'"/>
                        </form>

                        <button type="submit" class="btn btn-primary btn-lg btn-block"  aria-label="Left Align" form="imprimir_lista_precios">
                                <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                                IMPRIMIR
                        </button>
                        ';
                    }
                  
                echo '
                </br>
                <div class="panel  panel-success">
                    <div class="panel-heading"><strong>Pacientes del Dr. Martínez</strong></div>
                    <div class="panel-body">
                        <div class=" table-responsive">
                            <table class="table table-bordered table-hover table-striped" >
                            <thead>
                                <tr>
                                    <th data-field="numero" width="15">Fecha</th>
                                    <th data-field="estudio" width="70">Nombre del paciente</th>
                                    <th data-field="precio" width="15">Estudio realizado</th>
                                    <th data-field="precio" width="15">Institucion</th>
                                    <th data-field="precio" width="15">Médico tratante</th>';
                    echo '      </tr>
                           </thead>
                            <tbody>
                        ';
                          
                            while ($row = $mysql->f_obj($sql)) {
                                date_default_timezone_set('America/Mexico_City'); 
                                $row->fecha = date('d-m-Y', strtotime($row->fecha));
                                echo '<tr>'; 
                                echo '<fielset >';
                                echo '<td>'.$row->fecha.'</td>';
                                echo '<td>'.$row->nombre.'</td>';
                                echo '<td>'.$row->estudio.'</td>';
                                echo '<td>'.$row->institucion.'</td>';
                                echo '<td>'.$row->medico.'</td>';
                                echo '</tr>'; 
                                echo '</fielset>';
                            }   

                            $mysql->close();
                        echo'</tbody>
                            </table>
                        </div>
                    </div>
                </div>
                ';

                ?>



                <!--   lista de pacientes del Dr. Arevalo-->

                <?php
                    include_once "include/funciones_consultas.php";
                    
                    $fecha = $_POST['fecha_estudios'];
        
                    $fecha_fin = last_month_day($fecha);
                    $fecha_ini = first_month_day($fecha);  

                    include_once "include/mysql.php";

                    $mysql = new mysql();
                    $link = $mysql->connect(); 

                    $sql = $mysql->query($link,"SELECT  
                                            t1.fecha,
                                            CONCAT(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) AS nombre,
   
                                            (SELECT concat(t2.tipo,' ',t2.nombre) AS estudio 
                                                FROM estudio t2 
                                                WHERE idgammagramas = t1.estudio_idgammagramas) AS estudio,

                                            t1.institucion,

                                            (SELECT t3.grado 
                                                FROM doctores t3 
                                                WHERE iddoctores = t1.doctores_iddoctores) AS grado,

                                            (SELECT t3.nombre 
                                                FROM doctores t3 
                                                WHERE iddoctores = t1.doctores_iddoctores) AS nombre_med,

                                            (SELECT t3.ap_paterno 
                                                FROM doctores t3 
                                                WHERE iddoctores = t1.doctores_iddoctores 
                                                    AND t3.ap_paterno = 'AREVALO') AS ap_paterno_med,

                                            (SELECT t3.ap_materno 
                                                FROM doctores t3 
                                                WHERE iddoctores = t1.doctores_iddoctores
                                                    AND t3.ap_materno = 'AGUILAR') AS ap_materno_med

                                    FROM pacientes t1 inner join doctores t3
                                    WHERE  (t3.ap_paterno = 'AREVALO' AND t3.ap_materno = 'AGUILAR') 
                                            AND (t1.doctores_iddoctores = t3.iddoctores)
                                            AND(estudio_idgammagramas >= 3  AND estudio_idgammagramas <= 10) 
                                            AND (estatus = 'ATENDIDO' AND (fecha >= '$fecha_ini' AND fecha <= '$fecha_fin')) ORDER BY fecha;");
                    
                  
                echo '
                </br>
                <div class="panel  panel-success">
                    <div class="panel-heading"><strong>Pacientes del Dr. Arevalo</strong></div>
                    <div class="panel-body">
                        <div class=" table-responsive">
                            <table class="table table-bordered table-hover table-striped" >
                            <thead>
                                <tr>
                                    <th data-field="numero" width="15">Fecha</th>
                                    <th data-field="estudio" width="70">Nombre del paciente</th>
                                    <th data-field="precio" width="15">Estudio realizado</th>
                                    <th data-field="precio" width="15">Institucion</th>
                                    <th data-field="precio" width="15">Médico tratante</th>';
                    echo '      </tr>
                           </thead>
                            <tbody>
                        ';
                          
                            while ($row = $mysql->f_obj($sql)) {
                                date_default_timezone_set('America/Mexico_City'); 
                                $row->fecha = date('d-m-Y', strtotime($row->fecha));
                                echo '<tr>'; 
                                echo '<fielset >';
                                echo '<td>'.$row->fecha.'</td>';
                                echo '<td>'.$row->nombre.'</td>';
                                echo '<td>'.$row->estudio.'</td>';
                                echo '<td>'.$row->institucion.'</td>';
                                echo '<td>'.$row->grado.' '.$row->nombre_med.' '.$row->ap_paterno_med.' '.$row->ap_materno_med.'</td>';
                                echo '</tr>'; 
                                echo '</fielset>';
                            }   

                            $mysql->close();
                        echo'</tbody>
                            </table>
                        </div>
                    </div>
                </div>
                ';

                ?>


                <!-- Lista de pacientes atendidos Dra. Vidal-->

                <?php
                    include_once "include/funciones_consultas.php";
                    
                    $fecha = $_POST['fecha_estudios'];
        
                    $fecha_fin = last_month_day($fecha);
                    $fecha_ini = first_month_day($fecha);  

                    include_once "include/mysql.php";

                    $mysql = new mysql();
                    $link = $mysql->connect(); 

                    $sql = $mysql->query($link,"SELECT  
                                            t1.fecha,
                                            CONCAT(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) AS nombre,
   
                                            (SELECT concat(t2.tipo,' ',t2.nombre) AS estudio 
                                                FROM estudio t2 
                                                WHERE idgammagramas = t1.estudio_idgammagramas) AS estudio,

                                            t1.institucion,

                                            (SELECT t3.grado 
                                                FROM doctores t3 
                                                WHERE iddoctores = t1.doctores_iddoctores) AS grado,

                                            (SELECT t3.nombre 
                                                FROM doctores t3 
                                                WHERE iddoctores = t1.doctores_iddoctores
                                                    AND t3.nombre ='SILVIA') AS nombre_med,

                                            (SELECT t3.ap_paterno 
                                                FROM doctores t3 
                                                WHERE iddoctores = t1.doctores_iddoctores 
                                                    AND t3.ap_paterno = 'VIDAL') AS ap_paterno_med,

                                            (SELECT t3.ap_materno 
                                                FROM doctores t3 
                                                WHERE iddoctores = t1.doctores_iddoctores
                                                    AND t3.ap_materno = 'MUÑIZ') AS ap_materno_med

                                    FROM pacientes t1 inner join doctores t3
                                    WHERE (t3.nombre ='SILVIA') 
                                            AND (t1.doctores_iddoctores = t3.iddoctores) 
                                            AND (estudio_idgammagramas >= 3  AND estudio_idgammagramas <= 10) 
                                            AND (estatus = 'ATENDIDO' AND (fecha >= '$fecha_ini' AND fecha <= '$fecha_fin')) ORDER BY fecha;");
                    
                  
                echo '
                </br>
                <div class="panel  panel-success">
                    <div class="panel-heading"><strong>Pacientes del Dra. Vidal</strong></div>
                    <div class="panel-body">
                        <div class=" table-responsive">
                            <table class="table table-bordered table-hover table-striped" >
                            <thead>
                                <tr>
                                    <th data-field="numero" width="15">Fecha</th>
                                    <th data-field="estudio" width="70">Nombre del paciente</th>
                                    <th data-field="precio" width="15">Estudio realizado</th>
                                    <th data-field="precio" width="15">Institucion</th>
                                    <th data-field="precio" width="15">Médico tratante</th>';
                    echo '      </tr>
                           </thead>
                            <tbody>
                        ';
                          
                            while ($row = $mysql->f_obj($sql)) {
                                date_default_timezone_set('America/Mexico_City'); 
                                $row->fecha = date('d-m-Y', strtotime($row->fecha));
                                echo '<tr>'; 
                                echo '<fielset >';
                                echo '<td>'.$row->fecha.'</td>';
                                echo '<td>'.$row->nombre.'</td>';
                                echo '<td>'.$row->estudio.'</td>';
                                echo '<td>'.$row->institucion.'</td>';
                                echo '<td>'.$row->grado.' '.$row->nombre_med.' '.$row->ap_paterno_med.' '.$row->ap_materno_med.'</td>';
                                echo '</tr>'; 
                                echo '</fielset>';
                            }   

                            $mysql->close();
                        echo'</tbody>
                            </table>
                        </div>
                    </div>
                </div>
                ';

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