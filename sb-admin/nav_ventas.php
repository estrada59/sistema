<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="" href="index.php"><img src="images/logo_numedics.svg" width="210" height="78" id="bg" alt="NUMEDICS"></a>
               <!-- <a class="navbar-brand" href="index.php">Medicina Nuclear</a>           -->
            </div>
    
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                
                <li class="dropdown">
                    <?php 
                        $usuario = $_SESSION["usuario"];
                        echo'
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src=images/avatar/'.$usuario.'.jpg> Bienvenido(a) '.$usuario.' <b class="caret"></b></a>
                        ';
                    ?>
                   <!-- <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> John Smith <b class="caret"></b></a>-->
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
    $inicio= '';
    $citar_pacientes=' ';
    $ver_lista_precios='';
    $ver_pacientes_del_dia='';
    $editar_pacientes_por_fecha='';
    $anticipos_por_fecha='';
    $imprimir_hoja_tratamientos='';
    $imprimir_comision='';
    $facturas ='';
    $prueba_esfuerzo ='';

    if($pag == 'inicio'){
        $inicio = 'active';
    }
    if($pag == 'citar_pacientes'){
        $citar_pacientes = 'active';
    }
    if($pag == 'ver_lista_precios'){
        $ver_lista_precios = 'active';
    }
    if($pag == 'ver_pacientes_del_dia'){
        $ver_pacientes_del_dia = 'active';
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
    if($pag == 'imprimir_hoja_tratamientos'){
        $imprimir_hoja_tratamientos = 'active';
    }
    if($pag == 'imprimir_comision'){
        $imprimir_comision = 'active';
    }
    if($pag == 'facturas'){
        $facturas = 'active';
    }
    if($pag == 'prueba_esfuerzo'){
        $prueba_esfuerzo = 'active';
    }
    echo'
            <li class ="'.$inicio.'">
                <a href="index.php"><i class="fa fa-fw fa-dashboard"></i> Inicio</a>
            </li>';
        /*    <li class ="'.$citar_pacientes.'">
                <a href="controlador_citar_pacientes.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Citar pacientes</a>
            </li>
        */
    echo    ' <li class ="'.$ver_lista_precios.' ">
                <a href="controlador_ver_lista_precios.php"><span class="glyphicon glyphicon-list" aria-hidden="true"></span>   Ver lista de precios</a>
            </li>';

    echo  
           '<li class ="'.$ver_pacientes_del_dia.' ">
                <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-table"></i> Ver pacientes<i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="demo" class="collapse">
                    <li>
                        <a href="controlador_buscar_paciente.php"><span class="glyphicon glyphicon-search"></span>  Por nombre</a>
                    </li>
                    <li>
                        <a href="viewmod_ver_pacientes_del_dia.php"><i class="fa fa-fw fa-table"></i>Por día</a>
                    </li>
                    <li>
                        <a href="controlador_ver_pacientes_por_fecha.php"><i class="fa fa-fw fa-table"></i>Por fecha</a>
                    </li>
                    <li>
                        <a href="viewmod_ver_pacientes_por_semana.php"><i class="fa fa-fw fa-table"></i>Por semana</a>
                    </li>
                    <li>
                        <a href="controlador_ver_pacientes_por_mes.php"><i class="fa fa-fw fa-table"></i>Por mes</a>
                    </li>
                   
                </ul>
            </li>';    
            /*
            <li class ="'.$editar_pacientes_por_fecha.' ">
                <a href="javascript:;" data-toggle="collapse" data-target="#demo3"><span class="glyphicon glyphicon-pencil" ></span>  Editar  <i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="demo3" class="collapse">
                    <li>
                        <a href="controlador_ver_editar_pacientes_por_fecha.php">Datos del paciente</a>
                    </li>
                    <li>
                        <a href="controlador_ver_editar_estudio_de_paciente.php">Cambiar estudio de paciente</a>
                    </li>
                    <li>
                        <a href="controlador_ver_editar_institucion_a_particular.php">Cambiar de institución a particular</a>
                    </li>
                </ul>
            </li>
            <li class ="'.$anticipos_por_fecha.' ">
                <a href="javascript:;" data-toggle="collapse" data-target="#demo1"><span class="glyphicon glyphicon-usd" ></span> Cobros <i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="demo1" class="collapse">
                    <li>
                        <a href="controlador_ver_anticipos_por_fecha.php">Anticipos</a>
                    </li>
                    <li>
                        <a href="controlador_ver_corte_caja_por_dia.php">Corte de caja</a>
                    </li>
                    <li>
                        <a href="controlador_reimprimir_corte_caja_por_dia.php">Reimprimir corte de caja</a>
                    </li>
                    <li>
                        <a href="controlador_reimprimir_recibo.php">Reimprimir recibo</a>
                    </li>
                    <li>
                        <a href="controlador_ver_corte_caja_por_mes.php">Corte de caja mensual</a>
                    </li>
                </ul>
            </li>
            <li class ="'.$imprimir_hoja_tratamientos.'">
                <a href="controlador_imprimir_hoja_tratamientos.php"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Hoja tratamientos</a>
            </li>
        */
    echo'        <li class ="'.$imprimir_comision.' ">
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

        /*    echo '

            <li class ="'.$facturas.' ">
                <!-- <a href="controlador_ver_pacientes_por_semana_factura.php"><span class="glyphicon glyphicon-list-alt" ></span> Factura</a>-->
                <a href="controlador_ver_pacientes_por_mes_factura.php">Facturas</a>
            </li>';

            echo '

            <li class ="'.$prueba_esfuerzo.' ">
                <a href="controlador_ver_pacientes_por_mes_prueba_esfuerzo.php">Prueba de esfuerzo</a>
            </li>';
        */
}

?>     
              <?php 
                    
                    /*
                    <li>
                        <a href="tables.html"><i class="fa fa-fw fa-table"></i> Tables</a>
                    </li>
                    <li>
                        <a href="forms.html"><i class="fa fa-fw fa-edit"></i> Forms</a>
                    </li>
                    <li>
                        <a href="bootstrap-elements.html"><i class="fa fa-fw fa-desktop"></i> Bootstrap Elements</a>
                    </li>
                    <li>
                        <a href="bootstrap-grid.html"><i class="fa fa-fw fa-wrench"></i> Bootstrap Grid</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i> Dropdown <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo" class="collapse">
                            <li>
                                <a href="#">Dropdown Item</a>
                            </li>
                            <li>
                                <a href="#">Dropdown Item</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="blank-page.html"><i class="fa fa-fw fa-file"></i> Blank Page</a>
                    </li>
                    <li>
                        <a href="index-rtl.html"><i class="fa fa-fw fa-dashboard"></i> RTL Dashboard</a>
                    </li>*/?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>
