<?php include("conexion.php"); 
				  
	  if (isset($_POST['id_empresa'])){
      $id_empresa = $_POST['id_empresa'];
      $almacen_id = $_POST['almacen_id'];
  
     	estatus_almacen($id_empresa,$almacen_id);
	  }	
	
     function estatus_almacen($id_empresa,$almacen_id){ 
global  $conex;

if ($almacen_id == 0)
{
	$consulta = "SELECT 
		art.id as id_articulo,
		art.id_microsip as articulo_id,
		art.clave_microsip as clave_microsip,
		art.clave_empresa as clave_empresa,
		art.unidad_medida as unidad_medida,
		art.nombre as nombre,
		SUM(indet.diferencia) as consumido,
		exis.existencia_actual as existencia_sistema,
		exis.almacen_id as almacen_id,
		alm.almacen as almacen
		FROM articulos art 
		
		LEFT JOIN inventarios_det indet ON indet.id_articulo = art.id
		LEFT JOIN inventarios inv ON inv.id_inventario = indet.id_inventario
		INNER JOIN existencias exis ON exis.id_articulo = art.id AND exis.almacen_id = inv.almacen_id
		LEFT JOIN almacenes alm ON alm.almacen_id = inv.almacen_id
		WHERE inv.id_empresa = '$id_empresa' AND inv.estatus <> 'A' AND inv.cancelado = 'N'
		GROUP BY art.id";
}
else
{
	$consulta = "SELECT 
		art.id as id_articulo,
		art.id_microsip as articulo_id,
		art.clave_microsip as clave_microsip,
		art.clave_empresa as clave_empresa,
		art.unidad_medida as unidad_medida,
		art.nombre as nombre,
		SUM(indet.diferencia) as consumido,
		exis.existencia_actual as existencia_sistema,
		exis.almacen_id as almacen_id,
		alm.almacen as almacen
		FROM articulos art 
		
		LEFT JOIN inventarios_det indet ON indet.id_articulo = art.id
		LEFT JOIN inventarios inv ON inv.id_inventario = indet.id_inventario
		INNER JOIN existencias exis ON exis.id_articulo = art.id AND exis.almacen_id = inv.almacen_id
		LEFT JOIN almacenes alm ON alm.almacen_id = inv.almacen_id
		WHERE inv.id_empresa = '$id_empresa' AND inv.estatus <> 'A' AND inv.cancelado = 'N' AND inv.almacen_id = '$almacen_id'
		GROUP BY art.id";
}		
			

		
$resultado = mysql_query($consulta, $conex) or die(mysql_error());
// $row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);

if ($total_rows > 0){ // con resultados

echo '<table id="tabla_art_invdet" class="table table-striped table-bordered table-hover table-responsive display" >
		<thead>
			<tr class="bg-info">
				<th>Almacen</th>
				<th>#ALLPART</th>
				<th>Articulo</th>
				<th>Unid. Med.</th>
				<th>Exis. Sis.</th>
				<th>Cant. Ult. Conteo</th>
				<th>Consumido</th>
				<th>Con Orden</th>
				<th>Pend. Orden</th>
			</tr>
		</thead><tbody >';
						
	while($row2 = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
	{
		$id_arti = $row2['id_articulo'];
		$articulo_id = $row2['articulo_id'];
		$almacen_id = $row2['almacen_id'];
		$arr = explode('_',ultimo_inv_art($id_arti));
		$ult_inv_act = $arr[0]; 
		$fecha_inventario = $arr[1]; 
		$con_orden = cantidades_cobradas($articulo_id,$almacen_id);
		$pend_orden = $row2['consumido'] - $con_orden;
	echo '<tr> 
	<td class="reg_indetart" id="tdartca_'.$id_arti.'">'.$row2['almacen'].'</td>
	<td class="reg_indetart" id="tdartca_'.$id_arti.'">'.$row2['clave_microsip'].'</td>
	<td class="reg_indetart" id="tdartfo_'.$id_arti.'">'.$row2['nombre'].'</td>
	<td class="reg_indetart" id="tdartet_'.$id_arti.'">'.$row2['unidad_medida'].'</td>
	<td class="reg_indetart" id="tdartal_'.$id_arti.'" align="right"  title="Inventario segun el sistema de Microsip">'.$row2['existencia_sistema'].'</td>
	<td class="reg_indetart" id="tdartcl_'.$id_arti.'" align="right"  title="Fecha inventario: '.$fecha_inventario.'">'.$ult_inv_act.' </td>
	<td class="reg_indetart" id="tdartus_'.$id_arti.'" align="right" title="'.$almacen_id.'">'.$row2['consumido'].'</td>
	<td class="reg_indetart" id="tdartus_'.$id_arti.'" align="right" >'.$con_orden.'</td>
	<td class="reg_indetart" id="tdartus_'.$id_arti.'" align="right" >	'.$pend_orden.'</td>
	</tr>';                    							
		    
	                    							
	}				
	echo ' </tbody></table>';
	

 echo '<script> 
	$(document).ready(function(){
				
		$("#tabla_art_invdet").DataTable({
					});		
			
		});
</script>';  

 
}
else
{
					
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h4><a href="#">No existen registros de inventarios.</a></h4>
                          
                        </div>
                    </div>
				</div>';		
		


}

}




?>
