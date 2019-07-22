<!DOCTYPE html>

<?php $_POST["pagina"]="instituciones"; 
    session_start(); 
    include_once "../include/sesion.php";
    include_once "../include/mysql.php";
    comprueba_url();
    time_session();

    if($_SESSION['nivel_acceso'] >= 4){
    header("Location: index.php");
    exit();
    }

?>
<html lang="en">

<head>
    <?php include "header.php"; ?>
     <!-- css validation form -->
    <link rel="stylesheet" href="css/style_validation_form.css">
    <!-- jquery validation form -->
    <script src="js/livevalidation_standalone.js"></script>
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
                            Contrato creado<small></small>
                        </h1>

                        <ol class="breadcrumb">
                            <li class="active">
                                <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                                 Contrato creado
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

                <div class="row">

                    <div class="col-md-3 col-lg-3">
                    </div>

                    <div class="col-md-6 col-lg-6">
                        <!--        agregar lista de precios y eliminar         -->
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"> Contrato creado </h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                                <form role="form" id="atras" method="post" action="controlador_monto_ejecutado.php" accept-charset="UTF-8">
                                        <input id="submit" name="submit" form = "atras" class="btn btn-success btn-lg btn-block"  type="submit" value="Atrás">
                                </form>

                                <?php 
                                    // echo '<pre>';
                                    // print_r($_POST);
                                    // echo '</pre>';
                                    
                                    $id_contrato        = $_POST['id_contrato'];
                                    $monto_maximo       = $_POST['monto_maximo'];
                                    $fecha_inicio       = $_POST['fecha_inicio_contrato'].' 00:00:00';
                                    $fecha_fin          = $_POST['fecha_fin_contrato'].' 23:59:59';

                                    // echo $fecha_inicio;
                                    // echo $fecha_fin;

                                    $nombre_institucion = $_POST['nombre_institucion'];
                                    $nombre_minusculas = $_POST['nombre_minusculas'];


                                    $mysql = new mysql();
                            
                                    $link = $mysql->connect();
                                    $sql = $mysql->query($link,"UPDATE  tbl_montos_disponibles
                                                                        set monto_maximo = $monto_maximo,
                                                                            monto_restante = 0.00,
                                                                            monto_ejecutado =0.00,
                                                                            fecha_inicio = '$fecha_inicio',
                                                                            fecha_fin    = '$fecha_fin'
                                                                    WHERE id_montos_disponibles = $id_contrato;");


                                    /*********************************************/
                                    // PENDIENTE CALCULAR MONTO EJECUTADO Y RESTANTE APARTIR DE LA CONSULTA 
                                    // ANTERIOR
                                    /*********************************************/
                                    if($nombre_institucion == 'IMSS TUXTLA')
                                    {

                                        $sql = $mysql->query($link,"SELECT 	
                                                                    sum(estudio.imss_tuxtla) as total_ejercido
                                                            from pacientes INNER JOIN estudio on idgammagramas = pacientes.estudio_idgammagramas

                                                            where  ((fecha >= (select   DATE_FORMAT(fecha_inicio, '%Y-%m-%d') as fecha_inicio 
                                                                                    from tbl_montos_disponibles 
                                                                                    where id_montos_disponibles=  $id_contrato)
                                                                    )
                                                                    and
                                                                    (fecha <= (select   DATE_FORMAT(fecha_fin, '%Y-%m-%d') as fecha_fin 
                                                                                    from tbl_montos_disponibles 
                                                                                    where id_montos_disponibles=  $id_contrato)	
                                                                    ))
                                                                    and
                                                                    (pacientes.institucion = '$nombre_institucion')");

                                        $row = $mysql->f_obj($sql);

                                        if(isset($row))
                                        {
                                            $monto_restante = $monto_maximo - $row->total_ejercido;

                                            if($row->total_ejercido < $monto_maximo)
                                            {
                                                $sql_update = $mysql->query($link,"UPDATE  tbl_montos_disponibles
                                                                                set monto_ejecutado = $row->total_ejercido,
                                                                                    monto_restante = $monto_restante
                                                                            WHERE id_montos_disponibles = $id_contrato;"); 
                                            }else {
                                                $sql_update = $mysql->query($link,"UPDATE  tbl_montos_disponibles
                                                                                set monto_ejecutado = $row->total_ejercido,
                                                                                    monto_restante = $monto_restante
                                                                            WHERE id_montos_disponibles = $id_contrato;");
                                                echo '<pre>';
                                                echo 'Se rebasó el tope maximo del contrato por: '.$monto_restante;
                                                echo '</pre>';
                                            }
                                            
                                        }
                                        
                                    }else {
                                        

                                        $sql = $mysql->query($link,"SELECT 	
                                                                    sum(estudio.$nombre_minusculas) as total_ejercido
                                                            from pacientes INNER JOIN estudio on idgammagramas = pacientes.estudio_idgammagramas

                                                            where  ((fecha >= (select   DATE_FORMAT(fecha_inicio, '%Y-%m-%d') as fecha_inicio 
                                                                                    from tbl_montos_disponibles 
                                                                                    where id_montos_disponibles=  $id_contrato)
                                                                    )
                                                                    and
                                                                    (fecha <= (select   DATE_FORMAT(fecha_fin, '%Y-%m-%d') as fecha_fin 
                                                                                    from tbl_montos_disponibles 
                                                                                    where id_montos_disponibles=  $id_contrato)	
                                                                    ))
                                                                    and
                                                                    (pacientes.institucion = '$nombre_institucion')
                                                                    and 
                                                                    (pacientes.estatus = 'ATENDIDO' || pacientes.estatus = 'POR ATENDER') ");
                                        $row = $mysql->f_obj($sql);

                                        if(isset($row))
                                        {
                                            $monto_restante = $monto_maximo - $row->total_ejercido;

                                            if($row->total_ejercido < $monto_maximo)
                                            {
                                                $sql_update = $mysql->query($link,"UPDATE  tbl_montos_disponibles
                                                                                set monto_ejecutado = $row->total_ejercido,
                                                                                    monto_restante = $monto_restante
                                                                            WHERE id_montos_disponibles = $id_contrato;"); 
                                            }else {
                                                $sql_update = $mysql->query($link,"UPDATE  tbl_montos_disponibles
                                                                                set monto_ejecutado = $row->total_ejercido,
                                                                                    monto_restante = $monto_restante
                                                                            WHERE id_montos_disponibles = $id_contrato;");
                                                echo '<pre>';
                                                echo 'Se rebasó el tope maximo del contrato por: '.$monto_restante;
                                                echo '</pre>';
                                            }
                                            
                                        }
                                    }


                                ?>
                            </div><!-- /. fin panel body -->

                            <div class="panel-footer">   
                            </div>
                        </div><!-- /. fin panel primary -->
                    </div>

                    <div class="col-md-3 col-lg-3">
                    </div>
            </div> 
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Validación de formularios -->
    
    <script type="text/javascript">
        var monto_maximo = new LiveValidation('monto_maximo', { validMessage: 'OK!', wait: 500});
        monto_maximo.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        monto_maximo.add(Validate.Length, {minimum: 3 } );
        monto_maximo.add( Validate.Numericality );
    </script> 

</body>

</html>
