<?php

function select_avatar()
{
	$resul="cadena";
	$usuario = $_SESSION["usuario"];
	
	if($usuario == "enrique"){
		$resul = "images/avatar/"."$usuario".".jpg";
	}
	if($usuario == "adrian"){
		$resul = "images/avatar/"."adrian".".jpg";
		echo "este es resul".$resul;
	}
	if($usuario == "andrea"){
		$resul = "images/avatar/"."$usuario".".jpg";
	}
	return $resul;

}
	

?>