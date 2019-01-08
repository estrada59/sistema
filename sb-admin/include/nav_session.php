<?php 
	if($_SESSION['nivel_acceso'] == 3){   //recepcion
                include "nav.php";  }
	if($_SESSION['nivel_acceso'] == 1){   //gerencia
                include "nav_priv.php"; }
	if($_SESSION['nivel_acceso'] == 2){   //administrador
                include "nav_contador.php"; }
	if($_SESSION['nivel_acceso'] == 4){   //operario
                include "nav_op.php"; }
    if($_SESSION['nivel_acceso'] == 5){   //ventas
        include "nav_ventas.php"; }
 ?>
