<!DOCTYPE html>
<?php $_POST["pagina"]="inicio"; ?>
<html lang="en">

<head>
    <?php 	include "header.php";
    		session_start(); 
			include_once "../include/sesion.php";
            include_once "../include/mysql.php";
			comprueba_url();
            //time_session();       
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
                        <h1 class="page-header">
                            Inicio<small></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Inicio
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

                <?php 
                  /*  print "<pre>";
                    print_r($_POST);
                    print_r($_SESSION);
                    print "</pre>";
            */
                ?>
                <div class="row">
                    <div class="col-md-12" align="center">
                        <img src="images/logo_numedics.svg" width="600" height="250" id="bg"  alt="NUMEDICS">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <a href="http://www.medicinanucleardechiapas.com/" target="_blank" class="btn btn-success btn-lg btn-block" role="button">
                            <span class="glyphicon glyphicon-globe"></span> Página web MEDICINA NUCLEAR </a>
                    </div>
                    
                    <div class="col-md-6">
                        <a href="http://www.numedics.com.mx/" target="_blank" class="btn btn-success btn-lg btn-block" role="button">
                            <span class="glyphicon glyphicon-globe"></span> Página web NUMEDICS</a>
                    </div>
                </div>

                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
        </div>
        <!-- /#wrapper -->


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
