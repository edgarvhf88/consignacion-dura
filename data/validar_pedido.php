<?php include("../data/conexion.php"); 

$id_pedido = $_POST['id_pedido'];
ValidarPedidoCliente($id_pedido);
	echo '<script>
		
		lista_pedidos();
		 </script>';

?>