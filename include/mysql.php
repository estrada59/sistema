<?php
class mysql {
    
    var $server = "localhost";
	var $user = "root";
    var $pass = "";
    var $data_base = "mednuc";
    var $conexion;
    var $flag = false;
    var $error_conexion = "Error en la conexion a MYSQL";
    
    function connect(){
            $this->conexion = @mysqli_connect($this->server, $this->user, $this->pass, $this->data_base) or die($this->error_conexion);
            $this->flag = true;
            @mysqli_set_charset($this->conexion, "utf8");
            return $this->conexion;
    }
    function close(){
        if($this->flag == true){
            @mysqli_close($this->conexion);
        }
    }
    
    function query($link,$query){
        return @mysqli_query($link,$query);
    }
    
    function f_obj($query){
        return mysqli_fetch_object($query);
    }

    function f_array($query){
        return @mysqli_fetch_array($query, MYSQLI_ASSOC);
    }

    function f_row($query){
        return @mysqli_fetch_row($query);
    }
    function f_num($query){
        return @mysqli_num_rows($query);
    }

    function f_info($query){
        return @mysqli_info($link);
    }

    function free_sql($query){
        return @mysqli_free_result($query);
    }

    function affect_row(){
        return @mysqli_affected_rows($this->conexion);
    }
}
?>