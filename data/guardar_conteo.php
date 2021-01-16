<?php include("conexion.php");

      $id_articulo = $_POST['id_articulo'];
      $articulo_id = $_POST['articulo_id'];
      $id_inventario = $_POST['id_inventario'];
      $cantidad_contada = $_POST['cantidad_contada'];
		
	if ($id_articulo != "")
	{
		guardar($id_articulo,$articulo_id,$id_inventario,$cantidad_contada);
	}
function guardar($id_articulo,$articulo_id,$id_inventario,$cantidad_contada)
{   global $conex;
	
		$id_usuario_creador = $_SESSION['logged_user'];
		$fecha_hora_creacion = date("Y-m-d H:i:s");
				
	$sql_invdet = "SELECT invdet.id_inventario_det as id_inventario_det, inv.almacen_id as almacen_id, invdet.cantidad_contada as cantidad_contada, invdet.diferencia as diferencia 
	FROM inventarios_det invdet
	INNER JOIN inventarios inv ON inv.id_inventario = invdet.id_inventario
	WHERE invdet.id_inventario = '$id_inventario'
	AND invdet.id_articulo = '$id_articulo'";
	$resultado = mysql_query($sql_invdet, $conex) or die(mysql_error());
	$total_rows = mysql_num_rows($resultado);
	$row = mysql_fetch_assoc($resultado);
	$id_inventario_det = 0;
	if ($total_rows > 0) //si ya existe el articulo en los detalles del inventario actual
	{  
		$id_inventario_det = $row['id_inventario_det'];
		$almacen_id = $row['almacen_id'];
		$dif_db = $row['diferencia'];
		// $total_diferencias - $total_eordenes_facturadas
		$total_cantidad_cobrada = cantidades_cobradas($id_articulo,$almacen_id);
		$total_consumido_nopagado = suma_diferencias($id_articulo,$almacen_id,0) - $total_cantidad_cobrada;
		echo '<script> console.log("total_consumido suma diferencias = '.$total_consumido_nopagado.'"); </script>';
		//cantidad a consumida - cantidades con remision o factura en ordenes de compra capturadas
		
		$existencia_actual = existencia_articulo($articulo_id,$almacen_id) - $total_consumido_nopagado; // se resta el total consumido - lo cobrado ya que lo cobrado hace el descuento de inventario en el almacen
		//$cantidad_contada_total = suma_conteos($id_inventario_det) + $cantidad_contada;
		$cantidad_contada_total = $row['cantidad_contada'] + $cantidad_contada;
		echo '<script> console.log("cantidad_contada_total row cantidad contada = '.$cantidad_contada_total.'");console.log("existencia = '.$existencia_actual.'"); </script>';
		//$diferencia_total = $existencia_actual - $cantidad_contada;
		$diferencia = $dif_db  - $cantidad_contada;
			// update inventarios_det actualizar  cantidad_contada
		$update = "UPDATE inventarios_det 
							SET cantidad_contada='$cantidad_contada_total',
							diferencia='$diferencia'
							WHERE id_inventario_det='$id_inventario_det'";
		
							if (mysql_query($update, $conex) or die(mysql_error()))
							{
								//echo '<script> $("#ventana1").modal("show"); </script>';
								echo '<script> console.log("se actualizo inventario_det "); </script>';
							}	
	}
	else // si no existe entonces lo agrega
	{	
		$almacen_id = almacen_inventario($id_inventario);
		
		$total_cantidad_cobrada = cantidades_cobradas($id_articulo,$almacen_id);
		$total_consumido_nopagado = suma_diferencias($id_articulo,$almacen_id,0) - $total_cantidad_cobrada;
	 
		//cantidad a consumida - cantidades con remision o factura en ordenes de compra capturadas
		$existencia_actual = existencia_articulo($articulo_id,$almacen_id) - $total_consumido_nopagado;
		$diferencia = $existencia_actual - $cantidad_contada;
		$cantidad_contada_total = $cantidad_contada;
		$insert_invdet = "INSERT INTO inventarios_det (id_inventario,id_articulo,articulo_id,cantidad_contada,existencia_actual,diferencia,fecha_hora_creacion,id_usuario_creador) VALUES 
		('$id_inventario','$id_articulo','$articulo_id','$cantidad_contada','$existencia_actual','$diferencia','$fecha_hora_creacion','$id_usuario_creador')";
		if (mysql_query($insert_invdet, $conex) or die(mysql_error()))
		{
			$id_inventario_det =  mysql_insert_id();
			echo '<script> console.log("se inserto inventario_det"); </script>';
		}
	}
	
	$insert_invdet = "INSERT INTO inventarios_det_conteos (id_inventario_det,cantidad,existencia_momento,diferencia,id_usuario_creador,fecha_hora_creacion) VALUES 
	('$id_inventario_det','$cantidad_contada','$existencia_actual','$diferencia','$id_usuario_creador','$fecha_hora_creacion')";
	if (mysql_query($insert_invdet, $conex) or die(mysql_error()))
	{
		$id_conteo =  mysql_insert_id();
		echo '<script>  $("#tdunidadescontadas_'.$id_articulo.'").html('.$cantidad_contada_total.'); $("#modal_conteo_inventario").modal("hide");  $("#txt_inv_cantidad_contada").val("");</script>';
		//console.log("se registro el conteo"); console.log("existencia = '.$existencia_actual.'");
	}
	
	
	
	
mysql_free_result($resultado);  		
}	
?>		