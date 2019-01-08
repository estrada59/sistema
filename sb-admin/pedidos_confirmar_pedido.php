<!DOCTYPE html>

<?php $_POST["pagina"]="pedidos_material"; 
    session_start();
    include "../include/sesion.php";
    comprueba_url();
    //comprueba_empleado_activo();
    //cerrar_multiples_sesiones(); 

    /*if(!isset($_SESSION['total_detalle_prod']) and !isset($_SESSION['detalle'])){
        $_SESSION['total_detalle_prod'] = 0;
        $_SESSION['detalle'] = array();    
    }else{
        $_SESSION['total_detalle_prod'] = 0;
        $_SESSION['detalle'] = array();   
    }*/
    if(!isset($_SESSION['id'])){
        header("Location: index.php");
    }


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
    <!-- ESTE ARCHIVO ES CLAVE PARA LAS CONSULTAS Y CONTROLAR TODO EL PLUGIN-->
    <script type="text/javascript" src="libs/ajax_pedidos_productos.js"></script>
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
                            Confirmar pedido 
                        </h3>                       
                    </div>
                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-md-2 col-lg-2">
                            <?php //print_r($_POST);?>
                                <form id = "form_back_pedidos_confirmar_pedido" name="form_back_pedidos_confirmar_pedido" action="pedidos_agregar_pedido.php" method="post"> 
									<button type="submit" form="form_back_pedidos_confirmar_pedido" name="atras" id="atras"  class="btn btn-success  btn-block btn-retroceso"  >
										<span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
										Atrás
                                    </button>
                                </form>
                            </div>
                        </div>
                        <br/>
                        
                        </br>
                        <?php
                        /*echo'<pre>'; print_r($_SESSION); echo'</pre>';*/?>

                       
                        <div class="panel panel-danger">
                            
                            <div class="panel-body">
                                <?php
                                    include_once "include/funciones_consultas.php";
                                ?>
                                
                                <div class="col-md-12 col-lg-12 " id ="detalle_venta">
                                   
                                    <br/></br>
                                    
                                    <div class="panel panel-danger" >
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Detalle del pedido.</h3>
                                        </div>

                                        <div class="panel-body detalle-producto">
                                            <?php if(count($_SESSION['detalle'])>0){ 
                                                    $var='';?>
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
                                                            <td><?php echo $detalle['unidad_medida']; ?></td>
                                                            <td><?php echo $detalle['producto']; ?></td>
                                                            <td><?php echo $detalle['actividad'].'mCi'; ?></td>

                                                        </tr>
                                                        <?php }?>
                                                    </tbody>
                                                </table>


                                            <?php /*echo '<pre>';print_r($_SESSION); echo '</pre>'; echo '<pre>';print_r($_POST); echo '</pre>';*/  }
                                                  else{ $var='disabled';?>
                                                  
                                             <div class="panel-body"> No hay productos agregados</div>
                                                
                                                <!--COMPROBACIONES DEL CARRITO DE PEDIDOS-->
                                                <div class="modal fade" id="myModal" role="dialog">
                                                    <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title bg-ganger">¡Advertencia!</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                        <p>Tiene que seleccionar como mínimo un producto</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                        <button  type="submit" form="form_back_pedidos_confirmar_pedido" class="btn btn-primary">Aceptar</button>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                    </div><!-- /.modal-dialog -->
                                                </div><!-- /.modal -->
                                                <script>
                                                    $(document).ready(function()
                                                    {
                                                        $("#myModal").modal("show");
                                                        
                                                    });
                                                </script>
                                                <!-- FIN COMPROBACIONES DEL CARRITO DE PEDIDOS-->
                                            <?php }?>
                                            
                                            


                                        </div>
                                    </div>
                                </div>

                             
                            </div><!-- /. fin panel body -->

                            
                            <div class="panel-footer"> 
                                <div class="row"> 
                                 
                                    <div class="col-md-12">

                                            <div style="margin-top: 25px;">
                                                <button type="button" id="concluir" name="concluir" onclick = "this.disabled = true" class="btn btn-danger btn-block btn-insertar-producto" <?php echo $var ?> >Concluir pedido</button>
                                            </div>


                                            <form id = "form_back_pedidos_agregar_pedidos" name="form_back_pedidos_agregar_pedidos" action="pedidos_lista_pedidos.php" method="post"> 
                                                <button type="submit" form="form_back_pedidos_agregar_pedidos" name="finalizar" id="finalizar"  class="btn btn-success  btn-block btn-finalizar" >
                                                    <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
                                                    FINALIZAR
                                                </button>
                                            </form>
                                    </div>   
                                    <?php
                                 /*       echo '<pre>';
                                    print_r($_SESSION);
                                    echo $_SESSION['total_detalle_prod'];
                                    print_r($_POST);                                
                                    echo '</pre>';*/

                            
                                    ?>
                                </div>
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
        // To re-enable f5
        /* jQuery < 1.7 */
        /*$(document).unbind("keydown", disableF5);
            $(document).off("keydown", disableF5);*/
            document.getElementById("finalizar").style.display = "none";
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

</html>
