<?php
//Mensajes de session (obtiene la session)
function verifica_sesion(){
	$txt="";
	if(isset($_SESSION['usuario'])){
		echo "Bienvenido ".$_SESSION['usuario'];	
		$txt="Iniciar sesión";
		return $txt;
	}
	else{
		echo "Aún no inicia sesion";
		$txt="Cerrar sesión";
		return $txt;
	}
}
function verifica_sesion_btn_val(){
	if(isset($_SESSION['usuario'])){
		return 0;
	}
	else{
		return 1;
	}
}
function message_session(){
	if(verifica_sesion_btn_val()){
		echo "Iniciar sesión";
	}
	else{
		echo "Cerrar sesión";
	} 
}
function message_session1(){
	if(verifica_sesion_btn_val()){
		echo "Cerrar";
	}
	else{
		echo "Atrás";
	}
}
function show_textbox(){	
	if(verifica_sesion_btn_val()){
		//muestra user pass si no estas loggeado
		echo '
			<div class="modal-body ">
            	<div class="form-group">
					<div class="controls input-group" >
						<span class="input-group-addon glyphicon glyphicon-user"></span>
						<input class="form-control" autocomplete="off" placeholder="Usuario" name="user" type="text" id="iduser" >
					</div>
					
                </div>
             
			    <div class="form-group">
					<div class="input-group">
					    <span class="input-group-addon glyphicon glyphicon-lock"></span>
                      	<input class="form-control" placeholder="Contraseña" name="password" id="idpassword" type="password">
					</div>
                </div>
			</div>';
	}
}
function comprueba_url(){
	if(!isset($_SESSION["nivel_acceso"] )){
    	header("Location: ../index.php");
    	exit();
	}
}

function time_session(){

	date_default_timezone_set('America/Mexico_City');
	$idusuario = $_SESSION['id'];

	$mysql = new mysql();
    $link = $mysql->connect();

    $sqlactivo = $mysql->query($link, "SELECT activo
    									FROM usuarios 
										WHERE idusuario = '".$idusuario."' ");

    $row = $mysql->f_obj($sqlactivo);
    
    $activo = $row->activo;

    //verificamos si está activo el usuario de lo contrario cerramos todas las sesiones 
    if($activo){

    	$fecha = date("Y-m-d"); 
		$hora =date("H:i:s");

		if (isset($_SESSION['ultima_actividad']) && (time() - $_SESSION['ultima_actividad'] > 43200)){

			$idusuario = $_SESSION['id'];
		
    		$sql = $mysql->query($link, " 	UPDATE usuarios 
											SET activo = 0,
												fecha_salida = '".$fecha."',
												hora_salida=  '".$hora."'
											WHERE idusuario = '".$idusuario."' ");
    
	    	// last request was more than 30 minutes ago
    		session_unset();     // unset $_SESSION variable for the run-time 
    		session_destroy();   // destroy session data in storage
    		header("Location: ../index.php");
    		exit();
    	}
    }
    else{

    	$fecha = date("Y-m-d"); 
		$hora =date("H:i:s");

		if (isset($_SESSION['ultima_actividad']) && (time() - $_SESSION['ultima_actividad'] > 43200)) {

			$idusuario = $_SESSION['id'];
		
    		$sql = $mysql->query($link, " 	UPDATE usuarios 
											SET activo = 0,
												fecha_salida = '".$fecha."',
												hora_salida=  '".$hora."'
											WHERE idusuario = '".$idusuario."' ");
    
	    	// last request was more than 30 minutes ago
    		session_unset();     // unset $_SESSION variable for the run-time 
    		session_destroy();   // destroy session data in storage
    		header("Location: ../index.php");
    		exit();
    	}
    }

}
?>