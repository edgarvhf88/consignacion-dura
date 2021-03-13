<?php include("conexion.php");

//Genera una requi con la cantidad de cosumo, (tomar en cuanta el maximo para solicitar material si la cantidad de existenacia es mayor al maximo entonces no se debe agregar a la requi para evitar sobreinventario)


if (isset($_POST['almacen_id']))
{	
	$iduser = $_SESSION["logged_user"];
	$tipo_usuario = validar_usuario($iduser);
	$id_empresa = id_empresa($iduser);
	$almacen_id = $_POST['almacen_id'];
	
	date_default_timezone_set('America/Mexico_City');
	$fecha_actual = date("Y-m-d H:i:s");

	$sql = "SELECT e.existencia_actual as existencia_sistema,
			e.max as max,	
			a.id as id_articulo, 
			a.nombre as articulo, 
			a.clave_empresa as clave_empresa,
			a.clave_microsip as clave_microsip,
			a.precio as precio,
			a.unidad_medida as unidad_medida,
			(SELECT iidet.cantidad_contada
				FROM inventarios_det iidet
				INNER JOIN inventarios ii ON (ii.id_inventario=iidet.id_inventario)
				WHERE iidet.id_articulo = e.id_articulo 
				and ii.almacen_id = '$almacen_id' and ii.estatus = 'C'
				ORDER BY ii.fecha_hora_cierre DESC LIMIT 1) as existencia_fisica
			FROM existencias e 
			INNER JOIN articulos a on a.id = e.id_articulo
			WHERE e.almacen_id = '$almacen_id'";
		
	$res= mysql_query($sql, $conex) or die(mysql_error());
	//$row = mysql_fetch_assoc($res);
	$totalrows = mysql_num_rows($res);
	if ($totalrows > 0)
	{
		
		$consumo = 0;
		$existencia_fisica = 0;
		$id_pedido = "";
		$contador = 0;
		while($row = mysql_fetch_array($res,MYSQL_BOTH)) 
		{
			if ($row['existencia_fisica'] != "")
			{
				$consumo = $row['existencia_sistema'] - $row['existencia_fisica'];
				$existencia_fisica = $row['existencia_fisica'];
			}
			else
			{
				$consumo = 0;
				$existencia_fisica = $row['existencia_sistema'];
			}
			if ($consumo > 0)
			{ /// si existe consumo validar el max VS existencia, si hay sobre inventario no se debe requerir
				if ($existencia_fisica < $row['max'])
				{ // solo si hay menos que el maximo, es cuando se agrega a la requi
					$cantidad = $consumo;
					$id_articulo = $row['id_articulo'];
					$clave_microsip = $row['clave_microsip'];
					$clave_empresa = $row['clave_empresa'];
					$articulo = $row['articulo'];
					$precio_unitario = str_replace(",","",$row['precio']);
					$precio_total = ($precio_unitario) * ($cantidad);
					$unidad_medida = $row['unidad_medida'];
					
					if ($id_pedido == "")
					{ // si no tengo el id de pedido, lo obtengo ya sea por consulta o en su defecto por insercion
				
						$consulta = "SELECT * FROM pedidos WHERE id_usuario = '$iduser' AND estatus = '0' and id_sucursal = '$almacen_id' ";
						$resultado = mysql_query($consulta, $conex) or die(mysql_error());
						$row2 = mysql_fetch_assoc($resultado);
						$total_rows2 = mysql_num_rows($resultado);
						if ($total_rows2 > 0)
						{ // con resultados --> si existe pedido habiero debe mostrar advertencia.
							$id_pedido = $row2['id'];
						}
						else
						{ // si no existe pedido abierto
							$id_empresa = id_empresa($iduser);
							$id_departamento = DEPARTAMENTO($iduser);
							//$folio = folio_consecutivo($id_empresa,"PED");
							$folio = "";
							$insert_pedido = "INSERT INTO pedidos (id_usuario,folio,id_empresa,estatus,id_departamento,id_sucursal)
							VALUES ('$iduser','$folio','$id_empresa','0','$id_departamento','$almacen_id')";
							if (mysql_query($insert_pedido, $conex) or die(mysql_error()))
							{ $id_pedido =  mysql_insert_id(); }
						}
						
					}
					if ($id_pedido != "")
					{
						agregar($id_pedido,$id_articulo,$cantidad,$clave_microsip,$clave_empresa,$articulo,$precio_unitario,$precio_total,$unidad_medida);
						$contador++;
					}
				}
			}
		} // end while
		if ($contador > 0)
		{// si se agregaron los articulos al pedido entonces muestra la lista de la requi completa.
			echo '<script> mostrar_pedido(); </script>';	
		}
		
	}
}
 function agregar($id_pedido,$id_articulo,$cantidad,$clave_microsip,$clave_empresa,$articulo,$precio_unitario,$precio_total,$unidad_medida){ // 
	global $conex;
	$conclusion = "";
	//// consulta para verificar si existe el mismo articulo en la lista y asi lo sume en lugar de agregarlo de nuevo.
	$consulta_art_list = "SELECT pd.cantidad as cantidad, pd.id as id_det
	FROM pedidos_det pd
	WHERE pd.id_articulo = '$id_articulo' 
	AND pd.id_pedido = '$id_pedido'";  // estatus diferente a abieto y surtido
	$resultado_art_list = mysql_query($consulta_art_list, $conex) or die(mysql_error());
	$row_art_list = mysql_fetch_assoc($resultado_art_list);
	$total_rows_art_list = mysql_num_rows($resultado_art_list);
	$cantidad_art_list = 0; // la cantidad que esta en pedidos ordenados y en proceso
	$id_pedido_det = 0; // la cantidad que esta en pedidos ordenados y en proceso
	if ($total_rows_art_list > 0)
	{ // si exite el articulo en la lista obtiene la cantidad y 
		$cantidad_art_list = $row_art_list['cantidad'];
		$id_pedido_det = $row_art_list['id_det'];
	}
	if ($cantidad_art_list == 0) 
	{ // si la cantidad es 0 significa que no ha sido agregado a la lista
		$insert_articulos = "INSERT INTO pedidos_det (id_pedido,id_articulo,clave_empresa,clave_microsip,articulo,cantidad,precio_unitario,precio_total,unidad_medida) VALUES ('$id_pedido','$id_articulo','$clave_empresa','$clave_microsip','$articulo','$cantidad','$precio_unitario','$precio_total','$unidad_medida')";
		if (mysql_query($insert_articulos, $conex) or die(mysql_error()))
		{	$conclusion = 1; }
	}
	else				
	{ // si la cantidad es mayor a 0 entonces editara el articulo existente y se le agregara la cantidad solicitada
		if ($id_pedido_det != 0){
			$cantidad += $cantidad_art_list;
			$precio_total = ($precio_unitario) * ($cantidad);
			$update = "UPDATE pedidos_det 
				SET cantidad='$cantidad', precio_total='$precio_total' 
				WHERE id='$id_pedido_det'";
			if (mysql_query($update, $conex) or die(mysql_error()))
			{$conclusion = 1; }
				
		}
	}	
	if ($conclusion == 1){
		//echo '<script>  </script>';
	}		
				

}	
?>