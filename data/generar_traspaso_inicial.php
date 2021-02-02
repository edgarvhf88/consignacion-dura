<?php include("conexion.php");


generar_traspaso();


function generar_traspaso(){
	global $conex, $con_micro;
//// Obtener datos para la remision 

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

	
	$consulta_articulos = "SELECT * FROM inventario_inicial WHERE existe='S'";
	$resultado_articulos = mysql_query($consulta_articulos, $conex) or die(mysql_error());
	$total_row = mysql_num_rows($resultado_articulos);
	$lista_invdet = array();
	$suma_totales = 0;
	while($row_arti = mysql_fetch_array($resultado_articulos,MYSQL_BOTH)) 
	{
		$clave = ClaveArticulo($row_arti['id']);
		
		$lista_invdet[] = array("value" => $clave, 
							   "clave" => $clave, 
							   "id_microsip" => $row_arti['id'], 
							   "cantidad" => $row_arti['planta_3'], 
							   "costo_unitario" => "0", 
							   "costo_total" => "0");
	
		//$suma_totales +=  $row_arti['precio_total'];
	}
	$total_total = 0;


$docto_id = -1; // existe un triger en la base que convierte el -1 en un ID irrepetible y consecutivo
$almacen_destino_id = ""; // dura P3 = 11142	dura P4 = 11143	
$almacen_id = 11143;
$centro_costo_id = "";
$folio = ObtenerFolioAjusteEntrada();
$naturaleza_concepto = 'E';
$fecha = $fecha_actual; //"04.11.2019"; // GETDATE()
$hora =  $hora_actual; //"14:00:00";
$concepto_in_id = 27; // 27 es ajuste de entrada

$descripcion = 'Ajuste inicial';
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
	
	$docto_in_id = ObtenerIdEntrada($folio); 
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
	
	
	foreach($lista_invdet as $row_articulos) // foreacha de movimientos de entrada
	{
		$clave_articulo = $row_articulos['clave'];
		$articulo_id = $row_articulos['id_microsip'];
		$unidades = $row_articulos['cantidad'];
		
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
			$query_insert_det_e->bindParam(':almacen_id', $almacen_id, PDO::PARAM_INT);
			$query_insert_det_e->bindParam(':concepto_in_id', $concepto_in_id, PDO::PARAM_INT);
			$query_insert_det_e->bindParam(':tipo_movto', $tipo_movto_e, PDO::PARAM_STR);
			$query_insert_det_e->bindParam(':metodo_costeo', $metodo_costeo, PDO::PARAM_STR);
			$query_insert_det_e->bindParam(':centro_costo_id', $centro_costo_id, PDO::PARAM_INT);
			$query_insert_det_e->bindParam(':aplicado', $aplicado, PDO::PARAM_STR);
			$query_insert_det_e->bindParam(':rol', $tipo_movto_e, PDO::PARAM_STR);
			$query_insert_det_e->execute();
			
			
		} 
		catch (PDOException $e){ print "Error!: " . $e->getMessage() . "<br/>"; die(); }	
		if (!$query_insert_det_e){
		
			echo '<script> console.log("No se inserto el articulo"); </script>';
			exit;
		}
		else
		{	
			
			//	echo '<script> console.log("Se inserto el articulo "); </script>';
			
		}
		
	}

	////// // INSERTA EL SIGUIENTE NUMERO DE FOLIO ///////
	
	$folio_cosecutivo = $folio + 1;
	$consecutivo = Format9digit($folio_cosecutivo);
	$update_folio = "UPDATE FOLIOS_CONCEPTOS SET CONSECUTIVO = :consecutivo WHERE CONCEPTO_ID = '27'";
 
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
	
	 ///////////   APLICA EL DOCUMENTO PARA QUE SE modifique EL INVENTARIO  ///////// 
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
		
		echo '<script> 
			$("#modal_cargando").modal("hide");
			
			 
			 
			 </script>';
			 
	}
  
} /// si se inserta el pedido en microsip

 /// validacion de totalrows
}  //funcion
?>