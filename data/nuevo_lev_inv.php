
<?php include("conexion.php");
			$estatus = "A";
			$aplicado = "N";
			$cancelado = "N";
			$almacen_id = $_POST['almacen_id'];
			$id_usuario_creador = $_SESSION['logged_user'];
			date_default_timezone_set('America/Mexico_City');
			$fecha_hora_creacion = date("Y-m-d H:i:s");
			$fecha = date("Y-m-d");

	nuevo_inv($fecha,$fecha_hora_creacion,$id_usuario_creador,$estatus,$aplicado,$cancelado,$almacen_id);
	  
	
     function nuevo_inv($fecha,$fecha_hora_creacion,$id_usuario_creador,$estatus,$aplicado,$cancelado,$almacen_id){ // Funcion para agregar una relacion nueva
global $conex;

$id_empresa = id_empresa_almacen($almacen_id);

$datos = '';
$consulta_inventarios = "
				SELECT *
				FROM inventarios
				WHERE estatus = 'A' 
				AND almacen_id = '$almacen_id'	
				AND cancelado <> 'S'";	

$resultado = mysql_query($consulta_inventarios, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);
$lista_almacenes = lista_almacenes_consigna();
$cantidad_almacenes = count($lista_almacenes);
$ocultar = "";
if ($total_rows > 0){
	// si encuentra un inventario abierto obtiene los datos y un alert diciendo que se genero un levan
			
	$fecha = $row['fecha'];
	$fecha_hora_creacion = $row['fecha_hora_creacion'];
	$usuario_creador = Nombre($row['id_usuario_creador']);
	$estatus = 'Abierto';
	$folio = 'Se asigna al cerrar el Lev. Inv.';
	$aplicado = 'No';
	$cancelado = 'No';
	if ($row['aplicado'] == "S"){
		$aplicado = 'Si';	
	}
	if ($row['cancelado'] == "S"){
		$cancelado = 'Si';	
	}
	$opciones = "";
	$contar = 0;
	foreach($lista_almacenes as $id_almacen => $almacen){
		if ($row['almacen_id'] == $id_almacen){
			$opciones .= '		<option value=\"'.$id_almacen.'\" selected >'.$almacen.'</option>';	
			$ocultar .= '$("#btninvnew_'.$id_almacen.'").hide();';
			
			$contar++;
		}
		else 
		{
			/* $opciones .= '<option value=\"'.$id_almacen.'\">'.$almacen.'</option>';	 */
		}
			
	}	
		if($contar == $cantidad_almacenes){
			$ocultar .= '$("#btn_nuevo_inv").hide();';
		}
	$datos = '	<table class=\"table \"><thead><tr class=\"bg-info\"><td>Fecha y Hora </td><td>Usuario Creador </td><td>Estatus </td><td>Folio </td><td>Almacen Aplicado</td><!--		<td>Aplicado </td><td>Cancelado </td><td>P.O. </td><td>Remision </td><td>Factura </td> --> </tr> </thead> <tbody> <tr><td>'.$fecha_hora_creacion.' </td><td>'.$usuario_creador.' </td><td>'.$estatus.' </td><td>'.$folio.' </td><td><select class=\"select form-control\" id=\"select_almacen\" onchange=\"cambio_almacen();\">'.$opciones.'</select> </td><!--		<td>'.$aplicado.' </td><td>'.$cancelado.' </td><td>P.O. </td><td>Remision </td><td>Factura </td> --> </tr> </tbody> </table>';
   echo '<script> 
		//$("#btn_nuevo_inv").hide();
		$("#div_datos_inventario_actual").html("'.$datos.'");
		'.$ocultar.'
		//alert("Se ha creado un Levantamiento antes de que intenta crear este, no puede haber 2 Levantamientos en curso!, ya puede capturar los conteos de productos");
		lista_articulos_almacen(11,'.$row['id_inventario'].','.$row['almacen_id'].');
</script>'; 

 
} 
else /// sin resultados
{
	
	$insert_inv = "INSERT INTO inventarios (fecha,fecha_hora_creacion,id_usuario_creador,estatus,aplicado,cancelado,almacen_id,id_empresa)
					VALUES ('$fecha','$fecha_hora_creacion','$id_usuario_creador','$estatus','$aplicado','$cancelado','$almacen_id','$id_empresa')";
	if (mysql_query($insert_inv, $conex) or die(mysql_error()))
	{
		$id_inventario = mysql_insert_id();
		$usuario_creador = Nombre($id_usuario_creador);
		$estatus = 'Abierto';
		$folio = 'Se asigna al cerrar el Lev. Inv.';	
		$aplicado = 'No';
		$cancelado = 'No';	
		$opciones = "";
		$contar =0;
		
		$lista_almacenes = lista_almacenes_consigna();
		$cantidad_almacenes = count($lista_almacenes);
		$lista_almacenes_inventario = lista_almacenes_inventario();
		$cantidad_almacenes_inventario = count($lista_almacenes_inventario);
		
		
		foreach($lista_almacenes_inventario as $id_almacen => $almacen){
			if ($almacen_id == $id_almacen){
				$opciones .= '<option value=\"'.$id_almacen.'\" selected>'.$almacen.'</option>';
				$ocultar .= '$("#btninvnew_'.$id_almacen.'").hide();';
				
			}
			else 
			{
				$opciones .= '<option value=\"'.$id_almacen.'\">'.$almacen.'</option>';	 
			}
			
		}	
		if($cantidad_almacenes_inventario == $cantidad_almacenes){
			$ocultar .= '$("#btn_nuevo_inv").hide();';
		}
		$datos = '	<table class=\"table \"> <thead><tr class=\"bg-info\"><td>Fecha y Hora </td> <td>Usuario Creador </td><td>Estatus </td><td>Folio </td> <td>Almacen aplicado </td><!--	<td>Aplicado </td> <td>Cancelado </td> 	<td>P.O. </td> <td>Remision </td> <td>Factura </td> --> </tr> </thead> <tbody> <tr> <td>'.$fecha_hora_creacion.' </td> <td>'.$usuario_creador.' </td> <td>'.$estatus.' </td> <td>'.$folio.' </td> <td><select class=\"select form-control\" id=\"select_almacen\" onchange=\"cambio_almacen();\">'.$opciones.'</select> </td><!--	<td>'.$aplicado.' </td><td>'.$cancelado.' </td> <td>P.O. </td><td>Remision </td><td>Factura </td> --> </tr> </tbody> </table>';
		echo '<script> 
					//$("#btn_nuevo_inv").hide();
					$("#div_datos_inventario_actual").html("'.$datos.'");
					'.$ocultar.'
					//alert("Se creo nuevo Levantamiento de Inventario, ya puede comenzar a capturar conteos de productos");
					$("#btn_cerrar_inv").show();
					$("#btn_cancelar_inv").show();
					$("#txt_id_inventario_activo").val('.$id_inventario.');
					lista_articulos_almacen(11,'.$id_inventario.','.$almacen_id.');
		
			</script>'; 
	
	}
	

}
		

			
		
}


?>