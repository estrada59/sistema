
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
		echo "¿Cerrar sesión?";
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

?>