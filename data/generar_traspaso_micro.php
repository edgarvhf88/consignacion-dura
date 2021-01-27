<?php include("conexion.php");

if (isset($_POST['id_pedido_trapaso'])){
	
	$id_pedido_trapaso = $_POST['id_pedido_trapaso'];
	generar_traspaso($id_pedido_trapaso);
	
}

function recalc_saldo($articulo_id){
	global $conex, $con_micro;
			$del_saldo_in = "DELETE FROM SALDOS_IN WHERE ARTICULO_ID ='".$articulo_id."'";
 				try {
					$query_del = $con_micro->prepare($del_saldo_in);
					$query_del->execute();
				} 
				catch (PDOException $e){ print "DEL - Error!: " . $e->getMessage() . "<br/>"; die(); }
				if (!$query_del)
				{
					echo '<script> console.log("No se actualizo el siguiente folio"); </script>';
					exit;
				}
				else
				{
					//echo '<script> console.log("SE ELIMINO SALDOS_IN DE ARTICULO_ID"); </script>';
					$recal = "EXECUTE PROCEDURE RECALC_SALDOS_ART_IN('".$articulo_id."')";
					try {
						$query_recal = $con_micro->prepare($recal);
						$query_recal->execute();
					} 
					catch (PDOException $e){ print "RECALC - Error!: " . $e->getMessage() . "<br/>"; die(); }
					if (!$query_recal)
					{
						echo '<script> console.log("No se actualizo el siguiente folio"); </script>';
						exit;
					}
					else
					{
						//echo '<script> console.log("SE RECALCULO LA EXISTENCIA"); </script>';
					}	
				}
				
}	
function generar_traspaso($id_pedido_trapaso){
	global $conex, $con_micro;
//// Obtener datos para la remision 
	$consulta_pedido = "SELECT * 
						FROM pedido_traspaso WHERE id_pedido = '$id_pedido_trapaso' ";
		$resultado_pedido = mysql_query($consulta_pedido, $conex) or die(mysql_error());
		$row_pedido = mysql_fetch_assoc($resultado_pedido);
		$total_rows = mysql_num_rows($resultado_pedido);

date_default_timezone_set('America/Mexico_City');
$fecha_actual = date("d.m.Y");
$hora_actual = date("H:i:s");
$fecha_hora = date("Y/m/d H:i:s");
	echo '<script> 
$(document).ready(function()
{
	/////// Actualizar inventario por articulo
	function SyncInvArt(id_articulo,almacen_id)
	{
		$("#modal_cargando").modal("show");
		
			$.ajax({
			type: "post",
			url: "sincronizarinvart.php",
			data: {id_articulo:id_articulo,almacen_id:almacen_id},
			dataType: "html",
			success:  function (response) 
			{
				$("#resultados_js").html(response);
				$("#modal_cargando").modal("hide");
			}
		});
		
	};
});

</script>';
if ($total_rows > 0){
	$id_pedido_cliente = $row_pedido['id_pedido_cliente'];
//// inicia bucle con articulos de la solicitud de traspsaso
	$consulta_articulos = "SELECT indet.clave_microsip as clave, indet.cantidad as cantidad,  indet.precio_unitario as precio_unitario, indet.precio_total as precio_total, indet.id_microsip as id_microsip  
				FROM pedido_traspaso_det indet 
				WHERE indet.id_pedido = '$id_pedido_trapaso' ";
	$resultado_articulos = mysql_query($consulta_articulos, $conex) or die(mysql_error());
	$total_row = mysql_num_rows($resultado_articulos);
	$lista_invdet = array();
	$suma_totales = 0;
	while($row_arti = mysql_fetch_array($resultado_articulos,MYSQL_BOTH)) 
	{
		$lista_invdet[] = array("value" => $row_arti['clave'], 
							   "clave" => $row_arti['clave'], 
							   "id_microsip" => $row_arti['id_microsip'], 
							   "cantidad" => $row_arti['cantidad'], 
							   "costo_unitario" => $row_arti['precio_unitario'], 
							   "costo_total" => $row_arti['precio_total']);
	
		$suma_totales +=  $row_arti['precio_total'];
	}
	$total_total = $suma_totales;
///////////   SE INSERTA EL DOCUMENTO REMISION EN MICROSIP    /////////
$docto_id = -1; // existe un triger en la base que convierte el -1 en un ID irrepetible y consecutivo

$almacen_destino_id = $row_pedido['almacen_id']; // dura P3 = 11142	dura P4 = 11143	
$almacen_id = 19;
$centro_costo_id = "";
$folio = ObtenerFolioTraspaso();
$naturaleza_concepto = 'S';
$fecha = $fecha_actual; //"04.11.2019"; // GETDATE()
$hora =  $hora_actual; //"14:00:00";
$concepto_in_id = 36; // 36 es traspaso de salida
$concepto_in_id_e = 25; // 25 es traspaso de entrada
$descripcion = 'Solicitud de traspaso Folio: '.$row_pedido['folio'];
$importe_neto = $total_total;
$sistema_origen = 'IN';
$sucursal_id = Sucursal_Allpart();


$insertar = "INSERT INTO DOCTOS_IN 
(DOCTO_IN_ID,ALMACEN_ID,FOLIO,FECHA,DESCRIPCION,CONCEPTO_IN_ID,ALMACEN_DESTINO_ID,CENTRO_COSTO_ID, SISTEMA_ORIGEN,NATURALEZA_CONCEPTO,SUCURSAL_ID) VALUES  (:docto_id,:almacen_id,:folio,:fecha,:descripcion,:concepto_in_id,:almacen_destino_id,:centro_costo_id,:sistema_origen,:naturaleza_concepto,:sucursal_id)";

try {
	$query_insert = $con_micro->prepare($insertar);
	$query_insert->bindValue(':docto_id', $docto_id, PDO::PARAM_INT);
	$query_insert->bindValue(':almacen_id', $almacen_id, PDO::PARAM_INT);
	$query_insert->bindValue(':folio', $folio, PDO::PARAM_STR);
	$query_insert->bindValue(':fecha', $fecha, PDO::PARAM_STR);
	$query_insert->bindValue(':descripcion', $descripcion, PDO::PARAM_STR);
	$query_insert->bindValue(':concepto_in_id', $concepto_in_id, PDO::PARAM_INT);
	$query_insert->bindValue(':almacen_destino_id', $almacen_destino_id, PDO::PARAM_INT);
	$query_insert->bindValue(':centro_costo_id', $centro_costo_id, PDO::PARAM_INT);
	$query_insert->bindValue(':sistema_origen', $sistema_origen, PDO::PARAM_STR);
	$query_insert->bindValue(':naturaleza_concepto', $naturaleza_concepto, PDO::PARAM_STR);
	$query_insert->bindValue(':sucursal_id', $sucursal_id, PDO::PARAM_INT);
	$inserta = $query_insert->execute();
	
	$docto_in_id = ObtenerIdTraspaso($folio); 
   // print_r($query_insert);
  // echo $inserta;
} 
catch (PDOException $e){ print "Error!: " . $e->getMessage() . "<br/>"; die(); }


if (!$query_insert)
{ 	
echo '<script> console.log("No se inserto Traspaso"); </script>';
	exit;
}	
 else
{  //// AL TENER EXITO LA INSERCION DEL DOCUMENTO ENTONCES SE APLICA LAS SIGUIENTES INSERCIONES 
//	echo '<script> console.log("Se inserto el cabezera traspaso Folio: '.$folio.' id_traspaso: '.$docto_in_id.' -"); </script>';
	//echo $docto_id." -/- ".$almacen_id." -/- ".$folio." -/- ".$fecha." -/- ".$descripcion." -/- ".$concepto_in_id." -/- ".$almacen_destino_id." -/- ".$centro_costo_id."<br />";
	
		$tipo_movto = 'S';
		$tipo_movto_e = 'E';
		$metodo_costeo = 'C';
		$aplicado = 'S';
		$array_list_det_id = array();
	foreach($lista_invdet as $row_articulos) /// foreach de mov de salida traspaso
	{
		$clave_articulo = $row_articulos['clave'];
		$articulo_id = $row_articulos['id_microsip'];
		$unidades = $row_articulos['cantidad'];
		$unidades_a_surtir = $row_articulos['cantidad'];
		$costo_unitario = $row_articulos['costo_unitario'];
		$costo_total = $row_articulos['costo_total'];
			
		$insertar_det = "INSERT INTO DOCTOS_IN_DET (DOCTO_IN_DET_ID, DOCTO_IN_ID, CLAVE_ARTICULO, ARTICULO_ID, UNIDADES, COSTO_UNITARIO, COSTO_TOTAL, ALMACEN_ID, CONCEPTO_IN_ID, TIPO_MOVTO, METODO_COSTEO, CENTRO_COSTO_ID, APLICADO, ROL) VALUES (:docto_id,:docto_in_id,:clave_articulo,:articulo_id,:unidades,:costo_unitario,:costo_total,:almacen_id,:concepto_in_id,:tipo_movto,:metodo_costeo,:centro_costo_id,:aplicado,:rol)";
		
		///// insterta el movimiento de salida del almacen general
		try {
			$query_insert_det = $con_micro->prepare($insertar_det);
			$query_insert_det->bindParam(':docto_id', $docto_id, PDO::PARAM_INT);
			$query_insert_det->bindParam(':docto_in_id', $docto_in_id, PDO::PARAM_INT);
			$query_insert_det->bindParam(':clave_articulo', $clave_articulo, PDO::PARAM_STR, 20);
			$query_insert_det->bindParam(':articulo_id', $articulo_id, PDO::PARAM_INT);
			$query_insert_det->bindParam(':unidades', $unidades, PDO::PARAM_STR, 18);
			$query_insert_det->bindParam(':costo_unitario', $costo_unitario, PDO::PARAM_STR, 18);
			$query_insert_det->bindParam(':costo_total', $costo_total, PDO::PARAM_STR, 15);
			$query_insert_det->bindParam(':almacen_id', $almacen_id, PDO::PARAM_INT);
			$query_insert_det->bindParam(':concepto_in_id', $concepto_in_id, PDO::PARAM_INT);
			$query_insert_det->bindParam(':tipo_movto', $tipo_movto, PDO::PARAM_STR);
			$query_insert_det->bindParam(':metodo_costeo', $metodo_costeo, PDO::PARAM_STR);
			$query_insert_det->bindParam(':centro_costo_id', $centro_costo_id, PDO::PARAM_INT);
			$query_insert_det->bindParam(':aplicado', $aplicado, PDO::PARAM_STR);
			$query_insert_det->bindParam(':rol', $tipo_movto, PDO::PARAM_STR);
			$query_insert_det->execute();
			$id_det_art = ObtenerIdTraspasoDet($articulo_id,$docto_in_id,$concepto_in_id);
			$array_list_det_id[] = array("articulo_id" => $articulo_id, "docto_in_det_id" => $id_det_art);
		} 
		catch (PDOException $e){ print "Error!: " . $e->getMessage() . "<br/>"; die(); }	
		if (!$query_insert_det){
			echo '<script> console.log("No se inserto el articulo"); </script>';
			exit;
		}
		else
		{		
			//	echo '<script> console.log("Se inserto el articulo "); </script>';
		}
	}
	
	foreach($lista_invdet as $row_articulos) // foreacha de movimientos de entrada
	{
		$clave_articulo = $row_articulos['clave'];
		$articulo_id = $row_articulos['id_microsip'];
		$unidades = $row_articulos['cantidad'];
		$unidades_a_surtir = $row_articulos['cantidad'];
		$costo_unitario = $row_articulos['costo_unitario'];
		$costo_total = $row_articulos['costo_total'];
			
		$insertar_det_s = "INSERT INTO DOCTOS_IN_DET (DOCTO_IN_DET_ID, DOCTO_IN_ID, CLAVE_ARTICULO, ARTICULO_ID, UNIDADES, COSTO_UNITARIO, COSTO_TOTAL, ALMACEN_ID, CONCEPTO_IN_ID, TIPO_MOVTO, METODO_COSTEO, CENTRO_COSTO_ID, APLICADO, ROL) VALUES (:docto_id,:docto_in_id,:clave_articulo,:articulo_id,:unidades,:costo_unitario,:costo_total,:almacen_id,:concepto_in_id,:tipo_movto,:metodo_costeo,:centro_costo_id,:aplicado,:rol)";
	
		///// insterta el movimiento de Entrada al Almacen 11142(planta 3) o 111143(planta 4)
		try {
			$query_insert_det_e = $con_micro->prepare($insertar_det_s);
			$query_insert_det_e->bindParam(':docto_id', $docto_id, PDO::PARAM_INT);
			$query_insert_det_e->bindParam(':docto_in_id', $docto_in_id, PDO::PARAM_INT);
			$query_insert_det_e->bindParam(':clave_articulo', $clave_articulo, PDO::PARAM_STR, 20);
			$query_insert_det_e->bindParam(':articulo_id', $articulo_id, PDO::PARAM_INT);
			$query_insert_det_e->bindParam(':unidades', $unidades, PDO::PARAM_STR, 18);
			$query_insert_det_e->bindParam(':costo_unitario', $costo_unitario, PDO::PARAM_STR, 18);
			$query_insert_det_e->bindParam(':costo_total', $costo_total, PDO::PARAM_STR, 15);
			$query_insert_det_e->bindParam(':almacen_id', $almacen_destino_id, PDO::PARAM_INT);
			$query_insert_det_e->bindParam(':concepto_in_id', $concepto_in_id_e, PDO::PARAM_INT);
			$query_insert_det_e->bindParam(':tipo_movto', $tipo_movto_e, PDO::PARAM_STR);
			$query_insert_det_e->bindParam(':metodo_costeo', $metodo_costeo, PDO::PARAM_STR);
			$query_insert_det_e->bindParam(':centro_costo_id', $centro_costo_id, PDO::PARAM_INT);
			$query_insert_det_e->bindParam(':aplicado', $aplicado, PDO::PARAM_STR);
			$query_insert_det_e->bindParam(':rol', $tipo_movto_e, PDO::PARAM_STR);
			$query_insert_det_e->execute();
			$sub_movto_id = ObtenerIdTraspasoDet($articulo_id,$docto_in_id,$concepto_in_id_e);
		} 
		catch (PDOException $e){ print "Error!: " . $e->getMessage() . "<br/>"; die(); }	
		if (!$query_insert_det_e){
		
			echo '<script> console.log("No se inserto el articulo"); </script>';
			exit;
		}
		else
		{	
			foreach($array_list_det_id as $ids){
				if ($ids['articulo_id'] == $articulo_id)
				{	// al encontrar el id del articulo tomara el id_det_art del articulo
					// y lo insertara en la tabla SUB_MOVTOS_IN(DOCTO_IN_DET_ID,SUB_MOVTO_ID)
					$docto_in_det_id = $ids['docto_in_det_id'];
					$update_smi = "INSERT INTO SUB_MOVTOS_IN (DOCTO_IN_DET_ID,SUB_MOVTO_ID) VALUES (:docto_in_det_id,:sub_movto_id)";
 
					try {
						$query_smi = $con_micro->prepare($update_smi);
						$query_smi->bindValue(':docto_in_det_id', $docto_in_det_id, PDO::PARAM_INT);
						$query_smi->bindValue(':sub_movto_id', $sub_movto_id, PDO::PARAM_INT);
						$query_smi->execute();
					} 
					catch (PDOException $e){ print "Error!: " . $e->getMessage() . "<br/>"; die(); }
					if (!$query_smi)
					{
							echo '<script> console.log("No se actualizo el siguiente folio"); </script>';
						exit;
					}
					else
					{
						
						//echo '<script> console.log("Exito"); </script>';
					}
				}
			}
			
			//	echo '<script> console.log("Se inserto el articulo "); </script>';
			
		}
		
	}

	////// // INSERTA EL SIGUIENTE NUMERO DE FOLIO ///////
	
	$folio_cosecutivo = $folio + 1;
	$consecutivo = Format9digit($folio_cosecutivo);
	$update_folio = "UPDATE FOLIOS_CONCEPTOS SET CONSECUTIVO = :consecutivo WHERE CONCEPTO_ID = '36'";
 
	try {
		$query_update_folio = $con_micro->prepare($update_folio);
		$query_update_folio->bindValue(':consecutivo', $consecutivo, PDO::PARAM_STR);
		$query_update_folio->execute();
	} 
	catch (PDOException $e){ print "Error!: " . $e->getMessage() . "<br/>"; die(); }
	if (!$query_update_folio)
	{
			echo '<script> console.log("No se actualizo el siguiente folio"); </script>';
		exit;
	}
	 else
	{
		//echo '<script> console.log("Se actualizo el folio a: '.$consecutivo.'"); </script>';
	}
	////// // INSERTA DOCTO_IN_ID EN TABLA LIBRES_SALIDAS_IN ///////
	
	$update_LIB = "INSERT INTO LIBRES_SALIDAS_IN (DOCTO_IN_ID) VALUES (:docto_in_id)";
 
	try {
		$query_LIB = $con_micro->prepare($update_LIB);
		$query_LIB->bindValue(':docto_in_id', $docto_in_id, PDO::PARAM_INT);
		$query_LIB->execute();
	} 
	catch (PDOException $e){ print "Error!: " . $e->getMessage() . "<br/>"; die(); }
	if (!$query_LIB)
	{
			echo '<script> console.log("No se actualizo el siguiente folio"); </script>';
		exit;
	}
	 else
	{
		//echo '<script> console.log("Exito"); </script>';
	}
	
	 ///////////   APLICA EL DOCUMENTO PARA QUE SE DESCUENTE EL INVENTARIO  ///////// 
	$integracion = 'S';
	$costeo = 'C';
	$aplicar = "EXECUTE PROCEDURE APLICA_DOCTO_IN(:V_DOCTO_IN_ID)";
 
	try {
		$query_aplicar = $con_micro->prepare($aplicar);
		$query_aplicar->bindParam(':V_DOCTO_IN_ID', $docto_in_id, PDO::PARAM_INT);
		
		$query_aplicar->execute();

	} 
	catch (PDOException $e){ print "Error!: " . $e->getMessage() . "<br/>"; die(); }
	if (!$query_aplicar)
	{
		echo '<script> console.log("No se Aplico el traspaso"); </script>';
		exit;
	}
	 else
	{
			foreach($lista_invdet as $row_articulos) // foreacha de movimientos de entrada
			{
				
				$articulo_id = $row_articulos['id_microsip'];
				// codigo para recalculo de saldos de los articulos /*/*//*/*/*/*/*-/-*/-/-*/*-/-/-*/*/
					//$articulo_id = 6230;
				recalc_saldo($articulo_id);
				//echo '<script> SyncInvArt('.$articulo_id.','.$almacen_destino_id.'); </script>';
			}
			//// se cambioa el estatus de la solicitud de traspaso
			$sql_upfolio = "UPDATE pedido_traspaso SET folio_traspaso = '$folio', estatus = '2' WHERE id_pedido = '$id_pedido_trapaso' ";
			if (mysql_query($sql_upfolio, $conex) or die(mysql_error())){}
			//// se cambioa el estatus de pedido cliente a enviando delivering para que el almacenista se le active la opcion de recibirlo, despues de recibir confirmara su estatus y si no esta al 100 lo marcara de nuevo a estatus 1 de  lo contrario si esta al 100 entonces se cambiara a estatus 3
			$sql_upped = "UPDATE pedidos SET estatus = '2' WHERE id = '$id_pedido_cliente' ";
			if (mysql_query($sql_upped, $conex) or die(mysql_error())){
				// 
			}

		echo '<script> console.log("SE APLICO EL TRASPASO CORRECTAMENTE FOLIO: '.$folio.'"); 
		     
			
			$("#modal_cargando").modal("hide");
			SincronizarInventario('.$id_pedido_cliente.','.$almacen_destino_id.');
			 setTimeout(function(){
							$("#traspaso_detalle").modal("hide");
						},200,"JavaScript");
			 setTimeout(function(){
							 lista_solicitudes_traspaso();
						},200,"JavaScript");
			 
			 </script>';
			 
	}
  
} /// si se inserta el pedido en microsip

} /// validacion de totalrows
}  //funcion
?>