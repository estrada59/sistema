<!DOCTYPE html>
<?php $_POST["pagina"]="ver_pacientes_por_semana"; ?>
<html lang="es">

<head>
    <?php   include "header.php";
            session_start(); 
            include "../include/sesion.php";
            comprueba_url();

            /* if($_SESSION['nivel_acceso'] >= 4){
                header("Location: index.php");
                exit();
            }*/

            // if(!isset($_POST['fecha_estudios'])){
            //     header('Location: controlador_ver_pacientes_por_semana.php');
            // }
    ?>
</head>
<body>
    <div id="wrapper">

        <!-- Navigation -->
       <?php  
            if($_SESSION['nivel_acceso'] == 3){
                include "nav.php";  }
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
                            Pacientes citados
                                <?php 
                                
                                include_once 'include/funciones_consultas.php';
                                 $fecha_estudio = $_POST['fecha_estudios'];
                                //date_default_timezone_set('America/Mexico_City'); 
                                //$fecha_estudio = date('Y-m-d');
                                //echo($fecha_estudio);

                                $arreglo = explode('-', $fecha_estudio);

                                //$semana = obtener_semana($day, $month, $year);
                                //print_r($arreglo);
                                $año = $arreglo[0];
                                $mes = $arreglo[1];
                                $dia = $arreglo[2];

                                $semana = obtener_semana($dia, $mes, $año);
                               
                                echo '<small> del '.$semana['lunes'].' al '.$semana['domingo'].'</small>';
                              
                                ?>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                                Lista de pacientes citados <?php echo '<small> del '.$semana['lunes'].' al '.$semana['domingo'].'</small>'; ?>
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
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><?php echo fecha_letras($semana['lunes']);?></h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                                <?php
                                    include_once 'include/funciones_consultas.php';
                                    
                                    $arreglo = explode('-', $semana['lunes']);
                                   
                                    $año = $arreglo[0];
                                    $mes = $arreglo[1];
                                    $dia = $arreglo[2];

                                    $fecha = $dia.'-'.$mes.'-'.$año;

                                    if($_SESSION['nivel_acceso'] == 4){
                                        ver_pacientes_del_dia_operario($fecha, $_POST["pagina"]);}
                                    else{
                                        ver_pacientes_del_dia($fecha, $_POST["pagina"]);}
                                ?>
                            </div><!-- /. fin panel body -->

                            <!--<div class="panel-footer">
                            </div>-->
                        </div><!-- /. fin panel primary -->
                        

                        <!--        1er columnoa de captura   MARTES      -->
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><?php echo fecha_letras($semana['martes']);?></h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                                <?php
                                    $arreglo = explode('-', $semana['martes']);
                                   
                                    $año = $arreglo[0];
                                    $mes = $arreglo[1];
                                    $dia = $arreglo[2];

                                    $fecha = $dia.'-'.$mes.'-'.$año;
                                    if($_SESSION['nivel_acceso'] == 4){
                                        ver_pacientes_del_dia_operario($fecha,  $_POST["pagina"]);}
                                    else{
                                        ver_pacientes_del_dia($fecha, $_POST["pagina"]);}
                                ?>
                            </div><!-- /. fin panel body -->

                            <!--<div class="panel-footer">
                            </div>-->
                        </div><!-- /. fin panel primary -->



                        <!--        2er columnoa de captura   MIERCOLES      -->
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><?php echo fecha_letras($semana['miercoles']);?></h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                                <?php
                                    $arreglo = explode('-', $semana['miercoles']);
                                   
                                    $año = $arreglo[0];
                                    $mes = $arreglo[1];
                                    $dia = $arreglo[2];

                                    $fecha = $dia.'-'.$mes.'-'.$año;
                                    if($_SESSION['nivel_acceso'] == 4){
                                        ver_pacientes_del_dia_operario($fecha, $_POST["pagina"]);}
                                    else{
                                        ver_pacientes_del_dia($fecha, $_POST["pagina"]);}
                                ?>

                            </div><!-- /. fin panel body -->

                            <!--<div class="panel-footer">
                            </div>-->
                        </div><!-- /. fin panel primary -->




                        <!--        3RA columnoa de captura  JUEVES      -->
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><?php echo fecha_letras($semana['jueves']);?></h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                                <?php
                                    $arreglo = explode('-', $semana['jueves']);
                                   
                                    $año = $arreglo[0];
                                    $mes = $arreglo[1];
                                    $dia = $arreglo[2];

                                    $fecha = $dia.'-'.$mes.'-'.$año;
                                    if($_SESSION['nivel_acceso'] == 4){
                                        ver_pacientes_del_dia_operario($fecha, $_POST["pagina"]);}
                                    else{
                                        ver_pacientes_del_dia($fecha, $_POST["pagina"]);}
                                ?>
                            </div><!-- /. fin panel body -->

                            <!--<div class="panel-footer">
                            </div>-->
                        </div><!-- /. fin panel primary -->


                        <!--        4RA columnoa de captura  VIERNES       -->
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><?php echo fecha_letras($semana['viernes']);?></h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                                <?php
                                    $arreglo = explode('-', $semana['viernes']);
                                   
                                    $año = $arreglo[0];
                                    $mes = $arreglo[1];
                                    $dia = $arreglo[2];

                                    $fecha = $dia.'-'.$mes.'-'.$año;
                                    if($_SESSION['nivel_acceso'] == 4){
                                        ver_pacientes_del_dia_operario($fecha, $_POST["pagina"]);}
                                    else{
                                        ver_pacientes_del_dia($fecha, $_POST["pagina"]);}
                                ?>

                            </div><!-- /. fin panel body -->

                            <!--<div class="panel-footer">
                            </div>-->
                        </div><!-- /. fin panel primary -->


                        <!--        3RA columnoa de captura  SÁBADO       -->
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><?php echo fecha_letras($semana['sabado']);?></h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                                <?php
                                    $arreglo = explode('-', $semana['sabado']);
                                   
                                    $año = $arreglo[0];
                                    $mes = $arreglo[1];
                                    $dia = $arreglo[2];

                                    $fecha = $dia.'-'.$mes.'-'.$año;
                                    if($_SESSION['nivel_acceso'] == 4){
                                        ver_pacientes_del_dia_operario($fecha, $_POST["pagina"]);}
                                    else{
                                        ver_pacientes_del_dia($fecha, $_POST["pagina"]);}
                                ?>

                            </div><!-- /. fin panel body -->

                            <!--<div class="panel-footer">
                            </div>-->
                        </div><!-- /. fin panel primary -->

                        <!--        3RA columnoa de captura  DOMINGO       -->
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><?php echo fecha_letras($semana['domingo']);?></h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                                <?php
                                    $arreglo = explode('-', $semana['domingo']);
                                   
                                    $año = $arreglo[0];
                                    $mes = $arreglo[1];
                                    $dia = $arreglo[2];

                                    $fecha = $dia.'-'.$mes.'-'.$año;
                                    if($_SESSION['nivel_acceso'] == 4){
                                        ver_pacientes_del_dia_operario($fecha, $_POST["pagina"]);}
                                    else{
                                        ver_pacientes_del_dia($fecha, $_POST["pagina"]);}
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
</body>
</html>
