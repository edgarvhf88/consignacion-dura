<?php include("conexion.php"); 
/////////////codigo para validar empresa y cargar pagina con su estilo ////////////////////////////////////////////////////////////////////
$Display = '';			
$display_empresas = display_empresas();
$id_empresa_user_activo = id_empresa($_SESSION["logged_user"]);
foreach ($display_empresas as $id => $display_name){
	if ($id_empresa_user_activo == $id){
		$Display = $display_name;
		
	}
	//echo $id." ** ".id_empresa($_SESSION["logged_user"])."<br />"; 
};

include("../displays/".$Display.".php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$id_usuario = '';
				  
	  if (isset($_POST['id_user'])){
      $id_usuario = $_POST['id_user'];
      }
	 
	  if ($id_usuario != ''){
	  
			lista_pedidos($id_usuario);
	  }
	  else
	  {
		  echo 0;
	  }
     function lista_pedidos($id_usuario){ ///Mostrara la lista de los pedidos realizados asignados al vendedor
global $database_conexion, $conex, $titulo_modal_lista_articulos, $comprador_tabla_mis_pedidos, $folio_tabla_mis_pedidos, $fecha_tabla_mis_pedidos, $orden_cliente_tabla_mis_pedidos, $traking_tabla_mis_pedidos, $total_tabla_mis_pedidos, $sucursal_tabla_mis_pedidos, $estatus_tabla_mis_pedidos, $estatus_tipo_ordenado, $estatus_tipo_proceso, $estatus_tipo_ruta, $estatus_tipo_entregado;
$id_empresa = id_empresa($id_usuario);
$minimo =1;

$consulta_relacion = "
SELECT p.fecha_pedido_oficial as fecha_pedido_oficial, p.id as id_pedido, p.folio as folio, p.total_pedido as total_pedido, p.estatus as estatus, p.id_usuario as id_requi, p.id_empresa as id_empresa, p.orden_compra as orden_compra
FROM supervisor_relacion r
LEFT JOIN pedidos p on r.id_subordinado = p.id_usuario
WHERE r.id_supervisor = '$id_usuario' and p.estatus <> '0' and p.estatus <> '0p' and p.estatus <> '4' and p.id_empresa = '$id_empresa'
ORDER BY folio DESC";
$resultado_relacion = mysql_query($consulta_relacion, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado_relacion);
$total_relaciones = mysql_num_rows($resultado_relacion);

if ($total_relaciones > 0){ // con resultados

echo '<table id="pedidos_personal" class="table table-striped table-bordered table-hover responsive display">
                    	<thead>
                    		<tr class="info">
							
                            <th>'.$comprador_tabla_mis_pedidos.'</th>
                            <th>'.$folio_tabla_mis_pedidos.'</th>
							<th>'.$fecha_tabla_mis_pedidos.'</th>
                            <th>'.$orden_cliente_tabla_mis_pedidos.'</th>
                            <th>'.$total_tabla_mis_pedidos.'</th>
                            <th>'.$estatus_tabla_mis_pedidos.'</th>
                            
							
                    	</tr>
                    	</thead><tbody>';
$estatus = '';
$atributo = '';	
$tracking='';
$validacion_btn_tracking = '';					
while($row2 = mysql_fetch_array($resultado_relacion,MYSQL_BOTH)) // html de articulos a mostrar
{
switch($row2['estatus'])
{
	case 0:
	$estatus = "Abierta";
	break;
	case 1:
	$estatus = $estatus_tipo_ordenado;
	$clase_td = 'class="btn-warning"';	
	break;
	case 2:
	//$estatus = $estatus_tipo_proceso;
	$estatus = 'Pedido Preparado';
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
                   	<td  style="width:10%;" align="right" onclick="detalle_pedido('.$row2['id_pedido'].','.$row2['folio'].','.$row2['total_pedido'].');" >$'.number_format($row2['total_pedido'],2).'</td>
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
                                            '.$titulo_modal_lista_articulos.'
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
                                        
                                        <button type="button" class="btn btn-primary " data-dismiss="modal">Cerrar</button>
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