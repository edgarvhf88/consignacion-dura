<?php include("conexion.php"); 
				  
	  if (isset($_POST['id_empresa'])){
      $id_empresa = $_POST['id_empresa'];
  
     	list_invent($id_empresa);
	  }	
	 
     function list_invent($id_empresa){ 
global  $conex, $conex_sqli;


$resul = $conex_sqli->query("call lista_inventarios(".$id_empresa.")");

if($resul && $resul->num_rows>0){ 
$resul->fetch_all(MYSQLI_ASSOC);
$estatus;
$cancelado;

	echo '
	<table id="tabla_inv" class="table table-striped table-bordered table-hover table-responsive display" >
		<thead>
			<tr class="bg-primary">
				
				<th>Almacen</th>
				<th>Folio</th>
				<th>Fecha</th>
				<th>Usuario Creador</th>
				<th>Estado Inv.</th>
				<th class=" hidden-xs hidden-sm hidden-md hidden-lg">Cancelado </th>
				<th class=" hidden-xs hidden-sm hidden-md hidden-lg">Usuario Cierre</th>
				<th class="">Fecha Cierre</th>
				<th class="">Estado Cobro</th>
				<th class=" hidden-xs hidden-sm hidden-md hidden-lg">Usuario Cancelacion</th>
				<th class=" hidden-xs hidden-sm hidden-md hidden-lg">Fecha Cancelacion</th>
			</tr>
		</thead><tbody >';
		
	foreach($resul as $row2){
		if ($row2['estatus'] == "A"){
			$estatus= "ABIERTO";
		} else if ($row2['estatus'] == "C"){
			$estatus= "CERRADO";
		}
		if ($row2['cancelado'] == "S"){
			$cancelado= "SI";
			$clase = "danger"; /// cancelados
			$desc_clase = "Cancelado";
			$clase_func = "todos_cancelados";
		} else if ($row2['cancelado'] == "N"){
			$cancelado= "NO";
			$clase = ""; 
			$desc_clase = "";
			$clase_func = "";
		}
		if ($row2['fecha_hora_cancelacion'] == "0000-00-00 00:00:00")
		{
			$fecha_cancelacion = '-';
		}else { $fecha_cancelacion = $row2['fecha_hora_cancelacion']; }
		
		$usuario_cacelacion = Nombre($row2['id_usuario_cancelacion']);
		if ($usuario_cacelacion == "Sin Usuario")
		{
			$usuario_cacelacion = '-';
		}
		
		
		if (($row2['estatus'] == "A") && ($row2['cancelado'] == "N"))
		{
			// si estatus esta abierto y no esta cancelado entonces no lo muestra
		}else {
			echo '<tr class="'.$clase.' '.$clase_func.' " title="'.$desc_clase.'">
		
			<td class="elemen_reg_inv" id="tdalmac_'.$row2['id_inventario'].'">'.$row2['almacen'].'</td>
			<td class="elemen_reg_inv" id="tdfolio_'.$row2['id_inventario'].'">'.$row2['folio'].'</td>
			<td class="elemen_reg_inv" id="tdfecha_'.$row2['id_inventario'].'">'.$row2['fecha_hora_creacion'].'</td>
			<td class="elemen_reg_inv" id="tdidusc_'.$row2['id_inventario'].'">'.Nombre($row2['id_usuario_creador']).'</td>
			<td class="elemen_reg_inv" id="tdestat_'.$row2['id_inventario'].'">'.$estatus.'</td>
			<td class="elemen_reg_inv hidden-xs hidden-sm hidden-md hidden-lg" id="tdcance_'.$row2['id_inventario'].'">'.$cancelado.' </td>
			<td class="elemen_reg_inv hidden-xs hidden-sm hidden-md hidden-lg" id="tduscie_'.$row2['id_inventario'].'">'.Nombre($row2['id_usuario_cierre']).'</td>
			<td class="elemen_reg_inv " id="tdfehoc_'.$row2['id_inventario'].'">'.$row2['fecha_hora_cierre'].'</td>
			<td class="elemen_reg_inv " id="tdestadocobro_'.$row2['id_inventario'].'">'.$row2['fecha_hora_cierre'].'</td>
			<td class="elemen_reg_inv hidden-xs hidden-sm hidden-md hidden-lg" id="tduscan_'.$row2['id_inventario'].'">'.$usuario_cacelacion.'</td>
			<td class="elemen_reg_inv hidden-xs hidden-sm hidden-md hidden-lg" id="tdfecan_'.$row2['id_inventario'].'">'.$fecha_cancelacion.'</td>
			</tr>';                    							
		     
		}
	}
	echo ' </tbody></table>';
 
	echo '<script> 
	$(document).ready(function(){
				$("#tabla_inv").DataTable({
						"order": [[ 1, "asc" ]]
					});
		
                $(".elemen_reg_inv").on("click", function(){
                            var tr_id = $(this).attr("id")
							var arr_id = tr_id.split("_");
							var id_inventario = arr_id[1];
							console.log("id_inventario= "+id_inventario);
							inventario_detalles(id_inventario);
							$("#inpt_id_inventario").val(id_inventario);
							$("#txt_id_inventario_activo").val(id_inventario);
							$("#modal_listadet").modal("show");
							   
                });
				$("#modal_cargando").modal("hide");	
			
		});
</script>';  
}
else /// sin resultados
{
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h3><a href="#">No existen registros de inventarios.</a></h3>
                           
                        </div>
                    </div>
				</div>
				<script> 
	$(document).ready(function(){
			$("#modal_cargando").modal("hide");	
			
			});
		</script>
				';		
		


}
	


}




?>
