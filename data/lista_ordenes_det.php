<?php include("conexion.php"); 
				  
		$orden_id = $_POST['orden_id'];
	lista_oc_det($orden_id);
		
	function lista_oc_det($orden_id){ 
global  $conex;
		//id_empresa, clave_microsip, clave_empresa, nombre, descripcion, precio, src_img, id_microsip, id_marca, unidad_medida
		$consulta = "SELECT 
		art.clave_microsip as clave_microsip,
		art.clave_empresa as clave_empresa,
		art.unidad_medida as unidad_medida,
		art.nombre as nombre,
		oc_det.id_oc_det as id_oc_det,
		oc_det.posicion as posicion,
		oc_det.cantidad as cantidad,
		oc_det.udm as udm,
		oc_det.numero_parte as numero_parte,
		oc_det.descripcion as descripcion,
		oc_det.precio_unitario as precio_unitario,
		oc_det.precio_total as precio_total
					FROM ordenes_det oc_det 
					INNER JOIN articulos art ON art.id = oc_det.id_articulo
					WHERE oc_det.id_oc = '$orden_id' 
					";			
		

$resultado = mysql_query($consulta, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);

if ($total_rows > 0){ // con resultados
$estatus;
$cancelado;
$mostrar_cancel;
$folio;
$ocultar;



echo '	
<table id="tabla_oc_det" class="table table-striped table-bordered table-hover table-responsive display" >
		<thead>
			<tr class="bg-info">
				<th>#</th>
				<th>Cant.</th>
				<th>Unid. Med.</th>
				<th>#Parte</th>
				<th>Descripcion</th>
				<th>Precio Unitario</th>
				<th>Precio Total</th>
				<th>Articulo Microsip</th>
				<th><i class="fa fa-trash" aria-hidden="true"></i></th>
			</tr>
		</thead><tbody >';
						
	while($row2 = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
	{	
		$id_oc_det = $row2['id_oc_det'];
		$arti_microsip = $row2['clave_microsip'].' - '.$row2['nombre'];
	echo '<tr>
	<td class="reg_ocdet" id="tdcd_'.$id_oc_det.'">'.$row2['posicion'].' </td>
	<td class="reg_ocdet" id="tdca_'.$id_oc_det.'">'.$row2['cantidad'].'</td>
	<td class="reg_ocdet" id="tdfo_'.$id_oc_det.'">'.$row2['udm'].'</td>
	<td class="reg_ocdet" id="tdet_'.$id_oc_det.'">'.$row2['numero_parte'].'</td>
	<td class="reg_ocdet" id="tdal_'.$id_oc_det.'">'.$row2['descripcion'].'</td>
	<td class="reg_ocdet" id="tdcl_'.$id_oc_det.'">'.$row2['precio_unitario'].' </td>
	<td class="reg_ocdet" id="tdus_'.$id_oc_det.'">'.$row2['precio_total'].'</td>
	<td class="reg_ocdet" id="tdus_'.$id_oc_det.'">'.$arti_microsip.'</td>
	<td class="reg_ocdet" id="tdts_'.$id_oc_det.'" align="center">
	<i class="fa fa-trash btn btn-danger btn-sm delpartoc" aria-hidden="true" id="btndel_'.$id_oc_det.'"></i></td>
	';                    							
		    
						
	}				
	echo ' </tbody></table>';
	

 echo '<script> 
	$(document).ready(function(){
				
				
				$("#tabla_oc_det").DataTable({
					});
				
                $(".reg_ocdet").on("click", function(){
                            var tr_id = $(this).attr("id")
							var arr_id = tr_id.split("_");
							var id_inventario = arr_id[1];
							
							//enviar_correo_inv(id_inventario);
							   
                });
				$(".delpartoc").on("click", function(){
                            var tr_id = $(this).attr("id")
							var arr_id = tr_id.split("_");
							var id_oc_det = arr_id[1];
							
							del_part_oc(id_oc_det);
							   
                });
				
			
		});
</script>';  

 
} 
else /// sin resultados
{	
					
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h4><a href="#">Aun no se ha agregado ninguna partida.</a></h4>
                          
                        </div>
                    </div>
				</div>';		
		


}

}	
			
?>			