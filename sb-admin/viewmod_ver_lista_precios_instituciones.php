<!DOCTYPE html>
<?php $_POST["pagina"]="ver_lista_precios";  
    session_start(); 
    include "../include/sesion.php";
    comprueba_url();
    if(!isset($_POST['institucion'])){
        header('Location: controlador_ver_lista_precios.php');
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
            include_once 'include/nav_session.php';
       ?>
        <div id="page-wrapper">
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                             Lista de precios 
                             <?php
                                include_once 'include/funciones_consultas.php';
                                echo  pasarMayusculas($_POST['institucion']); ?> 
                             <small></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                                Lista de precios 
                                <?php 
                                    
                                    echo  $_POST['institucion'];
                                ?>
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
                    <div class="col-lg-3">
                    </div>
                    <div class="col-lg-3">
                    </div>
                    <div class="col-lg-3">
                       <?php 
                            if($_SESSION['nivel_acceso'] < 3){

                                echo'
                                <form role="form" id="aumentar_precio" method="post" action="viewmod_aumentar_precio.php">
                                    <input type="hidden" form="aumentar_precio" name="institucion" value="'. $_POST['institucion'].'"/>
                                </form>
                                
                                <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="aumentar_precio">
                                    <span class="glyphicon glyphicon-arrow-up " aria-hidden="true"></span>
                                    <span class="glyphicon glyphicon-arrow-up glyphicon-usd" aria-hidden="true"></span>
                                    AUMENTAR PRECIOS
                                </button>';
                            }
                            ?> 
                        
                    </div>
                </div>
                <!-- /.row -->

                <?php 
                    include_once "include/mysql.php"; 
                    $institucion = str_replace(" ", "_", $_POST['institucion']);
                    
                    $mysql =new mysql();
                    $link = $mysql->connect(); 
                    
                    if($_SESSION['nivel_acceso'] < 3){
                        $sql = $mysql->query($link,"SELECT  idgammagramas,
                                                        concat(tipo, ' ',nombre) AS estudio,
                                                        $institucion
                                                FROM estudio 
                                                WHERE idgammagramas=idgammagramas  ORDER BY idgammagramas");
                    }
                    if($_SESSION['nivel_acceso'] >= 3){
                        $sql = $mysql->query($link,"SELECT  idgammagramas,
                                                        concat(tipo, ' ',nombre) AS estudio,
                                                        $institucion
                                                FROM estudio 
                                                WHERE idgammagramas=idgammagramas and $institucion != 0.00 ORDER BY idgammagramas");
                    }
                    
                    //echo 'filas afectadas: (Encontradas) '.$mysql->affect_row().'';
                    
                    if($_SESSION['nivel_acceso'] < 3){
                        


                        echo'
                        <form role="form" id="imprimir_lista_precios" method="post" action="view_imprimir_lista_precios_instituciones.php" target="_blank">
                            <input type="hidden" form="imprimir_lista_precios" name="imprimir_precios" value="1"/>
                            <input type="hidden" form="imprimir_lista_precios" name="institucion" value="'.$institucion.'"/>
                        </form>

                        <button type="submit" class="btn btn-primary btn-lg btn-block"  aria-label="Left Align" form="imprimir_lista_precios">
                                <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                                IMPRIMIR LISTA
                        </button>
                        <br>
                        ';
                    }
                    
                    $num_row =$mysql->f_num($sql);
                    
                echo '
                <div class=" table-responsive">
                <table class="table table-bordered table-hover table-striped" id="myclass">
                    <thead>
                        <tr>
                            <!--<th data-field="id">Seleccionar</th>-->
                            <!--<th data-field="id">Item ID</th>-->
                            <th data-field="numero" width="15">No.</th>
                            <th data-field="estudio" width="70">Nombre del estudio</th>
                            <th data-field="precio" width="15">Precio</th>';
                            
                            if($_SESSION['nivel_acceso'] < 3){

                                echo'   <th data-field="editar" width="15">Editar</th>';
                               // echo'   <th data-field="eliminar" width="15">Eliminar</th>';
                            }
                
                echo '      </tr>
                    </thead>
                    <tbody>
                        ';
                          
                    while ($row = $mysql->f_row($sql)) {

                        $row[2]=number_format($row[2],2);
                        echo '<tr>'; 
                        
                        echo '<!--<td align="center"><input type="checkbox" value="'.$row[0].'"> </td>-->';
                        // echo '<td>'.$row[0].'</td>';
                        echo '<td>'.$row[0].'</td>';
                        echo '<td>'.$row[1].'</td>';
                        echo '<td>$ '.$row[2].'</td>';
                       
                        if($_SESSION['nivel_acceso'] < 3){
                       
                        echo '  <td>
                                    <form role="form" id="edicion'.$row[0].'" method="post" action="controlador_editar_lista_precios_instituciones.php">
                                        <input type="hidden" form="edicion'.$row[0].'" name="idgammagramas" value="'.$row[0].'"/>
                                        <input type="hidden" form="edicion'.$row[0].'" name="institucion" value="'.$institucion.'"/>
                                        <button type="submit" form="edicion'.$row[0].'" name="editar" class="btn btn-warning btn-default btn-block"  ">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                            Editar
                                        </button>
                                    </form>
                                </td>';
                      /*  echo '
                            <td><form role="form" id="eliminar'.$row[0].'" method="post" action="viewmod_eliminar_estudio_lista_precios.php">
                                <input type="hidden" form="eliminar'.$row[0].'" name="idgammagramas" value="'.$row[0].'"/>
                                <input type="hidden" form="eliminar'.$row[0].'" name="estudio" value="'.$row[1].'"/>
                                <input type="hidden" form="eliminar'.$row[0].'" name="precio" value="'.$row[2].'"/>
                                <input type="submit" form="eliminar'.$row[0].'" name="eliminar" value="Eliminar" class="btn btn-danger btn-default btn-block" />
                            </form></td>';*/
                        echo '</tr>'; 
                        }
                       
                    }

                    $mysql->close();
                echo'</tbody>
                </table>
                </div>
                ';

                ?>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#myclass').DataTable( {
                            "paging":   false,
                            "ordering": true,
                            "info":     false
                        } );
                    } );
                </script>
                



                 
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