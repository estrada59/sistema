<!DOCTYPE html>
<?php $_POST["pagina"]="pedidos_material"; ?>
<?php 
    session_start(); 

    if(!isset($_SESSION['id'])){
        header("Location: index.php");
    }

    include "../include/sesion.php";
    comprueba_url();
    //comprueba_empleado_activo();
    //cerrar_multiples_sesiones();
  
    
    if($_SESSION['nivel_acceso'] == 3){
    header("Location: index.php");
    exit();}

?>
<html lang="en">

<head>
    <?php 
        include "header.php"; 
        include_once 'include/mysql.php';
        include_once "include/funciones_consultas.php";
    ?>
    
    <script type="text/javascript" src="libs/ajax_pedidos_productos.js"></script>
    <!-- Alertity -->
    <link rel="stylesheet" href="libs/js/alertify/themes/alertify.core.css" />
    <link rel="stylesheet" href="libs/js/alertify/themes/alertify.bootstrap.css" id="toggleCSS" />
    <script src="libs/js/alertify/lib/alertify.min.js"></script>
</head>

<body >
    <div id="wrapper">
        <!-- Navigation -->
       <?php include_once "include/nav_session.php" ?>

        <div id="page-wrapper">
            <div class="container-fluid">
                
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-10">
                        <h3 class="page-header">
                            Agregar productos al pedido
                        </h3>                       
                    </div>
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-md-2 col-lg-2">
                        <form name="form_back_pedidos_lista_pedidos" action="pedidos_lista_pedidos.php" method="post">
                            
                            <a href="javascript:document.form_back_pedidos_lista_pedidos.submit()" class="btn btn-success  btn-block" role="button" ><span class="glyphicon glyphicon-arrow-left"></span> Atrás</a>                          
                        </form>
                    </div>
                </div>
                <br>
           
                <?PHP    
                    /*echo "<pre>";
                    print_r($_SESSION);
                    print_r($_POST);
                    echo "</pre>";*/
                ?>
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                  
                        <div class="panel panel-primary">
                            
                            <div class="panel-body">
                                                                       
                                <hr/>
                                <div class="col-md-12 col-lg-12" >
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>Cantidad:</label>
                                            <input id="txt_cantidad" name="txt_cantidad" type="number" min="1" class="form-control" placeholder="Ingrese cantidad" autocomplete="off" />
                                        </div>

                                        <div class="col-md-2">
                                             <label>Unidad de medida: </label>
                                                <select id="id_unidad_medida"  name="id_unidad_medida" class=" form-control">
                                                <?php                                               
                                                    echo '<option value = "0"> </option>';
                                                    $mysql =new mysql();
                                                    $link = $mysql->connect(); 

                                                    $sql = $mysql->query($link,"SELECT t1.id_unidad_medida, t1.descripcion
                                                                                FROM tblc_unidad_medida t1
                                                                                WHERE t1.activo = 1;");  

                                                    while ($row = $mysql->f_obj($sql)) {
                                                        echo '<option value ="'.$row->id_unidad_medida.'">'.$row->descripcion.'</option>';
                                                    }

                                                    $mysql->close();
                                                ?>
                                                   <!-- <option value = "0">NO APLICA VENTA</option> -->                                             
                                                </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Producto: </label>
                                                <select id="id_producto"  name="id_producto" class="form-control" >

                                                <?php 
                                                    echo '<option value = "0"> </option>';

                                                    $mysql = new mysql();
                                                    $link = $mysql->connect();

                                                    $sql = $mysql->query($link,"SELECT t1.id_productos, t1.descripcion
                                                                                FROM tblc_productos t1
                                                                                where t1.activo = 1;");  
                                    
                                                    while ($row = $mysql->f_obj($sql)) {
                                                        echo '<option value ="'.$row->id_productos.'">'.$row->descripcion.'</option>';
                                                    }

                                                    $mysql->close();
                                                ?>
                                                </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="actividad">Actividad:</label>
                                            <div class="input-group">
                                                <input id="txt_actividad" name="txt_actividad" type="number" min="0" class=" form-control" placeholder="Ingrese actividad mCi" autocomplete="off" />
                                                <div class="input-group-addon">mCi</div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div style="margin-top: 25px;">
                                                <button type="button" class="btn btn-success btn-agregar-producto">Agregar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                    
                                <hr>
                                

                                <div class="col-md-2 col-lg-2">                        
                                </div>
                                <div class="col-md-8 col-lg-8" id ="detalle">
                                    <br/></br>
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Productos agregados al pedido</h3>
                                        </div>
                                        <?php // print_r($_SESSION);?>
                                        <div class="panel-body detalle-producto">  
                                            
                                            <?php if( isset($_SESSION['detalle']) ){ ?>
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Cantidad</th>
                                                            <th>U. M.</th> 
                                                            <th>Descripción</th>                                                        
                                                            <th>Actividad</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                        foreach($_SESSION['detalle'] as $k => $detalle){ 
                                                            
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $detalle['cantidad'];?></td>
                                                            <td><?php echo $detalle['unidad_medida'];?></td>
                                                            <td><?php echo $detalle['producto'];?></td>
                                                            <td><?php echo $detalle['actividad'].'mCi';?></td>
                                                            <td><button type="button" class="btn btn-sm btn-danger eliminar-producto" id="<?php echo $detalle['id'];?>">Eliminar</button></td>
                                                        </tr>

                                                        <?php }?>

                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                            <?php }else{?>
                                            <div class="panel-body"> No hay productos agregados</div>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-lg-4">
                                    
                                </div>    
                            </div><!-- /. fin panel body -->

                            <div class="panel-footer">
                                <form role="form"  id="pedido" method="post" action="pedidos_confirmar_pedido.php"> 
                                    
                                    <div class="row" id="btn_continuar"> 
                                        <div class="col-md-12 col-lg-12">
                                            <button type="submit" form="pedido" name="sub_continuar" class="btn btn-success  btn-block">
                                                <span class="glyphicon " aria-hidden="true"></span>
                                                Continuar
                                            </button>
                                           <!--<a href="javascript:document.tipo_venta.submit()" class="btn btn-success btn-lg btn-block" role="button" >Continuar</a> -->
                                        </div> 
                                    </div>

                                </form>
                               
                            </div>
                        </div><!-- /. fin panel primary -->
                    </div>
                </div> 
            <!-- /.container-fluid -->
            </div>
        <!-- /#page-wrapper -->
        </div>
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


