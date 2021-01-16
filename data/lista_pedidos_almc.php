<?php include("conexion.php"); 

$id_empresa_user_activo = id_empresa($_SESSION["logged_user"]);

		$almacen_id = '';		  
		if (isset($_POST['almacen_id'])){
		$almacen_id = $_POST['almacen_id'];
		}
	 
	  if ($almacen_id != ''){
	  
			lista_pedidos($id_empresa_user_activo,$almacen_id);
	  }
	  else
	  {
		  echo 0;
	  }
	  
	
     function lista_pedidos($id_empresa_user_activo,$almacen_id){ ///Mostrara la lista de los pedidos realizados a la empresa y almacen relacionado
global $database_conexion, $conex;
$id_empresa = $id_empresa_user_activo;


$consulta_relacion = "
SELECT p.fecha_pedido_oficial as fecha_pedido_oficial, p.id as id_pedido, p.folio as folio, p.total_pedido as total_pedido, p.estatus as estatus, p.id_usuario as id_requi, p.id_empresa as id_empresa, p.orden_compra as orden_compra
FROM  pedidos p 
WHERE  p.estatus <> '0' and p.estatus <> '0p' and p.estatus <> '4' and p.id_empresa = '$id_empresa' and p.id_sucursal = '$almacen_id'
ORDER BY folio DESC";
$resultado_relacion = mysql_query($consulta_relacion, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado_relacion);
$total_relaciones = mysql_num_rows($resultado_relacion);

if ($total_relaciones > 0){ // con resultados

echo '<table id="pedidos_personal" class="table table-striped table-bordered table-hover responsive display">
                    	<thead>
                    		<tr class="info">
							
                            <th>Purchaser</th>
                            <th>#Req</th>
							<th>Date</th>
                            <th>P.O.</th>
                            <th>Status</th>
                            
							
                    	</tr>
                    	</thead><tbody>';
$estatus = '';
$atributo = '';	
$tracking='';
$validacion_btn_tracking = '';	
$estatus_traspaso = '';			
while($row2 = mysql_fetch_array($resultado_relacion,MYSQL_BOTH)) // html de articulos a mostrar
{
switch($row2['estatus'])
{
	case 0:
	$estatus = "Abierta";
	break;
	case 1:
	$estatus = 'Requested';
	$clase_td = 'class="btn-warning"';
	
	// verificar estatus en tabla de pedido_traspaso 
	$estatus_traspaso = VerifTraspasoStatus($row2['id_pedido']);
	
		if ($estatus_traspaso == 100 ){
			// si tiene articulos recibidos
			$estatus = 'Delivered '.$estatus_traspaso.'%';
				$clase_td = 'class="btn-success"';
		}else if ($estatus_traspaso != 0 ){
			// si tiene articulos recibidos
			$estatus = 'Parcial '.$estatus_traspaso.'%';
			$clase_td = 'class="btn-primary"';	
				
		}
		
		
	break;
	case 2:
	// estatus 2 cuando se procesa un pedido de traspaso realcionado con la requisicion del clente se cambia el estatus de 1 a 2 para que al almacenista le aparezca la opcion de recibir el material 
	$estatus = 'Delivering';
	$clase_td = 'class="btn-info"';	
	break;
	case 3:
	$estatus = 'Surtido';
	//$estatus = $estatus_tipo_ruta;
	$clase_td = 'class="btn-success"';	
	break;
	case 4:
	$estatus = "Surtido Parcial"; /* pendiente para requerimiento en futuro Atte. Ing. Edgar Herebia ;) */
	break;
	case 5:
	$estatus = $estatus_tipo_entregado;
	$clase_td = 'class="btn-success"';	
	break;
}


	
 echo ' <tr >
                    		
                   	<td style="width:20%;">'.Nombre($row2['id_requi']).'</td>
                   	
                   	<td style="width:10%;" align="right" onclick="detalle_pedido('.$row2['id_pedido'].','.$row2['folio'].','.$row2['total_pedido'].');">'.$row2['folio'].'</td>
					<td style="width:10%;" align="right" onclick="detalle_pedido('.$row2['id_pedido'].','.$row2['folio'].','.$row2['total_pedido'].');">'.$row2['fecha_pedido_oficial'].'</td>
					<td style="width:10%;" align="right" onclick="detalle_pedido('.$row2['id_pedido'].','.$row2['folio'].','.$row2['total_pedido'].');">'.$row2['orden_compra'].'</td>
                   
                   	<td style="width:10%;" align="center" id="td_estatus_'.$row2['id_pedido'].'" '.$clase_td.' onclick="detalle_pedido('.$row2['id_pedido'].','.$row2['folio'].','.$row2['total_pedido'].');">'.$estatus.'
					<input type="hidden" id="txt_folio_pedido_'.$row2['id_pedido'].'" value="'.$row2['folio'].'"/></td>
					
					 </tr>
					';
					
					
			//<td><button id="" onclick="enviar('.$row2['id'].');">Enviar</button></td>

					
}				
 echo ' </tbody></table>';
 
 echo ' <div class="modal fade" id="pedido_detalle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <!-- Header de la ventana -->
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h3 class="modal-title">
                                            List 
                                        </h3>
                                    </div>
                                    <!-- Contenido de la ventana -->
                                    <div class="modal-body" style="overflow:auto;>
                                        <p class="h4">Modal para pedido.</p>
                                        <div class="table-responsive">
                                          
                                        </div>
                                    </div>
                                    <!-- Footer de la ventana -->
                                    <div class="modal-footer">
                                        
                                        <button type="button" class="btn btn-primary " data-dismiss="modal">Close</button>
                                    </div>
                                    
                                </div>
                            </div>
                    </div>
					
	<script>
	$(document).ready(function(){
				
				$(".btn_cambio_estatus").click(function(){
					
				
					var btn_id = $(this).attr("id");
                    var arr_id = btn_id.split("_");
					var id_pedido = arr_id[2];
					var tipo = arr_id[1];
					cambiar_estatus(id_pedido,tipo);

					//alert("hola "+id_pedido+" "+tipo);					   
							   
							   
				});
				
				
	             $(".btn_addtracking").click(function(){
                               var btn_id = $(this).attr("id");
                         							   
							   var arr_id = btn_id.split("_");
							   var id_pedido = arr_id[1];
							   var folio = document.getElementById("txt_folio_pedido_"+id_pedido).value;
							
							   $("#txt_id_pedido").val(id_pedido);
							   
							  
							   jQuery("#modal_tracking .modal-header").html("Traking para Pedido: "+folio) ;
							
							  $("#modal_tracking").modal("show");

								$("#modal_tracking").on("shown.bs.modal", function() {
								$("#txt_tracking").focus();
								})	
								
							  
							  
                });
				$("#pedidos_personal").DataTable({
						"order": [[ 1, "ASC" ]]
					}); 
				
		});
	 
	   
		</script>	
					
					';
 
} 
else /// sin resultados
{
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h3><a href="#">You not have request</a></h3>
                           
                        </div>
                    </div>
				</div>';		
		


}

}




?>