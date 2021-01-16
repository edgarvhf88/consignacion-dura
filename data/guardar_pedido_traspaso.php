<?php include("conexion.php");

		$id_pedido_traspaso = $_POST['id_pedido_traspaso'];
		$fecha_entrega = $_POST['fecha_entrega'];
		$arr_cantidades = $_POST['arr_cantidades'];
	  
	  if ($id_pedido_traspaso != ''){
			
		guardar_pedido_traspaso($id_pedido_traspaso, $fecha_entrega, $arr_cantidades);	
	  }

     function guardar_pedido_traspaso($id_pedido_traspaso, $fecha_entrega, $arr_cantidades){ 
global $database_conexion, $conex;

date_default_timezone_set('America/Mexico_City');
$fecha_actual = date("Y-m-d H:i:s");

$total_pedido = "";
$consulta_lista = "SELECT SUM(precio_total) as Total FROM pedido_traspaso_det WHERE id_pedido = '$id_pedido_traspaso'";
$resultado_lista = mysql_query($consulta_lista, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado_lista);
$total_rows2 = mysql_num_rows($resultado_lista);

if ($total_rows2 > 0){
	$total_pedido = $row['Total'];
}
///*---obtiene datos almacen_id registrado y comprueba existencia-------------------------------------------------
$almacen_id = "";
$consulta_pedido = "SELECT pt.almacen_id as almacen_id, pt.id_pedido_cliente as id_pedido_cliente
					FROM pedido_traspaso pt
					WHERE pt.id_pedido = '$id_pedido_traspaso'";
$resultado_pedido = mysql_query($consulta_pedido, $conex) or die(mysql_error());
$row_p = mysql_fetch_assoc($resultado_pedido);
$total_rows_p = mysql_num_rows($resultado_pedido);

if ($total_rows_p > 0){
	$almacen_id = $row_p['almacen_id'];
}
/////-////////////////----------------------
$lista_cantidades = array();
foreach($arr_cantidades as $id => $valor){
$arrc = explode("_",$valor); 
$id_art_tras = $arrc[0];
$valor_cant = $arrc[1];
$lista_cantidades[$id_art_tras] = $valor_cant;
///* - verifica que los elementos de la lista tengan datos en las cantidades a sutir --
}
//print_r($lista_cantidades);
$ped_det = "SELECT pdet.id as id, a.precio as precio_unitario
					FROM pedido_traspaso_det pdet
					INNER JOIN articulos a on a.id = pdet.id_articulo
					WHERE pdet.id_pedido = '$id_pedido_traspaso'";
$res_peddet = mysql_query($ped_det, $conex) or die(mysql_error());
$total_rows_pdet = mysql_num_rows($res_peddet);

if ($total_rows_pdet > 0){
	
	while($rowe = mysql_fetch_array($res_peddet,MYSQL_BOTH)) {
		$id_elemento = $rowe['id'];
		$cantidad = $lista_cantidades[$id_elemento];
		$precio_unitario = $rowe['precio_unitario'];
		$precio_total = $cantidad * $precio_unitario;
		// aqui cambiaremos las cantidades registradas requeridas por las que ponga antes de requerir el traspaso
		$update_pedido_det = "UPDATE pedido_traspaso_det 
		SET cantidad='$cantidad', precio_total='$precio_total' 
		WHERE id='$id_elemento'";
		//update pedido_traspaso_det  cantidad
		if (mysql_query($update_pedido_det, $conex) or die(mysql_error()))
		{}	
	}

}
/////-////////////////----------------------
	$id_empresa = 11;
	$folio = folio_consecutivo($id_empresa,"PED_T");
//	echo $folio;
$estatus = '1';
$update_pedido = "UPDATE pedido_traspaso SET folio='$folio', estatus='$estatus', total_pedido='$total_pedido', almacen_id='$almacen_id', fecha_pedido_oficial='$fecha_actual', fecha_entrega='$fecha_entrega' WHERE id_pedido='$id_pedido_traspaso'";

		if (mysql_query($update_pedido, $conex) or die(mysql_error()))
		{	
			$folio_consecutivo = $folio + 1;
			$update_folio = "UPDATE folios SET folio='$folio_consecutivo'  WHERE id_empresa='$id_empresa' and tipo_folio='PED_T'";
			if (mysql_query($update_folio, $conex) or die(mysql_error())){}
			
			echo '<script>
					$("#modal_cargando").modal("hide");
					
						lista_solicitudes_traspaso();
					
					</script>';
		}
		else 
		{
			echo 0;
		}
		 

}

?>