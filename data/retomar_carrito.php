<?php include("conexion.php");

		$id_pedido = $_POST['id_pedido'];
			  
	  if ($id_pedido != ''){
			  
			retomar_carrito($id_pedido);
	  }
     function retomar_carrito($id_pedido){ // cambia el estatus del pedido a 0p para indicar que se quedara pendiente y continuar con un pedido nuevo aparte, para posteriormente continuar con el pedido o los pedidos guardados
global $database_conexion, $conex;

$total_pedido = "";
$consulta_lista = "SELECT SUM(precio_total) as Total FROM pedidos_det WHERE id_pedido = $id_pedido ";
$resultado_lista = mysql_query($consulta_lista, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado_lista);
$total_rows2 = mysql_num_rows($resultado_lista);
if ($total_rows2 > 0){
	$total_pedido = $row['Total'];
}


$estatus = '0';
$update = "UPDATE pedidos SET estatus='$estatus', total_pedido='$total_pedido' WHERE id='$id_pedido'";

		if (mysql_query($update, $conex) or die(mysql_error()))
		{
			echo '<script>
	$(document).ready(function(){
        $("#modal_cargando").modal("hide");
      
		mostrar_pedido();
     });   
	</script>';
			
		}
		

}



?>