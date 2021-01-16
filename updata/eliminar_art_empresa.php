<?php include("../data/conexion.php");

      $id_empresa = $_POST['select_empresa_delete'];
      $tipo_delete = $_POST['select_delete'];

	  if ($tipo_delete == 1){
		if ($id_empresa != ''){
			//  echo "uno";
			eliminar_articulos($id_empresa);
	  }  
	  }
	  else if ($tipo_delete == 2){
		if ($id_empresa != ''){
			//  echo "dos";
			eliminar_movimientos($id_empresa);
	  }   
	  }
	  
     function eliminar_articulos($id_empresa){ // Funcion para elimnar articulos del pedido
global $database_conexion, $conex;


$delete_articulo = "DELETE FROM articulos WHERE id_empresa = $id_empresa ";
		if (mysql_query($delete_articulo, $conex) or die(mysql_error()))
		{
			echo "Se eliminaron los articulos de la empresa ".EMPRESA_NOMBRE($id_empresa)." <a href='../updata'> regresar </a>";
		}
		

}

function eliminar_movimientos($id_empresa){ // Funcion para elimnar articulos del pedido
global $database_conexion, $conex;


$consulta = "SELECT * FROM pedidos WHERE id_empresa = '$id_empresa'";
$resultado = mysql_query($consulta, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);

if ($total_rows > 0){
	
while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) // 
{
	$id_pedido = $row['id'];
	$delete_item = "DELETE FROM pedidos_det WHERE id_pedido = '$id_pedido' ";
		if (mysql_query($delete_item, $conex) or die(mysql_error()))
		{
			//echo "Se eliminaron los articulos de la empresa ".EMPRESA_NOMBRE($id_empresa);
		}
}///end while

	$delete_movimientos = "DELETE FROM pedidos WHERE id_empresa = '$id_empresa' ";
		if (mysql_query($delete_movimientos, $conex) or die(mysql_error()))
		{
			echo "Se eliminaron los movimientos de la empresa ".EMPRESA_NOMBRE($id_empresa)." <a href='../updata'> regresar </a>";
		}
}


		

}

?>