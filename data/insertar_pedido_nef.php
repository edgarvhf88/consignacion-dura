<?php include("conexion.php");

/* function Format9digit($folio)
{	
switch(strlen($folio)){
		
			case 1: 
			$folio_siguiente = "00000000".$folio;
			break;
			case 2:
			$folio_siguiente = "0000000".$folio;
			break;
			case 3:
			$folio_siguiente = "000000".$folio;
			break;
			case 4:
			$folio_siguiente = "00000".$folio;
			break;
			case 5:
			$folio_siguiente = "0000".$folio;
			break;
			case 6:
			$folio_siguiente = "000".$folio;
			break;
			case 7:
			$folio_siguiente = "00".$folio;
			break;
			case 8:
			$folio_siguiente = "0".$folio;
			break;
			case 9:
			$folio_siguiente = $folio;
			break;
		 
	 }
return $folio_siguiente; 

} */

function ObtenerFolioPed()
{ 
global $con_micro_nef;
$folio = "";	
//$valor = 88878; 
$valor = 45076; // folio de pedido
$sql = "SELECT A.CONSECUTIVO 	
FROM FOLIOS_VENTAS A
WHERE (A.FOLIO_VENTAS_ID = '".$valor."')";

$consulta = $con_micro_nef->prepare($sql);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
$row_result = $consulta->fetch(PDO::FETCH_ASSOC);
if (!$consulta){
	 	 exit;}	
$folio_siguiente = $row_result['CONSECUTIVO'];
return $folio_siguiente;
}

function ObtenerIdPed($folio){ 
global $con_micro_nef;
$tipo_docto = "P"; // PEDIDO	
$sql = "SELECT A.DOCTO_VE_ID AS DOCTO_VE_ID	
FROM DOCTOS_VE A
WHERE (A.FOLIO = '".$folio."') AND (A.TIPO_DOCTO = '".$tipo_docto."')";
$consulta = $con_micro_nef->prepare($sql);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
$row_result = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$consulta){
	 exit;}	
$docto_ve_id = $row_result['DOCTO_VE_ID'];
return $docto_ve_id;
}

if ((isset($_POST['id_pedido'])) && ($_POST['id_pedido'] != "")){
	// si esta declarado el id_pedido y es diferente a nada entonces buscara el pedido para obtener los datos
	$id_pedido = $_POST['id_pedido'];
		//$fecha_actual = date("Y-m-d H:i:s");
		$fecha_actual = date("d.m.Y");
		$hora_actual = date("H:i:s");
		$fecha_hora = date("Y/m/d H:i:s");
	$docto_ve_id = "";	
	$docto_id = -1; // existe un triger en la base que convierte el -1 en un ID irrepetible y consecutivo
	$almacen_id = 19; // SE ASIGNA ABAJO
	$cliente_id = 307461; //cliente_id de AllPart matamoros
	$clave_cliente = "ALLMAT";
	$vendedor_id = 0;  //// CREAR UN VENDEDOR
	$cond_pago_id = 219; // 30 dias de credito para 0 dias = 209
	$dir_cli_id = 307463; // direccion 
	$dir_consig_id = 307463; // direccion 
	$moneda_id = 1; // MXN
	$tipo_docto = "P"; // PEDIDO
	$folio = ObtenerFolioPed(); // FOLIO CONSECUTIVO desde tabla
	$folio_cosecutivo = $folio;// FOLIO CONSECUTIVO para sumarle uno y actualizar la tabla de los folios
	$folio_PedNef = $folio;// FOLIO Pedido NEF para registrar en el sistema y relacionarlo
	$folio = Format9digit($folio);
	$fecha = $fecha_actual; //"28.03.2019"; // GETDATE()
	$hora = $hora_actual; // "14:05:00";
	$estatus = "P"; // PENDIENTE  /// C = CANCELADO // S = SURTIDO
	$orden_compra = ""; // ORDEN DE COMPRA ADJUNTADA - se asigna abajo
	$importe_neto = 0; // TOTAL EN PEDIDO - se asigna abajo
	$total_impuestos = "8"; // IMPUESTO MANEJADO
	$sistema_origen = "VE"; // SISTEMA_ORIGEN
	$tipo_dscto = "P"; // porcentual
	
	$consulta_pedido = "SELECT pn.almacen_id as almacen_id, pn.id_pedido_cliente as id_pedido_cliente, alm.almacen as almacen, pn.total_pedido as total_pedido
						FROM pedido_nef pn
						INNER JOIN almacenes alm ON alm.almacen_id = pn.almacen_id
						WHERE pn.id_pedido = '$id_pedido'";
	$resultado_pedido = mysql_query($consulta_pedido, $conex) or die(mysql_error());
	$row_p = mysql_fetch_assoc($resultado_pedido);
	$total_rows_p = mysql_num_rows($resultado_pedido);
	
	if ($total_rows_p > 0){
		// solo si encuentra el pedido entonces procede a insertarlo
		//$almacen_id = $row_p['almacen_id']; // el almacen es el de nef almacen general, id = 19
		$orden_compra = $row_p['almacen'];
		$importe_neto = $row_p['total_pedido'];
		$total_impuestos = $row_p['total_pedido'] * 0.08; 
	
		$insertar = "INSERT INTO DOCTOS_VE 
		(DOCTO_VE_ID, ALMACEN_ID, CLIENTE_ID, CLAVE_CLIENTE, COND_PAGO_ID, DIR_CLI_ID, DIR_CONSIG_ID, MONEDA_ID, TIPO_DOCTO, FOLIO, FECHA, HORA, ESTATUS, ORDEN_COMPRA, IMPORTE_NETO, TOTAL_IMPUESTOS, SISTEMA_ORIGEN, TIPO_DSCTO) VALUES (:docto_id,:almacen_id,:cliente_id,:clave_cliente,:cond_pago_id,:dir_cli_id,:dir_consig_id,:moneda_id,:tipo_docto,:folio,:fecha,:hora,:estatus,:orden_compra,:importe_neto,:total_impuestos,:sistema_origen,:tipo_dscto)";
		
		//echo "folio registrado de pedido = ".$folio."<br/>";
		
		try {
		$query_insert = $con_micro_nef->prepare($insertar);
		$query_insert->bindParam(':docto_id', $docto_id, PDO::PARAM_INT);
		$query_insert->bindParam(':almacen_id', $almacen_id, PDO::PARAM_INT);
		$query_insert->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
		$query_insert->bindParam(':clave_cliente', $clave_cliente, PDO::PARAM_STR, 20);
		$query_insert->bindParam(':cond_pago_id', $cond_pago_id, PDO::PARAM_INT);
		$query_insert->bindParam(':dir_cli_id', $dir_cli_id, PDO::PARAM_INT);
		$query_insert->bindParam(':dir_consig_id', $dir_consig_id, PDO::PARAM_INT);
		$query_insert->bindParam(':moneda_id', $moneda_id, PDO::PARAM_INT);
		$query_insert->bindParam(':tipo_docto', $tipo_docto, PDO::PARAM_STR, 1);
		$query_insert->bindParam(':folio', $folio, PDO::PARAM_STR, 9);
		$query_insert->bindParam(':fecha', $fecha, PDO::PARAM_STR);
		$query_insert->bindParam(':hora', $hora, PDO::PARAM_STR);
		$query_insert->bindParam(':hora', $hora, PDO::PARAM_STR);
		$query_insert->bindParam(':estatus', $estatus, PDO::PARAM_STR, 1);
		$query_insert->bindParam(':orden_compra', $orden_compra, PDO::PARAM_STR, 35);
		$query_insert->bindParam(':importe_neto', $importe_neto, PDO::PARAM_STR, 15);
		$query_insert->bindParam(':total_impuestos', $total_impuestos, PDO::PARAM_STR, 15);
		$query_insert->bindParam(':sistema_origen', $sistema_origen, PDO::PARAM_STR, 2);
		$query_insert->bindParam(':tipo_dscto', $tipo_dscto, PDO::PARAM_STR, 1);
		$query_insert->execute();
		
		 
		
		} catch (PDOException $e) {
		print "Error!: " . $e->getMessage() . "<br/>";
		die();
		}
		
		if (!$query_insert){
			echo '<script> console.log("No se pudo insertar pedido "); </script>';
			exit;
		}	
		else 
		{
			$docto_ve_id = ObtenerIdPed($folio);
			echo '<script> console.log("pedido nuevo insertado '.$docto_ve_id .'"); </script>'; 
			
			///// TAMBIEN SE TIENE QUE INSERTAR LOS DATOS ADICIONALES 
			$insertar_adicionales = "INSERT INTO LIBRES_PED_VE 
			(DOCTO_VE_ID,FECHAHORA,FOLIO,FECHA,ALMACENID,CLIENTEID,VENDEDORID,CLAVECLIENTE,ESTATUS) VALUES 
			(:DOCTO_VE_ID,:FECHAHORA,:FOLIO,:FECHA,:ALMACENID,:CLIENTEID,:VENDEDORID,:CLAVECLIENTE,:ESTATUS)";
			try {
				$query_insert_add = $con_micro_nef->prepare($insertar_adicionales);
				$query_insert_add->bindParam(':DOCTO_VE_ID', $docto_ve_id, PDO::PARAM_INT);
				$query_insert_add->bindParam(':FECHAHORA', $fecha_hora, PDO::PARAM_STR, 30);
				$query_insert_add->bindParam(':FOLIO', $folio, PDO::PARAM_STR, 10);
				$query_insert_add->bindParam(':FECHA', $fecha_actual, PDO::PARAM_STR);
				$query_insert_add->bindParam(':ALMACENID', $almacen_id, PDO::PARAM_STR, 10);
				$query_insert_add->bindParam(':CLIENTEID', $cliente_id, PDO::PARAM_STR, 10);
				$query_insert_add->bindParam(':VENDEDORID', $vendedor_id, PDO::PARAM_STR, 10);
				$query_insert_add->bindParam(':CLAVECLIENTE', $clave_cliente, PDO::PARAM_STR, 20);
				$query_insert_add->bindParam(':ESTATUS', $estatus, PDO::PARAM_STR, 1);
				$query_insert_add->execute();
			} 
			catch (PDOException $e){ print "Error!: " . $e->getMessage() . "<br/>"; die(); }
			if (!$query_insert_add)
			{ 	
			echo '<script> console.log("No se insertaron datos adicionales"); </script>';
				exit;
			}else{
				echo '<script> console.log("Se insertaron datos adicionales"); </script>';
				
			}
	
			
			/// inserta partidas de pedido
			$consulta_det = "SELECT a.clave_microsip_nef as clave_microsip_nef, pd.articulo as articulo, pd.cantidad as cantidad, pd.precio_unitario as precio_unitario, pd.precio_total as precio_total, pd.unidad_medida as unidad_medida, a.id_microsip_nef as id_microsip_nef	
					FROM pedido_nef_det pd
					INNER JOIN articulos a ON a.id = pd.id_articulo
					WHERE pd.id_pedido = '$id_pedido'";			
			$resultado = mysql_query($consulta_det, $conex) or die(mysql_error());
			$total_rows = mysql_num_rows($resultado);
			
			$unidades_a_surtir = "0";
			$clave_articulo = "";
			$articulo_id = "";
			$unidades = "";
			$precio_unitario = "";
			$precio_total_neto = "";
			$posicion = 0;
			if ($total_rows > 0)
			{ // con resultados
				while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) 
				{ 
					$articulo_id = $row['id_microsip_nef'];
					$clave_articulo = $row['clave_microsip_nef'];
					$unidades = $row['cantidad'];
					$precio_unitario = $row['precio_unitario'];
					$precio_total_neto = $row['precio_total'];
					$posicion++;
					/// insertara las partidas del pedido del cliente al pedido NEF
					$insertar_det = "INSERT INTO DOCTOS_VE_DET (DOCTO_VE_DET_ID, DOCTO_VE_ID, CLAVE_ARTICULO, ARTICULO_ID, UNIDADES, UNIDADES_A_SURTIR, PRECIO_UNITARIO, PRECIO_TOTAL_NETO, POSICION) VALUES (:docto_id,:docto_ve_id,:clave_articulo,:articulo_id,:unidades,:unidades_a_surtir,:precio_unitario,:precio_total_neto, :posicion)";
					
					try {
					$query_insert_det = $con_micro_nef->prepare($insertar_det);
					$query_insert_det->bindParam(':docto_id', $docto_id, PDO::PARAM_INT);
					$query_insert_det->bindParam(':docto_ve_id', $docto_ve_id, PDO::PARAM_INT);
					$query_insert_det->bindParam(':clave_articulo', $clave_articulo, PDO::PARAM_STR, 20);
					$query_insert_det->bindParam(':articulo_id', $articulo_id, PDO::PARAM_INT);
					$query_insert_det->bindParam(':unidades', $unidades, PDO::PARAM_STR, 18);
					$query_insert_det->bindParam(':unidades_a_surtir', $unidades_a_surtir, PDO::PARAM_STR, 18);
					$query_insert_det->bindParam(':precio_unitario', $precio_unitario, PDO::PARAM_STR, 18);
					$query_insert_det->bindParam(':precio_total_neto', $precio_total_neto, PDO::PARAM_STR, 15);
					$query_insert_det->bindParam(':posicion', $posicion, PDO::PARAM_INT);
					
					$query_insert_det->execute();
					
					
					} catch (PDOException $e) {
					print "Error!: " . $e->getMessage() . "<br/>";
					die();
					}	
					if (!$query_insert_det){
					
					//echo "No se pudo insertar pedido det";
					echo '<script> console.log("No se pudo insertar pedido det"); </script>';
					exit;
					}
					else
					{
						//echo "<br/> pedido detalle insertado";
						echo '<script> console.log("pedido detalle insertado"); </script>';
					} 
					
				} // end while
			} // con resultados de busqueda de pedido detalle
			
			 ///////////   APLICA EL DOCUMENTO DE DOCTOS_VE PARA QUE SE DESCUENTE EL INVENTARIO  ///////// 
		
			$aplicar = "EXECUTE PROCEDURE APLICA_DOCTO_VE(:V_DOCTO_VE_ID)";
		
			try {
				$query_aplicar = $con_micro_nef->prepare($aplicar);
				$query_aplicar->bindParam(':V_DOCTO_VE_ID', $docto_ve_id, PDO::PARAM_INT);
				
				$query_aplicar->execute();
		
			} 
			catch (PDOException $e){ print "Error!: " . $e->getMessage() . "<br/>"; die(); }
			if (!$query_aplicar)
			{
				echo '<script> console.log("No se Aplico EL PEDIDO"); </script>';
				exit;
			}
			else
			{
				echo '<script> console.log("SE APLICO EL PEDIDO CORRECTAMENTE '.$docto_ve_id.' FOLIO: '.$folio.'"); 
					</script>'; 
			}
			
				/// actualiza folio siguiente en tabla de folios
				$folio_ventas_id = 45076; 
				$folio_cosecutivo++;
				$consecutivo = Format9digit($folio_cosecutivo);
				$update_folio = "UPDATE FOLIOS_VENTAS SET CONSECUTIVO = :consecutivo WHERE FOLIO_VENTAS_ID = '".$folio_ventas_id."' ";
		
				try {
				$query_update_folio = $con_micro_nef->prepare($update_folio);
				$query_update_folio->bindParam(':consecutivo', $consecutivo, PDO::PARAM_STR, 9);
				$query_update_folio->execute();
				} catch (PDOException $e) {
				print "Error!: " . $e->getMessage() . "<br/>";
				die();
				}
				if (!$query_update_folio){
			
				//echo "No se actualizar folio consecutivo";
				exit;
				}
				else
				{
					//echo "<br/> se actualizo el folio a: ".$consecutivo; 
				}
				//***/**/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/**/*/*/*/
				//// actualiza folio de pedio nef en tabla de pedidos en sistema consigna
				$update_ped_folio = "UPDATE pedido_nef SET folio_pedido_microsip='$folio_PedNef' WHERE id_pedido='$id_pedido'";
				if (mysql_query($update_ped_folio, $conex) or die(mysql_error()))
				{
				echo '<script> $("#modal_cargando").modal("hide"); lista_pedidos_nef();  </script>';
				
				}
		}/// insert success
	}

}else {
	// ni no existe el dato id_pedido o es igual a nada entonces no realizara la insercion.
	
}

?>