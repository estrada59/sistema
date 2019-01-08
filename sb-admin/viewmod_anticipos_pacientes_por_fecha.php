<?php 
//echo $nombre.' '.$apepat.' '.$apemat;
//
?>
<!DOCTYPE html>
<?php $_POST["pagina"]="form"; 
	if(!isset($_POST['idpaciente'])){
		header("Location: index.php");
	}
?>
<html lang="es">

<head>
    <?php 	include "header.php";
    		session_start(); 
			include "../include/sesion.php";
			comprueba_url();
            if(!isset($_POST)){
                header('Location: index.php');
            }
 	?>
</head>

<body>

    <div id="wrapper">
        <!-- Navigation -->
       <?php
            if($_SESSION['nivel_acceso'] == 4){
                include "nav_op.php";  
            } 
            if($_SESSION['nivel_acceso'] == 3){
                include "nav.php";  
            }
            if($_SESSION['nivel_acceso'] == 1){
                include "nav_priv.php"; 
            }
       ?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <h1 class="page-header"> <small> ¿Están bien los datos?</small></h1>
                    </div>
                   <div class="col-lg-3">
                            <!-- regresar a la página anterior-->
                            <form role="form" id="anticipos" method="post" action="viewmod_ver_anticipos_por_fecha.php" accept-charset="UTF-8">
                                <?php 
                                    echo '
                                        <input type="hidden" form="anticipos" name="idpaciente"      value="'.$_POST['idpaciente'].'"/>
                                        <input type="hidden" form="anticipos" name="fecha_estudios"   value="'.$_POST['fecha_estudio'].'"/>
                                        <input type="hidden" form="anticipos" name="idanticipo"      value="'.$_POST['idanticipo'].'"/>
                                        <input type="hidden" form="anticipos" name="nombre_paciente" value="'.$_POST['paciente'].'"/>
                                        <input type="hidden" form="anticipos" name="estudio"         value="'.$_POST['nombre_estudio'].'"/>
                                        <input type="hidden" form="anticipos" name="precio"          value="'.$_POST['precio_estudio'].'"/>
                                        <input type="hidden" form="anticipos" name="debe"            value="'.$_POST['debe'].'"/>
                                        <input type="hidden" form="anticipos" name="hora"            value="'.$_POST['hora'].'"/>
                                        <input type="hidden" form="anticipos" name="institucion"     value="'.$_POST['institucion'].'"/>
                                    ';
                                ?>         
                            </form>
    
                            <button type="submit" form="anticipos" class="btn btn-success btn-lg btn-block"  >
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                Atrás
                            </button> 
                    </div>
                </div>
                <br>
                <!-- /.row -->
                
                <div class="table-responsive">
                <table class="table table-striped">
                    <tbody>

                <?php 
                //print_r($_POST);
                    $idpaciente         = $_POST['idpaciente'];
                    $fecha_estudio      = $_POST['fecha_estudio'];
                    $idanticipo         = $_POST['idanticipo'];

                    $nombre             = $_POST['paciente'];
                    $estudio            = $_POST['nombre_estudio'];
                    $precio_estudio     = $_POST['precio_estudio'];
                    $hora               = $_POST['hora'];
                    $debe               = $_POST['debe'];
                    $institucion        = $_POST['institucion'];
                    $fecha_actual       = $_POST['fecha_actual'];
                    $fecha_actual_print = $_POST['fecha_actual_print'];
                    
                    $tipo_pago          = $_POST['tipo_pago'];
                    $monto_anticipo     = $_POST['monto'];
                    $no_recibo          = $_POST['no_recibo'];
                    $factura            = $_POST['factura'];

                    $descuento          = $_POST['descuento'];
                    $existe_pac_desc    = $_POST['existe_pac_descuento']; // 0 ya se le dio descuento o ya hizo un primer pago
                                                                          // 1 si nunca se ha hecho anticipo y es su primer pago
                    $porciento_des      = $_POST['porciento_descuento'];

                    $precio_estudio_f = '$ '.number_format($precio_estudio,2);
                    $monto_anticipo_f = '$ '.number_format($monto_anticipo,2);
                    

                echo'   <tr class="info"> 
                            <td >Nombre del paciente</td>
                            <td>'.$nombre.'</td>
                        </tr>
                        <tr> 
                            <td>Estudio</td>
                            <td>'.$estudio.'</td>
                        </tr>
                        <tr> 
                            <td>Precio del estudio</td>
                            <td>'.$precio_estudio_f.'</td>
                        </tr>';

                        
                        if($existe_pac_desc && $porciento_des == 'orden'){ //primer pago, y no tiene descuentos

                            if($descuento > 0){ //primera vez
                            
                                $cant =( $precio_estudio * ($descuento /100) );
                                $debe = $debe-$cant;
                                $debe_f = '$ '.number_format($debe,2);

                            echo '<tr> 
                                    <td>Descuento</td>
                                    <td>'.$descuento.' %</td>
                                    </tr>';
                            echo '
                                <tr class="danger"> 
                                    <td>Debe (con descuento incluido)</td>
                                    <td>'.$debe_f.'</td>
                                </tr>';
                            }

                        }
                              
                        if($descuento == 0 && is_numeric($porciento_des)){ 
                                
                                    echo 'entro';
                                    echo '<tr> 
                                        <td>Descuento</td>
                                        <td>'.$porciento_des.' %</td>
                                        </tr>';
                                    $debe = $_POST['debe'];
                                    $debe_f = '$ '.number_format($debe,2);
                                    echo '
                                        <tr> 
                                            <td>Debe</td>
                                            <td>'.$debe_f.'</td>
                                        </tr>';
                        }
                        if($porciento_des == 'tiene_anticipo_previo'){// ya no es primer pago
                                    $debe = $_POST['debe'];
                                    $debe_f = '$ '.number_format($debe,2);

                                    echo '<tr> 
                                            <td>Descuento</td>
                                            <td>'.$descuento.' %</td>
                                        </tr>';
                                    echo'
                                        <tr> 
                                            <td>Debe</td>
                                            <td>'.$debe_f.'</td>
                                        </tr>';
                        }
                        

                        if($descuento == 100){ 
                            //actualizado el 22-01-2016
                            //sirve para que cuando hagan una donacion de estudio 100% descuento
                            //aunque escriban un monto en anticipo éste sea igual a $ 0.00 
                            $monto_anticipo_f = 0.00;
                            $monto_anticipo = 0.00;
                            $monto_anticipo_f = '$ '.number_format($monto_anticipo_f,2);
                        }
                        
                echo'                      
                        <tr> 
                            <td>Fecha del anticipo</td>
                            <td>'.$fecha_actual_print.'</td>
                        </tr> 
                        <tr> 
                            <td>Tipo de pago</td>
                            <td>'.$tipo_pago.'</td>
                        </tr> 
                        <tr> 
                            <td>Monto recibido</td>
                            <td>'.$monto_anticipo_f.'</td>
                        </tr>';
                        //$mysql->close();
                ?>
                    </tbody>
                </table>
                </div>
                <?php
                     /* print "<pre>";
                        print_r($_POST);
                        print_r($_SESSION);
                        print "</pre>";*/
                ?>
                <div class="row">
                    <div class="col-md-12 col-lg-12">  
                        <div class="panel panel-primary">        
                            <div class="panel-body">
                                <form role="form" id="imprimir" method="post" action="view_imprimir_recibo_anticipo.php" target="_blank" onsubmit="myFunction()" accept-charset="UTF-8">
                                <?php 
                                    echo '<input type="hidden" name="nombre_paciente"   form="imprimir" value="'.$nombre.'">';
                                    echo '<input type="hidden" name="estudio"           form="imprimir" value="'.$estudio.'">';
                                    echo '<input type="hidden" name="fecha_actual"      form="imprimir" value="'.$fecha_actual.'">';
                                    echo '<input type="hidden" name="fecha_estudio"     form="imprimir" value="'.$fecha_estudio.'"/>';
                                    echo '<input type="hidden" name="idpaciente"        form="imprimir" value="'.$idpaciente.'"/>';
                                    echo '<input type="hidden" name="idanticipo"        form="imprimir" value="'.$idanticipo.'"/>';
                                    echo '<input type="hidden" name="precio_estudio"    form="imprimir" value="'.$precio_estudio.'"/>';
                                    echo '<input type="hidden" name="hora"              form="imprimir" value="'.$hora.'"/>';
                                    echo '<input type="hidden" name="debe"              form="imprimir" value="'.$debe.'"/>';
                                    echo '<input type="hidden" name="institucion"       form="imprimir" value="'.$institucion.'"/>';
                                    echo '<input type="hidden" name="tipo_pago"         form="imprimir" value="'.$tipo_pago.'"/>';
                                    echo '<input type="hidden" name="monto_anticipo"    form="imprimir" value="'.$monto_anticipo.'"/>';
                                    echo '<input type="hidden" name="no_recibo"         form="imprimir" value="'.$no_recibo.'"/>';
                                    echo '<input type="hidden" name="factura"           form="imprimir" value="'.$factura.'"/>';
                                    echo '<input type="hidden" name="descuento"         form="imprimir" value="'.$descuento.'"/>';
                                ?>
                                </form>
                               
                                <div class="col-lg-12">
                                    <button type="submit" id="myBtn" class="btn btn-danger btn-lg btn-block"  form="imprimir">
                                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                                        IMPRIMIR
                                    </button> 
                                </div>
                            </div>
                        </div>           
                    </div>
                </div>
    </div>  <!--    fin col-lg-12   -->

            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

    <script>
     
    function myFunction() {
       // location.href ="viewmod_ver_anticipos_por_fecha.php";
       // hacer un redireccionamiento HTTP con Javascript
      // location.href =history.back();
       //document.getElementById("imprimir").reset();
       //window.location.href = "http://www.bufa.es";
       document.getElementById("myBtn").disabled = true;
    }
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
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
 
</body>

</html>
