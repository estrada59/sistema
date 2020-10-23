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
}

?>             
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>
