<!DOCTYPE html>
<?php $_POST["pagina"]="form"; ?>
<html lang="en">

<head>
    <?php include "header.php"; 

            session_start(); 
            include "../include/sesion.php";
            comprueba_url();

             if(!isset($_POST["idgammagramas"])){
                    header('Location: index.php');
            }
    ?>
     <!-- css validation form -->
    <link rel="stylesheet" href="css/style_validation_form.css">
    <!-- jquery validation form -->
    <script src="js/livevalidation_standalone.js"></script>
</head>

<body>
    <div id="wrapper">
        <!-- Navigation -->
       <?php 
            if($_SESSION['nivel_acceso'] == 3){
                include "nav.php";  
            }
            if($_SESSION['nivel_acceso'] == 2){
                include "nav_contador.php";  
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
                            Modificar estudio de 
                            <?php 
                                include_once 'include/funciones_consultas.php';
                                
                                $instituto = str_replace("_", " ", $_POST['institucion']);
                                $instituto = pasarMayusculas($instituto);
                                echo $instituto;
                            ?>
                             <small></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Modificar estudio
                            </li>
                        </ol>
                    </div>
                    <div class="col-lg-3">
                        <form role="form" id="atras" method="post" action="viewmod_ver_lista_precios_instituciones.php">
                        <?php echo '<input type="hidden" name="institucion" value="'.$instituto.'">'
                        ?>
                        </form>
                        <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="atras">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            Atrás
                        </button>                            
                    </div>
                </div>
                <!-- /.row -->
                <br>

                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <!--        1er columnoa de captura         -->
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"> Datos del estudio </h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                                <form role="form" id="nuevo_estudio" method="post" action="viewmod_editar_lista_precios_instituciones.php">
                                <?php 
                                    include "include/mysql.php";
                                    

                                    $id = $_POST["idgammagramas"];
                                    $institucion = $_POST['institucion'];

                                   // echo 'este es id'.$id;
                                    $mysql =new mysql();
                                    $link = $mysql->connect(); 

                                    $sql = $mysql->query($link,"SELECT  idgammagramas,
                                                                        tipo,
                                                                        nombre,
                                                                        $institucion
                                                                    FROM estudio 
                                                                    WHERE idgammagramas= $id");
 
                                    //echo 'filas afectadas: (Encontradas) '.$mysql->affect_row().'';
                                    // echo '"SELECT * FROM estudio WHERE idgammagramas="'.$id.'""';
                                    $row = $mysql->f_row($sql);

                                    echo'
                                    <div class="form-group">
                                        <label for="tipo_estudio">Tipo de estudio</label>
                                            
                                        <select class="form-control" form="nuevo_estudio" name="tipo_estudio" disabled>
                                            <option value="'.$row[1].'">Tipo actual: "'.$row[1].'"</option>
                                            <option>GAMMAGRAMA</option>
                                            <option>TRATAMIENTO</option>
                                            <option> </option>
                                        </select>
                                    </div> 
                                    <div class="form-group">
                                        <label for="nombre">Nombre del estudio</label>
                                        <input type="text" class="form-control" form="nuevo_estudio"  name="nombre" id="nombre" placeholder="Nombre del estudio" value="'.$row[2].'" >
                                    </div>  


                                    <div class="form-group">
                                        <label for="nombre">Precio del estudio</label>
                                        <input type="text" class="form-control" form="nuevo_estudio" name="precio" id="precio" placeholder="precio" value="'.$row[3].'">
                                    </div> 

                                    <div class="form-group">
                                        <input type="hidden" form="nuevo_estudio" name="idgammagramas" value="'.$row[0].'"/>
                                        <input type="hidden" form="nuevo_estudio" name="institucion" value="'.$institucion.'"/>
                                        <input type="hidden" form="nuevo_estudio" name="type_study" value="'.$row[1].'"/>
                                        <input type="hidden" form="nuevo_estudio" name="name" value="'.$row[2].'"/>
                                        
                                        <input type="hidden" form="nuevo_estudio" name="price" value="'.$row[3].'"/>
                                    </div>
                                    ';
                                ?>
                                </form>

                            </div><!-- /. fin panel body -->

                            <div class="panel-footer">
                                <!--<input id="submit" name="submit" class="btn btn-success btn-lg btn-block" form="nuevo_estudio" type="submit" value="Aceptar" class="btn btn-primary">-->

                                <button id="submit" name="submit" class="btn btn-danger btn-lg btn-block" form="nuevo_estudio" type="submit" >
                                    <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                                    ACEPTAR
                                </button>
                            </div>
                        </div><!-- /. fin panel primary -->
                    </div>
            </div> 
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->



    <!-- Validación de formularios -->
    <script type="text/javascript">
        var nombre = new LiveValidation('nombre', { validMessage: 'OK!', wait: 500});
        nombre.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        nombre.add(Validate.Length, {minimum: 5 } );
    </script> 
    <script type="text/javascript">
        var precio = new LiveValidation('precio', { validMessage: 'OK!', wait: 500});
        precio.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        precio.add(Validate.Length, {minimum: 3 } );
        precio.add( Validate.Numericality );
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
