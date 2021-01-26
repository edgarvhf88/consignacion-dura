<?php include("../data/conexion.php"); 

$id_pedido = $_POST['id_ped_tras'];




function surtir_partida ($id_pedido, $id_articulo, $cant_surtida) {
	global $conex;
	
	$consulta = "SELECT pt.cantidad as cantidad, pt.surtido as surtido, pt.id as id
				FROM pedidos_det pt 
				WHERE pt.id_pedido = '$id_pedido' and pt.id_articulo= '$id_articulo'";
	$res = mysql_query($consulta, $conex) or die(mysql_error());
	$row = mysql_fetch_assoc($res);
	$total = mysql_num_rows($res);

	if ($total > 0){
	$id_det = $row['id'];	
	$cantidad = $row['cantidad'];	
	$surtido = $row['surtido'];	
	$cantidad_surtido = $surtido + $cant_surtida;
	$up_surtir = "UPDATE pedidos_det SET surtido='$cantidad_surtido' WHERE id='$id_det'";
	if (mysql_query($up_surtir, $conex) or die(mysql_error())){ /* se actualiza la cantidad surtida */ }
				
	
	}
}

function surtir_traspaso($id_pedido){
	global $conex;
// buscar id_pedido_cliente, folio(de pedido pricncipal del cliente)
$consulta_p = "SELECT pt.id_pedido_cliente as id_pedido_cliente, p.folio as folio_pedido_cliente
				FROM pedido_traspaso pt 
				INNER JOIN pedidos p ON p.id = pt.id_pedido_cliente
				WHERE pt.id_pedido = '$id_pedido' ";
$resultado_p = mysql_query($consulta_p, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado_p);
$total_p = mysql_num_rows($resultado_p);
$folio_pedido_cliente = '';
$id_pedido_cliente = '';
if ($total_p > 0){
$folio_pedido_cliente = $row['folio_pedido_cliente'];	
$id_pedido_cliente = $row['id_pedido_cliente'];	

}
// Actualiza estatus de pedido traspaso
$up_traspaso = "UPDATE pedido_traspaso SET estatus='3' WHERE id_pedido='$id_pedido'";
	if (mysql_query($up_traspaso, $conex) or die(mysql_error()))
	{
		$consulta_lista = "SELECT * FROM pedido_traspaso_det WHERE id_pedido = $id_pedido ";
		$resultado_lista = mysql_query($consulta_lista, $conex) or die(mysql_error());
		$total_rows = mysql_num_rows($resultado_lista);

		$cant_surtida = '';
		$id_articulo = '';
		
		if ($total_rows > 0){
			while($row_p = mysql_fetch_array($resultado_lista,MYSQL_BOTH)) // html de articulos a mostrar
			{
				$id_articulo = $row_p['id_articulo'];
				$cant_surtida = $row_p['cantidad'];
				$up_traspaso = "UPDATE pedido_traspaso_det SET surtido='$cant_surtida' WHERE id_pedido='$id_pedido'";
				if (mysql_query($up_traspaso, $conex) or die(mysql_error()))
				{
					surtir_partida ($id_pedido_cliente, $id_articulo, $cant_surtida);
				}
				
				 
			}
		}
		ValidarPedidoCliente($id_pedido_cliente);
	}
}

// validar primero que tenga una imagen relacionada si no no puede recibir el traspaso.
	$cosulta_imagenes = "SELECT * FROM relacion_imagenes WHERE id_docto= '$id_pedido' AND tipo_docto='TRAS'";
	$res_img = mysql_query($cosulta_imagenes, $conex) or die(mysql_error());
	$total_imgs = mysql_num_rows($res_img);
	$html_imagenes = '';
	if ($total_imgs > 0){ // si encuentra imagenes relacionadas entonces realiza la recepcion de traspaso
	surtir_traspaso($id_pedido);
	echo '<script>
		$(document).ready( function () {
		$("#pedido_detalle").modal("hide");
		lista_pedidos();
		});
		 </script>';
	}
	else{
		echo '<script>
		$(document).ready( function () {
		
		 alert("No puede recibir el traspaso hasta que agregue la captura de la hoja de traspaso firmada");
		});
		 </script>';
	}
	

