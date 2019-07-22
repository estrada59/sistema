<?php
//include connection file 
include_once 'include/mysql.php';

$mysql = new mysql();
$conn = $mysql->connect(); 

// initilize all variable
$params =  $totalRecords = $data = array();

$sqlTot = $sqlRec = $where = "";


$params = $_REQUEST;
$limit = $params["rowCount"];

//$params['current']=1;
//$limit=10;

if (isset($params["current"])) { $page  = $params["current"]; } else { $page=1; };  
$start_from = ($page-1) * $limit;
// check search value exist
if( !empty($params['searchPhrase']) ) {   
	$where .=" WHERE ";
	$where .=" ( id_pedidos LIKE '".$params['searchPhrase']."%' ";    
	$where .=" OR fecha_pedido LIKE '".$params['searchPhrase']."%' )";
}
if( !empty($params['sort']) ) {  
	$where .=" ORDER By ".key($params['sort']) .' '.current($params['sort'])." ";
}

$resp = $mysql->query($conn, "SET lc_time_names = 'es_ES';");
// getting total number records without any search
$sql = "SELECT 	tbl_pedidos.id_pedidos,

(SELECT 
	concat(users.nombre,' ',users.ap_paterno,' ', users.ap_materno) as nombre 
FROM users 
WHERE users.idusuario = tbl_pedidos.idusuario) AS nombre,

(SELECT DATE_FORMAT(tbl_pedidos.fecha_pedido, '%d %b %Y %r')) as fecha_pedido,

(SELECT t2.descripcion
FROM tblc_status_pedido t2
WHERE t2.id_status_pedido = tbl_pedidos.id_status_pedido ) AS estatus

FROM tbl_pedidos ";
$sqlTot .= $sql;
$sqlRec .= $sql;


//concatenate search sql if value exist
if(isset($where) && $where != '') {

	$sqlTot .= $where;
	$sqlRec .= $where;
}
if ($limit!=-1)
$sqlRec .= "LIMIT $start_from, $limit";
	
$queryTot = mysqli_query($conn, $sqlTot) or die("database error:". mysqli_error($conn));


$totalRecords = mysqli_num_rows($queryTot);

$queryRecords = mysqli_query($conn, $sqlRec) or die("error to fetch employees data");

//iterate on results row and create new index array of data
while( $row = mysqli_fetch_assoc($queryRecords) ) { 
	$data[] = $row;
	//echo "<pre>";print_R($data);die;
}	

$json_data = array(
		"current"            => intval( $params['current'] ), 
		"rowCount"            => 10, 			
		"total"    => intval( $totalRecords ),
		"rows"            => $data   // total data array
		);

echo json_encode($json_data);  // send data as json format

?>

