<?php include("../data/conexion.php");

$almacen_id = $_POST['select_almacenes'];
$id_empresa = "11";
$query_docdet = "SELECT DI.FOLIO AS FOLIO, 
					DI.DOCTO_IN_ID AS DOCTO_INV_ID, 
					DI.NATURALEZA_CONCEPTO AS NATURALEZA_CONCEPTO, 
					DI.CANCELADO AS CANCELADO, 
					DI.APLICADO AS APLICADO, 
					DI.DESCRIPCION AS DESCRIPCION, 
					DI.USUARIO_CREADOR AS USUARIO_CREADOR, 
					DI.FECHA_HORA_CREACION AS FECHA_HORA_CREACION, 
					DI.USUARIO_ULT_MODIF AS USUARIO_ULT_MODIF, 
					DI.FECHA_HORA_MODIF AS FECHA_HORA_MODIF, 
					DI.USUARIO_CANCELACION AS USUARIO_CANCELACION, 
					DI.FECHA_HORA_CANCELACION AS FECHA_HORA_CANCELACION, 
					DID.DOCTO_IN_DET_ID AS DOCTO_INV_DET_ID,
					DID.ARTICULO_ID AS ARTICULO_ID,
					DID.TIPO_MOVTO AS TIPO_MOVTO,
					DID.COSTO_UNITARIO AS COSTO_UNITARIO,
					DID.COSTO_TOTAL AS COSTO_TOTAL,
					DID.CLAVE_ARTICULO AS CLAVE_ARTICULO,
					DID.UNIDADES AS UNIDADES,
					DID.FECHA AS FECHA,
					ALM.NOMBRE AS NOMBRE_ALMACEN,
					A.NOMBRE AS NOMBRE_ARTICULO
					FROM DOCTOS_IN_DET DID
					LEFT JOIN DOCTOS_IN DI ON DI.DOCTO_IN_ID = DID.DOCTO_IN_ID
					INNER JOIN ARTICULOS A ON A.ARTICULO_ID = DID.ARTICULO_ID
					INNER JOIN ALMACENES ALM ON ALM.ALMACEN_ID = DID.ALMACEN_ID
					WHERE DID.ALMACEN_ID = '$almacen_id'";

$consulta = $con_micro->prepare($query_docdet);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);

if (!$consulta){ /*echo "sin resultados"; */ exit;}	
$cont = 0;
$folio_anterior = "";
while($row = $consulta->fetch())	
{

	echo $row->CLAVE_ARTICULO."</br>";
	
	$folio = $row->FOLIO;
	$docto_inv_id = $row->DOCTO_INV_ID;
	$docto_inv_det_id = $row->DOCTO_INV_DET_ID;
	$nombre_articulo = $row->NOMBRE_ARTICULO;
	$unidades = $row->UNIDADES;
	$fecha = $row->FECHA;
	$naturaleza_concepto = $row->NATURALEZA_CONCEPTO;
	$cancelado = $row->CANCELADO;
	$aplicado = $row->APLICADO;
	$descripcion = $row->DESCRIPCION;
	$usuario_creador = $row->USUARIO_CREADOR;
	$fecha_hora_creacion = $row->FECHA_HORA_CREACION;
	$usuario_ult_modif = $row->USUARIO_ULT_MODIF;
	$fecha_hora_modif = $row->FECHA_HORA_MODIF;
	$usuario_cancelacion = $row->USUARIO_CANCELACION;
	$fecha_hora_cancelacion = $row->FECHA_HORA_CANCELACION;
	$clave_articulo = $row->CLAVE_ARTICULO;
	$articulo_id = $row->ARTICULO_ID;
	$tipo_movto = $row->TIPO_MOVTO;
	$costo_unitario = $row->COSTO_UNITARIO;
	$costo_total = $row->COSTO_TOTAL;
	$nombre_almacen = $row->NOMBRE_ALMACEN;
	
	$q_buscar_almacen = "SELECT * mov_inv WHERE folio = '$folio'";
	$buscar_almacen = mysql_query($q_buscar_almacen, $conex) or die(mysql_error());
	$total_rows_almacen = msql_num_rows($buscar_almacen);
	if ($total_rows_almacen <= 0)
	{
		$q_insert_espejo = "INSERT INTO mov_inv (folio, docto_inv_id, docto_inv_det_id, nombre_articulo, unidades, almacen_id, fecha, naturaleza_concepto, cancelado, aplicado, descripcion, usuario_creador, fecha_hora_creacion, usuario_ult_modif, fecha_hora_modif, usuario_cancelacion, fecha_hora_cancelacion, clave_articulo, articulo_id, tipo_movto, costo_unitario, costo_total) 
		VALUES ('$folio','$docto_inv_id','$docto_inv_det_id','$nombre_articulo','$unidades','$almacen_id','$fecha','$naturaleza_concepto','$cancelado','$aplicado','$descripcion','$usuario_creador','$fecha_hora_creacion','$usuario_ult_modif','$fecha_hora_modif','$usuario_cancelacion','$fecha_hora_cancelacion','$clave_articulo','$articulo_id','$tipo_movto','$costo_unitario','$costo_total')"; 
		$insert_espejo = mysql_query($q_insert_espejo, $conex) or die(mysql_error());
	} 
	else 
	{
		if($cont > 0) // si no se ha insertado nada en este bucle
		{		// entonces elimina registros anteriores para reemplazarlos con los nuevos
			
		}
		else  // si los registros que encuentra en la busqueda de arriba son los que se esta insertando en este bucle, entonces continua insertando.
		{
			
		}
	}
	$cont++;
	$folio_anterior = $folio;
	
	
}

$q_buscar_almacen = "SELECT * almacenes WHERE almacen_id = '$almacen_id'";
$buscar_almacen = mysql_query($q_buscar_almacen, $conex) or die(mysql_error());
$total_rows_almacen = msql_num_rows($buscar_almacen);
if ($total_rows_almacen <= 0){ // sin resultados, 
	$q_insert_almacen = "INSERT INTO almacenes (almacen,almacen_id)  VALUES ('$nombre_almacen','$almacen_id')";
	$insert_almacen = mysql_query($q_insert_almacen, $conex) or die(mysql_error());
}

// validacion de almacen dependiedo el seleciconado es la clave relacionada que se buscara
// tabla de claves con rol de claves



$insert_articulo = "INSERT INTO articulos (id_empresa,clave_microsip,clave_empresa,nombre,descripcion,precio,src_img,unidad_medida) 
VALUES ('$id_empresa','$clave_articulo','$datos[1]','$datos[2]','$datos[3]','$datos[4]','$datos[5]','$datos[6]')";

$registrar = mysql_query($query, $conex) or die(mysql_error());
$id_articulo =  mysql_insert_id();	 
	
				foreach ($lista_categorias as $id_categoria => $categoria) {
					$id_check = 'chkcat_'.$id_categoria;
				if (isset($_POST['chkcat_'.$id_categoria]) ) {
					$query_categorias = "INSERT INTO registros_categorias (id_categoria,id_articulo) 
							VALUES ('$id_categoria','$id_articulo')";
				$registrar_categorias = mysql_query($query_categorias, $conex) or die(mysql_error());
				} else {
					
					// si no esta marcado ell chek es como si no existiera 
				}	
				
				
				
				} /**/

?>