<?php include("../data/conexion.php"); 


function cargar_remision($folio, $oc, $id_pedido)
{
	
	$folio_bus=Format9digit($folio);
	//con el folio proporcionado cargo la remision y la muestro en una tabla
		$rec_folio = rem_sin_rec($folio); 
		if ($oc !=""){
		if ($rec_folio != "" )
		{
			$boton_recepcionar= '<h4 align"center">Recepcion: '.$rec_folio.'</h4>';
		}
		else {
			$boton_recepcionar = '<div class="col-lg-12" align="center">
							 <button id="gen_tras_micro" onclick="recepcionar('.$folio.', '.$oc.', '.$id_pedido.');" class="btn btn-primary" >Generar Recepcion en AllPart </button>
							 </div>';
		}
		}
		else {$boton_recepcionar="";}
		
		 //selecciono la base de datos
		global $con_micro_nef;
		 //hago la consulta
		$aplicar = "SELECT
        DVD.CLAVE_ARTICULO AS CLAVE,
        ART.nombre AS NOMBRE,
        DVD.unidades as CANTIDAD,
        DVD.precio_unitario as PRECIO,
        DVD.precio_total_neto AS TOTAL
        FROM DOCTOS_VE_DET DVD
        INNER JOIN ARTICULOS ART ON ART.ARTICULO_ID = DVD.ARTICULO_ID
        INNER JOIN DOCTOS_VE DV  ON DV.DOCTO_VE_ID = DVD.DOCTO_VE_ID
        WHERE DV.FOLIO = '$folio_bus' AND DV.tipo_docto='R'";
		//empiezo la consulta
		$query_aplicar = $con_micro_nef->prepare($aplicar);
		$query_aplicar->execute();
		$results = $query_aplicar->fetchAll(PDO::FETCH_ASSOC);
		//recorro el array y concateno la variables
		$rems="";
		$tabla=' 
		
				<h4 align"center">Folio: '.$folio.'</h4>
		
		<table id="remision_det" class="table table-striped table-bordered table-hover table-responsive">
                    	<thead>
                            <tr class="info">
                                
                                <th>Clave Microsip</th>
                                <th>Articulo</th>
                                <th>Cantidad</th>
                                <th>Precio Unit.</th>
                                <th>Total</th>
                                
                            </tr>
                        </thead><tbody>';
		foreach($results as $row)
		{	//*******************************************************************

         $tabla .=' <tr>
             <td>'.$row['CLAVE'].'</td>
             <td>'.$row['NOMBRE'].'</td>
             <td>'.$row['CANTIDAD'].'</td>
             <td align="right">$'.number_format($row['PRECIO'],2).' </td>
             <td align="right">$'.number_format($row['TOTAL'],2).'</td>
         </tr>';
      
			//*******************************************************************
		}
	
	$tabla .=' </table>
                <script>
                $(document).ready( function () {
                    $("#remision_det").DataTable();
                } );
                </script>';
	$tabla .=$boton_recepcionar;			
				echo $tabla;
	
	
}

//OBTENER EL FOLIO SIGUIENTE DE UNA RECEPCION 
function ObtenerFolioRec()
{ 
global $con_micro;
$folio = "";	
//$valor = 88878; 
$valor = 2329; // folio de COMPRAS recepcion
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

function Formatrapdigit($folio){
switch(strlen($folio)){
		
			case 1: 
			$folio_siguiente = "RAP00000".$folio;
			break;
			case 2:
			$folio_siguiente = "RAP0000".$folio;
			break;
			case 3:
			$folio_siguiente = "RAP000".$folio;
			break;
			case 4:
			$folio_siguiente = "RAP00".$folio;
			break;
			case 5:
			$folio_siguiente = "RAP0".$folio;
			break;
			case 6:
			$folio_siguiente = "RAP".$folio;
			break;
			
		 
	 }
return $folio_siguiente; 
}

//obtengo el id de la recepcion insertada 
function ObtenerIdRec($folio){ 
global $con_micro;
$tipo_docto = "R"; // RECEPCION	
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

function ObtenerIdOc($folio){ 
global $con_micro;
$tipo_docto = "O"; // ordend e compra
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

function rem_sin_rec($folio)
{
	global $con_micro;
		
	//$valor = 88878; 
	$valor = 2329; // folio de COMPRAS recepcion
	$sql = "SELECT FOLIO	
	FROM DOCTOS_CM A
	WHERE (A.FOLIO_PROV = '".$folio."' AND A.TIPO_DOCTO='R' AND PROVEEDOR_ID='1768')";
	
	$consulta = $con_micro->prepare($sql);
	$consulta->execute();
	$consulta->setFetchMode(PDO::FETCH_OBJ);
	$row_result = $consulta->fetch(PDO::FETCH_ASSOC);
	if (!$consulta){
			exit;}	
	$folio_rec = $row_result['FOLIO'];
	if($folio_rec!= "" and $folio_rec!='0'){
	return $folio_rec;}
}

function ObtenerIdLigas($fuente, $destino)
{ 
global $con_micro;

$sql = "SELECT A.DOCTO_CM_LIGA_ID AS DOCTO_CM_LIGA_ID	
FROM DOCTOS_CM_LIGAS A
WHERE (A.DOCTO_CM_FTE_ID = '".$fuente."') AND (A.DOCTO_CM_DEST_ID = '".$destino."')";
$consulta = $con_micro->prepare($sql);
$consulta->execute();
$consulta->setFetchMode(PDO::FETCH_OBJ);
$row_result = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$consulta){
	 exit;}	
$id = $row_result['DOCTO_CM_LIGA_ID'];
return $id;
}

//inserta las ligas entre una orden de compra y una recepcion 
function insertar_ligas($docto_cm_id_rec, $docto_cm_id_oc)
{ 	global $con_micro;
	$docto_id=-1;
	
	
	if ($docto_cm_id_rec != 0 and $docto_cm_id_oc !=0)
	{
			//insertar la liga principal
		//guardar la relacion en doctos cm ligas
			$insertar_det = "INSERT INTO DOCTOS_CM_LIGAS (DOCTO_CM_LIGA_ID, DOCTO_CM_FTE_ID, DOCTO_CM_DEST_ID) VALUES (:docto_id,:docto_fte_id,:docto_dest_id)";
					try {
					$query_insert_det = $con_micro->prepare($insertar_det);
					$query_insert_det->bindParam(':docto_id', $docto_id, PDO::PARAM_INT);
					$query_insert_det->bindParam(':docto_fte_id', $docto_cm_id_oc, PDO::PARAM_INT);
					$query_insert_det->bindParam(':docto_dest_id', $docto_cm_id_rec, PDO::PARAM_INT);
					$query_insert_det->execute();
					
					} catch (PDOException $e) {
					print "Error!: " . $e->getMessage() . "<br/>";
					die();
					}	
	if (!$query_insert_det)
	{echo '<script> console.log("No se aplico la relacion en DOCTOS LIGAS"); </script>';
						exit;}
	else 
	{echo '<script> console.log("Se genero la relacion correctamente"); </script>';}
	
		//consulta para los identificadores 
	$consulta = "SELECT 
	dc.docto_cm_det_id as ID_RECEPCION, 
	dc2.docto_cm_det_id as ID_ORDEN
	FROM doctos_cm_det dc
	inner join doctos_cm_det dc2 on dc2.docto_cm_id = ".$docto_cm_id_oc." and dc.articulo_id = dc2.articulo_id
	WHERE dc.docto_cm_id = ".$docto_cm_id_rec;
	
	$resultado = $con_micro->prepare($consulta);
	$resultado->execute();
	$res = $resultado->fetchAll(PDO::FETCH_ASSOC);
	
	
	$docto_id_det=ObtenerIdLigas($docto_cm_id_oc, $docto_cm_id_rec);
	
	foreach($res as $row)
	{
		$docto_cm_fte_id = $row['ID_ORDEN'];
		$docto_cm_dest_id = $row['ID_RECEPCION'];
		
		
	//recorer el ciclo e incertar los detalles 
	$insertar_det = "INSERT INTO DOCTOS_CM_LIGAS_DET (DOCTO_CM_LIGA_ID, DOCTO_CM_DET_FTE_ID, DOCTO_CM_DET_DEST_ID) VALUES (:docto_id,:docto_fte_id,:docto_dest_id)";
					try {
					$query_insert_det = $con_micro->prepare($insertar_det);
					$query_insert_det->bindParam(':docto_id', $docto_id_det, PDO::PARAM_INT);
					$query_insert_det->bindParam(':docto_fte_id', $docto_cm_fte_id, PDO::PARAM_INT);
					$query_insert_det->bindParam(':docto_dest_id', $docto_cm_dest_id, PDO::PARAM_INT);
					$query_insert_det->execute();
					
					} catch (PDOException $e) {
					print "Error!: " . $e->getMessage() . "<br/>";
					die();
					}	
					if (!$query_insert_det)
						{echo '<script> console.log("No se aplico la relacion en DOCTOS LIGAS"); </script>';
						exit;}
					else 
						{echo '<script> console.log("Se genero la relacion correctamente"); </script>';}
	}					
	}
	
}

$tipo = $_POST['tipo'];
if ($tipo==1)
{//carga la remision a recepcionar 
$oc = $_POST['oc'];
$folio = $_POST['folio'];
$id_pedido = $_POST['id_pedido'];

cargar_remision($folio, $oc, $id_pedido);
}
else
{//inserta la recepcion tomando los valores de la remision 
	//variables para la cabecera
	$folio_rem=$_POST['folio'];
	$id_pedido_dura=$_POST['id_pedido'];
	$folio_orden_compra=$_POST['oc'];
	$folio_orden_compra = Format9digit($folio_orden_compra);
	$folio_rem_bus = Format9digit($folio_rem);
	//reviso si se ha recepcionado esta remision antes para evitar duplicidad
	
	$fecha_actual = date("d.m.Y");
	$hora_actual = date("H:i:s");
	$fecha_hora = date("Y/m/d H:i:s");
	$docto_ve_id = "";	 
	$docto_id = -1; // existe un triger en la base que convierte el -1 en un ID irrepetible y consecutivo
	$tipo_cambio="1";
	$descripcion="RECEPCION generada por el sistema de consigancion Dura";
	$usuario_creacion="ELIZABETHO";
	$almacen_id = 19; // SE ASIGNA ABAJO
	$sucursal_id = Sucursal_Allpart(); // SE ASIGNA ABAJO
	$proveedor_id = 1768; //cliente_id de AllPart matamoros
	$clave_prov = "NEFMAT";
	$cond_pago_id = 1624; // 30 dias de credito para 0 dias = 209
	$moneda_id = 1; // MXN
	$tipo_docto = "R"; // orden de compra
	$folio = ObtenerFolioRec(); // FOLIO CONSECUTIVO desde tabla
	$folio_cosecutivo = $folio;// FOLIO CONSECUTIVO para sumarle uno y actualizar la tabla de los folios
	$folio = Formatrapdigit($folio);
	
	
	$fecha = $fecha_actual; //"28.03.2019"; // GETDATE()
	$hora = $hora_actual; // "14:05:00";
	$estatus = "P"; // PENDIENTE  /// C = CANCELADO // S = SURTIDO
	$folio_prov = $folio_rem; // ORDEN DE COMPRA ADJUNTADA - se asigna abajo
	$importe_neto = 0; // TOTAL EN PEDIDO - se asigna abajo
	$total_impuestos = "8"; // IMPUESTO MANEJADO
	$sistema_origen = "CM"; // SISTEMA_ORIGEN
	$tipo_dscto = "P"; // porcentual
	$subtipo_docto='N';
	$forma_emitida='N';
	$contabilizado='N';
	$acreditar_cxp='N';
	
	/////////////--------------///////////////------////////////-----/////////////////////////		
	//consulta si la remision existe y trae datos de la cabecera 
	$consulta_pedido_ms = "SELECT *
						FROM DOCTOS_VE
						WHERE FOLIO = '$folio_rem_bus' AND TIPO_DOCTO ='R'";
	
	$resultado_pedido = $con_micro_nef->prepare($consulta_pedido_ms);
	$resultado_pedido->execute();
	$resultado_ped = $resultado_pedido->fetchAll(PDO::FETCH_ASSOC);
	
	foreach($resultado_ped as $row_p){
		
		// solo si encuentra el pedido entonces procede a insertarlo
		//$almacen_id = $row_p['almacen_id']; // el almacen es el de nef almacen general, id = 19
		$importe_neto = $row_p['IMPORTE_NETO'];
		$total_impuestos = $row_p['TOTAL_IMPUESTOS'] * 0.08; }

	//aqui llamo una funcion que revisa que no haya ya una recepcion enlazada a esa remision 
	if(!$resultado_ped){exit;}//si da positivo salgo de la ejecucion y mando un mensaje de error
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
			echo '<script> console.log("No se pudo insertar la RECEPCION "); </script>';
			exit;
		}	
		else 
		{
			$docto_cm_id = ObtenerIdRec($folio);
			echo '<script> console.log("Nueva RECEPCION insertado '.$docto_cm_id .'"); </script>'; 
			
		}
	
			//aqui llamo el las partidas de la remision  en nef para insertar en allpart como recepcion 
			/// inserta partidas de orden de compra
			$consulta_det = "  SELECT
			DVD.ARTICULO_ID AS ARTICULO_ID,
			DVD.UNIDADES AS UNIDADES, 
			DVD.PRECIO_UNITARIO AS PRECIO_UNITARIO,
			DVD.PRECIO_TOTAL_NETO AS PRECIO_TOTAL_NETO

			FROM DOCTOS_VE_DET DVD

			INNER JOIN doctos_ve DV  ON DV.docto_ve_id = DVD.docto_ve_id
			WHERE DV.FOLIO = '$folio_rem_bus' AND DV.tipo_docto='R'";			
			
			
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
			
				foreach($resultado as $row_rem_art) 
				{ 
					$articulo_id_nef = $row_rem_art['ARTICULO_ID'];
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
					
					$unidades = $row_rem_art['UNIDADES'];
					$precio_unitario = $row_rem_art['PRECIO_UNITARIO'];
					$precio_total_neto = $row_rem_art['PRECIO_TOTAL_NETO'];
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
					echo '<script> console.log("No se pudo insertar RECEPCION det"); </script>';
					exit;
					}
					else
					{
						//echo "<br/> pedido detalle insertado";
						echo '<script> console.log("RECEPCION detalle insertado"); </script>';
					} 
					
				} // end while
			} // con resultados de busqueda de pedido detalle
			
			//traer el id de la orden de compra
			$docto_cm_id_oc=ObtenerIdOc($folio_orden_compra);
			insertar_ligas($docto_cm_id, $docto_cm_id_oc);
			
			
			 ///////////   APLICA EL DOCUMENTO DE DOCTOS_VE PARA QUE SE DESCUENTE EL INVENTARIO  ///////// 
		
			$aplicar = "EXECUTE PROCEDURE APLICA_DOCTO_CM(:V_DOCTO_CM_ID)";
		
			try {
				$query_aplicar = $con_micro->prepare($aplicar);
				$query_aplicar->bindParam(':V_DOCTO_CM_ID', $docto_cm_id, PDO::PARAM_INT);
				
				$query_aplicar->execute();
		
			} 
			catch (PDOException $e){ print "Error!: " . $e->getMessage() . "<br/>"; die(); }
			if (!$query_aplicar)
			{
				echo '<script> console.log("No se Aplico RECEPCION."); </script>';
				exit;
			}
			else
			{
				
				echo '<script>  $("#modal_cargando").modal("hide"); console.log("SE APLICO LA RECEPCION DE COMPRA CORRECTAMENTE '.$docto_cm_id.' FOLIO: '.$folio.'"); 
					</script>'; 
			}
			
				/// actualiza folio siguiente en tabla de folios
				$folio_compras_id = 2329; 
				$folio_cosecutivo++;
				$consecutivo = Format9digit($folio_cosecutivo);
				$update_folio = "UPDATE FOLIOS_COMPRAS SET CONSECUTIVO = :consecutivo WHERE FOLIO_COMPRAS_ID = '".$folio_compras_id."' ";
		
				try {
				$query_update_folio = $con_micro->prepare($update_folio);
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
				
			// guardo la relacion de remision y recepcion 
				
				
				$liga_dura = "INSERT INTO ligas_doctos (id_pedido, remision_nef, recepcion_allpart) 
				VALUES ('$id_pedido_dura', '$folio_rem_bus', '$folio')";
				if (mysql_query($liga_dura, $conex) or die(mysql_error()))
				{
				echo '<script> 
						$("#remision_detalle").modal("hide");
						setTimeout(function(){
							lista_pedidos_nef();
						},1000,"JavaScript");   </script>';
				
				}  
		}/// insert success
	
	
	
	
}





















   
   
?>
