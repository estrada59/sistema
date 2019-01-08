<!DOCTYPE html>
<?php $_POST["pagina"]="pedidos_material"; ?>
<html lang="en">

<head>
    <?php 	include "header.php";
    		session_start(); 
			include "include/sesion.php";
            comprueba_url();

            if(!isset($_SESSION['detalle'])){
                $_SESSION['detalle'] = array(); 
            }else{
                $_SESSION['detalle'] = array();   
            }     
 	?>

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
                            Buscar pedidos<small></small>
                        </h3>
                    </div>
                </div>
                <!-- /.row -->
                <?php 
                   /* print_r($_SESSION);
                    print_r($_POST);*/
                ?>

                <!-- lista de botones -->
                <div class="row">
                    <div class="col-md-2 col-lg-2">
                    <?php
                        if($_SESSION['nivel_acceso']<=2){
                            echo'<form name="form_send_agregar_empresa" action="pedidos_agregar_empresa.php" method="post">     ';
                            echo'    <a href="javascript:document.form_send_agregar_empresa.submit()" class="btn btn-primary  btn-block" role="button" ><span class="glyphicon glyphicon-plus"></span> Agregar empresa</a>';
                            echo'</form>';

                        }
                    ?>
                    </div>
                    <div class="col-md-2 col-lg-2">
                    <?php   
                        if($_SESSION['nivel_acceso']<=2){
                            echo'<form name="form_send_agregar_pedido" action="pedidos_agregar_producto.php" method="post">';
                            echo'    <a href="javascript:document.form_send_agregar_pedido.submit()" class="btn btn-primary  btn-block" role="button" ><span class="glyphicon glyphicon-plus"></span> Agregar producto</a>';
                            echo'</form>';
                        }
                    ?>
                    </div>
                    <div class="col-md-3 col-lg-3">
                         
                    </div>
                </div>
                <!-- fin lista de botones -->

              

                <!-- tabla busqueda de pedidos -->
                <div class="row">
                    <div class="col-lg-12">
                    <div class="table-responsive">
                        <table id="pedidos_grid" class="table table-condensed table-hover table-striped" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th data-column-id="id_pedidos" data-type="numeric" data-identifier="true" data-order="asc">#Pedido</th>
                                <th data-column-id="nombre">Solicitó</th>
                                <th data-column-id="fecha_pedido" >Fecha de pedido</th>
                                <th data-column-id="estatus">Estatus General</th>
                                
                                <th data-column-id="commands" data-formatter="commands" data-sortable="false">Seleccionar</th>
                            </tr>
                        </thead>
                        </table>
                    </div>
                    </div>
                </div>
                <!-- /tabla busqueda de pedidos -->

                <?php // print_r($_SESSION);?>
                <div class="row">
                    <div class="col-lg-12">
                    <?php   
                        if($_SESSION['nivel_acceso']==4){
                            echo'<form name="form_send_lista_tipo_agregar_venta" action="pedidos_agregar_pedido.php" method="post">';
                            echo'   <a href="javascript:document.form_send_lista_tipo_agregar_venta.submit()" class="btn btn-primary  btn-block" role="button" ><span class="glyphicon glyphicon-plus"></span> Agregar pedido</a>';
                            echo'</form>';
                        }
                    ?>
                    </div>
                </div>
                           
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
        </div>
        <!-- /#wrapper -->
    </div>



<!-- AJAX CONSULTA DE PEDIDOS-->

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
        url: "pedidos_data_pedidos.php",
        formatters: {
            "commands": function(column, row)
            {
                return "<?php 
                            if($_SESSION["nivel_acceso"] == 4 ){

                                echo '<button type=\"button\" class=\"btn btn-default command-select\" data-row-id=\"" + row.id_pedidos + "\"><span class=\"glyphicon glyphicon-plus\"></span></button>'; 
                            }?>"
                                  
                <?php 
                    if($_SESSION["nivel_acceso"] <= 4 ){
                        echo'+"<button type=\"button\" class=\"btn  btn-default command-delete\" data-row-id=\"" + row.id_pedidos + "\"><span class=\"glyphicon glyphicon-pencil\"></span></button>";';
                    }
                ?>
            }
        },
        labels: {
            noResults: "¡No se encontraron resultados!",
            search: "Buscar",
            refresh: "Actualizar",
            loading: "Espere un momento..."
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
</body>

</html>