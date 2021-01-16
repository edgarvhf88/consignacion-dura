<?php include("conexion.php");

		$id_pedido = $_POST['id_pedido'];
		$fecha_entrega = $_POST['fecha_entrega'];
	  
	  if ($id_pedido != ''){
			
		guardar_pedido_nef($id_pedido,$fecha_entrega);	
	  }

     function guardar_pedido_nef($id_pedido, $fecha_entrega){ 
global $database_conexion, $conex;

date_default_timezone_set('America/Mexico_City');
$fecha_actual = date("Y-m-d H:i:s");

$total_pedido = "";
$consulta_lista = "SELECT SUM(precio_total) as Total FROM pedido_nef_det WHERE id_pedido = '$id_pedido'";
$resultado_lista = mysql_query($consulta_lista, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado_lista);
$total_rows2 = mysql_num_rows($resultado_lista);

if ($total_rows2 > 0){
	$total_pedido = $row['Total'];
}

$almacen_id = "";
$consulta_pedido = "SELECT pn.almacen_id as almacen_id, pn.id_pedido_cliente as id_pedido_cliente
					FROM pedido_nef pn
					WHERE pn.id_pedido = '$id_pedido'";
$resultado_pedido = mysql_query($consulta_pedido, $conex) or die(mysql_error());
$row_p = mysql_fetch_assoc($resultado_pedido);
$total_rows_p = mysql_num_rows($resultado_pedido);

if ($total_rows_p > 0){
	$almacen_id = $row_p['almacen_id'];
}

	$id_empresa = 11;
	$folio = folio_consecutivo($id_empresa,"PED_N");
	
$estatus = '1';
$update_pedido = "UPDATE pedido_nef SET folio='$folio', estatus='$estatus', total_pedido='$total_pedido', almacen_id='$almacen_id', fecha_pedido_oficial='$fecha_actual', fecha_entrega='$fecha_entrega' WHERE id_pedido='$id_pedido'";

		if (mysql_query($update_pedido, $conex) or die(mysql_error()))
		{	
			$folio_consecutivo = $folio + 1;
			$update_folio = "UPDATE folios SET folio='$folio_consecutivo'  WHERE id_empresa='$id_empresa' and tipo_folio='PED_N'";
			if (mysql_query($update_folio, $conex) or die(mysql_error())){}
			/// generar un pedido en sistema microsip con los datos capturados en este pedido_nef
			echo '<script>
					
					$("#modal_cargando").modal("hide");
					//mis_pedidos();
					
					var confirma_pedmicro = confirm("Desea generar pedido en Microsip NEF en este momento, si desea generarlo mas tarde precione Cancelar");
					if (confirma_pedmicro){
						// se genera pedido con funcion
						insertar_pedido_nef("'.$id_pedido.'");
					}else{
						// mostrar lista de pedidos NEF
						lista_pedidos_nef();
					}
					
					
					</script>';
			
		}
		else 
		{
			echo 0;
		}
		

}

?>