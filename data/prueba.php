<?php
include("conexion.php"); 

$folio = "000217734";

function ObtenerIdPedi($folio){ 
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

// echo ObtenerIdPedi($folio);
 //echo CantRecibir(335,4);
 
  $sql_tipocambio = "SELECT TIPO_CAMBIO AS TIPO_CAMBIO
  FROM HISTORIA_CAMBIARIA
 WHERE (MONEDA_ID = '41560') AND (HISTORIA_CAMB_ID = (SELECT MAX(HISTORIA_CAMB_ID) FROM HISTORIA_CAMBIARIA))
 ";
  // Dolares = 41560
$consulta_cambio = $con_micro_nef->prepare($sql_tipocambio);
$consulta_cambio->execute();
$consulta_cambio->setFetchMode(PDO::FETCH_OBJ);
$row_cambio = $consulta_cambio->fetch(PDO::FETCH_ASSOC);

if (!$consulta_cambio){
 //echo "sin resultados";
	 exit;}	
	$tipocambio = 0; 

$tipocambio = $row_cambio["TIPO_CAMBIO"];
echo $tipocambio;

?>