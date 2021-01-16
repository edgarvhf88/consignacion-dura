<?php include("conexion.php");

if (isset($_POST['id_pedido'])){
	
	$id_pedido = $_POST['id_pedido'];
	ActualizarExistencia($id_pedido);
	
}
function ActualizarExistencia($id_pedido){
	global $conex, $con_micro;

$existencia_microsip;
$id_arti;

$consulta_articulos = "SELECT a.id_microsip as id_microsip, a.id as id_arti 
							FROM pedidos_det pd 
							INNER JOIN articulos a on a.id = pd.id_articulo
							WHERE pd.id_pedido = '$id_pedido' ";
	$resultado_articulos = mysql_query($consulta_articulos, $conex) or die(mysql_error());
	$total_rows = mysql_num_rows($resultado_articulos);
	
	$posicion = 1;
	while($row_articulos = mysql_fetch_array($resultado_articulos,MYSQL_BOTH)) 
	{
		$id_arti = $row_articulos['id_arti'];
		$existencia_microsip = ExistenciaMicrosip($row_articulos['id_microsip']);
		$min_max_reorden = explode("_",MinMaxReorden($row_articulos['id_microsip']));
		$maximo = $min_max_reorden[0];
		$minimo = $min_max_reorden[1];
		$reorden = $min_max_reorden[2];
		
		$update = "UPDATE existencias SET min='$minimo', max='$maximo', reorden='$reorden', existencia_actual='$existencia_microsip' WHERE id_articulo='$id_arti'";
		if (mysql_query($update, $conex) or die(mysql_error()))
		{echo '<script> console.log("Se aplico actualizacion de inventario en toolcrib"); </script>';}
	}
}	
	
	
?>