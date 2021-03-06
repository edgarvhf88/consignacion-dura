<?php include("conexion.php"); 
				  
	  if (isset($_POST['almacen_id'])){
      $almacen_id = $_POST['almacen_id'];
		
     	list_ordenes($almacen_id);
	  	}
	 
     function list_ordenes($almacen_id){ 
global  $conex;

if ($almacen_id == 0)
{
	$consulta = "SELECT 
				ord.id_oc as id_oc,
				ord.folio as folio,
				ord.fecha_oc as fecha_oc,
				ord.fecha_creacion as fecha_creacion,
				ord.requisitor as requisitor,
				ord.comprador as comprador,
				ord.estatus as estatus,
				ord.req_factura as req_factura,
				ord.fecha_req_fac as fecha_req_fac,
				ord.folio_remision as folio_remision,
				ord.folio_factura as folio_factura,
				ord.subtotal as subtotal,
				ord.total as total,
				ord.terminos_credito as terminos_credito,
				ord.contacto as contacto,
				ord.tipo_oc as tipo_oc,
				alm.almacen as almacen,
				usu.nombre as nombre,
				usu.apellido as apellido
				FROM ordenes ord 
				LEFT JOIN almacenes alm ON alm.almacen_id = ord.almacen_id
				LEFT JOIN usuarios usu ON usu.id = ord.id_creador
				WHERE ord.cancelado = 'N' ";	
}	
else 
{
	$consulta = "SELECT 
				ord.id_oc as id_oc,
				ord.folio as folio,
				ord.fecha_oc as fecha_oc,
				ord.fecha_creacion as fecha_creacion,
				ord.requisitor as requisitor,
				ord.comprador as comprador,
				ord.estatus as estatus,
				ord.req_factura as req_factura,
				ord.fecha_req_fac as fecha_req_fac,
				ord.folio_remision as folio_remision,
				ord.folio_factura as folio_factura,
				ord.subtotal as subtotal,
				ord.total as total,
				ord.terminos_credito as terminos_credito,
				ord.contacto as contacto,
				ord.tipo_oc as tipo_oc,
				alm.almacen as almacen,
				usu.nombre as nombre,
				usu.apellido as apellido
				FROM ordenes ord 
				LEFT JOIN almacenes alm ON alm.almacen_id = ord.almacen_id
				LEFT JOIN usuarios usu ON usu.id = ord.id_creador
				WHERE ord.cancelado = 'N' AND ord.almacen_id = '$almacen_id'";	
}			

$resultado = mysql_query($consulta, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);

if ($total_rows > 0){ // con resultados

echo '
<table id="tabla_ord" class="table table-striped table-bordered table-hover table-responsive display" >
		<thead>
			<tr class="bg-primary">
				
				<th>Almacen</th>
				<th>#OC</th>
				<th >Fecha OC</th>
				<th>Tipo OC</th>
				<th>Usuario Creador</th>
				<th>Estatus</th>
				<th>Requisitor</th>
				<th>Comprador</th>
				<!-- <th>Subtotal</th> -->
				<th>Total</th>
				<th>OC<i class="fa fa-paperclip" aria-hidden="true"></i></th>
				<th>Fact. Requerida</th>
				<th>#Remision</th>
				<th>#Factura</th>
			</tr>
		</thead><tbody >';
						
	while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
	{	
		$id_oc = $row['id_oc'];
		$almacen = $row['almacen'];
		$folio_oc = '<a href="#" class="link_rapido" id="ahreffoliooc_'.$id_oc.'" >'.$row['folio'].'</a><input type="hidden" id="txtfoliooc_'.$id_oc.'" value="'.$row['folio'].'" />';
		$fecha_oc = date_format(date_create($row['fecha_oc']),'d-M-Y');
		
		$usuario_creador = $row['nombre']." ".$row['apellido'];
		//$estatus = $row['estatus'];
		$requisitor = $row['requisitor'];
		$comprador = $row['comprador'];
		$subtotal = $row['subtotal'];
		//$total = $row['total'];
		$req_factura = $row['req_factura'];
		$folio_remision = $row['folio_remision'];
		$folio_factura = $row['folio_factura'];
		if ($row['total'] > 0)
		{
			$total = str_replace(",","",$row['total']);
			$total = number_format($total,2);
		}
		else
		{
			$total = "";
		}
		if ($row['tipo_oc'] == "")
		{
			$tipo_oc = "Sin Guardar";
		}
		else if ($row['tipo_oc'] == "A")
		{
			$tipo_oc = "Abierta";
		}
		else if ($row['tipo_oc'] == "C")
		{
			$tipo_oc = "Cerrada";
		}
		if ($row['estatus'] == 0)
		{
			$estatus = "Capturando";
		}
		else if ($row['estatus'] == 1)
		{
			
			if ($row['tipo_oc'] == "A")
			{
				$estatus = "Guardado";
			}
			else if ($row['tipo_oc'] == "C")
			{
				$estatus = "Por Remisionar";
			}
		}
		else if ($row['estatus'] == 2) //
		{
			$estatus = "Remisionado";
		}
		else if ($row['estatus'] == 3) //
		{
			$estatus = "Facturado";
		}
		else if ($row['estatus'] == 4) //
		{
			$estatus = "Parcial";
		}
		
		if($folio_remision != "" and $folio_factura =="")
		{
			$rowfacturar ='<div class="dropdown">
						<button class="btn btn-danger btn-block dropdown-toggle btn_estatus" onclick="buscar_facturas('.$folio_remision.');" type="button" id="btnestatus_1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Factura
						</button>
				</div>';
		}
		else 
		{
			if($folio_factura != "" ){
			$folio_factura_sin = str_replace("FD","", $folio_factura);
			$folio_factura_sin = str_replace(",","", number_format($folio_factura_sin,0));
			$folio_factura_sin = 'FD'.$folio_factura_sin;
			$rowfacturar = $folio_factura_sin;}
			else {$rowfacturar = $folio_factura;}
			
		}
		
		
		echo '<tr class="" title="">
		
		<td class="elemen_reg_ord" id="tdalmac_'.$id_oc.'">'.$almacen.'</td>
		<td class="elemen_reg_ord" id="tdfolio_'.$id_oc.'">'.$folio_oc.'</td>
		<td class="elemen_reg_ord" id="tdfecha_'.$id_oc.'">'.$fecha_oc.'</td>
		<td class="elemen_reg_ord" id="tdtipooc_'.$id_oc.'">'.$tipo_oc.'</td>
		<td class="elemen_reg_ord" id="tdidusc_'.$id_oc.'">'.$usuario_creador.'</td>
		<td class="elemen_reg_ord" id="tdestat_'.$id_oc.'">'.$estatus.'</td>
		<td class="elemen_reg_ord" id="tdrequisitor_'.$id_oc.'">'.$requisitor.'</td>
		<td class="elemen_reg_ord" id="tdcomprador_'.$id_oc.'">'.$comprador.'</td>
		<!-- <td class="elemen_reg_ord" id="tdsubtotal_'.$id_oc.'">'.$subtotal.'</td> -->
		<td class="elemen_reg_ord" id="tdtotal_'.$id_oc.'">'.$total.'</td>
		<td class="elemen_reg_ord" id="	'.$id_oc.'"> </td>
		<td class="elemen_reg_ord" id="tdreqfac_'.$id_oc.'">'.$req_factura.'</td>
		<td class="elemen_reg_ord" id="tdfoliorem_'.$id_oc.'">'.$folio_remision.'</td>
		<td class="elemen_reg_ord" id="tdfoliofac_'.$id_oc.'">'.$rowfacturar.'</td>
		
		</tr>';                    							
		     
		
		               					
		                    		
		                    							
	}				
	echo ' </tbody></table>';
 
 echo '<div class="modal fade" id="orden_detalle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <!-- Header de la ventana -->
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h3 class="modal-title" id="detalle_modal_titulo">
                                           Detalle de remision
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
				$("#tabla_ord").DataTable({
						"order": [[ 1, "asc" ]]
					});
		
                $(".link_rapido").on("click", function(){
                            var tr_id = $(this).attr("id")
							var arr_id = tr_id.split("_");
							var id = arr_id[1];
							var folio_oc = document.getElementById("txtfoliooc_"+id).value;
							
							$("#div_lista_ordenes").hide();
							$("#txt_orden_id").val("");
							$("#txt_orden").val(folio_oc);
							//verif_orden();
							detalle_orden(id);
							
							$("#div_datos_ordenes").show();
                });
				/* $(".elemen_reg_ord").on("click", function(){
                            var tr_id = $(this).attr("id");
							var arr_id = tr_id.split("_");
							var id_inventario = arr_id[1];
							
							//inventario_detalles(id_inventario);
							//$("#inpt_id_inventario").val(id_inventario);
							//$("#txt_id_inventario_activo").val(id_inventario);
							//$("#modal_listadet").modal("show");
							   
                }); */
			
		});
</script>';  

 
} 
else /// sin resultados
{
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h3><a href="#">Todabia no se han capturado ordenes de compra</a></h3>
                           
                        </div>
                    </div>
				</div>';		
		


}

}




?>