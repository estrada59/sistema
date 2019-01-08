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
                            Agregar nuevo producto
                        </h3>                       
                    </div>
                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-md-2 col-lg-2">
                                <?php //print_r($_POST);?>
                                <form name="form_back_pedidos_lista_pedidos" action="pedidos_lista_pedidos.php" method="post">
                                    <a href="javascript:document.form_back_pedidos_lista_pedidos.submit()" class="btn btn-success btn-regresar btn-block" role="button" ><span class="glyphicon glyphicon-arrow-left"></span> Atrás</a>                          
                                </form>

                            </div>

                            <div class="col-md-7 col-lg-7">
                            </div>

                            <div class="col-md-3 col-lg-3">
                                <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target=".modal-agregar-producto">Agregar nuevo producto</button>
                            </div>
                        </div>                  
                    </div>
                </div>
                <br/>
                
                <!-- tabla busqueda de pedidos -->
                <div class="row">
                    <div class="col-lg-12">
                       
                        <div class="table-responsive">
                            <table id="pedidos_grid" class="table table-condensed table-hover table-striped" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th data-column-id="id_productos" data-type="numeric">#Producto</th>
                                        <th data-column-id="descripcion">Descripcion</th>
                                        <!--<th data-column-id="commands" data-formatter="commands" data-sortable="false">Seleccionar</th>-->
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /tabla busqueda de pedidos -->   


                <!-- Small modal -->
                <div class="modal fade modal-agregar-producto" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <form id="agregar_producto_catalogo" action="pedidos_data_insert_productos.php" method="post">

                            </form>
                            <div class="modal-body">
                                <input class="form-control" maxlength="90" form="agregar_producto_catalogo" name="txt_descripcion" type="text" placeholder="ingrese nombre del producto" onkeyup="mayus(this);">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" form="agregar_producto_catalogo" class="btn btn-warning btn-sm" >
                                    Guardar cambios
                                </button>
                            </div>
                        </div>
                    </div>
                </div>   
               
            </div> 
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->


</body>

<script type="text/javascript">
    function mayus(e) {
        e.value = e.value.toUpperCase();
    }
</script>
<script type="text/javascript">
    $( document ).ready(function() {
        var grid = $("#pedidos_grid").bootgrid({
        ajax: true,
        post: function ()
        {
            return {
                id: "b0df282a-0d67-40e5-8558-c9e93b7befed"
            };
        },
        url: "pedidos_data_productos.php",
        formatters: {
            "commands": function(column, row)
            {
                return "<?php 
                            if($_SESSION["nivel_acceso"] != 1 ){

                                echo '<button type=\"button\" class=\"btn btn-default command-select\" data-row-id=\"" + row.id_pedidos + "\"><span class=\"glyphicon glyphicon-plus\"></span></button>'; 
                            }?>"
                                  
                <?php 
                    if($_SESSION["nivel_acceso"] == 1 ){
                        echo'+"<button type=\"button\" class=\"btn  btn-default command-delete\" data-row-id=\"" + row.id_pedidos + "\"><span class=\"glyphicon glyphicon-pencil\"></span></button>";';
                    }
                ?>
            }
        }
    }).on("loaded.rs.jquery.bootgrid", function()
    {  
        /* Executes after data is loaded and rendered */
        grid.find(".command-select").on("click", function(e)
        {
           post('pedidos_ver_detalle_pedido.php', {id_pedidos: $(this).data("row-id") });
            //alert("You pressed edit on row: " + $(this).data("row-id"));
        }).end().find(".command-delete").on("click", function(e)
            {
                //alert("You pressed delete on row: " + $(this).data("row-id"));
                post("pedidos_ver_detalle_pedido_admin.php", {id_pedidos: $(this).data("row-id") });  
                      
            });
        });
    });

    function post(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
         }
    }

    document.body.appendChild(form);
    form.submit();
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

</html>
