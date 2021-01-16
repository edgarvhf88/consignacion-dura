<?php include("conexion.php"); 
		
		$almacen_id = $_POST['almacen_id'];
     	
		verif_inv($almacen_id);
	  		 
     function verif_inv($almacen_id){ 
global  $conex;

if ($almacen_id == 0)
	{
				$consulta_inventarios = "
					SELECT 
						i.id_inventario as id_inventario,
						i.fecha as fecha,
						i.fecha_hora_creacion as fecha_hora_creacion,
						i.id_usuario_creador as id_usuario_creador,
						i.aplicado as aplicado,
						i.cancelado as cancelado,
						i.almacen_id as almacen_id,
						alm.almacen as almacen
						FROM inventarios i 
						INNER JOIN almacenes alm ON alm.almacen_id = i.almacen_id
						WHERE i.estatus = 'A' AND i.cancelado = 'N' AND i.aplicado = 'N'";
	}
	else
	{			$consulta_inventarios = "
					SELECT 
						i.id_inventario as id_inventario,
						i.fecha as fecha,
						i.fecha_hora_creacion as fecha_hora_creacion,
						i.id_usuario_creador as id_usuario_creador,
						i.aplicado as aplicado,
						i.cancelado as cancelado,
						i.almacen_id as almacen_id,
						alm.almacen as almacen
						FROM inventarios i 
						INNER JOIN almacenes alm ON alm.almacen_id = i.almacen_id
						WHERE i.estatus = 'A'  AND i.cancelado = 'N' AND i.aplicado = 'N' AND i.almacen_id = '$almacen_id'";
	}
	$resultado = mysql_query($consulta_inventarios, $conex) or die(mysql_error());
	$total_rows = mysql_num_rows($resultado);	
	
$lista_almacenes = lista_almacenes_consigna();
$cantidad_almacenes = count($lista_almacenes);
$lista_almacenes_inventario = lista_almacenes_inventario();
$cantidad_almacenes_inventario = count($lista_almacenes_inventario);
if ($total_rows > 0){
	if ($total_rows == 1){
	$row = mysql_fetch_assoc($resultado);	
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
	$ocultar = "";
	
	foreach($lista_almacenes_inventario as $id_almacen => $almacen){
		if ($row['almacen_id'] == $id_almacen){
			$opciones .= '<option value=\"'.$id_almacen.'\" selected>'.$almacen.'</option>';	
			$ocultar .= '$("#btninvnew_'.$id_almacen.'").hide();';	
		}
		else 
		{	
			$ocultar .= '$("#btninvnew_'.$id_almacen.'").show();';	
			 $opciones .= '<option value=\"'.$id_almacen.'\">'.$almacen.'</option>';	 
		}
		
	}
	if($cantidad_almacenes_inventario == $cantidad_almacenes){
			$ocultar .= '$("#btn_nuevo_inv").hide();';
		}
	 	
	$datos = '	<table class=\"table \"><thead><tr class=\"bg-info\"><td>Fecha y Hora </td><td>Usuario Creador </td><td>Estatus </td><td>Folio </td><td>Almacen en Invenatrio </td><!--		<td>Aplicado </td><td>Cancelado </td><td>P.O. </td><td>Remision </td><td>Factura </td> --> </tr> </thead> <tbody> <tr><td>'.$fecha_hora_creacion.' </td><td>'.$usuario_creador.' </td><td>'.$estatus.' </td><td>'.$folio.' </td><td><select class=\"select form-control\" id=\"select_almacen\" onchange=\"cambio_almacen();\">'.$opciones.'</select> </td><!--		<td>'.$aplicado.' </td><td>'.$cancelado.' </td><td>P.O. </td><td>Remision </td><td>Factura </td> --> </tr> </tbody> </table>';
   echo '<script> 
		$("#btn_nuevo_inv").show();
		$(".almacenes_sin_inventario_activo").show();
		'.$ocultar.'
		$("#div_datos_inventario_actual").html("'.$datos.'");
		//alert("Se ha creado un Levantamiento antes de que intenta crear este,  ya puede capturar los conteos de productos");
		$("#btn_cerrar_inv").show();
		$("#btn_cancelar_inv").show();
		$("#txt_id_inventario_activo").val('.$row['id_inventario'].');
		lista_articulos_almacen(11,'.$row['id_inventario'].','.$row['almacen_id'].');
</script>';  

	}
	else if ($total_rows > 1)
	{ //while para seleccionar el inventario activo de la planta que queramos
		$info = '<div class=\"col-lg-12\"> <h4> Actualmente existe mas de un inventario en curso, seleccione con cual quiere trabajar</h4></div>			<div class=\"col-lg-12\">';
		$ocultar = "";
		
		$contador_almacenes = 0; // comparacion cantidad de almacenes contra cantidad de almacenes que estan en proceso de inventario
		while($row2 = mysql_fetch_array($resultado,MYSQL_BOTH))
		{
			foreach($lista_almacenes as $id_almacen => $almacen){
				if ($row2['almacen_id'] == $id_almacen){
					$contador_almacenes++;	
					$ocultar .= '$("#btninvnew_'.$id_almacen.'").hide();';
				}else{
					$ocultar .= '$("#btninvnew_'.$id_almacen.'").show();';
				}
					
			}
			
			$info .= '<div class=\"col-lg-4 btn btn-warning\" onclick=\"varificar_captura('.$row2['almacen_id'].');\">'.$row2['almacen'].'</div>';
		}
		if($cantidad_almacenes_inventario == $cantidad_almacenes){
			$ocultar .= '$("#btn_nuevo_inv").hide();';
		}
		
		$info .= '</div>';
		echo '<script>
		$("#btn_nuevo_inv").show();
		'.$ocultar.'
		$("#div_datos_inventario_actual").html("'.$info.'");
		$("#div_localizar_articulo").html("");
		$("#btn_cerrar_inv").hide();
		$("#btn_cancelar_inv").hide();
		</script>';
	}
} 
else /// sin resultados
{
			// $(document).ready(function(){});
 echo '<script> 
		$("#btn_nuevo_inv").show();
		$(".almacenes_sin_inventario_activo").show();
		$("#div_datos_inventario_actual").html("<h4>Para iniciar seleccione el almacen en las opciones del boton \"Nueva Captura Inventario\" </h4>");
		$("#div_localizar_articulo").html("");
		$("#btn_cerrar_inv").hide();
		$("#btn_cancelar_inv").hide();
</script>'; 		
		


}
mysql_free_result($resultado);  
}




?>
