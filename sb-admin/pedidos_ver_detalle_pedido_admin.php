<!DOCTYPE html>

<?php $_POST["pagina"]="venta_cobros"; 
    session_start();
    include_once "../include/sesion.php";
    include_once "include/mysql.php";
    comprueba_url();
    //comprueba_empleado_activo();
    //cerrar_multiples_sesiones(); 


    if($_SESSION['nivel_acceso'] ==3){
        header("Location: index.php");
        exit();}
        //error_reporting(0);
?>
<html lang="en">

<head>
    <?php 
        include "header.php";
    ?>
    <!-- ESTE ARCHIVO ES CLAVE PARA LAS CONSULTAS Y CONTROLAR TODO EL PLUGIN-->
    
    <!-- Alertity -->
    <link rel="stylesheet" href="libs/js/alertify/themes/alertify.core.css" />
    <link rel="stylesheet" href="libs/js/alertify/themes/alertify.bootstrap.css" id="toggleCSS" />
    <script src="libs/js/alertify/lib/alertify.min.js"></script>
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
                        <h3 class="page-header">
                            Detalle de pedido 
                        </h3>                       
                    </div>
                </div>
                <!-- /.row -->
                
                <div class="row">

                    <div class="col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-md-2 col-lg-2">
                                <form id = "form_back_pedidos_lista_pedidos" name="form_back_lista_tipo_agregar_venta" action="pedidos_lista_pedidos.php" method="post">
                                    <button type="submit" form="form_back_pedidos_lista_pedidos" name="atras" id="atras"  class="btn btn-success btn-block"  >
										<span class="glyphicon " aria-hidden="true"></span>
										Atrás
                                    </button>
                                   <!-- <a id="atras" href="javascript:document.form_back_lista_tipo_agregar_venta.submit()" class="btn btn-success btn-lg btn-block" role="button" ><span class="glyphicon glyphicon-arrow-left"></span> Atrás</a>-->
                                </form>
                            </div>
                        </div>

                        <br/>

                        <div class="row">
                            <div class="col-md-12 col-lg-12">
                                <div class="alert alert-info">
                                    <div class="form-group">  
                                        <?php 
                                            
                                            $id_pedido = $_POST['id_pedidos'];
                                            
                                            $mysql = new mysql();
                                            $link =$mysql->connect();
                                            
                                            $resp = $mysql->query($link, "SET lc_time_names = 'es_ES';");
                                            //$resp = $mysql->query($link, "call sp_s_fecha_pedido($id_pedido)");

                                            $cad = "SELECT  (SELECT DATE_FORMAT(tbl_pedidos.fecha_pedido, '%d %b %Y')) as fecha_pedido
                                                    FROM tbl_pedidos
                                                    WHERE id_pedidos = $id_pedido";

                                            $fecha = $mysql->query($link, $cad);
                                            $row =  $mysql->f_obj($fecha);


                                            //ESTATUS GENERAL DE PEDIDO
                                            $cad = "SELECT   
                                                        													
                                                            (SELECT t4.id_status_det_pedidos
                                                            FROM tblc_status_det_pedidos t4
                                                            WHERE t4.id_status_det_pedidos = t1.id_status_det_pedidos) as id_estatus_detalle,

                                                            (SELECT t4.descripcion
                                                            FROM tblc_status_det_pedidos t4
                                                            WHERE t4.id_status_det_pedidos = t1.id_status_det_pedidos) as descripcion

                                                    FROM tbl_detalle_pedidos t1
                                                    WHERE t1.id_pedidos= $id_pedido ";
                                            
                                            $query = $mysql->query($link, $cad);
                                            
                                            $array_estatus = array();
                                            $estatus_general_pedido='';
                                            $up_status_general='';
                                            
                                           
                                            while($row_estatus =  $mysql->f_obj($query)){
                                                    $array_estatus[]= $row_estatus->descripcion;
                                            }

                                            //TOTAL DE ELEMENTOS EN EL ARRAY
                                            $total = count($array_estatus);
                                            

                                            //BUSCA SI HAY ESTATUS PENDIENTE
                                            if (in_array("PENDIENTE", $array_estatus)) {
                                                $estatus_general_pedido='PENDIENTE';
                                                $up_status_general = 1;
                                            }else {
                                               
                                                //BUSCA SI TODOS ESTAN CANCELADOS
                                                $str = ", " . implode(", ",$array_estatus) . ",";
                                                $count = substr_count($str, ' CANCELADO,');
                                                
                                                if($count == $total){
                                                    $estatus_general_pedido='CANCELADO';
                                                    $up_status_general = 2;
                                                }else{
                                                    $estatus_general_pedido='RECIBIDO';
                                                    $up_status_general = 3;
                                                }
                                                
                                            }
                                            
                                            $cad = "UPDATE tbl_pedidos 
                                                    SET id_status_pedido = $up_status_general
                                            WHERE tbl_pedidos.id_pedidos = $id_pedido; ";

                                            $query = $mysql->query($link, $cad); 
                                            // FIN ESTATUS
                                        ?>  

                                        <div class="row">
                                            <div class="col-lg-2">    
                                                <h4 label label-info>PEDIDO #: <?php echo '<strong>'.$id_pedido.'</strong>'; ?></h4>
                                            </div>
                                            <div class="col-lg-4">
                                                 <h4 label label-info>FECHA DE PEDIDO : <?php echo '<strong>'.$row->fecha_pedido.'</strong>'; ?></h4>
                                            </div>
                                            <div class="col-lg-6">
                                                <?php
                                                    if($estatus_general_pedido=='PENDIENTE'){
                                                        echo '<h4 label label-info>ESTATUS GENERAL : <a class="btn btn-primary" href="#" > '.$estatus_general_pedido.'</a></h4>';
                                                    }
                                                    if($estatus_general_pedido=='RECIBIDO'){
                                                        echo '<h4 label label-info>ESTATUS GENERAL: <a class="btn btn-success" href="#" > '.$estatus_general_pedido.'</a> </h4>';
                                                    }
                                                    if($estatus_general_pedido=='CANCELADO'){
                                                        echo '<h4 label label-info>ESTATUS GENERAL: <a class="btn btn-danger" href="#" > '.$estatus_general_pedido.'</a> </h4>';
                                                    }
                                                ?>
                                                 
                                            </div>
                                        </div>
                                        <!--<h4>VENTA TOTAL DE: $ 500.00</h4>
                                        <h4>Debe:  $ 300.00</h4>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        </br>

                        <div class="panel panel-success"> 
                            <div class="panel-body">
                                <?php
                                    
                                    //consulta y detalle de pedido

                                    $mysql = new mysql();
                                    $link =$mysql->connect();

                                    $cadena = "SELECT   t1.id_detalle_pedidos,
                                                        
                                                        
                                                        (SELECT t2.descripcion
                                                        FROM tblc_unidad_medida t2
                                                        WHERE t2.id_unidad_medida = t1.id_unidad_medida) AS um,
                                                        
                                                        (SELECT t3.descripcion
                                                        FROM tblc_productos t3
                                                        WHERE t3.id_productos = t1.id_productos) as producto,

                                                        (SELECT t4.descripcion
                                                        FROM tblc_status_det_pedidos t4
                                                        WHERE t4.id_status_det_pedidos = t1.id_status_det_pedidos) as estatus_detalle,

                                                        (SELECT t4.id_status_det_pedidos
                                                        FROM tblc_status_det_pedidos t4
                                                        WHERE t4.id_status_det_pedidos = t1.id_status_det_pedidos) as id_estatus_detalle,

                                                        
                                                        t1.cantidad,
                                                        t1.actividad,
                                                        t1.observaciones,

                                                        DATE_FORMAT(t1.fecha_recibido, '%d %b %Y %r') as fecha_recibido
                                                        
                                                FROM tbl_detalle_pedidos t1
                                                WHERE t1.id_pedidos=$id_pedido;";

                                    $sql = $mysql->query( $link, $cadena);


                                    

                                    //Creamos un Bucle que recorra toda la tabla de SQL

                                    echo '<div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped table-responsive">';
                                    echo '<thead>
                                            <tr>
                                                <th style="text-align:center">Cant.</th>
                                                <th style="text-align:center">U.M.</th>
                                                <th style="text-align:center" >Producto</th>
                                                <th style="text-align:center">Actividad</th>
                                                <th style="text-align:center" class="warning">Estatus de Compra</th>
                                                <th style="text-align:center" class="warning">Empresa</th>
                                                <th style="text-align:center" class="warning">Fecha solicitud</th>';
                                            
                                                if($_SESSION['nivel_acceso'] != 1){
                                                    echo '<th style="text-align:center">Editar</th>';
                                                }

                                                
                                    echo        '<th style="text-align:center">Fecha recibido</th>
                                                <th style="text-align:center">Estatus de recepción</th>
                                                <th style="text-align:center">Observaciones</th>
                                                <th style="text-align:center" class="warning">Estatus Factura</th>
                                                <th style="text-align:center" class="warning">No. Fact</th>';
                                                
                                                if($_SESSION['nivel_acceso'] != 1){
                                                    echo'<th style="text-align:center">Editar</th>';
                                                }
                                    
                                    echo'   </tr>
                                        </thead>
                                        <tbody>';
                                        
                                   
                                    
                                    while($row =  $mysql->f_obj($sql)){

                                        //consulta de compra
                                        $cad5="SELECT 
                                                        t1.id_solicitud_pedidos,
                                                        
                                                        (SELECT t2.descripcion
                                                        FROM tblc_empresas t2
                                                        WHERE t2.id_empresas = t1.id_empresas) as empresa,
                                                        
                                                        (SELECT t3.descripcion
                                                        FROM tblc_status_solicitudes t3
                                                        WHERE t3.id_status_solicitudes = t1.id_status_solicitudes) as estatus_solicitud,
                                                        
                                                        
                                                        DATE_FORMAT(t1.fecha_solicitud, '%d %b %Y %r') as fecha_solicitud
                                
                                                FROM tbl_solicitud_pedidos t1
                                                WHERE id_detalle_pedidos = $row->id_detalle_pedidos;";

                                        $sql5 = $mysql->query( $link, $cad5);
                                        
                                        $row5 =  $mysql->f_obj($sql5);


                                        //consulta de factura
                                        $cad6="SELECT (SELECT t2.descripcion
                                                        FROM tblc_status_facturas t2
                                                        WHERE t2.id_status_facturas= t1.id_status_facturas) as estatus_factura,
                                        
                                                        t1.no_factura
                                        
                                                        
                                                FROM tbl_facturas t1
                                                WHERE t1.id_solicitud_pedidos= $row5->id_solicitud_pedidos;";

                                        $sql6 = $mysql->query( $link, $cad6);
                                        
                                        $row6 =  $mysql->f_obj($sql6);
                                        
                                        //TIRAS DE COLORES
                                        //ARTICULO CON ESTATUS PENDIENTE
                                        if($row->id_estatus_detalle == 1){
                                            echo '<tr class="active">';
                                        }
                                        if($row->id_estatus_detalle == 2){
                                            echo '<tr class="danger">';
                                        }
                                        if($row->id_estatus_detalle == 3){
                                            echo '<tr class="success">';
                                        }

                                                echo '<td>'.$row->cantidad.'</td>';
                                                echo '<td>'.$row->um.'</td>';
                                                echo '<td>'.$row->producto.'</td>';
                                                echo '<td>'.$row->actividad.' mCi</td>'; 


                                                echo '<td class="warning">'.$row5->estatus_solicitud.'</td>';
                                                echo '<td class="warning">'.$row5->empresa.'</td>';
                                                echo '<td class="warning">'.$row5->fecha_solicitud.'</td>';

                                            if($_SESSION['nivel_acceso'] != 1){
                                                echo '<td >';

                                                echo '  <button type="button" class="btn btn-warning btn-sm" name="editar'.$row->id_detalle_pedidos.'" data-toggle="modal" data-target="#myModal'.$row->id_detalle_pedidos.'">
                                                            Editar
                                                        </button>';
                                                

                                                echo'        <!-- Modal -->
                                                        <div class="modal fade" id="myModal'.$row->id_detalle_pedidos.'"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
                                                            <div class="modal-dialog modal-sm" role="document">
                                                                <div class="modal-content">
                                                            
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                        <h4 class="modal-title" id="myModalLabel">Actualizar estatus</h4>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <form id="form_editar1'.$row->id_detalle_pedidos.'" action="pedidos_data_update_estatus_compra.php"  method="post">
                                                                            <input type="hidden" name="id_pedidos" value="'.$id_pedido.'" form="form_editar1'.$row->id_detalle_pedidos.'">
                                                                            
                                                                            <input type="hidden" name="id_detalle_pedido" value="'.$row->id_detalle_pedidos.'" form="form_editar1'.$row->id_detalle_pedidos.'">

                                                                            <label>Estatus de compra:</label>
                                                                            <select class="form-control " form="form_editar1'.$row->id_detalle_pedidos.'" name="estatus_solicitud">';
                                                                                if(isset($row5->estatus_solicitud)){
                                                                                    $cad2 = " SELECT *
                                                                                                from tblc_status_solicitudes
                                                                                                where activo=1;";

                                                                                    $sql2 = $mysql->query( $link, $cad2);

                                                                                    $row2 =  $mysql->f_obj($sql2);

                                                                                    echo '<option value="'.$row2->id_status_solicitudes.'"> '.$row5->estatus_solicitud.'</option>';

                                                                                    $cad2 = " SELECT *
                                                                                                from tblc_status_solicitudes
                                                                                                where activo=1;";

                                                                                    $sql2 = $mysql->query( $link, $cad2);
                                                                                    
                                                                                    while($row2 =  $mysql->f_obj($sql2)){
                                                                                        echo '<option value="'.$row2->id_status_solicitudes.'"> '.$row2->descripcion.'</option>';
                                                                                    }
                                                                                }

                                                echo'
                                                                            </select>
                                                                            </br>
                                                                            <label>Empresa:</label>
                                                                            <select class="form-control " form="form_editar1'.$row->id_detalle_pedidos.'" name="empresa" >';
                                                                                if(isset($row5->empresa)){
                                                                                    $cad3 = " SELECT t1.id_empresas
                                                                                            from tblc_empresas t1
                                                                                            where t1.descripcion= '$row5->empresa';";

                                                                                    $sql3 = $mysql->query( $link, $cad3);
                                                                                    
                                                                                    $row3 =  $mysql->f_obj($sql3);
                                                                                    echo '<option value="'.$row3->id_empresas.'"> '.$row5->empresa.'</option>';

                                                                                    $cad3 = " SELECT t1.id_empresas, t1.descripcion
                                                                                            from tblc_empresas t1
                                                                                            where t1.activo=1;";

                                                                                    $sql3 = $mysql->query( $link, $cad3);
                                                                                    
                                                                                    while($row3 =  $mysql->f_obj($sql3)){
                                                                                        echo '<option value="'.$row3->id_empresas.'"> '.$row3->descripcion.'</option>';
                                                                                    } 

                                                                                }
                                                                                  
                                                                                
                                                echo'
                                                                            </select>
                                                                            </br>
                                                                            
                                                                        </form>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" form="form_editar1'.$row->id_detalle_pedidos.'" class="btn btn-warning btn-sm" >
                                                                            Guardar cambios
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>';
                                                echo '</td>'; 
                                            }
                                                echo '<td >';
                                            
                                                    if($row->fecha_recibido != '0000-00-00 00:00:00')
                                                    echo $row->fecha_recibido;
                                                echo '</td>';

                                                echo '<td>'.$row->estatus_detalle.'</td>';

                                                echo '<td>'.$row->observaciones.'</td>';
                                                echo '<td class="warning">'.$row6->estatus_factura.'</td>';
                                                echo '<td class="warning">'.$row6->no_factura.'</td>';

                                            if($_SESSION['nivel_acceso'] != 1){
                                                echo '<td >';
                                                
                                                echo '  <button type="button" class="btn btn-warning btn-sm" name="editar'.$row->id_detalle_pedidos.'" data-toggle="modal" data-target="#myModal2'.$row->id_detalle_pedidos.'">
                                                            Editar
                                                        </button>';

                                                echo'        <!-- Modal -->
                                                        <div class="modal fade" id="myModal2'.$row->id_detalle_pedidos.'"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
                                                            <div class="modal-dialog modal-sm" role="document">
                                                                <div class="modal-content">
                                                            
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                        <h4 class="modal-title" id="myModalLabel">Actualizar estatus</h4>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <form id="form_editar2'.$row->id_detalle_pedidos.'" action="pedidos_data_update_estatus_factura.php"  method="post">
                                                                            <input type="hidden" name="id_pedidos" value="'.$id_pedido.'" form="form_editar2'.$row->id_detalle_pedidos.'">
                                                                            <input type="hidden" name="id_solicitud_pedidos" value="'.$row5->id_solicitud_pedidos.'" form="form_editar2'.$row->id_detalle_pedidos.'">
                                                                            

                                                                            <label>Estatus facturas:</label>
                                                                            <select class="form-control " form="form_editar2'.$row->id_detalle_pedidos.'" name="estatus_facturas" >';
                                                                                
                                                                            if(isset($row6->estatus_factura)){
                                                                                $cad4 = " SELECT t1.id_status_facturas
                                                                                            from tblc_status_facturas t1
                                                                                            where t1.descripcion='$row6->estatus_factura';";

                                                                                $sql4 = $mysql->query( $link, $cad4);
                                                                                $row4 =  $mysql->f_obj($sql4);

                                                                                echo '<option value="'.$row4->id_status_facturas.'"> '.$row6->estatus_factura.'</option>';

                                                                                $cad4 = " SELECT t1.id_status_facturas, t1.descripcion
                                                                                            from tblc_status_facturas t1
                                                                                            where t1.activo=1;";

                                                                                $sql4 = $mysql->query( $link, $cad4);
                                                                                
                                                                                while($row4 =  $mysql->f_obj($sql4)){
                                                                                    echo '<option value="'.$row4->id_status_facturas.'"> '.$row4->descripcion.'</option>';
                                                                                }
                                                                            }

                                                echo'
                                                                            </select>
                                                                            </br>
                                                                            <label>No. de Factura: </label>
                                                                            </br>
                                                                            <textarea class="form-control " form="form_editar2'.$row->id_detalle_pedidos.'"    name="no_factura" maxlength="190"></textarea>
                                                                        </form>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" form="form_editar2'.$row->id_detalle_pedidos.'" class="btn btn-warning btn-sm" >
                                                                            Guardar cambios
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>';
                                                echo '</td>'; 
                                            }         
                                            echo '</tr>';
                                        
                                    }
                                        echo '</tbody>';
                                    echo '</table>
                                        </div>';
                                     
                                    $mysql->close();
                                ?>

                            </div><!-- /. fin panel body -->

                            <div class="panel-footer"> 
                                
                            </div>
                        </div><!-- /. fin panel primary -->

                </div>
            </div> 
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->


</body>

<script type="text/javascript">
    function disableF5(e) { if ((e.which || e.keyCode) == 116) e.preventDefault(); };
        // To disable f5
    $(document).bind("keydown", disableF5);
        /* OR jQuery >= 1.7 */
    $(document).on("keydown", disableF5);  
</script>

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

    <script src="js/bootstrap.min.js"></script>

</html>

