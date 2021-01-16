<?php   include("conexion.php");

if (isset($_POST['clave_nombre'])){
	$clave_nombre = $_POST['clave_nombre'];
	$almacen_id = $_POST['almacen_id'];
	 BuscarArticuloMicrosip($clave_nombre,$almacen_id);
	
}

      
function BuscarArticuloMicrosip($clave_nombre,$almacen_id){
	
global $con_micro;	

//$almacen_id = '390226';
$consulta = "SELECT 
		ARTI.ARTICULO_ID AS ARTID,
		ARTI.NOMBRE AS ARTICULO,
		ARTI.UNIDAD_VENTA AS UDM,
		PRECIOS_A.PRECIO AS PRECIO,
		NIVA.INVENTARIO_MAXIMO AS MAXIMO,
		NIVA.INVENTARIO_MINIMO AS MINIMO,
		NIVA.PUNTO_REORDEN AS REORDEN,
		CLAVES.CLAVE_ARTICULO AS CLAVE
FROM ARTICULOS ARTI
	FULL OUTER JOIN CLAVES_ARTICULOS CLAVES ON CLAVES.ARTICULO_ID = ARTI.ARTICULO_ID
	LEFT JOIN PRECIOS_ARTICULOS PRECIOS_A ON PRECIOS_A.ARTICULO_ID = ARTI.ARTICULO_ID AND PRECIOS_A.PRECIO_EMPRESA_ID = '42'
	LEFT JOIN NIVELES_ARTICULOS NIVA ON NIVA.ARTICULO_ID = ARTI.ARTICULO_ID AND NIVA.ALMACEN_ID = '$almacen_id'
	
WHERE (CLAVES.ROL_CLAVE_ART_ID = '17')
AND (ARTI.ESTATUS = 'A')
AND (ARTI.ES_ALMACENABLE = 'S')
AND (ARTI.NOMBRE LIKE '%$clave_nombre%')
AND (ARTI.ARTICULO_ID IN(SELECT DOCS_INDET.ARTICULO_ID FROM DOCTOS_IN_DET DOCS_INDET WHERE (DOCS_INDET.ALMACEN_ID = '$almacen_id')))";
 
$tabla = ""; 
$res = $con_micro->prepare($consulta);
$res->execute();
$res->setFetchMode(PDO::FETCH_OBJ);
if (!$res){
	 echo "<div style='color:#FF0000'>fallo en consulta!</div>";
	 exit;}	 
$count = 0;

// si esta agregado poner color verde, si no esta agregado colocar el boton agregar a toolcrib
$tabla .= '<table id="articulos" class="table table-striped table-bordered table-hover table-responsive display">
                    	<thead>
                    		<tr class="bg-info">
                    			<th>Clave</th>
                    		    <th>Articulo</th>
								<th>Unid. Medida</th>
								<th>Existencia</th>
                    		    <th>Precio</th>
                    		    <th>Estandares</th>
                    		    <th>Agregar a Catalogo</th>
							</tr>
                    	</thead><tbody>';
	$existencia = 0;					
	$agregado = "";					
	$boton = "";					
while ($row=$res->fetch()){
			$count++;
			$existencia = ExistenciaMicrosip($row->ARTID,$almacen_id);
			$agregado = validar_articulo($row->ARTID,$almacen_id);
			if ($agregado == 0){
				$boton = '<input type="button" class="btn btn-success btn-lg add_articulo_microsip" value="+" id="btnid_'.$row->ARTID.'">';
			
			//$lista[$row->ARTID] = $row->CLAVE.' - '.$row->ARTICULO;
				$tabla .= '<tr id="trbuscararticulomicrosip_'.$row->ARTID.'"><td>'.$row->CLAVE.'</td>
				<td>'.utf8_encode($row->ARTICULO).'</td>
				<td>'.$row->UDM.'</td>
				<td align="right">'.$existencia.'</td>
				<td align="right">$'.number_format($row->PRECIO,2).'</td>
				<td>Max:'.number_format($row->MAXIMO,0).' min:'.number_format($row->MINIMO,0).' ReOrden:'.number_format($row->REORDEN,0).'</td>
				<td align="center">
				'.$boton.'
				
				<input type="hidden"  value="'.utf8_encode($row->CLAVE).'" id="tx_clave_'.$row->ARTID.'">
				<input type="hidden"  value="'.utf8_encode($row->ARTICULO).'" id="tx_articulo_'.$row->ARTID.'">
				<input type="hidden"  value="'.$row->UDM.'" id="tx_udm_'.$row->ARTID.'">
				<input type="hidden"  value="'.$existencia.'" id="tx_existencia_'.$row->ARTID.'">
				<input type="hidden"  value="'.number_format($row->PRECIO,2).'" id="tx_precio_'.$row->ARTID.'">
				<input type="hidden"  value="'.number_format($row->MAXIMO,0).'" id="tx_maximo_'.$row->ARTID.'">
				<input type="hidden"  value="'.number_format($row->MINIMO,0).'" id="tx_minimo_'.$row->ARTID.'">
				<input type="hidden"  value="'.number_format($row->REORDEN,0).'" id="tx_reorden_'.$row->ARTID.'">
				
				</td>
				
				</tr>';	
			}else {
				$boton = "";
			}
	}
$tabla .= ' </tbody></table>
<script>
	$(document).ready(function(){
                $("#articulos").DataTable({
						"order": [[ 1, "ASC" ]]
					});
				$(".add_articulo_microsip").on("click", function(){
                            var tr_id = $(this).attr("id")
							var arr_id = tr_id.split("_");
							var id_articulo = arr_id[1];
							
							
							var clave_empresa = document.getElementById("tx_clave_"+id_articulo).value;
							var clave_microsip = document.getElementById("tx_clave_"+id_articulo).value;
							var articulo = document.getElementById("tx_articulo_"+id_articulo).value;
							//var descripcion = document.getElementById("art_descip_"+id_articulo).value;
					
							var precio = document.getElementById("tx_precio_"+id_articulo).value;
							var minimo = document.getElementById("tx_minimo_"+id_articulo).value;
							var maximo = document.getElementById("tx_maximo_"+id_articulo).value;
							var reorden = document.getElementById("tx_reorden_"+id_articulo).value;
							var existencia = document.getElementById("tx_existencia_"+id_articulo).value;
							var udm = document.getElementById("tx_udm_"+id_articulo).value;
							//var imagen = document.getElementById("art_imagen_"+id_articulo).value;
							
							$("#txt_id_articulo_microsip").val(id_articulo);
							$("#txt_clave_empresa").val(clave_empresa);
							$("#txt_clave_microsip").val(clave_microsip);	
							$("#txt_nombre_articulo").val(articulo);
							//$("#txt_descripcion").val(descripcion);
							
							$("#txt_precio").val(precio);
							$("#txt_min").val(minimo);
							$("#txt_max").val(maximo);
							$("#txt_reorden").val(reorden);
							$("#txt_existencia").val(existencia);
							$("#txt_udm").val(udm);
							$("#txt_almacen_id").val('.$almacen_id.');
							//$("#txt_imagen").val(imagen);
							jQuery("#modal_articulo .modal-header").html("Agregar Articulo Microsip a ToolCrib") ;
							//$("#modal_articulo_microsip").modal("hide");
							$("#modal_articulo").modal("show");


							
							   
                });
		});
	 
		</script>	
';	

if ($count > 0){
	
echo $tabla;	
}
else
{
	echo "Sin Resultados";
}

}
?>