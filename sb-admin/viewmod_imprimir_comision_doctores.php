<?php
include "include/mysql.php";

//captura datos del formulario 
function ver(){
//echo $nombre.' '.$tipo_estudio.' '.$precio;

$mysql =new mysql();
$link = $mysql->connect(); 

$sql = $mysql->query($link,"SELECT * FROM 'estudio'");
 
echo 'filas afectadas:  '.$mysql->affect_row().'';
return $sql;	
//$mysql->close();
//header('Location: agregar_nuevo_estudio.php');
}
?>
<!DOCTYPE html>
<?php $_POST["pagina"]="form"; 
    session_start(); 
    include "../include/sesion.php";
    comprueba_url();

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
       ?>
        <div id="page-wrapper">
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                             Lista de precios PARTICULAR<small></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                                Lista de precios PARTICULAR
                            </li>
                        </ol>
                    </div>
                    <div class="col-lg-3">
                            <form role="form" id="ver_lista" method="post" action="controlador_ver_lista_precios.php" accept-charset="UTF-8">
                            </form>
                            <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                Atrás
                            </button>                            
                    </div>
                </div>
                <!-- /.row -->

                <?php  
                    $mysql =new mysql();
                    $link = $mysql->connect(); 
                    
                    $sql = $mysql->query($link,"SELECT  idgammagramas,
                                                        concat(tipo, ' ',nombre) AS estudio,
                                                        precio
                                                FROM estudio 
                                                WHERE 1 ORDER BY idgammagramas");
 
                    echo 'filas afectadas: (Encontradas) '.$mysql->affect_row().'';
                    
                    if($_SESSION['nivel_acceso'] < 2){
                        echo'
                        <form role="form" id="imprimir_lista_precios" method="post" action="view_imprimir_lista_precios.php" target="_blank">
                            <input type="hidden" form="imprimir_lista_precios" name="imprimir_precios" value="1"/>
                        </form>

                        <button type="submit" class="btn btn-primary btn-lg btn-block"  aria-label="Left Align" form="imprimir_lista_precios">
                                <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                                IMPRIMIR
                        </button>
                        ';
                    }
                    
                    $num_row =$mysql->f_num($sql);
                    
                echo '
                <div class=" table-responsive">
                <table class="table table-bordered table-hover table-striped" >
                    <thead>
                        <tr>
                            <!--<th data-field="id">Seleccionar</th>-->
                            <!--<th data-field="id">Item ID</th>-->
                            <th data-field="numero" width="15">No.</th>
                            <th data-field="estudio" width="70">Nombre del estudio</th>
                            <th data-field="precio" width="15">Precio</th>';
                            
                            if($_SESSION['nivel_acceso'] < 2){

                                echo'   <th data-field="editar" width="15">Editar</th>
                                        <th data-field="eliminar" width="15">Eliminar</th>';
                            }
                
                echo '      </tr>
                    </thead>
                    <tbody>
                        ';
                          
                    while ($row = $mysql->f_row($sql)) {

                        $row[2]=number_format($row[2],2);
                        echo '<tr>'; 
                        echo '<fielset >';
                        echo '<!--<td align="center"><input type="checkbox" value="'.$row[0].'"> </td>-->';
                        // echo '<td>'.$row[0].'</td>';
                        echo '<td>'.$row[0].'</td>';
                        echo '<td>'.$row[1].'</td>';
                        echo '<td>$ '.$row[2].'</td>';
                       
                        if($_SESSION['nivel_acceso'] < 2){
                       
                        echo '  <td>
                                    <form role="form" id="edicion'.$row[0].'" method="post" action="controlador_editar_lista_precios.php">
                                        <input type="hidden" form="edicion'.$row[0].'" name="idgammagramas" value="'.$row[0].'"/>
                                        <button type="submit" form="edicion'.$row[0].'" name="editar" class="btn btn-warning btn-default btn-block"  ">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                            Editar
                                        </button>
                                    </form>
                                </td>';
                        echo '

                            <td><form role="form" id="eliminar'.$row[0].'" method="post" action="viewmod_eliminar_estudio_lista_precios.php">
                                <input type="hidden" form="eliminar'.$row[0].'" name="idgammagramas" value="'.$row[0].'"/>
                                <input type="hidden" form="eliminar'.$row[0].'" name="estudio" value="'.$row[1].'"/>
                                <input type="hidden" form="eliminar'.$row[0].'" name="precio" value="'.$row[2].'"/>
                                <input type="submit" form="eliminar'.$row[0].'" name="eliminar" value="Eliminar" class="btn btn-danger btn-default btn-block" />
                            </form></td>';
                        echo '</tr>'; 
                        }
                       echo '</fielset>';
                    }

                    $mysql->close();
                echo'</tbody>
                </table>
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