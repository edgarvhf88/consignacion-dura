<?php include("conexion.php"); 
				  
	  if (isset($_POST['id_empresa'])){
      $id_empresa = $_POST['id_empresa'];
  
     	lista_det($id_empresa);
	  }	
	
     function lista_det($id_empresa){ 
global  $conex, $conex_sqli;


$resul = $conex_sqli->query("call 	lista_invdet_art(".$id_empresa.")");

if($resul && $resul->num_rows>0){ 
$resul->fetch_all(MYSQLI_ASSOC);
echo '<table id="tabla_art_invdet" class="table table-striped table-bordered table-hover table-responsive display" >
		<thead>
			<tr class="bg-info">
				<th>#DURA</th>
				<th>#ALLPART</th>
				<th>Almacen</th>
				<th>Articulo</th>
				<th>Unid. Med.</th>
				<th>Exis. Sis.</th>
				<th>Cant. Ult. Conteo</th>
				<th>Consumido</th>
			</tr>
		</thead><tbody >';
	foreach($resul as $row2){
		$id_arti = $row2['id_articulo'];
		$arr = explode('_',ultimo_inv_art($id_arti));
		$ult_inv_act = $arr[0]; 
		$fecha_inventario = $arr[1]; 
		
	echo '<tr>
	<td class="reg_indetart" id="tdartcd_'.$id_arti.'">'.$row2['clave_empresa'].' </td>
	<td class="reg_indetart" id="tdartca_'.$id_arti.'">'.$row2['clave_microsip'].'</td>
	<td class="reg_indetart" id="tdartfo_'.$id_arti.'">'.$row2['almacen'].'</td>
	<td class="reg_indetart" id="tdartfo_'.$id_arti.'">'.$row2['nombre'].'</td>
	<td class="reg_indetart" id="tdartet_'.$id_arti.'">'.$row2['unidad_medida'].'</td>
	<td class="reg_indetart" id="tdartal_'.$id_arti.'">'.$row2['existencia_sistema'].'</td>
	<td class="reg_indetart" id="tdartcl_'.$id_arti.'" title="Fecha inventario: '.$fecha_inventario.'">
	'.$ult_inv_act.' </td>
	<td class="reg_indetart" id="tdartus_'.$id_arti.'">'.$row2['consumido'].'</td>
	</tr>';
	}		
echo ' </tbody></table>';
	

 echo '<script> 
	$(document).ready(function(){
		$("#modal_cargando").modal("hide");			
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
				</div><script> 
	$(document).ready(function(){
			$("#modal_cargando").modal("hide");	
			
			});
		</script>';		
		


}

}




?>
