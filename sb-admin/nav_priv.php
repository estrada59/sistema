
 <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
               <!-- <img src="images/log.jpg">-->
                <a class="" href="index.php"><img src="images/logo_numedics.svg" width="200" height="78" id="bg" alt="NUMEDICS"></a>
            </div>
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php"></a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                
                <li class="dropdown"> 
                	   <?php 

                        $usuario = $_SESSION["usuario"];
                        echo'
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src=images/avatar/'.$usuario.'.jpg> Bienvenido '.$usuario.' <b class="caret"></b></a>
                        ';
                    ?>
                  
                    <ul class="dropdown-menu">
                        <li>
                            <a href="controlador_perfil_usuario.php"><i class="fa fa-fw fa-gear"></i> Perfil</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="../login/logout.php"><i class="fa fa-fw fa-power-off"></i>SALIR</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">

                <?php $pag = $_POST["pagina"];
                        menu($pag);
                ?>

<?php  
function menu($pag){
    $inicio                     ='';
    $nuevo_estudio              ='';
    $ver_lista_precios          ='';
    $ver_pacientes_del_dia      ='';
    $ver_pacientes_por_fecha    ='';
    $ver_pacientes_por_semana   ='';
    $anticipos_por_fecha        ='';
    $imprimir_comision          ='';
    $modificar_estudio_paciente ='';
    $agregar_cliente ='';
    $buscar_paciente ='';
    $facturas ='';
    $instituciones ='';
    $grafica ='';
    $pedidos_material=   '';
    $prueba_esfuerzo ='';


    if($pag == 'inicio'){
        $inicio = 'active';
    }
    if($pag == 'nuevo_estudio'){
        $nuevo_estudio = 'active';
    }
    if($pag == 'ver_lista_precios'){
        $ver_lista_precios = 'active';
    }
    if($pag == 'ver_pacientes_del_dia'){
        $ver_pacientes_del_dia = 'active';
    }
    if($pag == 'ver_pacientes_por_fecha'){
        $ver_pacientes_por_fecha= 'active';
    }
    if($pag == 'ver_pacientes_por_semana'){
        $ver_pacientes_por_semana = 'active';
    }
    if($pag == 'ver_pacientes_por_mes'){
        $ver_pacientes_por_mes = 'active';
    }
    if($pag == 'editar_pacientes_por_fecha'){
        $editar_pacientes_por_fecha = 'active';
    }
    if($pag == 'anticipos_por_fecha'){
        $anticipos_por_fecha = 'active';
    }
    if($pag == 'imprimir_comision'){
        $imprimir_comision = 'active';
    }
    if($pag == 'modificar_estudio_paciente'){
        $modificar_estudio_paciente = 'active';
    }
    if($pag == 'agregar_cliente'){
        $agregar_cliente = 'active';
    }
    if($pag == 'buscar_paciente'){
        $buscar_paciente = 'active';
    }
    if($pag == 'facturas'){
        $facturas = 'active';
    }
    if($pag == 'instituciones'){
        $instituciones = 'active';
    }
    if($pag == 'grafica'){
        $grafica = 'active';
    }
    if($pag == 'pedidos_material'){
        $pedidos_material = 'active';
    }
    if($pag == 'prueba_esfuerzo'){
        $prueba_esfuerzo = 'active';
    }
    echo'
            <li class ="'.$inicio.'">
                <a href="index.php"><i class="fa fa-fw fa-dashboard"></i> Inicio</a>
            </li>
            <li class ="'.$ver_lista_precios.' ">
                <a href="controlador_ver_lista_precios.php"><span class="glyphicon glyphicon-list" aria-hidden="true"></span>   Ver lista de precios</a>
            </li>
            <li class ="'.$nuevo_estudio.' ">
                <a href="controlador_agregar_nuevo_estudio.php"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>   Agregar nuevo estudio</a>
            </li>
            
            <li class ="'.$ver_pacientes_del_dia.' ">
                <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-table"></i> Ver pacientes<i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="demo" class="collapse">
                    <li>
                        <a href="controlador_ver_pacientes_del_dia.php"><i class="fa fa-fw fa-table"></i>Por día</a>
                    </li>
                    <li>
                        <a href="controlador_ver_pacientes_por_fecha.php"><i class="fa fa-fw fa-table"></i>Por fecha</a>
                    </li>
                    <li>
                        <a href="controlador_ver_pacientes_por_semana.php"><i class="fa fa-fw fa-table"></i>Por semana</a>
                    </li>
                    <li>
                        <a href="controlador_ver_pacientes_por_mes.php"><i class="fa fa-fw fa-table"></i>Por mes</a>
                    </li>
                </ul>
            </li>
            <!--<li class ="'.$buscar_paciente.' ">
                <a href="controlador_buscar_paciente.php"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>  Buscar paciente</a>
            </li>-->

            <li class ="'.$anticipos_por_fecha.' ">
                <a href="javascript:;" data-toggle="collapse" data-target="#demo1"><i class="fa fa-fw fa-arrows-v"></i> Cobros <i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="demo1" class="collapse">
                    <li>
                        <a href="controlador_ver_corte_caja_por_dia.php">Corte de caja</a>
                    </li>
                    <li>
                        <a href="controlador_reimprimir_corte_caja_por_dia.php">Reimprimir corte de caja</a>
                    </li>
                    <li>
                        <a href="controlador_ver_corte_caja_por_mes.php">Corte de caja mensual</a>
                    </li>
                </ul>
            </li>';
        
        echo '
            <li class ="'.$imprimir_comision.' ">
                <a href="javascript:;" data-toggle="collapse" data-target="#demo2"><span class="glyphicon glyphicon-list-alt" ></span> Comisión <i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="demo2" class="collapse">
                    <!--<li>
                        <a href="controlador_imprimir_comision.php">SPECT Cardiaco</a>
                    </li>-->
                    <li>
                        <a href="controlador_imprimir_comision_doctores.php">Doctores estudios en general</a>
                    </li>
                </ul>
            </li>';
        
        echo '
        <li class ="'.$facturas.' ">
            <a href="javascript:;" data-toggle="collapse" data-target="#facturas"><span class="glyphicon glyphicon-list-alt" ></span> Facturas <i class="fa fa-fw fa-caret-down"></i></a>
            <ul id="facturas" class="collapse">
                <!--<li>
                    <a href="controlador_ver_pacientes_por_mes_factura.php">Factura</a>
                </li>-->
                <li>
                        <a href="controlador_ver_folios_facturas.php">Imprimir folios Facturas</a>
                </li>
            </ul>
        </li>';

        echo '
            
            <li class ="'.$instituciones.' ">
                    <li>
                        <a href="controlador_relacion_instituciones.php">Relaciones de instituciones</a>
                    </li>
            </li>
            <li class ="'.$grafica.' ">
                <a href="javascript:;" data-toggle="collapse" data-target="#graficas"> Graficas <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="graficas" class="collapse">
                        <li>
                            <a href="controlador_graficar_pacientes_por_mes.php">Graficas por mes</a>
                        </li>
                        <li>
                            <a href="controlador_graficar_pacientes_por_año.php">Graficas por año</a>
                        </li>
                        <li>
                            <a href="controlador_graficar_por_estudios_por_año.php">Graficas por estudios anual</a>
                        </li>
                    </ul> 
            </li>
            <li class ="'.$pedidos_material.'">
                <a href="pedidos_lista_pedidos.php"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Lista de pedidos</a>
            </li>
            ';
        echo '

            <!--<li class ="'.$prueba_esfuerzo.' ">
                <a href="controlador_ver_pacientes_por_mes_prueba_esfuerzo.php">Prueba de esfuerzo</a>
            </li>-->';
    /*    echo '
            <li class ="'.$modificar_estudio_paciente.'">
                <a href="controlador_modificar_estudio_paciente.php"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Modificar estudio paciente</a>
            </li>
           
            <li class ="'.$agregar_cliente.' ">
                <a href="javascript:;" data-toggle="collapse" data-target="#demo2"><i class="fa fa-fw fa-arrows-v"></i> Clientes <i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="demo2" class="collapse">
                    <li>
                        <a href="controlador_agregar_cliente.php">Agregar cliente</a>
                    </li>
                    <li>
                        <a href="#">Modificar datos cliente</a>
                    </li>
                    <li>
                        <a href="viewmod_ver_clientes.php">Ver clientes</a>
                    </li>
                </ul>
            </li>

            ';
    */
}
?>

                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>
