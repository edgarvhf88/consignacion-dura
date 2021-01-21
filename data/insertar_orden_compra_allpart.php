<?php include("../data/conexion.php"); 




//OBTENER EL FOLIO SIGUIENTE DE UNA RECEPCION 
function ObtenerFolioOC()
{ 
global $con_micro;
$folio = "";	
//$valor = 88878; 
$valor = 1628; // folio de COMPRAS
$sql = "SELECT A.CONSECUTIVO 	
FROM FOLIOS_COMPRAS A
WHERE (A.FOLIO_COMPRAS_ID = '".$valor."')";

$consulta = $con_micro->prepare($sql);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
$row_result = $consulta->fetch(PDO::FETCH_ASSOC);
if (!$consulta){
	 	 exit;}	
$folio_siguiente = $row_result['CONSECUTIVO'];
return $folio_siguiente;
}


//obtengo el id de la recepcion insertada 
function ObtenerIdOc($folio){ 
global $con_micro;
$tipo_docto = "O"; // RECEPCION	
$sql = "SELECT A.DOCTO_CM_ID AS DOCTO_CM_ID	
FROM DOCTOS_CM A
WHERE (A.FOLIO = '".$folio."') AND (A.TIPO_DOCTO = '".$tipo_docto."')";
$consulta = $con_micro->prepare($sql);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
$row_result = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$consulta){
	 exit;}	
$docto_cm_id = $row_result['DOCTO_CM_ID'];
return $docto_cm_id;
}







//doy un folio para hacer una orden de compra
if ((isset($_POST['folio'])) && ($_POST['folio'] != "")){
	
	
	//variables para la cabecera
	$folio_ped=$_POST['folio'];
	$folio_ped_bus = Format9digit($folio_ped);
	$fecha_actual = date("d.m.Y");
	$hora_actual = date("H:i:s");
	$fecha_hora = date("Y/m/d H:i:s");
	$docto_ve_id = "";	 
	$docto_id = -1; // existe un triger en la base que convierte el -1 en un ID irrepetible y consecutivo
	$tipo_cambio="1";
	$descripcion="Orden generada por el sistema de consigancion Dura";
	$usuario_creacion="ELIZABETHO";
	$almacen_id = 19; // SE ASIGNA ABAJO
	$sucursal_id = 14059; // SE ASIGNA ABAJO
	$proveedor_id = 1768; //cliente_id de AllPart matamoros
	$clave_prov = "NEFMAT";
	$cond_pago_id = 1624; // 30 dias de credito para 0 dias = 209
	$moneda_id = 1; // MXN
	$tipo_docto = "O"; // orden de compra
	$folio = ObtenerFolioOC(); // FOLIO CONSECUTIVO desde tabla
	$folio_cosecutivo = $folio;// FOLIO CONSECUTIVO para sumarle uno y actualizar la tabla de los folios
	$folio = Format9digit($folio);
	$fecha = $fecha_actual; //"28.03.2019"; // GETDATE()
	$hora = $hora_actual; // "14:05:00";
	$estatus = "P"; // PENDIENTE  /// C = CANCELADO // S = SURTIDO
	$folio_prov = $folio_ped; // ORDEN DE COMPRA ADJUNTADA - se asigna abajo
	$importe_neto = 0; // TOTAL EN PEDIDO - se asigna abajo
	$total_impuestos = "8"; // IMPUESTO MANEJADO
	$sistema_origen = "CM"; // SISTEMA_ORIGEN
	$tipo_dscto = "P"; // porcentual
	$subtipo_docto='N';
	$forma_emitida='N';
	$contabilizado='N';
	$acreditar_cxp='N';
	
	/////////////--------------///////////////------////////////-----/////////////////////////		
	//consulta si el pedido existe en nef
	$consulta_pedido_ms = "SELECT *
						FROM DOCTOS_VE
						WHERE FOLIO = '$folio_ped_bus' AND TIPO_DOCTO ='P'";
	
	$resultado_pedido = $con_micro_nef->prepare($consulta_pedido_ms);
	$resultado_pedido->execute();
	$resultado_ped = $resultado_pedido->fetchAll(PDO::FETCH_ASSOC);
	
	foreach($resultado_ped as $row_p){
		
		// solo si encuentra el pedido entonces procede a insertarlo
		//$almacen_id = $row_p['almacen_id']; // el almacen es el de nef almacen general, id = 19
		$importe_neto = $row_p['IMPORTE_NETO'];
	$total_impuestos = $row_p['TOTAL_IMPUESTOS'] * 0.08; }
	
	if(!$resultado_ped){exit;}
	else{
	//INSERTO LA orden de compra
		$insertar = "INSERT INTO DOCTOS_CM 
		(DOCTO_CM_ID, TIPO_CAMBIO, DESCRIPCION, USUARIO_CREADOR, ALMACEN_ID, SUCURSAL_ID, PROVEEDOR_ID, CLAVE_PROV, COND_PAGO_ID,  MONEDA_ID, TIPO_DOCTO, FOLIO, FECHA, ESTATUS, FOLIO_PROV, IMPORTE_NETO, TOTAL_IMPUESTOS, SISTEMA_ORIGEN, TIPO_DSCTO, SUBTIPO_DOCTO, FORMA_EMITIDA, CONTABILIZADO, ACREDITAR_CXP) VALUES (:docto_id,:tipo_cambio,:descripcion,:usuario_creador,:almacen_id,:sucursal_id,:proveedor_id,:clave_prov,:cond_pago_id,:moneda_id,:tipo_docto,:folio,:fecha,:estatus,:folio_prov,:importe_neto,:total_impuestos,:sistema_origen,:tipo_dscto, :subtipo_docto, :forma_emitida, :contabilizado, :acreditar_cxp)";
try {
		$query_insert = $con_micro->prepare($insertar);
		$query_insert->bindParam(':docto_id', $docto_id, PDO::PARAM_INT);
		$query_insert->bindParam(':almacen_id', $almacen_id, PDO::PARAM_INT);
		$query_insert->bindParam(':tipo_cambio', $tipo_cambio, PDO::PARAM_STR, 18);
		$query_insert->bindParam(':descripcion', $descripcion, PDO::PARAM_STR, 200);
		$query_insert->bindParam(':usuario_creador', $usuario_creador, PDO::PARAM_STR, 31);
		$query_insert->bindParam(':sucursal_id', $sucursal_id, PDO::PARAM_INT);
		$query_insert->bindParam(':proveedor_id', $proveedor_id, PDO::PARAM_INT);
		$query_insert->bindParam(':clave_prov', $clave_prov, PDO::PARAM_STR, 20);
		$query_insert->bindParam(':cond_pago_id', $cond_pago_id, PDO::PARAM_INT);
		$query_insert->bindParam(':moneda_id', $moneda_id, PDO::PARAM_INT);
		$query_insert->bindParam(':tipo_docto', $tipo_docto, PDO::PARAM_STR, 1);
		$query_insert->bindParam(':folio', $folio, PDO::PARAM_STR, 9);
		$query_insert->bindParam(':fecha', $fecha, PDO::PARAM_STR);
		$query_insert->bindParam(':hora', $hora, PDO::PARAM_STR);
		
		$query_insert->bindParam(':estatus', $estatus, PDO::PARAM_STR, 1);
		$query_insert->bindParam(':folio_prov', $folio_prov, PDO::PARAM_STR, 9);
		$query_insert->bindParam(':importe_neto', $importe_neto, PDO::PARAM_STR, 15);
		$query_insert->bindParam(':total_impuestos', $total_impuestos, PDO::PARAM_STR, 15);
		$query_insert->bindParam(':sistema_origen', $sistema_origen, PDO::PARAM_STR, 2);
		$query_insert->bindParam(':tipo_dscto', $tipo_dscto, PDO::PARAM_STR, 1);
		$query_insert->bindParam(':subtipo_docto', $subtipo_docto, PDO::PARAM_STR, 1);
		$query_insert->bindParam(':forma_emitida', $forma_emitida, PDO::PARAM_STR, 1);
		$query_insert->bindParam(':contabilizado', $contabilizado, PDO::PARAM_STR, 1);
		$query_insert->bindParam(':acreditar_cxp', $acreditar_cxp, PDO::PARAM_STR, 1);
		
		
		$query_insert->execute();
		 
		
		}
 catch (PDOException $e) {
		print "Error!: " . $e->getMessage() . "<br/>";
		die();
		}
		
		if (!$query_insert){
			echo '<script> console.log("No se pudo insertar la ORDEN DE COMPRA "); </script>';
			exit;
		}	
		else 
		{
			$docto_cm_id = ObtenerIdOc($folio);
			echo '<script> console.log("Nueva ORDEN DE COMPRA insertado '.$docto_cm_id .'"); </script>'; 
			
		}
	
			//aqui llamo el las partidas del pedido en nef para insertar en allpart como ordend e compra 
			/// inserta partidas de orden de compra
			$consulta_det = "  SELECT
			DVD.ARTICULO_ID AS ARTICULO_ID,
			DVD.UNIDADES AS UNIDADES, 
			DVD.PRECIO_UNITARIO AS PRECIO_UNITARIO,
			DVD.PRECIO_TOTAL_NETO AS PRECIO_TOTAL_NETO

			FROM DOCTOS_VE_DET DVD

			INNER JOIN doctos_ve DV  ON DV.docto_ve_id = DVD.docto_ve_id
			WHERE DV.FOLIO = '$folio_ped_bus' AND DV.tipo_docto='P'";			
			
			
			$resultado_aplicar = $con_micro_nef->prepare($consulta_det);
			$resultado_aplicar->execute();
			$resultado = $resultado_aplicar->fetchAll(PDO::FETCH_ASSOC);
			
			$clave_articulo = "";
			$articulo_id = "";
			$unidades = "";
			$precio_unitario = "";
			$precio_total_neto = "";
			$posicion = 0;
			$unidades_a_recibir = "0";
			$contenido_umed="1";
			if (!$resultado)
			{exit;}
			else
			{ // con resultados
			
				foreach($resultado as $row_ped_art) 
				{ 
					$articulo_id_nef = $row_ped_art['ARTICULO_ID'];
					//con este traigo clave, umed y articulo_id
					
						global $database_conexion, $conex;
						$id_art = "SELECT id_microsip, clave_microsip, unidad_medida
											FROM articulos
											WHERE id_microsip_nef = '$articulo_id_nef'";			
						$resultado= mysql_query($id_art, $conex) or die(mysql_error());
						$row = mysql_fetch_assoc($resultado);
						$total = mysql_num_rows($resultado);
						if ($total > 0)
							{
								$clave_articulo_allpart = $row['clave_microsip'];
								$umed=$row['unidad_medida'];
								$articulo_id_allpart=$row['id_microsip'];
							}
					//*******************************************
					
					$unidades = $row_ped_art['UNIDADES'];
					$precio_unitario = $row_ped_art['PRECIO_UNITARIO'];
					$precio_total_neto = $row_ped_art['PRECIO_TOTAL_NETO'];
					$posicion++;
					
					/// insertara las partidas del pedido del cliente al pedido NEF
					$insertar_det = "INSERT INTO DOCTOS_CM_DET (DOCTO_CM_DET_ID, DOCTO_CM_ID, CLAVE_ARTICULO, ARTICULO_ID, UNIDADES, UNIDADES_A_REC, PRECIO_UNITARIO, PRECIO_TOTAL_NETO, POSICION, UMED, CONTENIDO_UMED) VALUES (:docto_id,:docto_cm_id,:clave_articulo,:articulo_id,:unidades,:unidades_a_recibir,:precio_unitario,:precio_total_neto, :posicion, :umed, :contenido_umed)";
					
					try {
					$query_insert_det = $con_micro->prepare($insertar_det);
					$query_insert_det->bindParam(':docto_id', $docto_id, PDO::PARAM_INT);
					$query_insert_det->bindParam(':docto_cm_id', $docto_cm_id, PDO::PARAM_INT);
					$query_insert_det->bindParam(':clave_articulo', $clave_articulo_allpart, PDO::PARAM_STR, 20);
					$query_insert_det->bindParam(':articulo_id', $articulo_id_allpart, PDO::PARAM_INT);
					$query_insert_det->bindParam(':unidades', $unidades, PDO::PARAM_STR, 18);
					$query_insert_det->bindParam(':unidades_a_recibir', $unidades_a_recibir, PDO::PARAM_STR, 18);
					$query_insert_det->bindParam(':precio_unitario', $precio_unitario, PDO::PARAM_STR, 18);
					$query_insert_det->bindParam(':precio_total_neto', $precio_total_neto, PDO::PARAM_STR, 15);
					$query_insert_det->bindParam(':posicion', $posicion, PDO::PARAM_INT);
					$query_insert_det->bindParam(':umed', $umed, PDO::PARAM_STR, 20);//************
					$query_insert_det->bindParam(':contenido_umed', $contenido_umed, PDO::PARAM_STR, 18);//*********
					
					
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
		
			$aplicar = "EXECUTE PROCEDURE APLICA_DOCTO_CM(:V_DOCTO_CM_ID)";
		
			try {
				$query_aplicar = $con_micro_nef->prepare($aplicar);
				$query_aplicar->bindParam(':V_DOCTO_CM_ID', $docto_cm_id, PDO::PARAM_INT);
				
				$query_aplicar->execute();
		
			} 
			catch (PDOException $e){ print "Error!: " . $e->getMessage() . "<br/>"; die(); }
			if (!$query_aplicar)
			{
				echo '<script> console.log("No se Aplico ORDEN COMPRA"); </script>';
				exit;
			}
			else
			{
				
				echo '<script>  $("#modal_cargando").modal("hide"); console.log("SE APLICO LA ORDEN DE COMPRA CORRECTAMENTE '.$docto_cm_id.' FOLIO: '.$folio.'"); 
					</script>'; 
			}
			
				/// actualiza folio siguiente en tabla de folios
				$folio_compras_id = 1628; 
				$folio_cosecutivo++;
				$consecutivo = Format9digit($folio_cosecutivo);
				$update_folio = "UPDATE FOLIOS_COMPRAS SET CONSECUTIVO = :consecutivo WHERE FOLIO_COMPRAS_ID = '".$folio_compras_id."' ";
		
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
				
				 //// guardo la orden de compra en el pedido
				$folio_oc=str_replace (",","",number_format($folio,0))
				$update_ped_folio = "UPDATE pedido_nef SET orden_compra='$folio_oc' WHERE folio_pedido_microsip='$folio_ped'";
				if (mysql_query($update_ped_folio, $conex) or die(mysql_error()))
				{
				echo '<script> 
						setTimeout(function(){
							lista_pedidos_nef();
						},1000,"JavaScript");   </script>';
				
				} 
		}/// insert success
	

}else {
	// ni no existe el dato id_pedido o es igual a nada entonces no realizara la insercion.
	
}



































?>