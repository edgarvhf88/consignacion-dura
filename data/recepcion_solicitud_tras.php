<?php include("../data/conexion.php"); 


function cargar_recepcion($folio, $oc, $id_pedido)
{
		$folio_bus=$folio; //VIENE COMPLETO PARA EVITAR EL RAP >:V

		//con el folio proporcionado cargo la remision y la muestro en una tabla
		$tras_folio = rec_sin_tras ($folio , $id_pedido);
		$tras_folio = folio_tras($tras_folio);
		
		
		if ($oc !=""){
		if ($tras_folio != "" )
		{
			$boton_recepcionar= '<h4 align"center">Solicitud de traspaso: '.$tras_folio.'</h4>';
		}
		else {
			$boton_recepcionar = '<div class="col-lg-12" align="center">
							 <button id="gen_tras_micro" onclick="solicitar_tras_de_rec(\''.$folio.'\', '.$id_pedido.');" class="btn btn-primary" >Generar solicitud de traspaso </button>
							 </div>';
		}
		}
		else {$boton_recepcionar="";}
		
		 //selecciono la base de datos
		global $con_micro;
		 //hago la consulta
		$aplicar = "SELECT
        DVD.CLAVE_ARTICULO AS CLAVE,
        ART.nombre AS NOMBRE,
        DVD.unidades as CANTIDAD,
        DVD.precio_unitario as PRECIO,
        DVD.precio_total_neto AS TOTAL
        FROM DOCTOS_CM_DET DVD
        INNER JOIN ARTICULOS ART ON ART.ARTICULO_ID = DVD.ARTICULO_ID
        INNER JOIN DOCTOS_CM DV  ON DV.DOCTO_CM_ID = DVD.DOCTO_CM_ID
        WHERE DV.FOLIO = '$folio_bus' AND DV.tipo_docto='R'";
		//empiezo la consulta
		$query_aplicar = $con_micro->prepare($aplicar);
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


function rec_sin_tras($folio, $id_pedido)
{
	global $database_conexion, $conex;
	$sql = "SELECT id_pedido_traspaso	
	FROM ligas_doctos 
	WHERE (id_pedido = '".$id_pedido."' AND recepcion_allpart = '".$folio."')";
	
	$resultado= mysql_query($sql, $conex) or die(mysql_error());
	$row = mysql_fetch_assoc($resultado);
	$total = mysql_num_rows($resultado);
	if ($total > 0)
		{
			$folio_tras = $row['id_pedido_traspaso'];	
		}
	else {$folio_tras="";}
	return $folio_tras;
}


function folio_tras($id)
{
	global $database_conexion, $conex;
	$sql = "SELECT folio	
	FROM pedido_traspaso 
	WHERE (id_pedido = '".$id."')";
	
	$resultado= mysql_query($sql, $conex) or die(mysql_error());
	$row = mysql_fetch_assoc($resultado);
	$total = mysql_num_rows($resultado);
	if ($total > 0)
		{
			$folio_tras = $row['folio'];	
		}
	else {$folio_tras="";}
	return $folio_tras;
}



function id_pedido_cliente($id_pedido)
{
	global $database_conexion, $conex;
	$sql = "SELECT id_pedido_cliente	
	FROM pedido_nef 
	WHERE (id_pedido = '".$id_pedido."')";
	
	$resultado= mysql_query($sql, $conex) or die(mysql_error());
	$row = mysql_fetch_assoc($resultado);
	$total = mysql_num_rows($resultado);
	if ($total > 0)
		{
			$id = $row['id_pedido_cliente'];	
			return $id;
		}
	
}

function id_almacen($id_pedido)
{
	global $database_conexion, $conex;
	$sql = "SELECT almacen_id	
	FROM pedido_nef 
	WHERE (id_pedido = '".$id_pedido."')";
	
	$resultado= mysql_query($sql, $conex) or die(mysql_error());
	$row = mysql_fetch_assoc($resultado);
	$total = mysql_num_rows($resultado);
	if ($total > 0)
		{
			$id = $row['almacen_id'];	
			return $id;
		}
	
}


function requisitor($id)
{
	global $database_conexion, $conex;
	$sql = "SELECT nombre	
	FROM usuarios 
	WHERE (id = '".$id."')";
	
	$resultado= mysql_query($sql, $conex) or die(mysql_error());
	$row = mysql_fetch_assoc($resultado);
	$total = mysql_num_rows($resultado);
	if ($total > 0)
		{
			$nombre = $row['nombre'];	
			return $nombre;
		}
	
}

$id_pedido = "";

$tipo = $_POST['tipo'];

if ($tipo==1)
{//carga la remision a recepcionar 
	$oc = $_POST['oc'];
	$folio = $_POST['folio'];
	$id_pedido = $_POST['id_pedido'];
	cargar_recepcion($folio, $oc, $id_pedido);
}
else 
{//inserta la solicitud de traspaso 
	//variables
	$folio = $_POST['folio'];
	$id_pedido = $_POST['id_pedido'];
	$primer_ciclo="";
	
	//variables de cabecera
	$id_pedido_cliente=id_pedido_cliente($id_pedido);
	$id_usuario=$_SESSION["logged_user"];	
	$requisitor=requisitor($id_usuario);
	$almacen_id=id_almacen($id_pedido);//listo
	$estatus=1;
	$total_pedido="";//listo
	$id_empresa='11';
	$tipo='PED_T';
	$folio_traspaso=folio_consecutivo($id_empresa,$tipo);
	//variables de los detalles
	$id_pedido_tras="";//listo
	$articulo = "";//listo	
	$unidad_medida = "";//listo	
	$id_articulo = "";//listo
	
	//inserto la cabecera 
	$consulta_rec = "SELECT 
						DC.IMPORTE_NETO AS IMPORTE_NETO, 
						DC.ALMACEN_ID AS ALMACEN_ID,
						DCD.ARTICULO_ID AS ARTICULO_ID,
						DCD.PRECIO_UNITARIO AS PRECIO_UNITARIO,
						DCD.PRECIO_TOTAL_NETO AS TOTAL,
						DCD.UNIDADES AS UNIDADES,
						DCD.CLAVE_ARTICULO AS CLAVE_ARTICULO
						
						FROM DOCTOS_CM DC
						INNER JOIN DOCTOS_CM_DET DCD ON DCD.DOCTO_CM_ID = DC.DOCTO_CM_ID
						WHERE FOLIO = '$folio' AND TIPO_DOCTO ='R'";
	
	$resultado_recs = $con_micro->prepare($consulta_rec);
	$resultado_recs->execute();
	$resultado_rec = $resultado_recs->fetchAll(PDO::FETCH_ASSOC);
	
	foreach($resultado_rec as $row_rec)
	{		
	//aqui se incerta la cabecera///*******************************************************************
	if ($primer_ciclo == "")
	{
		$total_pedido = $row_rec['IMPORTE_NETO'];
		
		//cabecera insercion 
		$traspaso = "INSERT INTO pedido_traspaso 
		(id_pedido_cliente, id_usuario, folio, requisitor, almacen_id, estatus, total_pedido) 
		VALUES ('$id_pedido_cliente', '$id_usuario', '$folio_traspaso', '$requisitor', '$almacen_id', '$estatus', '$total_pedido')";
				if (mysql_query($traspaso, $conex) or die(mysql_error()))
				{
				 $id_pedido_tras =  mysql_insert_id();
				//si se inserta actualizo la tabla de folios 
				$folio_consecutivo = $folio_traspaso + 1;
				$update_folio = "UPDATE folios SET folio='$folio_consecutivo'  WHERE id_empresa='$id_empresa' and tipo_folio='PED_T'";
				if (mysql_query($update_folio, $conex) or die(mysql_error())){}
				
				}  
		$primer_ciclo = "1";
		
	}
	///******************************************************************************************
			//variables de microsip 
			$clave_microsip = $row_rec['CLAVE_ARTICULO'];
			$id_microsip = $row_rec['ARTICULO_ID'];
			$cantidad = $row_rec['UNIDADES'];
			$precio_unitario = $row_rec['PRECIO_UNITARIO'];
			$precio_total = $row_rec['TOTAL'];
			//consulta a consigna
			global $database_conexion, $conex;
			$sql = "SELECT nombre, unidad_medida, id	
			FROM articulos 
			WHERE (id_microsip = '".$id_microsip."')";
			$resultado= mysql_query($sql, $conex) or die(mysql_error());
			$row = mysql_fetch_assoc($resultado);
			$total = mysql_num_rows($resultado);
			if ($total > 0)
				{
					$articulo = $row['nombre'];	
					$unidad_medida = $row['unidad_medida'];	
					$id_articulo = $row['id'];	
				}
			
			
			
			
			$traspaso = "INSERT INTO pedido_traspaso_det
			(id_pedido, id_articulo, clave_microsip, id_microsip, articulo, cantidad, precio_unitario, precio_total, unidad_medida) 
			VALUES ('$id_pedido_tras', '$id_articulo', '$clave_microsip', '$id_microsip', '$articulo', '$cantidad', '$precio_unitario', '$precio_total', '$unidad_medida')";
				if (mysql_query($traspaso, $conex) or die(mysql_error()))
				{
				
				
				}  


	}
	
	
	///guardo la relacion para no generar otro traspaso con esa recepcion 
				$liga_dura = "UPDATE ligas_doctos SET id_pedido_traspaso = '$id_pedido_tras' WHERE recepcion_allpart ='$folio' AND id_pedido = '$id_pedido'";
				
				if (mysql_query($liga_dura, $conex) or die(mysql_error()))
				{
				echo '<script> 
						$("#remision_detalle").modal("hide");
						setTimeout(function(){
							lista_pedidos_nef();
						},1000,"JavaScript");   </script>';
				
				}  
	
	
}






















   
   
?>
