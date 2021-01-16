<?php include("conexion.php");

function Format9digit($folio){
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
}
function ObtenerFolio(){ 
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

function ObtenerId($folio){ 
global $con_micro_nef;
$tipo_docto = "P"; // PEDIDO	
$sql = "SELECT A.DOCTO_VE_ID AS DOCTO_VE_ID	
FROM DOCTOS_VE A
WHERE (A.FOLIO = '".$folio."' AND A.TIPO_DOCTO = '".$tipo_docto."')";
$consulta = $con_micro_nef->prepare($sql);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
$row_result = $consulta->fetch(PDO::FETCH_ASSOC);
if (!$consulta){
	 exit;}	
$docto_ve_id = $row_result['DOCTO_VE_ID'];
return $docto_ve_id;
}

$docto_id = -1; // existe un triger en la base que convierte el -1 en un ID irrepetible y consecutivo
$almacen_id = 385939; // almacen web
$cliente_id = 195321; //id de publico en general
$clave_cliente = "VPG";
$cond_pago_id = 219; // 30 dias de credito para 0 dias = 209
$dir_cli_id = 195327; // direccion de publico en general
$dir_consig_id = 195327; // direccion de publico en general
$moneda_id = 1; // MXN
$tipo_docto = "P"; // PEDIDO
$folio = ObtenerFolio(); // FOLIO CONSECUTIVO
$folio_cosecutivo = $folio;// FOLIO CONSECUTIVO
$fecha = "28.03.2019"; // GETDATE()
$hora = "14:05:00";
$estatus = "P"; // PENDIENTE
$orden_compra = "123456"; // ORDEN DE COMPRA ADJUNTADA 
$importe_neto = "100"; // TOTAL EN PEDIDO
$total_impuestos = "8"; // IMPUESTO MANEJADO
$sistema_origen = "VE"; // SISTEMA_ORIGEN
$tipo_dscto = "P"; 
$folio = Format9digit($folio);

$insertar = "INSERT INTO DOCTOS_VE 
(DOCTO_VE_ID, ALMACEN_ID, CLIENTE_ID, CLAVE_CLIENTE, COND_PAGO_ID, DIR_CLI_ID, DIR_CONSIG_ID, MONEDA_ID, TIPO_DOCTO, FOLIO, FECHA, HORA, ESTATUS, ORDEN_COMPRA, IMPORTE_NETO, TOTAL_IMPUESTOS, SISTEMA_ORIGEN, TIPO_DSCTO) VALUES (:docto_id,:almacen_id,:cliente_id,:clave_cliente,:cond_pago_id,:dir_cli_id,:dir_consig_id,:moneda_id,:tipo_docto,:folio,:fecha,:hora,:estatus,:orden_compra,:importe_neto,:total_impuestos,:sistema_origen,:tipo_dscto)";

echo "folio registrado de pedido = ".$folio."<br/>";

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

$docto_ve_id = ObtenerId($folio); 
 
} catch (PDOException $e) {
   print "Error!: " . $e->getMessage() . "<br/>";
   die();
}

if (!$query_insert){
	 
	 echo "No se pudo insertar pedido";
	 exit;
	 }	else{ echo " pedido nuevo insertado <br/>"; }


 
$clave_articulo = "02D00036";
$articulo_id = "106280";
$unidades = "1";
$unidades_a_surtir = "0";
$precio_unitario = "47.4";
$precio_total_neto = "47.4";
$posicion = "1";
	 
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
	 
	 echo "No se pudo insertar pedido det";
	 exit;
	 }
	 else
	 {

 echo "<br/> pedido detalle insertado";
 

 
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
	 
	 echo "No se actualizar folio consecutivo";
	 exit;
	 }
	 else
	 {
		 
		 echo "<br/> se actualizo el folio a: ".$consecutivo; 
	 }
 } 

?>